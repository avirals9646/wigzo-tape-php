# Wigzo Tape PHP + MySQL Project

This project is a converted version of the original Wigzo Tape ecommerce website.

The old project used:

- Python FastAPI for backend
- React for frontend
- MongoDB for database

This converted project uses:

- PHP for backend
- React for frontend
- MySQL for database

## Important Local Running Note

This project can run in two database modes:

- `sqlite` for very easy local testing
- `mysql` for real hosting or XAMPP/phpMyAdmin practice

For this prepared copy, `backend/config/config.php` is set to:

```php
'db_driver' => 'sqlite',
```

That means a beginner can run the project locally without creating a MySQL database first.

If you want to use MySQL, change it to:

```php
'db_driver' => 'mysql',
```

Then follow the MySQL database setup steps below.

## What This Website Does

This is an ecommerce website for selling wig tape products.

Main features:

- Home page
- Product listing
- Product details
- User signup and login
- Shopping cart
- Checkout
- Coupon code support
- Dummy payment flow
- User order history
- Blog pages
- Contact form
- Admin dashboard
- Admin product management
- Admin order management
- Admin user management
- Admin coupon management
- Admin blog management
- Admin contact form replies

## Folder Structure

After extracting the zip, you will see:

```text
wigzo-tape-php/
  backend/
    public/
      index.php
      .htaccess
    config/
      config.php
      config.example.php
    database/
      schema.sql
      seed.sql
  frontend/
    src/
    public/
    package.json
    .env
  README.md
```

Meaning of the folders:

- `backend` contains the PHP API.
- `frontend` contains the React website.
- `backend/database/schema.sql` creates the MySQL tables.
- `backend/database/seed.sql` adds sample products, admin login, and coupon.
- `backend/config/config.php` stores database settings.

## Software You Need

Install these first:

1. XAMPP
2. Node.js
3. A code editor such as VS Code
4. A browser such as Chrome

## Step 1: Install XAMPP

XAMPP gives you PHP, MySQL, and phpMyAdmin.

1. Download XAMPP from:

```text
https://www.apachefriends.org/
```

2. Install it.
3. Open XAMPP Control Panel.
4. Start:

```text
Apache
MySQL
```

Both should show green/running.

## Step 2: Install Node.js

React needs Node.js.

1. Download Node.js from:

```text
https://nodejs.org/
```

2. Install the LTS version.
3. After installation, open Command Prompt and check:

```bash
node -v
npm -v
```

If both commands show version numbers, Node.js is installed correctly.

## Step 3: Extract the Project

Extract this zip file:

```text
wigzo-tape-php-mysql-converted.zip
```

You can place it anywhere, for example:

```text
C:\xampp\htdocs\wigzo-tape-php
```

or:

```text
Desktop\wigzo-tape-php
```

For beginners, using `C:\xampp\htdocs\wigzo-tape-php` is easiest.

## Step 4: Create the Database

If you are using the default SQLite mode, you can skip this step.

The PHP backend will automatically create this local database file:

```text
backend/database/wigzo_tape.sqlite
```

Only follow this MySQL step if you changed `db_driver` to `mysql`.

1. Start XAMPP.
2. Make sure MySQL is running.
3. Open your browser.
4. Visit:

```text
http://localhost/phpmyadmin
```

5. Click `New` on the left side.
6. Create a database named:

```text
wigzo_tape_db
```

7. Click the database name `wigzo_tape_db`.
8. Click the `Import` tab.
9. Choose this file:

```text
backend/database/schema.sql
```

10. Click `Go`.

This creates the tables.

Now import the starter data:

1. Stay inside the same database.
2. Click `Import` again.
3. Choose this file:

```text
backend/database/seed.sql
```

4. Click `Go`.

This adds:

- Admin user
- Products
- FIRSTTIME coupon

## Step 5: Configure the Backend

Open this file:

```text
backend/config/config.php
```

For XAMPP, the default settings are usually:

