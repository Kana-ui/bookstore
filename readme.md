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

## Deployed Version (mi-linux)

The project is fully deployed on the University’s mi-linux server and can be accessed here:
https://mi-linux.wlv.ac.uk/~2413674/bookstore/public/login.php

The deployed version uses the same codebase, with updated database credentials and correct file permissions (directories 755, files 644). The **vendor/** folder is included on the server so Twig works, and all features such as authentication, CRUD, and AJAX live search function normally in the deployed environment.