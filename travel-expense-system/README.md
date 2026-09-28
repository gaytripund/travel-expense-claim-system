# Travel Expense Claim System (Laravel)

A simple Laravel app where employees submit travel expense claims with a bill
upload, and managers approve, reject, or send claims back for correction.

## Features
- Employee & Manager roles (chosen at registration)
- Employees: submit claims (title, from/to, date, amount, description, bill file), view their own claims, edit and resubmit claims that were sent back
- Managers: view all claims, approve / reject (with reason) / send back for correction (with reason)
- File upload for bills (jpg, jpeg, png, pdf, max 2MB), stored in `storage/app/public/bills`

## Requirements
- PHP >= 8.1
- Composer

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

The project already uses SQLite (`database/database.sqlite`), so no separate
database server is required. Just run the commands above and visit
http://127.0.0.1:8000.

## How to use
1. Register two accounts: one as "employee", one as "manager".
2. Log in as the employee, click "New Claim", fill the form, attach a bill file, submit.
3. Log in as the manager, open the claim, and Approve / Reject / Send Back for Correction.
4. If sent back, log in as the employee again, open the claim, click "Edit & Resubmit".

## Project structure
- `app/Models/User.php`, `app/Models/ExpenseClaim.php` — Eloquent models
- `app/Http/Controllers/AuthController.php` — register/login/logout
- `app/Http/Controllers/ExpenseClaimController.php` — claim submission + approval workflow
- `app/Http/Middleware/EnsureRole.php` — restricts approve/reject/return routes to managers
- `database/migrations/` — users, password_reset_tokens, expense_claims tables
- `routes/web.php` — all routes
- `resources/views/` — Blade templates (Bootstrap via CDN, no build step needed)