```php
'db_driver' => 'mysql',
'db_host' => '127.0.0.1',
'db_port' => '3306',
'db_name' => 'wigzo_tape_db',
'db_user' => 'root',
'db_pass' => '',
'jwt_secret' => 'change-this-secret-key',
'cors_origins' => 'http://localhost:3000',
```

For easiest local testing without MySQL, use:

```php
'db_driver' => 'sqlite',
```

If you are using XAMPP and you did not set a MySQL password, keep `db_pass` empty.

Important:

- `db_name` must match your database name.
- `db_user` is usually `root` on XAMPP.
- `db_pass` is usually empty on XAMPP.
- `cors_origins` should be `http://localhost:3000` when running React locally.

## Step 6: Run the PHP Backend

Open Command Prompt.

Go to the backend public folder.

Example:

```bash
cd C:\xampp\htdocs\wigzo-tape-php\backend\public
```

Start the PHP backend:

```bash
php -S localhost:8000
```

Keep this window open.

Your backend is now running at:

```text
http://localhost:8000
```

Test the backend by opening this in your browser:

```text
http://localhost:8000/api/products
```

If you see product data, the backend is working.

## Step 7: Configure the Frontend

Open this file:

```text
frontend/.env
```

It should contain:

```env
REACT_APP_BACKEND_URL=http://localhost:8000
```

This tells React where the PHP backend is running.

## Step 8: Run the React Frontend

Open a second Command Prompt.

Go to the frontend folder.

Example:

```bash
cd C:\xampp\htdocs\wigzo-tape-php\frontend
```

Install frontend packages:

```bash
npm install --legacy-peer-deps
```

This can take a few minutes.

After installation, start the frontend:

```bash
npm start
```

The website should open automatically.

If it does not open, visit:

```text
http://localhost:3000
```

## Step 9: Login Details

Admin login:

```text
Email: admin@wigzotape.com
Password: admin123
```

Coupon code:

```text
FIRSTTIME
```

## How to Use the Website

### As a Customer

1. Open:

```text
http://localhost:3000
```

2. Browse products.
3. Register a new account.
4. Login.
5. Add products to cart.
6. Go to cart.
7. Go to checkout.
8. Try coupon code:

```text
FIRSTTIME
```

9. Place the order.
10. View your orders in dashboard.

### As an Admin

1. Open:

```text
http://localhost:3000/login
```

2. Login with:

```text
admin@wigzotape.com
admin123
```

3. Go to:

```text
http://localhost:3000/admin
```

From the admin panel, you can manage:

- Products
- Orders
- Users
- Coupons
- Blogs
- Contact forms

## Important URLs

Frontend:

```text
http://localhost:3000
```

Backend:

```text
http://localhost:8000
```

Products API:

```text
http://localhost:8000/api/products
```

phpMyAdmin:

```text
http://localhost/phpmyadmin
```

## Common Problems and Fixes

### Problem: `php` command is not recognized

This means PHP is not added to your system PATH.

Easy fix:

Use the full PHP path:

```bash
C:\xampp\php\php.exe -S localhost:8000
```

Run this command from:

```text
backend/public
```

### Problem: Backend says database error

Check these things:

1. MySQL is running in XAMPP.
2. Database name is exactly:

```text
wigzo_tape_db
```

3. `backend/config/config.php` has correct database settings.
4. You imported both:

```text
schema.sql
seed.sql
```

### Problem: React says backend not found

Check:

1. PHP backend command is still running.
2. Browser can open:

```text
http://localhost:8000/api/products
```

3. `frontend/.env` contains:

```env
REACT_APP_BACKEND_URL=http://localhost:8000
```

4. Restart React after changing `.env`.

Stop React with `Ctrl + C`, then run:

```bash
npm start
```

### Problem: npm install fails

Try:

```bash
npm cache clean --force
npm install --legacy-peer-deps
```

If it still fails, delete:

```text
frontend/node_modules
frontend/package-lock.json
```

Then run:

```bash
npm install --legacy-peer-deps
```

### Problem: Login not working

