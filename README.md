# MindScan - Effects of Social Media on Mental Health

A university capstone research platform that analyzes social media behavior and generates AI-inspired mental health reports. Built with HTML, CSS, JavaScript, Bootstrap 5, PHP, and MySQL.

## Features
- AI-style mental health assessment with 10 questions
- Rule-based scoring and risk classification
- Interactive research dashboard with Chart.js
- Report generation with jsPDF and voice narration
- Admin dashboard with analytics, CSV export, and record management
- Dark/light mode, glassmorphism UI, animations

## Tech Stack
- Frontend: HTML5, CSS3, JavaScript, Bootstrap 5
- Backend: PHP
- Database: MySQL
- Libraries: Chart.js, AOS, Font Awesome, SweetAlert, jsPDF

## Setup (XAMPP / WAMP)
1. Place the project folder inside your web root (e.g., `htdocs/MindScan`).
2. Start Apache and MySQL from XAMPP/WAMP control panel.
3. Import the database schema:
   - Open phpMyAdmin
   - Create a database named `mental_health_system`
   - Import [database/mental_health_system.sql](database/mental_health_system.sql)
4. Update database credentials in [includes/config.php](includes/config.php) if needed.
5. Browse the site: `http://localhost/MindScan/`

## Default Admin Login
- Username: `admin`
- Password: `admin123`

## Notes
- Update the contact email in [contact_submit.php](contact_submit.php) with a real inbox and configure SMTP for production.

## Pages
- Home: `index.php`
- Visualization Dashboard: `dashboard.php`
- Assessment: `assessment.php`
- Generated Report: `report.php`
- Admin Dashboard: `admin/dashboard.php`
- Contact: `contact.php`
