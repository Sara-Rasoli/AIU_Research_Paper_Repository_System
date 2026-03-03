<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIU Research Paper Repository</title>
    <meta name="description"
        content="Albukhary International University's official academic research paper repository. Submit, search, and download research papers.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/components.css">
    <style>
:root {
    --primary: #1f3a5f;        /* dark academic navy */
    --primary-dark: #162c46;   /* deeper shade for hover */
    --secondary: #274c77;      /* complementary dark blue */
    --accent: #3c6e71;         /* teal accent if needed */
    --gray-50: #f8f9fa;
    --gray-400: #adb5bd;
    --gray-500: #6c757d;
}

/* Landing Page Background */
.landing-page {
    min-height: 100vh;
    background: url("./images/background.jpeg") center center / cover no-repeat;

    position: relative;
    z-index: 0;
}

.background-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 1;
}

.hero,
.features-section,
.cta-section,
.footer,
.nav-header {
    position: relative;
    z-index: 2;
}


/* Dark blur overlay */
.landing-page::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.55);   /* dark tint */
    backdrop-filter: blur(4px);        /* subtle blur */
    z-index: 1;
}

/* Ensure content sits above overlay */
.hero,
.features-section,
.cta-section,
.footer,
.nav-header {
    position: relative;
    z-index: 2;
}

/* Hero Section */
.hero {
    padding: 100px 0 80px;
    text-align: center;
    color: #fff;
}

.hero-subtitle {
    display: inline-block;
    background: rgba(31, 58, 95, 0.2);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
    color: #fff;
}

.hero h1 {
    font-size: 3.5rem;
    line-height: 1.1;
    margin-bottom: 20px;
    color: #fff;
}

.hero p {
    color: #f8f9fa;
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.features-header {
    text-align: center;
    margin-bottom: 60px;
}

.features-header h2 {
    font-size: 2.25rem;
    margin-bottom: 16px;
    color: var(--primary);
}

.features-header p {
    color: var(--gray-500);
    max-width: 600px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.feature-number {
    position: absolute;
    top: 16px;
    left: 16px;
    width: 28px;
    height: 28px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
}

.feature-card {
    position: relative;
}

/* CTA Section */
.cta-section {
    background: var(--secondary);
    padding: 80px 0;
    text-align: center;
}

.cta-section h2 {
    color: white;
    font-size: 2rem;
    margin-bottom: 16px;
}

.cta-section p {
    color: var(--gray-400);
    margin-bottom: 32px;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-outline {
    border: 2px solid #fff;
    color: #fff;
    background: transparent;
}

.btn-outline:hover {
    background: #fff;
    color: var(--primary);
}

/* Responsive */
@media (max-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.25rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}


    </style>
</head>

<body>
    <div class="background-overlay"></div>

        <!-- Navigation Header -->
        <header class="nav-header">
            <div class="container">
                <a href="index.php" class="nav-brand">
                    <img src="images/logo.png" alt="AIU Logo">
                    <span class="nav-brand-text">AIU <span>Repository</span></span>
                </a>
                <nav class="nav-links">
                    <a href="module1_student_access.php" class="btn btn-primary">Login / Register</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="container hero-content">
                <span class="hero-subtitle">📚 Academic Research Platform</span>
                <h1>AIU Research Paper<br>Repository</h1>
                <p>Your centralized platform for submitting, discovering, and accessing academic research papers at
                    Albukhary International University.</p>
                <a href="module1_student_access.php" class="btn btn-lg"
                    style="background: white; color: var(--primary);">
                    Get Started
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </section>

        <!-- Features/Modules Section -->
        <section class="features-section">
            <div class="container">
                <div class="features-header">
                    <h2>System Modules</h2>
                    <p>Explore the comprehensive features of our research paper repository management system.</p>
                </div>

                <div class="features-grid">
                    <!-- Module 1 -->
                    <a href="module1_student_access.php" class="feature-card animate-fade-in-up stagger-1">
                        <span class="feature-number">1</span>
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Student & Access</h3>
                        <p class="feature-description">Manage student accounts, login credentials, and access control
                            with secure authentication.</p>
                    </a>

                    <!-- Module 2 -->
                    <a href="module2_submission.php" class="feature-card animate-fade-in-up stagger-2">
                        <span class="feature-number">2</span>
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14,2 14,8 20,8" />
                                <line x1="12" y1="18" x2="12" y2="12" />
                                <line x1="9" y1="15" x2="15" y2="15" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Paper Submission</h3>
                        <p class="feature-description">Submit research papers with metadata, track approval status, and
                            manage uploads.</p>
                    </a>

                    <!-- Module 3 -->
                    <a href="module3_search_download.php" class="feature-card animate-fade-in-up stagger-3">
                        <span class="feature-number">3</span>
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Search & Download</h3>
                        <p class="feature-description">Search approved papers by title, category, or supervisor and
                            download instantly.</p>
                    </a>

                    <!-- Module 4 -->
                    <a href="module4_reports_citations.php" class="feature-card animate-fade-in-up stagger-4">
                        <span class="feature-number">4</span>
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Reports & Citations</h3>
                        <p class="feature-description">Track citations and generate comprehensive academic impact
                            reports.</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2>Ready to get started?</h2>
                <p>Join our academic community and contribute to research excellence.</p>
                <div class="cta-buttons">
                    <a href="module1_student_access.php" class="btn btn-primary btn-lg">Login to Your Account</a>
                    <a href="access/register.php" class="btn btn-outline btn-lg"
                        style="border-color: white; color: white;">Create New Account</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <div class="footer-brand-text">AIU Repository</div>
                        <p>Albukhary International University's official academic research paper repository.</p>
                    </div>
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="module1_student_access.php">Login</a></li>
                            <li><a href="access/register.php">Register</a></li>
                            <li><a href="module3_search_download.php">Search Papers</a></li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h4>Modules</h4>
                        <ul>
                            <li><a href="module1_student_access.php">Student Access</a></li>
                            <li><a href="module2_submission.php">Paper Submission</a></li>
                            <li><a href="module4_reports_citations.php">Reports</a></li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h4>Contact</h4>
                        <ul>
                            <li><a href="#">AIU Support</a></li>
                            <li><a href="#">University Website</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2026 Albukhary International University. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>