Check:

1. `seed.sql` was imported.
2. The users table has the admin user.
3. You are using:

```text
admin@wigzotape.com
admin123
```

### Problem: Port already in use

If port `8000` is busy, use another port:

```bash
php -S localhost:8001
```

Then update:

```text
frontend/.env
```

to:

```env
REACT_APP_BACKEND_URL=http://localhost:8001
```

Restart React after changing this.

## How to Stop the Project

To stop React:

Press:

```text
Ctrl + C
```

in the React terminal.

To stop PHP backend:

Press:

```text
Ctrl + C
```

in the PHP backend terminal.

To stop database:

Open XAMPP Control Panel and stop MySQL.

## How to Add a New Product

1. Login as admin.
2. Go to admin panel.
3. Open Products tab.
4. Add product details.
5. Save.

The product will be stored in MySQL.

## How to Create a Coupon

1. Login as admin.
2. Go to admin panel.
3. Open Coupons tab.
4. Generate or type coupon code.
5. Choose percentage or fixed discount.
6. Save.

## How to Deploy on Cheap Hosting

Most cheap hosting providers give:

- cPanel
- PHP
- MySQL
- phpMyAdmin

### Backend Hosting

1. Create a MySQL database from cPanel.
2. Create a MySQL user.
3. Import:

```text
backend/database/schema.sql
backend/database/seed.sql
```

using phpMyAdmin.

4. Upload backend files.

If your backend URL will be:

```text
https://yourdomain.com/api-backend
```

upload the contents of:

```text
backend/public
```

to:

```text
public_html/api-backend
```

5. Upload `backend/config/config.php`.
6. Update `config.php` with hosting database details.

Example:

```php
'db_host' => 'localhost',
'db_name' => 'cpaneluser_wigzo_tape_db',
'db_user' => 'cpaneluser_dbuser',
'db_pass' => 'your_database_password',
'cors_origins' => 'https://yourdomain.com',
```

### Frontend Hosting

Before building frontend, open:

```text
frontend/.env
```

Set your real backend URL:

```env
REACT_APP_BACKEND_URL=https://yourdomain.com/api-backend
```

Then run:

```bash
cd frontend
npm install --legacy-peer-deps
npm run build
```

After build completes, upload the contents of:

```text
frontend/build
```

to:

```text
public_html
```

Do not upload the whole `frontend` folder to `public_html`.

Only upload the files inside `frontend/build`.

## Security Notes Before Real Use

Before using this project for a real store:

1. Change `jwt_secret` in:

```text
backend/config/config.php
```

2. Change the admin password.
3. Use HTTPS on hosting.
4. Add real email sending if needed.
5. Add real Razorpay verification before accepting real payments.
6. Do not show database passwords publicly.

## Payment Note

The payment flow is currently dummy-compatible.

That means it is useful for student projects, demos, and testing.

For a real shop, Razorpay signature verification must be fully connected with your real Razorpay keys.

## Email Note

The old project printed email messages instead of sending real emails.

This PHP version also keeps email simple.

For real emails, you can later add PHPMailer or SMTP.

## Quick Start Summary

Fastest local setup using SQLite:

1. Open terminal in `backend/public`.
2. Run:

```bash
php -S localhost:8000
```

3. Open another terminal in `frontend`.
4. Run:

```bash
npm install --legacy-peer-deps
npm start
```

5. Open:

```text
http://localhost:3000
```

MySQL/XAMPP setup:

1. Start XAMPP Apache and MySQL.
2. Open phpMyAdmin.
3. Create database `wigzo_tape_db`.
4. Import `schema.sql`.
5. Import `seed.sql`.
6. Open terminal in `backend/public`.
7. Run:

```bash
php -S localhost:8000
```

8. Open another terminal in `frontend`.
9. Run:

```bash
npm install --legacy-peer-deps
npm start
```

10. Open:

```text
http://localhost:3000
```

Admin:

```text
admin@wigzotape.com
admin123
```

That is it. The project should now run locally.
