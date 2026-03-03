📚 AIU Research Paper Repository System
A web-based academic paper repository built with PHP, MySQL, and XAMPP, designed for Albukhary International University coursework.
This system allows students to submit research papers, manage categories and supervisors, and track approval status.
Administrators can review, approve/reject submissions, and manage student accounts.

🚀 Features
👩‍🎓 Student Portal
Secure login and session management

Submit new papers with:

Title, abstract, publication year

Category (choose existing or add new)

Supervisor (choose existing or add new)

Keywords (comma-separated, linked to papers)

PDF file upload (stored in /uploads/)

View submitted papers with approval status

Edit or delete own submissions

🧑‍💼 Admin Dashboard
Manage paper approvals (Approve, Reject, Pending)

View paper details and open PDFs

Manage student accounts:

Update status (Active, Suspended, Inactive)

Delete accounts (removes associated papers)

Generate reports

⚙️ Technical Highlights
Database schema with tables:

student, research_paper, category, supervisor, keyword, paper_keyword, paper_approval

File handling:

Safe PDF upload

Relative paths stored in DB (uploads/filename.pdf)

UI/UX:

Responsive design with custom CSS

Action buttons for edit, delete, view

Status badges (Approved, Pending, Rejected)

🛠️ Installation
Clone the repository:

bash
git clone https://github.com/yourusername/aiu-paper-repository.git
Move project into XAMPP htdocs:

Code
C:\xampp\htdocs\design02_aiu_paper_repository_db\
Import the database:

Open phpMyAdmin

Create a database (e.g., aiu_paper_repository_db)

Import database.sql (included in repo)

Configure database connection:

Edit db_connect.php with your MySQL credentials:

php
$connection = new mysqli("localhost", "root", "", "aiu_paper_repository_db");
Start Apache & MySQL in XAMPP.

Access system:

Code
http://localhost/design02_aiu_paper_repository_db/
📂 Project Structure
Code
design02_aiu_paper_repository_db/
│── dashboards/          # Student & Admin dashboards
│── actions/             # Edit/Delete paper actions
│── uploads/             # PDF storage
│── styles/              # CSS files
│── images/              # Logos and assets
│── db_connect.php       # Database connection
│── index.php            # Landing page
│── module1_student_access.php  # Student login
│── module2_submission.php      # Paper submission
│── ...
🧪 Usage
Students: Register/login → Submit papers → Track approval status.
Admins: Login → Review papers → Approve/Reject → Manage students.

🔒 Security Notes
Session-based authentication
File uploads restricted to PDF
Relative paths stored in DB (no absolute Windows paths)
Confirmation prompts for delete actions
Role-based access control

👩‍💻 Author
Developed by Sara Rasoli  
Albukhary International University
