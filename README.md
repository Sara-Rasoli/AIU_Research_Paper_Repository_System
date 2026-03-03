# 📚 AIU Research Paper Repository System

A web-based academic paper repository built using **PHP, MySQL, and XAMPP** for Albukhary International University coursework.

This system allows students to submit research papers, manage categories and supervisors, and track approval status.  
Administrators can review submissions, approve/reject papers, and manage student accounts.

---

## 🚀 Features

### 👩‍🎓 Student Portal
- Secure login with session management
- Submit new research papers including:
  - Title
  - Abstract
  - Publication Year
  - Category (select existing or add new)
  - Supervisor (select existing or add new)
  - Keywords (comma-separated, linked to papers)
- Upload PDF files (stored in `/uploads/`)
- View submitted papers with approval status
- Edit or delete own submissions

---

### 🧑‍💼 Admin Dashboard
- Manage paper approvals (Approve, Reject, Pending)
- View full paper details and open uploaded PDFs
- Manage student accounts:
  - Update account status (Active, Suspended, Inactive)
  - Delete student accounts (removes associated papers)
- Generate reports

---

## ⚙️ Technical Highlights

### 🗄 Database Schema

Main tables:
- `student`
- `research_paper`
- `category`
- `supervisor`
- `keyword`
- `paper_keyword`
- `paper_approval`

### 📂 File Handling
- Secure PDF upload system
- Uploaded files stored in `/uploads/`
- Relative paths stored in database (`uploads/filename.pdf`)
- No absolute Windows paths stored

### 🎨 UI/UX
- Responsive layout with custom CSS
- Clear action buttons (Edit, Delete, View)
- Status badges (Approved, Pending, Rejected)

---

## 🛠️ Installation Guide

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/yourusername/aiu-paper-repository.git
2️⃣ Move Project to XAMPP

Move the folder into:

C:\xampp\htdocs\design02_aiu_paper_repository_db\
3️⃣ Import Database

Open phpMyAdmin

Create a new database:

aiu_paper_repository_db

Import database.sql (included in this repository)

4️⃣ Configure Database Connection

Open db_connect.php and update:

$connection = new mysqli("localhost", "root", "", "aiu_paper_repository_db");
5️⃣ Start XAMPP

Start:

Apache

MySQL

6️⃣ Access the System

Open in browser:

http://localhost/design02_aiu_paper_repository_db/
📂 Project Structure
design02_aiu_paper_repository_db/
│── dashboards/                    # Student & Admin dashboards
│── actions/                       # Edit/Delete paper actions
│── uploads/                       # PDF storage
│── styles/                        # CSS files
│── images/                        # Logos and assets
│── db_connect.php                 # Database connection
│── index.php                      # Landing page
│── module1_student_access.php     # Student login
│── module2_submission.php         # Paper submission
│── module3_search_download.php    # Search & download module
│── module4_reports_citations.php  # Reports & citations
🧪 Usage
Students

Register / Login

Submit research papers

Track approval status

Admins

Login to dashboard

Review submissions

Approve or reject papers

Manage student accounts

🔒 Security Features

Session-based authentication

File uploads restricted to PDF format

Relative file paths stored in database

Confirmation prompts for delete actions

Basic input validation

📌 Future Improvements

Search and filter papers by keyword or category

Export reports (PDF / Excel)

Email notifications for approval status

Advanced role-based access control

Improved UI enhancements

👩‍💻 Author

Sara Rasoli
Computer Science Student
Albukhary International University
