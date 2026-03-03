<?php
session_start();
require_once 'db_connect.php';

// Ensure logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: module1_student_access.php");
    exit;
}
$student_id = $_SESSION['student_id'];

// Get student info
$stmt = $connection->prepare("SELECT student_fname, student_lname FROM student WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($fname, $lname);
$stmt->fetch();
$stmt->close();

$message = "";
$messageType = "";

/* -------------------- REPORT GENERATION -------------------- */
/* -------------------- REPORT GENERATION -------------------- */
if (isset($_POST['generate_report'])) {
    $paper_id = intval($_POST['paper_id']);
    $report_type_id = intval($_POST['report_type_id']);
    $programme = isset($_POST['programme']) ? $_POST['programme'] : "";

    // Map programme names to fixed admin IDs
    $programme_admin_map = [
        "SCI"                  => 2001,
        "SBSS"                 => 2002,
        "Media & Communication"=> 2003,
        "Education"            => 2004
    ];

    // Validate programme
    if (!array_key_exists($programme, $programme_admin_map)) {
        die("❌ Invalid programme selected.");
    }

    $admin_id = $programme_admin_map[$programme];

    // Handle custom report type
    $custom_report_type = isset($_POST['custom_report_type']) ? trim($_POST['custom_report_type']) : "";
$custom_report_description = isset($_POST['custom_report_description']) ? trim($_POST['custom_report_description']) : null;

if (!empty($custom_report_type)) {
    $sql = "INSERT INTO report_type (type_name, description) VALUES (?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $custom_report_type, $custom_report_description);
    $stmt->execute();
    $report_type_id = $stmt->insert_id;
}


    // Insert into report table
    $sql = "INSERT INTO report (report_type_id, student_id, paper_id, created_at, admin_id)
            VALUES (?, ?, ?, CURDATE(), ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iiii", $report_type_id, $student_id, $paper_id, $admin_id);

    if ($stmt->execute()) {
        $message = "Report generated successfully!";
        $messageType = "success";
    } else {
        $message = "Report generation failed: " . $connection->error;
        $messageType = "error";
    }
}





/* -------------------- ADD CITATION -------------------- */
if (isset($_POST['add_citation'])) {
    $paper_id = intval($_POST['paper_id']);
    $source_id = intval($_POST['source_id']);
    $citation_date = $_POST['citation_date'];

    if (!empty($citation_date)) {
        $sql = "INSERT INTO citation (paper_id, source_id, citation_date) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iis", $paper_id, $source_id, $citation_date);
        if ($stmt->execute()) {
            $message = "Citation added successfully!";
            $messageType = "success";
        } else {
            $message = "Citation insert failed: " . $connection->error;
            $messageType = "error";
        }
    } else {
        $message = "Citation date is required.";
        $messageType = "error";
    }
}

/* -------------------- ADD SOURCE -------------------- */
if (isset($_POST['add_source'])) {
    $source_name = trim($_POST['source_name']);
    $source_type = trim($_POST['source_type']);
    $publisher   = trim($_POST['publisher']);
    $year        = intval($_POST['year']);

    $sql = "INSERT INTO citation_sourse (source_name, source_type, publisher, year) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssi", $source_name, $source_type, $publisher, $year);

    if ($stmt->execute()) {
        $message = "Source added successfully!";
        $messageType = "success";
    } else {
        $message = "Source insert failed: " . $connection->error;
        $messageType = "error";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Citations - AIU Repository</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/components.css">
    <style>
        .module-page {
            min-height: 100vh;
            background: #cae5f4;
        }

        .page-container {
            max-width: 1000px;
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
            color: var(--gray-500);
            margin-bottom: 32px;
        }

        .tab-container {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .tab-nav {
            display: flex;
            border-bottom: 1px solid var(--gray-100);
        }

        .tab-btn {
            flex: 1;
            padding: 16px 24px;
            background: none;
            border: none;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--gray-500);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            position: relative;
        }

        .tab-btn:hover {
            color: var(--primary);
            background: rgba(108, 99, 255, 0.05);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary);
        }

        .tab-content {
            padding: 24px;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .action-card {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
        }

        .action-card h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            font-size: 1rem;
            color: var(--secondary);
        }

        .action-card h4 svg {
            color: var(--primary);
        }

        .inline-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .inline-form .form-group {
            margin: 0;
        }

        .reports-list {
            margin-top: 24px;
        }

        .report-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--white);
            border: 1px solid var(--gray-100);
            border-radius: var(--radius-md);
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .report-item:hover {
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(108, 99, 255, 0.1);
        }

        .report-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .report-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: rgba(108, 99, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .report-details h5 {
            margin: 0 0 4px;
            font-size: 0.9375rem;
        }

        .report-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .citation-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .tab-btn span {
                display: none;
            }

            .inline-form {
                flex-direction: column;
            }

            .inline-form .form-group,
            .inline-form .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="module-page">
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
                <span class="current">Reports & Citations</span>
            </nav>

            <h1 class="page-title">Reports & Citations</h1>
            <p class="page-subtitle">Generate reports for your papers and manage citation records.</p>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <span class="alert-icon"><?php echo $messageType === 'error' ? '⚠️' : '✅'; ?></span>
                    <div class="alert-content"><?php echo $message; ?></div>
                </div>
            <?php endif; ?>

            <div class="tab-container">
                <div class="tab-nav">
                    <button class="tab-btn active" onclick="openTab(event, 'reports')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14,2 14,8 20,8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        <span>Generate Reports</span>
                    </button>
                    <button class="tab-btn" onclick="openTab(event, 'citations')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                        <span>Add Citations</span>
                    </button>
                    <button class="tab-btn" onclick="openTab(event, 'history')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        <span>History</span>
                    </button>
                </div>

                <div class="tab-content">
                    <!-- Reports Tab -->
                    <div id="reports" class="tab-panel active">
                        <div class="action-card">
                            <h4>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                </svg>
                                Generate New Report
                            </h4>
                            <form method="POST" class="inline-form">

    <div class="form-group">
        <label class="form-label">Select Paper</label>
        <select name="paper_id" class="form-select" required>
            <option value="" disabled selected>Choose paper...</option>
            <?php
            $papers = $connection->query("SELECT paper_id, title FROM research_paper");
            while ($p = $papers->fetch_assoc()) {
                echo "<option value='{$p['paper_id']}'>" . htmlspecialchars($p['title']) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
    <label class="form-label">Report Type</label>
    <select name="report_type_id" class="form-select">
        <option value="">-- Select Existing Type --</option>
        <?php
        $types = $connection->query("SELECT * FROM report_type");
        while ($t = $types->fetch_assoc()) {
            echo "<option value='{$t['report_type_id']}'>" . htmlspecialchars($t['type_name']) . "</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label class="form-label">Or Add New Report Type</label>
    <input type="text" name="custom_report_type" class="form-input" placeholder="Enter new type">
</div>

<div class="form-group">
    <label class="form-label">Report Description</label>
    <textarea name="custom_report_description" class="form-textarea" placeholder="Enter description (optional)"></textarea>
</div>



    <div class="form-group">
    <label class="form-label">Programme</label>
    <select name="programme" class="form-select" required>
        <option value="">-- Select Programme --</option>
        <option value="SCI">SCI 2001</option>
        <option value="SBSS">SBSS 2002</option>
        <option value="Media & Communication">Media & Communication 2003</option>
        <option value="Education">Education 2004</option>
    </select>
</div>


    <button type="submit" name="generate_report" class="btn btn-primary">
        Generate
    </button>
</form>

                        </div>
                    </div>

                    <!-- Citations Tab -->
                    <div id="citations" class="tab-panel">
                        <div class="action-card">
                            <h4>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                                Add New Citation
                            </h4>
                            <form method="POST" class="inline-form">
                                <div class="form-group">
                                    <label class="form-label">Paper</label>
                                    <select name="paper_id" class="form-select" required>
                                        <option value="" disabled selected>Choose paper...</option>
                                        <?php
                                        $papers = $connection->query("SELECT paper_id, title FROM research_paper WHERE student_id = $student_id");
                                        while ($p = $papers->fetch_assoc()) {
                                            echo "<option value='{$p['paper_id']}'>" . htmlspecialchars($p['title']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
    <label class="form-label">Source</label>
    <select name="source_id" class="form-select" required>
        <option value="">-- Select Source --</option>
        <?php
        $sources = $connection->query("SELECT source_id, source_name, source_type FROM citation_sourse");
        while ($s = $sources->fetch_assoc()) {
            echo "<option value='{$s['source_id']}'>" . htmlspecialchars($s['source_name']) . " ({$s['source_type']})</option>";
        }
        ?>
    </select>
</div>

                                <div class="form-group">
                                    <label class="form-label">Citation Date</label>
                                    <input type="date" name="citation_date" class="form-input" required>
                                </div>
                                <button type="submit" name="add_citation" class="btn btn-primary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                    Add Citation
                                </button>
                            </form>
                            <!-- Add New Source form goes here -->
        <h5>Add New Source</h5>
        <form method="POST" class="inline-form">
            <input type="text" name="source_name" placeholder="Source Name" required>
            <input type="text" name="source_type" placeholder="Type (e.g., Journal)" required>
            <input type="text" name="publisher" placeholder="Publisher" required>
            <input type="number" name="year" placeholder="Year" required>
            <button type="submit" name="add_source" class="btn btn-secondary">+ Add Source</button>
        </form>
                        </div>
                    </div>

                    <!-- History Tab -->
                    <div id="history" class="tab-panel">
                        <h4 style="margin-bottom: 16px;">Your Generated Reports</h4>
                        <div class="reports-list">
                            <?php
                            $history = $connection->query("SELECT r.report_id, rt.type_name, rp.title, r.created_at 
                                                           FROM report r
                                                           JOIN report_type rt ON r.report_type_id = rt.report_type_id
                                                           JOIN research_paper rp ON r.paper_id = rp.paper_id
                                                           WHERE r.student_id = $student_id
                                                           ORDER BY r.created_at DESC");

                            if ($history->num_rows === 0) {
                                echo '<p style="text-align: center; color: var(--gray-500); padding: 40px;">No reports generated yet.</p>';
                            }

                            while ($h = $history->fetch_assoc()) {
                                ?>
                                <div class="report-item">
                                    <div class="report-info">
                                        <div class="report-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                <polyline points="14,2 14,8 20,8" />
                                            </svg>
                                        </div>
                                        <div class="report-details">
                                            <h5><?php echo htmlspecialchars($h['type_name']); ?></h5>
                                            <p><?php echo htmlspecialchars($h['title']); ?></p>
                                        </div>
                                    </div>
                                    <span
                                        class="badge badge-primary"><?php echo date('M j, Y', strtotime($h['created_at'])); ?></span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected panel and activate button
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>
</body>

</html>