# Bookstore Web Application

A simple PHP & MySQL web application for managing a small bookstore catalog.  
Built for the 5CS045 Task 2 assessment.

---

## 1. Features Overview

- Full **CRUD** on books (Create, Read, Update, Delete)
- **User authentication** (login, logout, roles)
- **Password hashing** using `password_hash()` and `password_verify()`
- **Session-protected** pages for all CRUD operations
- **Math CAPTCHA** on login and registration
- **Multi-criteria search** (title, genre, year)
- **AJAX live search** (results update without full page reload)
- **Twig template engine** for views (`home`, `add`, `edit`)
- Basic responsive **CSS** for desktop and mobile

---

## 2. How to Run Locally (XAMPP)

1. Start **Apache** and **MySQL** in XAMPP  
   - MySQL is configured on **port 3307**.

2. Copy the project into:

   ```text
   C:\xampp\htdocs\bookstore
