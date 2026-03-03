<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db_connect.php';

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: module1_student_access.php");
    exit;
}
$student_id = $_SESSION['student_id'];

// Check student status
$sql_status = "SELECT status_id, student_fname, student_lname FROM student WHERE student_id = ?";
$stmt = $connection->prepare($sql_status);
$stmt->bind_param("i", $student_id); // student_id is INT
$stmt->execute();
$stmt->bind_result($status_id, $fname, $lname);
$stmt->fetch();
$stmt->close();

if ($status_id != 1) { // 1 = Active
    $error_message = "Your account is not active. Search and download disabled.";
}

/* -------------------- DOWNLOAD HANDLER -------------------- */
if (isset($_GET['download_id'])) {
    $paper_id = intval($_GET['download_id']);

    $sql_paper = "SELECT file_path FROM research_paper rp 
                  JOIN paper_approval pa ON rp.paper_id = pa.paper_id
                  WHERE rp.paper_id = ? AND pa.approval_status = 'Approved'";
    $stmt = $connection->prepare($sql_paper);
    $stmt->bind_param("i", $paper_id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    if (!$stmt->fetch()) {
        die("❌ Paper not approved or not found.");
    }
    $stmt->close();

    // ✅ Check file exists before serving
    if (!file_exists($file_path)) {
        die("❌ File not found on server.");
    }

    // Log download
    $sql_log = "INSERT INTO download_record (paper_id, student_id) VALUES (?, ?)";
    $stmt = $connection->prepare($sql_log);
    $stmt->bind_param("ii", $paper_id, $student_id); // both are INT
    $stmt->execute();

    // Increment download count
    $connection->query("UPDATE research_paper SET download_count = download_count + 1 WHERE paper_id = $paper_id");

    // Serve file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    readfile($file_path);
    exit;
}

/* -------------------- SEARCH HANDLER -------------------- */
$query = "SELECT 
            rp.paper_id,
            rp.title,
            rp.abstract,
            rp.publication_year,
            rp.download_count,
            c.category_name,
            s.supervisor_name,
            st.student_fname,
            st.student_lname
          FROM research_paper rp
          JOIN category c ON rp.category_id = c.category_id
          JOIN supervisor s ON rp.supervisor_id = s.supervisor_id
          JOIN student st ON rp.student_id = st.student_id
          JOIN paper_approval pa ON rp.paper_id = pa.paper_id
          WHERE pa.approval_status = 'Approved'";


$filters = [];
$types = "";

if (!empty($_GET['title'])) {
    $query .= " AND rp.title LIKE ?";
    $filters[] = "%" . $_GET['title'] . "%";
    $types .= "s";
}
if (!empty($_GET['abstract'])) {
    $query .= " AND rp.abstract LIKE ?";
    $filters[] = "%" . $_GET['abstract'] . "%";
    $types .= "s";
}
if (!empty($_GET['keyword'])) {
    $query .= " AND rp.paper_id IN (
                   SELECT pk.paper_id 
                   FROM paper_keyword pk 
                   JOIN keyword k ON pk.keyword_id = k.keyword_id 
                   WHERE k.keyword_name LIKE ?
               )";
    $filters[] = "%" . $_GET['keyword'] . "%";
    $types .= "s";
}
if (!empty($_GET['category'])) {
    $query .= " AND c.category_name = ?";
    $filters[] = $_GET['category'];
    $types .= "s";
}
if (!empty($_GET['supervisor'])) {
    $query .= " AND s.supervisor_name = ?";
    $filters[] = $_GET['supervisor'];
    $types .= "s";
}
if (!empty($_GET['year'])) {
    $query .= " AND rp.publication_year = ?";
    $filters[] = (int) $_GET['year'];
    $types .= "i";
}

$query .= " ORDER BY rp.publication_year DESC, rp.paper_id DESC";

