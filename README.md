# Capstone Generasi Gigih EM-05

## Requirement
- PHP 7.3+
- [Laravel 8](https://laravel.com/docs/8.x)
- MySQL server

## How to run
### Installation
- Clone repository
- Go to project directory
- Run `composer install` or `compose update` to generate dependencies
- Generate key `php artisan key:generate`
- Copy `env.example` file to `env`
- Change database configuration
- Create local database
- Generate JWT key with `php artisan jwt:secret` or copy from `env.example`
### Run the project
- Go to project directory
- Run migrations and seeder if needed. Quick command with `php artisan migrate:refresh --seed`
- Run `php artisan serve`
- Open `http://127.0.0.1:8000`

## How to verify Registration email & Forgot password
- Open `https://mailtrap.io`
- Go to the login page and enter email & password
- Email account : `gigihbeem5@gmail.com`
- Password account : `p0o9i8u7`
- Then click `Project` button on the My Project tab menu
- To be able to see the incoming email for verification and forget password you can go to the Inboxes tab

## Documentation & Resources
- [Postman Collection](https://documenter.getpostman.com/view/20080194/UzJFudWu#intro)
- [ERD + Use case](https://lucid.app/lucidchart/a9052a03-273d-4cd2-8ef7-90d295edc1f2/edit?viewport_loc=-308%2C1%2C2219%2C1065%2CvPAncC6G.YyX&invitationId=inv_c7ed0e39-ab7b-473b-aa72-b9889aee3f6e#)
