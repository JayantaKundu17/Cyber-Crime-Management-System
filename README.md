## Live Demo

[https://cybercrime-management.infinityfreeapp.com/](https://cybercrime-management.infinityfreeapp.com/)

---

# Cyber Crime Management System

A web-based **Cyber Crime Management System** developed to assist in organizing and managing information related to cybercrime investigations. The system provides separate modules for managing users, cases, victims, suspects, officers, reports, evidence, and audit logs.

The project demonstrates the use of **PHP, MySQL, HTML, CSS, and JavaScript** to build a database-driven web application with authentication and CRUD functionality.

---

## Overview

The Cyber Crime Management System provides a centralized platform for managing investigation-related information.

Instead of maintaining separate records for different aspects of an investigation, the system organizes information into interconnected modules such as:

* Users and administrators
* Cybercrime cases
* Victims
* Suspects
* Officers
* Investigation reports
* Digital evidence
* Audit logs

The application uses **PHP** for server-side processing and **MySQL** for persistent data storage.

---

## Features

### User Management

* User registration and login
* User authentication
* User logout
* Administrative user management
* Add, edit, and delete users
* User listing

### Case Management

* Create cybercrime cases
* View case information
* Edit existing cases
* Delete cases
* Manage case status and investigation details

### Victim Management

* Add victim information
* View victim records
* Edit victim details
* Delete victim records
* Associate victims with cases

### Suspect Management

* Add suspect information
* View suspect records
* Edit suspect details
* Delete suspect records
* Associate suspects with cases

### Officer Management

* Officer dashboard
* Officer-related case management
* Assignment of officers to investigations

### Report Management

* Create investigation reports
* View reports
* Edit reports
* Delete reports
* Associate reports with cases and officers

### Evidence Management

* Add evidence records
* Manage evidence information
* Edit and delete evidence
* Associate evidence with specific cases
* Store evidence file references

### Audit Logging

* Record important system actions
* Track user activities
* Maintain an audit trail for administrative operations

---

## Technology Stack

| Technology       | Purpose                                               |
| ---------------- | ----------------------------------------------------- |
| **PHP**          | Server-side application logic and database operations |
| **MySQL**        | Relational database management                        |
| **SQL**          | Database creation, queries, and data management       |
| **HTML5**        | Web page structure                                    |
| **CSS3**         | User interface styling                                |
| **JavaScript**   | Client-side functionality and interactions            |
| **Apache**       | Local PHP web server                                  |
| **Git & GitHub** | Version control and source-code hosting               |

---

## Project Architecture

The application follows a traditional server-side web architecture:

```text
                    User
                     │
                     ▼
              HTML / CSS / JS
                     │
                     ▼
                   PHP
                     │
              MySQL Queries
                     │
                     ▼
                  MySQL
```

PHP handles requests from the frontend, performs the required operations, and communicates with the MySQL database.

---

## Project Structure

```text
Cyber-Crime-Management-System/
│
├── admin/
│   ├── add_user.php
│   ├── admin_dashboard.php
│   ├── delete_user.php
│   ├── edit_user.php
│   └── manage_users.php
│
├── audit_log/
│   └── view_logs.php
│
├── cases/
│   ├── add_case.php
│   ├── delete_case.php
│   ├── edit_case.php
│   └── manage_cases.php
│
├── evidence/
│   ├── add_evidence.php
│   ├── delete_evidence.php
│   ├── edit_evidence.php
│   └── manage_evidence.php
│
├── officers/
│   └── officer_dashboard.php
│
├── reports/
│   ├── add_report.php
│   ├── delete_report.php
│   ├── edit_report.php
│   └── reports.php
│
├── suspects/
│   ├── add_suspect.php
│   ├── delete_suspect.php
│   ├── edit_suspect.php
│   └── view_suspects.php
│
├── users/
│   ├── db_connect.php
│   ├── list_users.php
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── victims/
│   ├── add_victim.php
│   ├── delete_victim.php
│   ├── edit_victim.php
│   └── list_victims.php
│
├── assets/
├── dashboard.html
├── index.html
├── index.php
├── logout.php
├── script.js
├── styles.css
├── db_connect.php
├── cybercrime_dbms.sql
└── README.md
```

---

## Database

The project uses **MySQL** with the database name:

```text
cybercrime_dbms
```

The database contains interconnected entities for managing:

```text
Users
Cases
Victims
Suspects
Officers
Reports
Evidence
Audit Logs
```

The SQL database structure is provided in:

```text
cybercrime_dbms.sql
```

This file can be imported through **phpMyAdmin** to recreate the database.

---

## Local Installation

### Prerequisites

Install:

* XAMPP
* Git
* A modern web browser

XAMPP provides the Apache web server and MySQL database environment required by the application.

### 1. Clone the repository

```bash
git clone https://github.com/JayantaKundu17/Cyber-Crime-Management-System.git
```

Alternatively, download the repository as a ZIP file from GitHub.

### 2. Move the project to XAMPP

Copy the project folder into:

```text
xampp/htdocs/
```

The final path should look like:

```text
xampp/htdocs/Cyber-Crime-Management-System/
```

### 3. Start XAMPP

Open XAMPP and start:

```text
Apache
MySQL
```

Both services should be running before opening the application.

### 4. Create the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a new database named:

```text
cybercrime_dbms
```

### 5. Import the database

Select the `cybercrime_dbms` database in phpMyAdmin.

Choose:

```text
Import → cybercrime_dbms.sql
```

and execute the import.

### 6. Verify database configuration

The project uses the default local XAMPP configuration:

```text
Host:     localhost
Username: root
Password: empty
Database: cybercrime_dbms
```

The database connection is configured in:

```text
db_connect.php
```

### 7. Run the application

Open:

```text
http://localhost/Cyber-Crime-Management-System/
```

---

## Demo Accounts

The included database contains fictional demonstration accounts for testing.

```text
Administrator
Email: admin@example.com
Password: Demo@123

Officer
Email: officer@example.com
Password: Demo@123
```

These credentials are intended only for the included demonstration database.

---

## CRUD Operations

The system implements CRUD functionality across multiple modules.

```text
Create
   ↓
Add new records

Read
   ↓
View existing records

Update
   ↓
Edit records

Delete
   ↓
Remove records
```

CRUD functionality is implemented for areas such as:

* Users
* Cases
* Victims
* Suspects
* Reports
* Evidence

---

## Security Considerations

The project demonstrates basic authentication and database-driven access control.

For production deployment, additional security measures should be implemented, including:

* Strong password policies
* Environment-based database credentials
* HTTPS
* CSRF protection
* Comprehensive input validation
* Prepared statements throughout the application
* Secure session configuration
* Role-based authorization
* Secure file-upload validation
* Production database credentials and access restrictions

This repository is intended primarily as an **educational project and demonstration application**.

---


## Future Improvements

Possible future improvements include:

* Responsive UI improvements
* Advanced role-based access control
* Improved evidence management
* Search and filtering
* Case analytics and dashboards
* Improved validation and security
* Automated backups
* REST API integration
* Cloud deployment
* Enhanced audit and activity monitoring

---

## Purpose

This project was developed as an educational demonstration of a **database-driven full-stack web application** using PHP and MySQL.

It demonstrates practical implementation of:

* Web development
* Database design
* CRUD operations
* Authentication
* User management
* Relational data management
* File handling
* Audit logging

---

## Author

**Jayanta Kundu**

GitHub: [@JayantaKundu17](https://github.com/JayantaKundu17)

---

## License

This project is intended for **educational purposes**.
