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

<p align="center">
  <a href="http://imru-oep.iamraisulislam.com/" target="_blank">
    <img src="https://img.shields.io/badge/Live%20Demo-Visit%20Website-2ea44f?style=for-the-badge" alt="Live Demo" />
  </a>
</p>

---

<h3>📃 Abstract</h3>

<p align="justify">This project developed a web-based Online Examination Portal. It is designed for universities and corporate organizations. The system supports both academic and professional assessments. The portal was built using Laravel, MySQL, and Bootstrap. It provides more flexibility than general-purpose quiz tools. Users can access the system online from different locations. This makes the examination process easier and more flexible.</p>

<p align="justify">The system controls access based on user roles. It has three main roles: Admin, Teacher, and Student. Users can only access features assigned to their roles. The system also monitors students during exams. The webcam stays active throughout the exam. Tab switching is detected and recorded. The timer runs continuously during the exam. The exam is automatically submitted when the time expires. These features improve exam security and help prevent cheating.</p>

<p align="justify">The system uses the Groq API. It uses the \textbf{openai/gpt-oss-120b} model for AI-assisted question generation. Teachers can create questions manually or with AI assistance. The system can generate questions based on specific topics. Different students can receive different question sets. The system also balances questions across three difficulty levels. This reduces the chance of students sharing answers. It also helps maintain exam fairness. Written answers are reviewed manually by teachers. Objective questions are scored automatically after submission. Students first see the automatically generated results. The teacher reviews the written answers manually. The final result is updated after the review.</p>

---

<h3>📖 Table of Contents</h3>

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

<h3>🎓 About the Project</h3>

<p align="justify"><strong>IMRU Online Examination Portal</strong> is a comprehensive academic examination management system designed to digitise and streamline the entire examination lifecycle — from course setup to result delivery.</p>

<p align="justify">The platform provides three distinct role-based portals (<strong>Admin</strong>, <strong>Teacher</strong>, <strong>Student</strong>) and supports both manually created exams and AI-generated exam sets via the <strong>Gorq API</strong>, complete with automated MCQ grading, subjective answer review, live proctoring, and detailed performance analytics.</p>

> <p align="justify">Developed by <strong>Muhammad Raisul Islam</strong>, IIT, Jahangirnagar University (JU).</p>

---

<h3>✨ Features</h3>

<h4>🛡️ Admin Panel</h4>
<ul align="justify">
  <li>Full control over all system data: courses, exams, questions, students, and enrollments</li>
  <li>Manage teacher accounts and assign courses</li>
  <li>Global exam rules and instruction library management</li>
  <li>System-wide performance reporting</li>
</ul>

<h4>👨‍🏫 Teacher Portal</h4>
<ul align="justify">
  <li><strong>Dashboard</strong> with 8 live stat cards (courses, exams, enrolled students, pending reviews, total results, passed, failed, avg score & pass rate)</li>
  <li>Three analytics charts: Pass vs Fail doughnut, Monthly Pass/Fail Trend (6-month line), Exams per Course bar chart</li>
  <li>Quick-access tables: Upcoming Exams, Pending Reviews, Recent Attempts, Per-Course Stats</li>
  <li>Create and manage <strong>Courses</strong>, <strong>Exams</strong>, <strong>Questions</strong>, and <strong>Enrollments</strong></li>
  <li><strong>AI Exam Generation</strong> via Gorq API with Question Type Balancer, Difficulty Balancer, and Anti-repeat Filter</li>
  <li>Per-exam <strong>Exam Settings</strong> — toggle instructions and security rules independently per exam</li>
  <li><strong>Review Answer</strong> module — manually grade Short & Long Question answers with verdict and marks</li>
  <li><strong>Performance</strong> analytics per exam and course</li>
  <li><strong>Proctoring Monitor</strong> — view tab-switch, clipboard, and webcam event logs per student</li>
</ul>

<h4>🎓 Student Portal</h4>
<ul align="justify">
  <li>Dashboard with enrolled courses, upcoming exams, exams attempted, and average score</li>
  <li><strong>My Exams</strong> — card view of all assigned exams with status badges (Ongoing, Ended, Upcoming, Submitted)</li>
  <li>Exam detail page with full summary before starting</li>
  <li>Instructions & Rules agreement screen before exam entry</li>
  <li>Live <strong>Answer Sheet</strong> with countdown timer, question progress counter, MCQ radio buttons, and free-text areas for subjective questions</li>
  <li><strong>Real-time auto-submit</strong> when timer expires (if rule enabled)</li>
  <li><strong>View Result</strong> — two-stage result: partial (pre-review) and final (post-review) with full scorecard, percentage, and Pass/Fail badge</li>
</ul>

<h4>🔒 Proctoring & Exam Security</h4>
<ul align="justify">
  <li>Cut / Copy / Paste restrictions (default)</li>
  <li>Back Button restrictions (default)</li>
  <li>Tab Switching detection — auto-submit on violation</li>
  <li>Fullscreen / Browser Maximized enforcement</li>
  <li>Webcam Required (configurable)</li>
  <li>Single Attempt enforcement</li>
  <li>Auto Submit on timer expiry</li>
  <li>Internet Connection reminder</li>
</ul>

---

<h3>👥 User Roles</h3>
<table>
  <thead>
    <tr>
      <th>Role</th>
      <th>Access</th>
      <th>Login URL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Admin</strong></td>
      <td>Full system management</td>
      <td><code>/admin/login</code></td>
    </tr>
    <tr>
      <td><strong>Teacher</strong></td>
      <td>Courses, exams, questions, students, reviews, analytics</td>
      <td><code>/academic/login</code> (Teacher tab)</td>
    </tr>
    <tr>
      <td><strong>Student</strong></td>
      <td>Exams, results, profile</td>
      <td><code>/academic/login</code> (Student tab)</td>
    </tr>
  </tbody>
</table>

---

<h3>🛠️ Tech Stack</h3>
<table>
  <thead>
    <tr>
      <th>Layer</th>
      <th>Technology</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Backend Framework</td>
      <td>Laravel 9.x</td>
    </tr>
    <tr>
      <td>Language</td>
      <td>PHP 8.2+</td>
    </tr>
    <tr>
      <td>Database</td>
      <td>MySQL 8.0+</td>
    </tr>
    <tr>
      <td>Frontend</td>
      <td>Bootstrap 5, Bootstrap Icons</td>
    </tr>
    <tr>
      <td>Charts</td>
      <td>Chart.js 4.x</td>
    </tr>
    <tr>
      <td>AI Integration</td>
      <td>Groq API (exam generation)</td>
    </tr>
    <tr>
      <td>Authentication</td>
      <td>Laravel Multi-Guard (Admin / Teacher / Student)</td>
    </tr>
    <tr>
      <td>Template Engine</td>
      <td>Blade</td>
    </tr>
  </tbody>
</table>

---

<h3>🚀 Getting Started</h3>

<h4>Prerequisites</h3>
<ul align="justify">
  <li>PHP >= 8.2</li>
  <li>Composer</li>
  <li>MySQL >= 8.0</li>
  <li>Node.js & npm (for asset compilation)</li>
</ul>

<h4>Installation</h4>

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

<h3>📁 Project Structure</h3>

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

<h3>🔄 Exam Creation Workflow</h3>

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

<h3>📄 License</h3>

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

<h3>👤 Author</h3>

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
