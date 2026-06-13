<p align="center">
  <img src="public/assets/admin/img/brand/logo.png" width="340" alt="IMRU Online Examination Portal" />
</p>

<h1 align="center">IMRU — Online Examination Portal</h1>

<p align="center">
  A full-featured, web-based examination platform built with <strong>Laravel</strong> — supporting AI-powered exam generation, real-time proctoring, multi-role access, and automated result processing.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=flat-square&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/Chart.js-4.x-FF6384?style=flat-square&logo=chartdotjs&logoColor=white" alt="Chart.js" />
  <img src="https://img.shields.io/badge/License-MIT-22c55e?style=flat-square" alt="MIT License" />
</p>

---

## 📃 Abstract

> This project proposes a secure **Online Examination Portal** for universities and corporate recruiters to conduct admission and recruitment tests. The system includes features such as **automated evaluation**, **video camera monitoring**, **no tab change restriction**, **timer-based controls**, and **result analysis dashboards**. It supports multiple exam formats, ensuring flexibility and integrity. With modern web technologies and cloud scalability, the platform provides a reliable, accessible, and cost-effective solution for large-scale online examinations.

---

## 📖 Table of Contents

- [Abstract](#-abstract)
- [About the Project](#-about-the-project)
- [Features](#-features)
- [User Roles](#-user-roles)
- [Tech Stack](#-tech-stack)
- [Getting Started](#-getting-started)
- [Project Structure](#-project-structure)
- [Exam Creation Workflow](#-exam-creation-workflow)
- [License](#-license)
- [Author](#-author)

---

## 🎓 About the Project

**IMRU Online Examination Portal** is a comprehensive academic examination management system designed to digitise and streamline the entire examination lifecycle — from course setup to result delivery.

The platform provides three distinct role-based portals (**Admin**, **Teacher**, **Student**) and supports both manually created exams and AI-generated exam sets via the **Gorq API**, complete with automated MCQ grading, subjective answer review, live proctoring, and detailed performance analytics.

> Developed by **Muhammad Raisul Islam**, IIT, Jahangirnagar University (JU).

---

## ✨ Features

### 🛡️ Admin Panel
- Full control over all system data: courses, exams, questions, students, and enrollments
- Manage teacher accounts and assign courses
- Global exam rules and instruction library management
- System-wide performance reporting

### 👨‍🏫 Teacher Portal
- **Dashboard** with 8 live stat cards (courses, exams, enrolled students, pending reviews, total results, passed, failed, avg score & pass rate)
- Three analytics charts: Pass vs Fail doughnut, Monthly Pass/Fail Trend (6-month line), Exams per Course bar chart
- Quick-access tables: Upcoming Exams, Pending Reviews, Recent Attempts, Per-Course Stats
- Create and manage **Courses**, **Exams**, **Questions**, and **Enrollments**
- **AI Exam Generation** via Gorq API with Question Type Balancer, Difficulty Balancer, and Anti-repeat Filter
- Per-exam **Exam Settings** — toggle instructions and security rules independently per exam
- **Review Answer** module — manually grade Short & Long Question answers with verdict and marks
- **Performance** analytics per exam and course
- **Proctoring Monitor** — view tab-switch, clipboard, and webcam event logs per student

### 🎓 Student Portal
- Dashboard with enrolled courses, upcoming exams, exams attempted, and average score
- **My Exams** — card view of all assigned exams with status badges (Ongoing, Ended, Upcoming, Submitted)
- Exam detail page with full summary before starting
- Instructions & Rules agreement screen before exam entry
- Live **Answer Sheet** with countdown timer, question progress counter, MCQ radio buttons, and free-text areas for subjective questions
- **Real-time auto-submit** when timer expires (if rule enabled)
- **View Result** — two-stage result: partial (pre-review) and final (post-review) with full scorecard, percentage, and Pass/Fail badge

### 🔒 Proctoring & Exam Security
- Cut / Copy / Paste restrictions (default)
- Back Button restrictions (default)
- Tab Switching detection — auto-submit on violation
- Fullscreen / Browser Maximized enforcement
- Webcam Required (configurable)
- Single Attempt enforcement
- Auto Submit on timer expiry
- Internet Connection reminder

---

## 👥 User Roles

| Role | Access | Login URL |
|------|--------|-----------|
| **Admin** | Full system management | `/admin/login` |
| **Teacher** | Courses, exams, questions, students, reviews, analytics | `/academic/login` (Teacher tab) |
| **Student** | Exams, results, profile | `/academic/login` (Student tab) |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel 9.x |
| Language | PHP 8.2+ |
| Database | MySQL 8.0+ |
| Frontend | Bootstrap 5, Bootstrap Icons |
| Charts | Chart.js 4.x |
| AI Integration | Gorq API (exam generation) |
| Auth | Laravel Multi-Guard (Admin / Teacher / Student) |
| Template Engine | Blade |

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & npm (for asset compilation)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/rishowmin/IMRU_OEP.git
cd IMRU_OEP

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install && npm run build

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imru_oep_db
DB_USERNAME=root
DB_PASSWORD=

# 6. (Optional) Add your Gorq API key in .env for AI exam generation
GORQ_API_KEY=your_api_key_here

# 7. Run migrations and seeders
php artisan migrate --seed

# 8. Start the development server
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

---

## 📁 Project Structure

```
IMRU_OEP/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Teacher/        # Teacher portal controllers (TechDashboardController, etc.)
│   │   └── Student/        # Student portal controllers
│   └── Models/
│       └── Academic/       # AcaCourse, AcaExam, AcaQuestion, AcaExamAttempt, AcaExamResult, ...
├── resources/
│   └── views/
│       ├── admin/          # Admin Blade views
│       ├── teacher/        # Teacher Blade views (dashboard, docs, exams, ...)
│       └── student/        # Student Blade views (dashboard, docs, answer sheet, result, ...)
├── routes/
│   └── web.php             # Route definitions for all three portals
├── database/
│   ├── migrations/         # Database schema migrations
│   └── seeders/            # Default data seeders
└── public/
    └── assets/             # CSS, JS, images, logo
```

## 🔄 Exam Creation Workflow

```
Teacher Logs In
      │
      ▼
  Exam Creation
  ┌────────────────────────────────────────┐
  │                                        │
  ▼                                        ▼
General (Custom)                    AI Generated
  │                                        │
  ├─ Fill exam details                     ├─ Provide title, topic,
  │  (course, date, marks, etc.)           │  question type, count
  │                                        │
  ├─ Add Questions                         ├─ Gorq API generates
  │  ├─ Write Custom Questions             │  Question Bank
  │  └─ Select from Question Bank         │  ├─ Type Balancer
  │                                        │  ├─ Difficulty Balancer
  │                                        │  └─ Anti-repeat Filter
  │                                        │
  │                                        ├─ Saved as Draft
  │                                        └─ Review → Publish
  │                                                │
  └──────────────────┬─────────────────────────────┘
                     ▼
              Exam Generated
                     │
                     ▼
             Exam Settings
        ┌────────────────────────────────┐
        │ Default (always on)            │
        │  • Cut/Copy/Paste Restrictions │
        │  • Back Button Restrictions    │
        │                                │
        │ Instructions (toggle per exam) │
        │  • Timer Policy                │
        │  • Auto Submit                 │
        │  • Single Attempt              │
        │  • Internet Connection         │
        │                                │
        │ Rules (toggle per exam)        │
        │  • Tab Switching               │
        │  • Fullscreen Required         │
        │  • Webcam Required             │
        └────────────────────────────────┘
                     │
                     ▼
        Students Access & Take Exam
                     │
                     ▼
        MCQ → Auto Graded instantly
        Subjective → Teacher Reviews
                     │
                     ▼
              Final Result Published
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👤 Author

<p align="center">
  <img src="public/assets/admin/img/brand/icon.png" width="64" alt="IMRU Icon" /><br/>
  <strong>Muhammad Raisul Islam</strong><br/>
  Institute of Information Technology (IIT)<br/>
  Jahangirnagar University (JU), Bangladesh<br/><br/>
  <a href="https://github.com/rishowmin">
    <img src="https://img.shields.io/badge/GitHub-rishowmin-181717?style=flat-square&logo=github" alt="GitHub" />
  </a>
</p>

---

<p align="center">
  <img src="public/assets/admin/img/brand/logo.png" width="160" alt="IMRU Logo" /><br/>
  <sub>© IMRU Online Examination Portal — All Rights Reserved</sub>
</p>