$stmt = $connection->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $connection->error . " | Query: " . $query);
}
if ($filters) {
    $stmt->bind_param($types, ...$filters);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Papers - AIU Repository</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/components.css">
    <style>
        .search-page {
            min-height: 100vh;
            background: rgb(204, 223, 240);
        }

        .page-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
        }

        .breadcrumb span {
            color: var(--gray-400);
        }

        .breadcrumb .current {
            color: var(--secondary);
            font-weight: 500;
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--gray-600);
            margin-bottom: 32px;
        }

        .search-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-md);
            margin-bottom: 32px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            
        }

        .search-grid .form-group.full-width {
            grid-column: span 3;
        }

        .search-bar {
            display: flex;
            gap: 12px;
        }

        .search-bar .form-input {
            flex: 1;
        }

        .filter-toggle {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
        }

        .filters-panel {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-100);
            
        }

        .filters-panel.show {
            display: block;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .results-count {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .paper-grid {
            display: grid;
            gap: 20px;
        }

        .paper-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-md);
            transition: all 0.2s;
            border: 1px solid var(--gray-100);
        }

        .paper-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .paper-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .paper-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--secondary);
            margin: 0 0 8px;
        }

        .paper-meta {
            display: flex;
            gap: 16px;
            margin-bottom: 12px;
        }

        .paper-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .paper-meta-item svg {
            width: 14px;
            height: 14px;
        }

        .paper-abstract {
            color: var(--gray-600);
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .paper-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--gray-100);
        }

        .paper-tags {
            display: flex;
            gap: 8px;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--primary-gradient);
            color: white;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
        }

        .no-results {
            text-align: center;
            padding: 60px;
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
        }

        .no-results svg {
            width: 64px;
            height: 64px;
            color: var(--gray-300);
            margin-bottom: 16px;
        }

        .no-results h3 {
            color: var(--gray-600);
            margin-bottom: 8px;
        }

        .no-results p {
            color: var(--gray-500);
        }

        @media (max-width: 768px) {
            .search-grid {
                grid-template-columns: 1fr;
            }

            .search-grid .form-group.full-width {
                grid-column: span 1;
            }

            .paper-meta {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="search-page">
        <!-- Navigation Header -->
        <header class="nav-header">
            <div class="container">
                <a href="index.php" class="nav-brand">
                    <img src="images/logo.png" alt="AIU Logo">
                    <span class="nav-brand-text">AIU <span>Repository</span></span>
                </a>
                <nav class="nav-user">
                    <span class="nav-user-name"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></span>
                    <a href="dashboards/student_dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>
                    <a href="access/logout.php" class="btn btn-danger btn-sm">Logout</a>
                </nav>
            </div>
        </header>

        <div class="page-container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="dashboards/student_dashboard.php">Dashboard</a>
                <span>›</span>
                <span class="current">Search & Download</span>
            </nav>

            <h1 class="page-title">Search Research Papers</h1>
            <p class="page-subtitle">Find and download approved research papers from the AIU repository.</p>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <div class="alert-content"><?php echo $error_message; ?></div>
                </div>
            <?php else: ?>
                <!-- Search & Filter -->
                <div class="search-card">
                    <form method="GET">
                        <div class="search-bar">
                            <input type="text" name="title" class="form-input" placeholder="Search by title..."
                                value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">
                            <button type="submit" class="btn btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="M21 21l-4.35-4.35" />
                                </svg>
                                Search
                            </button>
                        </div>

                        <button type="button" class="filter-toggle" onclick="toggleFilters()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46" />
                            </svg>
                            Advanced Filters
                        </button>

                        <div class="filters-panel" id="filtersPanel">
                            <div class="search-grid">
                                <div class="form-group">
                                    <label class="form-label">Abstract Contains</label>
                                    <input type="text" name="abstract" class="form-input" placeholder="Keywords in abstract"
                                        value="<?php echo isset($_GET['abstract']) ? htmlspecialchars($_GET['abstract']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Keyword</label>
                                    <input type="text" name="keyword" class="form-input" placeholder="Paper keyword"
                                        value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        <?php
                                        $cat_result = $connection->query("SELECT category_name FROM category");
                                        while ($cat = $cat_result->fetch_assoc()) {
                                            $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category_name']) ? 'selected' : '';
                                            echo "<option value='{$cat['category_name']}' $selected>{$cat['category_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Supervisor</label>
                                    <select name="supervisor" class="form-select">
                                        <option value="">All Supervisors</option>
                                        <?php
                                        $sup_result = $connection->query("SELECT supervisor_name FROM supervisor");
                                        while ($sup = $sup_result->fetch_assoc()) {
                                            $selected = (isset($_GET['supervisor']) && $_GET['supervisor'] == $sup['supervisor_name']) ? 'selected' : '';
                                            echo "<option value='{$sup['supervisor_name']}' $selected>{$sup['supervisor_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Year</label>
                                    <select name="year" class="form-select">
                                        <option value="">All Years</option>
                                        <?php
                                        $year_result = $connection->query("SELECT DISTINCT publication_year FROM research_paper ORDER BY publication_year DESC");
                                        while ($yr = $year_result->fetch_assoc()) {
                                            $selected = (isset($_GET['year']) && $_GET['year'] == $yr['publication_year']) ? 'selected' : '';
                                            echo "<option value='{$yr['publication_year']}' $selected>{$yr['publication_year']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Results -->
                <?php if ($result->num_rows > 0): ?>
                    <div class="results-header">
                        <span class="results-count"><?php echo $result->num_rows; ?> paper(s) found</span>
                    </div>

                    <div class="paper-grid">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="paper-card">
                                <div class="paper-header">
                                    <div>
                                        <h3 class="paper-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                                        <div class="paper-meta">
                                            <span class="paper-meta-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                    <line x1="16" y1="2" x2="16" y2="6" />
                                                    <line x1="8" y1="2" x2="8" y2="6" />
                                                    <line x1="3" y1="10" x2="21" y2="10" />
                                                </svg>
                                                <?php echo $row['publication_year']; ?>
                                            </span>
                                            <span class="paper-meta-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                                    <circle cx="12" cy="7" r="4" />
                                                </svg>
                                                <?php echo htmlspecialchars($row['supervisor_name']); ?>
                                            </span>
                                            <span class="paper-meta-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
        <circle cx="12" cy="7" r="4" />
    </svg>
    <?php echo htmlspecialchars($row['student_fname'] . ' ' . $row['student_lname']); ?>
</span>

                                            
                                            <span class="paper-meta-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                                    <polyline points="7,10 12,15 17,10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                                <?php echo $row['download_count'] ?? 0; ?> downloads
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <p class="paper-abstract">
                                    <?php echo htmlspecialchars(substr($row['abstract'], 0, 200)) . (strlen($row['abstract']) > 200 ? '...' : ''); ?>
                                </p>

                                <div class="paper-footer">
                                    <div class="paper-tags">
                                        <span
                                            class="badge badge-primary"><?php echo htmlspecialchars($row['category_name']); ?></span>
                                    </div>
                                    <a href="module3_search_download.php?download_id=<?php echo $row['paper_id']; ?>"
                                        class="download-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                            <polyline points="7,10 12,15 17,10" />
                                            <line x1="12" y1="15" x2="12" y2="3" />
                                        </svg>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        <h3>No papers found</h3>
                        <p>Try adjusting your search criteria or browse all approved papers.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const panel = document.getElementById('filtersPanel');
            panel.classList.toggle('show');
        }

        // Show filters if any are set
        <?php if (!empty($_GET['abstract']) || !empty($_GET['keyword']) || !empty($_GET['category']) || !empty($_GET['supervisor']) || !empty($_GET['year'])): ?>
            document.getElementById('filtersPanel').classList.add('show');
        <?php endif; ?>
    </script>
</body>

</html>