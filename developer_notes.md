# INSTRUCTIONS FOR THIS REPOSITORY

## 1. update and install packages and vendor folder
Stand inside laravel folder, type:

1. 'composer update'
2. 'composer install'

This will load composer repositories, then import packages and create the vendor folder with autoload script

## 2. Copy and rename file .env.example to .env
Settings here will overwrite config/database.php

- mysql is set at port 6666 (if running MySQL directly on host computer rather than in Docker container, DB_PORT needs to be changed to 3306)
- database name is 'sctr'
- username for this db is 'root'
- password for this db is 'fstr_hrdr_sctr'

## 3. Create model Customer
php artisan make:model Customer -m

All models extend Illuminate\Database\Eloquent\Model which is used by eloquent to create communication between application and database
-m directive instruct laravel to create a db migration alongside creating our Customer model.

## 4. Go to laravel/app/Models/Customer.php
Models folder contains database tables only

Remove content in class Customer and fill with:

protected $table = 'customer'; // table name
protected $primaryKey = 'id';
public $timestamps = false; // remove columns created_at and updated_at
protected $addHttpCookie = true;  // send csrf token for post requests in order to work with post/put


then run migrations so laravel will generate our tables:
php artisan migrate

# 5. Define routes in /routes/web.php
First define that model Customer will be used at top of file:
use App\Models\Customer;

If changing routes file:
- php artisan cache:clear
- php artisan route:clear

# 6. Create functions for inserting/updating table data
Look inside in app/Http/Controllers/Sctr
isset is used in order to update columns only if specific body data is received


# 7. Verify csrf token for the routes above
Go to app/Http/Middleware/VerifyCsrfToken.php
Then enter your routes where csrf token will be disabled.
This is needed in order to make POST/PUT requests

# 9. Create key and run laravel server
php artisan key:generate
php artisan serve

Server now runs at localhost:8000
