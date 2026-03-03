<?php
session_start();
require_once 'db_connect.php'; // central DB connection file

$message = "";
$messageType = "";

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_login = "SELECT * FROM account WHERE username = ?";
    $stmt = $connection->prepare($sql_login);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $role = $row['role_type'];
            $account_id = $row['account_id'];

            // Set session variables
            $_SESSION['account_id'] = $account_id;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $role;

            if ($role == 'STUDENT') {
                $_SESSION['student_id'] = $account_id;
                header("Location: dashboards/student_dashboard.php");
            } elseif ($role == 'ADMIN') {
                $_SESSION['admin_id'] = $account_id;
                header("Location: dashboards/admin_dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid password. Please try again.";
            $messageType = "error";
        }
    } else {
        $message = "Account not found. Please check your username.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AIU Research Paper Repository</title>
    <meta name="description" content="Login to access the AIU Research Paper Repository system.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/components.css">
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #94d0ee 0%, #023d5b 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .auth-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 0.6s ease;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo img {
            height: 70px;
            width: auto;
            margin-bottom: 16px;
        }

        .auth-logo h1 {
            font-size: 1.5rem;
            color: var(--secondary);
            margin: 0;
        }

        .auth-logo p {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin: 8px 0 0;
        }

        .auth-form {
            margin-top: 32px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            width: 20px;
            height: 20px;
        }

        .input-icon-wrapper .form-input {
            padding-left: 48px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        .auth-footer p {
            color: var(--gray-600);
            margin: 0;
            font-size: 0.875rem;
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }

        .back-link {
            position: absolute;
            top: 24px;
            left: 24px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <a href="index.php" class="back-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Home
        </a>

        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-logo">
                    <img src="images/logo.png" alt="AIU Logo">
                    <h1>Welcome Back</h1>
                    <p>Sign in to access your account</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <span class="alert-icon"><?php echo $messageType === 'error' ? '⚠️' : '✅'; ?></span>
                        <div class="alert-content"><?php echo $message; ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <input type="text" name="username" class="form-input" placeholder="Enter your username"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" name="password" class="form-input" placeholder="Enter your password"
                                required>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary btn-block btn-lg">
                        Sign In
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="access/register.php">Create New Account</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>