# Nexora Institute — Training Center Website

A premium, fully-responsive website for a skill-development / training institute, built with **HTML + Tailwind CSS + JavaScript** on the front and **PHP + MySQL** on the back. Everything — courses, prices, students, certificates, fee receipts, projects and site content — is controlled from a powerful **Admin Console**.

---

## ✨ Features

### Public website
- Modern, animated landing page (hero, stats, categories, featured courses, testimonials, FAQ, CTA)
- Course catalog with **category filter** and **search**
- Detailed course pages with syllabus, pricing & discounts
- Student **project showcase**
- **Certificate verification** page (anyone can validate a certificate number)
- Contact form (saved to the database) with map & WhatsApp button
- 100% responsive (mobile → desktop)

### Student Login Panel
- Secure login (bcrypt-hashed passwords)
- Dashboard with enrolled courses, certificates, fee summary
- **Personal Details** — editable by the student **until a certificate is generated** (then locked)
- **Certificates** — view & print/download (print-ready design)
- **Fee Receipts** — view & print/download

### Admin Console (controls everything)
- Dashboard with live stats (students, courses, revenue, enquiries…)
- **Courses** — full create / edit / delete, set price, discount, syllabus, level, featured
- **Categories** management
- **Students** — create (auto Enrollment ID), edit, reset password, lock/unlock details
- **Enrollments** — assign students to courses, track status
- **Fee Receipts** — generate official receipts (auto receipt number) and print
- **Certificates** — issue certificates (auto number). Issuing **locks** the student's personal details and marks the enrollment complete
- **Projects** — manage the public project showcase
- **Enquiries** — read & respond to contact messages
- **Settings** — edit institute name, phone, email, address, stats, social links + change admin password

---

## 🧰 Tech Stack
| Layer | Technology |
|------|------------|
| Frontend | HTML5, Tailwind CSS (Play CDN), Vanilla JavaScript |
| Backend | PHP 8.x (PDO) |
| Database | MySQL / MariaDB |
| Fonts | Inter, Space Grotesk, Playfair Display (Google Fonts) |

---

## 🚀 Setup (local — XAMPP / WAMP / MAMP)

1. **Copy the project** into your web root, e.g.
   - XAMPP → `C:\xampp\htdocs\institute`
   - Linux → `/var/www/html/institute`

2. **Create the database** and import the schema:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
   …or in **phpMyAdmin**: create a database, then *Import* → choose `sql/schema.sql`.
   This creates the `nexora_institute` database, all tables, and demo data.

3. **Configure credentials** in `config/config.php` (defaults shown):
   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_NAME', 'nexora_institute');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // set your MySQL password
   ```
   (You can also set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` as environment variables.)

4. **Open in the browser:**
   - Website → `http://localhost/institute/`
   - Student portal → `http://localhost/institute/student/login.php`
   - Admin console → `http://localhost/institute/admin/login.php`

> The app auto-detects its base path, so it also works when placed in a subfolder.

---

## 🔑 Default Logins

| Role | URL | Username / Email | Password |
|------|-----|------------------|----------|
| **Admin** | `/admin/login.php` | `admin` | `admin@123` |
| **Student** | `/student/login.php` | `student@nexora.com` | `student@123` |

> **Change these immediately in production** (Admin → Settings, and Student → Personal Details).

---

## 📁 Project Structure
```
institute/
├── index.php              # Landing page
├── courses.php            # Course catalog (filter + search)
├── course.php             # Course detail (?slug=)
├── projects.php           # Project showcase
├── verify.php             # Certificate verification (?code=)
├── about.php  contact.php # About & Contact
├── config/                # config.php (creds) + database.php (PDO)
├── includes/              # functions, header, footer, receipt template
├── assets/                # css/style.css, js/main.js, uploads/
├── student/               # Student portal (login, dashboard, profile, etc.)
├── admin/                 # Admin console (CRUD for everything)
└── sql/schema.sql         # Database schema + seed data
```

---

## 🌐 Deploying to shared hosting (cPanel)
1. Upload the folder (or a zip, then *Extract*) into `public_html`.
2. In **MySQL Databases**, create a database + user, assign privileges.
3. Import `sql/schema.sql` via **phpMyAdmin**.
4. Update `config/config.php` with the hosting DB name/user/password.
5. Visit your domain.

### Note on Tailwind
This project loads Tailwind via the **Play CDN** for zero-build simplicity. For best production performance you can later compile a static CSS file with the Tailwind CLI, but it is **not required** — the site works as-is.

---

## 🔒 Security notes
- Passwords are hashed with **bcrypt** (`password_hash`).
- All forms are protected with **CSRF tokens**.
- All database access uses **prepared statements** (PDO).
- Turn off error display in production (set `display_errors` to `0` in `config/config.php`).

---

Built with care. For support: **+91 98466 48947**
