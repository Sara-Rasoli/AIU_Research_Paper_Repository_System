<?php
session_start();
require_once 'db_connect.php'; // adjust path if needed

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: module1_student_access.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$message = "";
$messageType = "";

// Fetch student info
$sql_student = "SELECT student_fname, student_lname, student_email, status_id 
                FROM student WHERE student_id = ?";
$stmt = $connection->prepare($sql_student);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $email, $status_id);
$stmt->fetch();
$stmt->close();

// Map status_id to text
$status_map = [1 => "Active", 2 => "Suspended", 3 => "Inactive"];
$status_text = isset($status_map[$status_id]) ? $status_map[$status_id] : "Unknown";

// Handle paper submission only if Active
if ($status_id == 1 && isset($_POST['submit_paper'])) {
    $title    = $_POST['title'];
    $abstract = $_POST['abstract'];
    $year     = $_POST['year'];

    // Category handling
    if (isset($_POST['category_id']) && $_POST['category_id'] === 'new' && !empty($_POST['new_category'])) {
        $new_category = $_POST['new_category'];
        $sql_new_cat = "INSERT INTO category (category_name) VALUES (?)";
        $stmt_cat = $connection->prepare($sql_new_cat);
        $stmt_cat->bind_param("s", $new_category);
        $stmt_cat->execute();
        $category_id = $stmt_cat->insert_id;
        $stmt_cat->close();
    } else {
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
    }

    // Supervisor handling
    if (isset($_POST['supervisor_id']) && $_POST['supervisor_id'] === 'new' && !empty($_POST['new_supervisor'])) {
        $new_supervisor = $_POST['new_supervisor'];
        $sql_new_sup = "INSERT INTO supervisor (supervisor_name) VALUES (?)";
        $stmt_sup = $connection->prepare($sql_new_sup);
        $stmt_sup->bind_param("s", $new_supervisor);
        $stmt_sup->execute();
        $supervisor_id = $stmt_sup->insert_id;
        $stmt_sup->close();
    } else {
        $supervisor_id = isset($_POST['supervisor_id']) ? $_POST['supervisor_id'] : null;
    }

    // Handle file upload safely
    $file_name = isset($_FILES['pdf_file']['name']) ? $_FILES['pdf_file']['name'] : null;
    $file_tmp  = isset($_FILES['pdf_file']['tmp_name']) ? $_FILES['pdf_file']['tmp_name'] : null;
    $upload_dir = "uploads/";

    if ($file_name && $file_tmp) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Store relative path instead of Windows path
        $file_path = "uploads/" . basename($file_name);

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert into research_paper
            $sql_paper = "INSERT INTO research_paper 
                          (student_id, title, abstract, publication_year, category_id, supervisor_id, file_path) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql_paper);
            $stmt->bind_param("issiiis", $student_id, $title, $abstract, $year, $category_id, $supervisor_id, $file_path);

            if ($stmt->execute()) {
                $paper_id = $stmt->insert_id;

                // ✅ Handle keywords
                if (!empty($_POST['keywords'])) {
                    $keywords = explode(",", $_POST['keywords']); // split by comma
                    foreach ($keywords as $kw) {
                        $kw = trim($kw);
                        if ($kw == "") continue;

                        // Check if keyword already exists
                        $sql_kw = "SELECT keyword_id FROM keyword WHERE keyword_name = ?";
                        $stmt_kw = $connection->prepare($sql_kw);
                        $stmt_kw->bind_param("s", $kw);
                        $stmt_kw->execute();
                        $stmt_kw->bind_result($keyword_id);

                        if ($stmt_kw->fetch()) {
                            // keyword exists
                        } else {
                            // Insert new keyword
                            $sql_kw_insert = "INSERT INTO keyword (keyword_name) VALUES (?)";
                            $stmt_kw_insert = $connection->prepare($sql_kw_insert);
                            $stmt_kw_insert->bind_param("s", $kw);
                            $stmt_kw_insert->execute();
                            $keyword_id = $stmt_kw_insert->insert_id;
                            $stmt_kw_insert->close();
                        }
                        $stmt_kw->close();

                        // Link paper ↔ keyword
                        $sql_link = "INSERT INTO paper_keyword (paper_id, keyword_id) VALUES (?, ?)";
                        $stmt_link = $connection->prepare($sql_link);
                        $stmt_link->bind_param("ii", $paper_id, $keyword_id);
                        $stmt_link->execute();
                        $stmt_link->close();
                    }
                }

                // Insert into paper_approval with Pending status
                $sql_approval = "INSERT INTO paper_approval (paper_id, approval_status, admin_id) 
                                 VALUES (?, 'Pending', NULL)";
                $stmt2 = $connection->prepare($sql_approval);
                $stmt2->bind_param("i", $paper_id);
                $stmt2->execute();
                $stmt2->close();

                $message = "Paper submitted successfully and is now pending approval.";
                $messageType = "success";
            } else {
                $message = "Error inserting paper: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "File upload failed.";
            $messageType = "error";
        }
    } else {
        $message = "No file uploaded.";
        $messageType = "error";
    }
}
?>

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Submission - AIU Repository</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/components.css">
    <style>
        .module-page {
            min-height: 100vh;
            background: #d3f2fc;
        }

        .page-container {
            max-width: 900px;
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
            color: #100445;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .breadcrumb span {
            color: var(--gray-400);
        }

        .breadcrumb .current {
            color: var(--secondary);
            font-weight: 500;
        }

        .user-banner {
            background: rgb(3, 58, 112);
            color: white;
            padding: 24px 32px;
            border-radius: var(--radius-xl);
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-banner h2 {
            color: white;
            margin: 0 0 4px;
            font-size: 1.25rem;
        }

        .user-banner p {
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            font-size: 0.875rem;
        }

        .submission-card {
            background: #f9fafa;
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: #000;
            margin-bottom: 32px;
        }

        .submission-card h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            font-size: 1.25rem;
            color: var(--secondary);
        }

        .submission-card h3 svg {
            color: var(--primary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-grid .form-group.full-width {
            grid-column: span 2;
        }

        .file-upload-zone {
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-lg);
            padding: 40px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .file-upload-zone:hover {
            border-color: var(--primary);
            background: rgba(187, 211, 233, 0.49);
        }

        .file-upload-zone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            margin-bottom: 12px;
            color: var(--gray-500);
        }

        .file-upload-text {
            color: var(--gray-600);
            font-weight: 500;
        }

        .file-upload-hint {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-top: 8px;
        }

        

        .papers-section {
            background: #034467;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            color: #fff;
        }

        .section-header h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            font-size: 1.125rem;
            color: #fff
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all 0.2s;
        }

        .action-btn-edit {
            background: rgba(59, 130, 246, 0.1);
            color: #0a3490;
        }

        .action-btn-edit:hover {
            background: rgba(252, 253, 253, 0.2);
        }

        .action-btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
        }

        .action-btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .status-disabled {
            text-align: center;
            padding: 48px;
            background: rgba(245, 158, 11, 0.1);
            border-radius: var(--radius-xl);
            margin-bottom: 32px;
        }

        .status-disabled h3 {
            color: #B45309;
            margin-bottom: 8px;
        }

        .status-disabled p {
            color: var(--gray-600);
            margin: 0;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-grid .form-group.full-width {
                grid-column: span 1;
            }

            .user-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
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
                <span class="current">Paper Submission</span>
            </nav>

            <!-- User Banner -->
            <div class="user-banner">
                <div>
                    <h2>Welcome, <?php echo htmlspecialchars($fname . " " . $lname); ?></h2>
                    <p><?php echo htmlspecialchars($email); ?></p>
                </div>
                <span class="badge badge-<?php echo strtolower($status_text); ?>"><?php echo $status_text; ?></span>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <span class="alert-icon"><?php echo $messageType === 'error' ? '⚠️' : '✅'; ?></span>
                    <div class="alert-content"><?php echo $message; ?></div>
                </div>
            <?php endif; ?>

            <?php if ($status_id != 1): ?>
                <div class="status-disabled">
                    <h3>⚠️ Account Status: <?php echo htmlspecialchars($status_text); ?></h3>
                    <p>You cannot upload papers while your account status is <?php echo strtolower($status_text); ?>.</p>
                </div>
            <?php else: ?>
                <!-- Submission Form -->
<div class="submission-card">
    <h3>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" 
             stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
            <polyline points="14,2 14,8 20,8" />
            <line x1="12" y1="18" x2="12" y2="12" />
            <line x1="9" y1="15" x2="15" y2="15" />
        </svg>
        Submit New Paper
    </h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">

            <!-- Paper Title -->
            <div class="form-group full-width">
                <label class="form-label required">Paper Title</label>
                <input type="text" name="title" class="form-input" 
                       placeholder="Enter your paper title" required>
            </div>

            <!-- Abstract -->
            <div class="form-group full-width">
                <label class="form-label required">Abstract</label>
                <textarea name="abstract" class="form-textarea"
                          placeholder="Provide a brief summary of your paper..." required></textarea>
            </div>

            <!-- Publication Year -->
            <div class="form-group">
                <label class="form-label required">Publication Year</label>
                <input type="number" name="year" class="form-input" 
                       placeholder="e.g. 2026" value="2026" required>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label class="form-label required">Category</label>
                <select name="category_id" id="category_select" class="form-select"
                        onchange="toggleCategoryInput(this.value)" required>
                    <option value="" disabled selected>-- Select Category --</option>
                    <?php
                    $cat_result = $connection->query("SELECT category_id, category_name FROM category");
                    while ($row = $cat_result->fetch_assoc()) {
                        echo "<option value='{$row['category_id']}'>" . htmlspecialchars($row['category_name']) . "</option>";
                    }
                    ?>
                    <option value="new">+ Add New Category</option>
                </select>
                <input type="text" name="new_category" id="new_category" class="form-input"
                       style="display:none; margin-top: 10px;" placeholder="Enter new category name">
            </div>

            <!-- Supervisor -->
            <div class="form-group">
                <label class="form-label required">Supervisor</label>
                <select name="supervisor_id" id="supervisor_select" class="form-select"
                        onchange="toggleSupervisorInput(this.value)" required>
                    <option value="" disabled selected>-- Select Supervisor --</option>
                    <?php
                    $sup_result = $connection->query("SELECT supervisor_id, supervisor_name FROM supervisor");
                    while ($row = $sup_result->fetch_assoc()) {
                        echo "<option value='{$row['supervisor_id']}'>" . htmlspecialchars($row['supervisor_name']) . "</option>";
                    }
                    ?>
                    <option value="new">+ Add New Supervisor</option>
                </select>
                <input type="text" name="new_supervisor" id="new_supervisor" class="form-input"
                       style="display:none; margin-top: 10px;" placeholder="Enter new supervisor name">
            </div>

            <!-- ✅ Keywords -->
            <div class="form-group full-width">
                <label class="form-label required">Keywords</label>
                <input type="text" name="keywords" class="form-input"
                       placeholder="Enter keywords separated by commas" required>
            </div>

            <!-- PDF File -->
            <div class="form-group full-width">
                <label class="form-label required">PDF File</label>
                <div class="file-upload-zone">
                    <input type="file" name="pdf_file" accept="application/pdf" required>
                    <div class="file-upload-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" 
                             stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="17,8 12,3 7,8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </div>
                    <div class="file-upload-text">Click to upload or drag and drop</div>
                    <div class="file-upload-hint">PDF files only (max 10MB)</div>
                </div>
            </div>

        </div>

        <!-- Submit Button -->
        <div style="margin-top: 24px;">
            <button type="submit" name="submit_paper" class="btn btn-primary btn-lg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" 
                     stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                    <polyline points="17,8 12,3 7,8" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                Upload Paper
            </button>
        </div>
    </form>
</div>

            <?php endif; ?>

            <!-- Your Papers -->
            <div class="papers-section">
                <div class="section-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14,2 14,8 20,8" />
                        </svg>
                        Your Submitted Papers
                    </h3>
                </div>
                <div class="table-container" style="box-shadow: none; border-radius: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_papers = "SELECT rp.paper_id, rp.title, rp.publication_year, rp.file_path, pa.approval_status 
               FROM research_paper rp
               LEFT JOIN paper_approval pa ON rp.paper_id = pa.paper_id
               WHERE rp.student_id = ?";

                            $stmt = $connection->prepare($sql_papers);
                            $stmt->bind_param("i", $student_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo '<tr><td colspan="4" style="text-align: center; padding: 40px; color: var(--gray-500);">No papers submitted yet.</td></tr>';
                            }

                            while ($row = $result->fetch_assoc()) {
                                $status = $row['approval_status'] ? $row['approval_status'] : 'Pending';
                                $status_class = strtolower($status);
                                ?>
                                <tr>
    <td style="font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo $row['publication_year']; ?></td>
    <td>
        <span class="badge badge-<?php echo $status_class; ?>">
            <?php echo htmlspecialchars($status); ?>
        </span>
    </td>
    <td>
        <div class="action-buttons">
            <a href="actions/edit_paper.php?id=<?php echo $row['paper_id']; ?>"
               class="action-btn action-btn-edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit
            </a>

            <a href="actions/delete_paper.php?id=<?php echo $row['paper_id']; ?>"
               class="action-btn action-btn-delete"
               onclick="return confirm('Are you sure?');">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <polyline points="3,6 5,6 21,6" />
                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                </svg>
                Delete
            </a>

            <!-- ✅ View Paper button -->
            <a href="<?php echo htmlspecialchars($row['file_path']); ?>"
               target="_blank" class="btn btn-secondary btn-sm">
               View Paper
            </a>
        </div>
    </td>
</tr>

                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCategoryInput(value) {
            document.getElementById('new_category').style.display = (value === 'new') ? 'block' : 'none';
        }
        function toggleSupervisorInput(value) {
            document.getElementById('new_supervisor').style.display = (value === 'new') ? 'block' : 'none';
        }
    </script>
</body>

</html>