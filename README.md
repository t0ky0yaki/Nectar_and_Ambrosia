# Nectar & Ambrosia – Café POS System

## Installation and Setup

### Requirements
- PHP and Apache
- Microsoft SQL Server
- SQL Server Management Studio (SSMS)
- Microsoft PHP Drivers for SQL Server (`sqlsrv`)

### Setup
1. Clone or download this repository into your local web server's directory.
2. Import or execute the provided `database.sql` file in Microsoft SQL Server Management Studio to create the database and its tables.
3. Copy `config.example.php` and rename it to `config.php`.
4. Update the database connection settings in `config.php` to match your local SQL Server instance and database.
5. Start Apache and Microsoft SQL Server.
6. Open the application in your browser using `http://localhost/YOUR_PROJECT_FOLDER/`.

**Note:** The actual `config.php` file is excluded from the repository for security reasons. Each user must configure their own database connection.

