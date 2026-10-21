# Proiect-PHP

A PHP-based online magazine and article publishing platform. The application allows users to create accounts, read articles, and access different functionalities depending on their role: client, author, editor, or administrator.

This project is a university-style web application built with PHP, MySQL, HTML, and CSS. It includes user management, article management, role-based access control, mailing functionality, and a basic web interface.

## Overview

The website is designed to function as an online magazine where:
- users must create an account before accessing articles,
- different user types have different permissions,
- editors can manage publications,
- authors can create or edit their own articles,
- administrators manage users and access levels.

The project includes several PHP pages for authentication, article handling, dashboards, and administrative operations.

## Features

- User registration and login
- Article reading and browsing
- Article creation and updates
- Role-based access control
- Admin panel for user management
- Editor panel for article management
- Author dashboard for personal content
- Database connection abstraction with PDO
- Email sending support
- Feedback/contact form handling
- Basic CSS-based styling
- Security-oriented notes and improvements for public deployment

## User Roles

### Client
- Can view articles
- Requires login to access protected pages

### Author
- Can write and edit personal articles
- May receive commission depending on article activity

### Editor
- Can modify and publish articles
- Manages editorial workflows

### Administrator
- Can add or remove users
- Manages advanced application permissions

## Technologies Used

- PHP
- MySQL / MariaDB
- PDO
- HTML
- CSS
- PHPMailer-related classes included in the project

## Project Structure

The repository contains the core application files and supporting assets:

- `Database.php` — database connection class
- `login-user.php` — login logic
- `create-user.php` — user registration
- `homepage.php` — main homepage
- `homepage-admin.php` — admin dashboard
- `homepage-author.php` — author dashboard
- `homepage-editor.php` — editor dashboard
- `create-articles.php` — article creation
- `read-articles.php` — article viewing
- `admin-pannel.php` — admin panel
- `editor-pannel.php` — editor panel
- `feedback.php` — contact or feedback form
- `logout.php` — logout functionality
- `files-handler.php` — file-related processing
- `Mailer.php` — email mailer logic
- `mail_cod.php` / `send_email.php` — email sending flow
- `mail_config.php` — email configuration
- `operatii_db.php` — database operations
- `verify_recaptcha.php` — verification helper
- `style.css` — styling
- `proiect_descriere.html` — project specification
- `TODO.md` — pending tasks and notes
- `PHP_magazine.png`, `UML.png` — project visuals

There is also a `remote_files/public_html` folder that appears to contain a deployment-style version of the application.

## Requirements

Before running the project, ensure you have:

- PHP 7.4+ or PHP 8.x
- Apache or Nginx
- MySQL or MariaDB
- A local server environment such as XAMPP, WAMP, or MAMP
- A browser

## Installation

1. Clone the repository:

```bash
git clone https://github.com/Meanderingg/Proiect-PHP.git
```

2. Place the project in your local web server folder:
   - XAMPP: `htdocs/`
   - WAMP: `www/`

3. Start Apache and MySQL.

4. Create a database for the application.

5. Update the database credentials in `Database.php` to match your local environment.

## Database Configuration

The application uses a singleton `Database` class and PDO to connect to a MySQL database.

Example structure:

```php
private string $host = "localhost";
private string $dbName = "proiect";
private string $username = "root";
private string $password = "";
```

Update it to your own local values:

```php
private string $host = "localhost";
private string $dbName = "your_database";
private string $username = "your_user";
private string $password = "your_password";
```

Important:
- The database must already exist before running the app.
- The tables and columns must match the logic used in the PHP files.

## Running the Project

Once the configuration is complete:

1. Start your local web server and database service.
2. Open the project in a browser using a path similar to:

```text
http://localhost/Proiect-PHP/
```

or the relevant local folder path for your server setup.

3. Navigate to the homepage or login page.

Because the project is built as a set of individual PHP pages, the app is likely accessed by opening the relevant page rather than using a full framework route system.

## Security Notes

The project documentation and TODO notes mention several security concerns that should be addressed before public deployment:

- SQL injection prevention
- XSS protection
- CSRF protection
- Form spoofing prevention
- Request spoofing prevention
- Public form protection against automated submissions
- CAPTCHA or equivalent protection for public forms

This project is not yet presented as fully production-ready security-wise, and it should be treated as an academic or learning project unless additional hardening is performed.

## License
This is an academic project, please contact me if you want to use the code.
