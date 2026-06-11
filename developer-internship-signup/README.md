# Developer Internship Signup Page

This project follows the internship requirement:

- HTML, CSS, JavaScript, and PHP are in separate files.
- Frontend uses Bootstrap and jQuery AJAX only.
- No form submission is used.
- Browser login state is stored in `localStorage`.
- Backend session data is stored in Redis.
- Registration credentials are stored in MongoDB.
- Profile details are stored in MySQL using prepared statements.

## Folder Structure

```text
developer-internship-signup/
  register.html
  login.html
  profile.html
  assets/
    css/style.css
    js/app.js
    js/register.js
    js/login.js
    js/profile.js
  api/
    config.php
    helpers.php
    register.php
    login.php
    profile.php
    update-profile.php
    logout.php
  database/
    mysql_schema.sql
```

## Requirements

Install and run:

- PHP 8+
- MySQL
- MongoDB
- Redis
- PHP extensions: `pdo_mysql`, `mongodb`, `redis`

For XAMPP/WAMP, enable `pdo_mysql` in `php.ini`. Install MongoDB and Redis extensions if they are not already available.

## Database Setup

1. Start MySQL.
2. Import `database/mysql_schema.sql`.
3. Start MongoDB on `mongodb://127.0.0.1:27017`.
4. Start Redis on `127.0.0.1:6379`.
5. Update database credentials in `api/config.php` if your MySQL password is not empty.

## Easiest Run Option: Docker

If Docker Desktop is installed, this starts PHP, MySQL, MongoDB, and Redis together:

```powershell
cd "C:\Users\Chandramohan Sumathi\Documents\guvi\developer-internship-signup"
docker compose up --build
```

Open:

```text
http://127.0.0.1:8000/register.html
```

In VS Code you can press `Ctrl+Shift+P`, choose `Tasks: Run Task`, then select `Run Full Stack with Docker`.

## Run in VS Code

Open this folder in VS Code:

```powershell
cd "C:\Users\Chandramohan Sumathi\Documents\guvi\developer-internship-signup"
code .
```

Run the PHP server:

```powershell
php -S 127.0.0.1:8000
```

If you use XAMPP and `php` is not available globally:

```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000
```

In VS Code you can also press `Ctrl+Shift+P`, choose `Tasks: Run Task`, then select `Run PHP Server` or `Run PHP Server with XAMPP`.

Open:

```text
http://127.0.0.1:8000/register.html
```

## Flow

1. Register a new user.
2. Login with the same email and password.
3. Update age, date of birth, contact, city, and bio on the profile page.
