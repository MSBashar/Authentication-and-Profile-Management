# Assignment: 02
### Name: Md. Shafiul Bashar
### Email: msbashar.info@gmail.com

---

# Authentication & Profile Management System

<p align="center">
  <img src="Authentication & Profile Management System.png" alt="Update your personal information" width="70%">
</p>

## 📋 Project Overview
This project is a secure User Authentication and Profile Management system developed using **PHP (Object-Oriented Programming)** and **MySQL**. It features a modern, responsive user interface styled with **Tailwind CSS**. 

The system provides a complete workflow for users to register, log in, manage their profiles, and change passwords securely. It includes automatic database initialization and advanced security features such as "Remember Me" functionality and a token-based live-tested **Forgot Password** system integrated with **Mailpit**.

## 🚀 Key Features

### 1. User Authentication
* **Registration:** Validates user input and creates accounts with unique emails.  (Database is auto-created if missing)
* **Login:** Secure authentication with "Remember Me" functionality using cookie-based tokens.
* **Logout:** Securely terminates sessions and clears persistent cookies.
* **Forgot Password:** A secure, token-based recovery workflow that sends **real emails** via the PHP `mail()` function, captured locally for testing.

### 2. Profile Management
* **Dashboard:** A protected area for authenticated users to view their information.
* **Edit Profile:** Allows users to update their name and email address.
* **Change Password:** A dedicated interface for updating account security.
* **Access Control:** Restricted pages automatically redirect unauthenticated users to the login page.

### 3. Security Measures
* **Password Hashing:** Uses `password_hash()` with Bcrypt for secure storage and `password_verify()` for login.
* **SQL Injection Prevention:** 100% implementation of PDO Prepared Statements for all database queries.
* **XSS Protection:** Input sanitization using `htmlspecialchars()` to prevent script injection.
* **Session Management:** Robust session handling to maintain and protect user states.

## 🛠️ Technologies Used
* **Backend:** PHP 7.4.33 (OOP Principles)
* **Database:** MySQL
* **Frontend:** HTML5, Tailwind CSS (via CDN)
* **Email Testing:** Mailpit (Local SMTP Testing Tool)

## 📂 Project Structure

```text
├── database/
│   ├── Database.php               # MySQL Connection Class (Singleton/OOP)
│   ├── index.php                  # Preventing direct access and redirecting to the login page
│   └── setup.php                  # Script to initialize database & tables
├── helper/
│   ├── index.php                  # Preventing direct access and redirecting to the login page
│   ├── logout.php                 # Secure Logout Script
│   ├── prevent-direct-access.php  # Preventing direct access and redirecting to the login page
│   ├── security-check.php         # Redirect logic for unauthenticated users
│   ├── SendMail.php               # Script to sending mail.
│   └── Validator.php              # Server-side input validation logic
├── logic/
│   ├── index.php                  # Preventing direct access and redirecting to the login page
│   └── User.php                   # Core User Logic (Register, Login, Update, etc.)
├── pages/
│   ├── auth/
│   │   ├── forgot-password.php  # Password Recovery Request
│   │   ├── index.php            # Preventing direct access and redirecting to the login page
│   │   ├── login.php            # User Login Form
│   │   ├── reset-password.php   # New Password Entry Form
│   │   └── signup.php           # User Registration Form
│   └── includes/
│   │   ├── index.php            # Preventing direct access and redirecting to the login page
│   │   ├── sidebar.php          # Reusable Sidebar Navigation
│   │   └── topbar.php           # Reusable Top Navigation Bar
│   └── layouts/page
│   │   ├── auth-layout.php      # Layout for auth pages
│   │   ├── index.php            # Preventing direct access and redirecting to the login 
│   │   └── main-layout.php.php  # Layout for authenticated pages
│   ├── change-password.php      # Password Update Form
│   ├── dashboard.php            # Protected User Dashboard
│   ├── edit-profile.php         # Profile Modification Form
│   ├── index.php                # Preventing direct access and redirecting to the login page
├── index.php                    # Project Landing/Entry Point
├── init.php                     # Global Configuration & Class Autoloader
└── README.md                    # Project Documentation
```

## ⚙️ Setup & Installation

### 1. Prerequisites
* Local server (XAMPP/WAMP/MAMP/Laragon).
* MySQL running with `root` and no password.
* **Mailpit** installed and running (default SMTP port: 1025).

### 2. Database Initialization
1.  Place all project files in your server's root folder (htdocs or www).
2.  Open your browser and run the setup script:
    `http://localhost/your-project-folder/database/setup.php`
3.  This will create the database `auth-and-profile-management-system` and the `users` and `password_resets` table automatically.

### 3. Usage
* Start by visiting `http://localhost/your-project-folder/pages/auth/signup.php`.
* After registration, use `login.php` (http://localhost/your-project-folder/) to access your dashboard.

### 4. Testing Email (Forgot Password)
 To test the password reset functionality:
* Ensure Mailpit is running on your machine.
* Request a password reset via the Forgot Password link on the login page.
* Access the captured email and reset link at: http://localhost:8025/

## 🛡️ Security Features implemented
* Password Hashing: Bcrypt encryption via PASSWORD_DEFAULT.
* Token Security: Secure random byte generation for cookies and reset links.
* XSS Prevention: Input sanitization using htmlspecialchars().
* Database Protection: 100% Prepared Statements via PDO.
