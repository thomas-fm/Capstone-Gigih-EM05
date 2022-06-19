# Capstone Generasi Gigih EM-05

## Requirement
- PHP 7.3+
- Laravel 8
- MySQL server

## How to run
### First run
- Clone repository
- Go to project directory
- Run `composer install` to generate dependencies
- Generate key `php artisan key:generate`
- Copy `env.example` file to `env`
- Change database configuration
- Create local database
### Run local
- Go to project directory
- Run migrations and seeder if needed
- Run `php artisan serve`
- Open `http://127.0.0.1:8000`

## Documentation & Resources
- ERD + Use case : https://lucid.app/lucidchart/a9052a03-273d-4cd2-8ef7-90d295edc1f2/edit?viewport_loc=-308%2C1%2C2219%2C1065%2CvPAncC6G.YyX&invitationId=inv_c7ed0e39-ab7b-473b-aa72-b9889aee3f6e#
- Laravel 8 : https://laravel.com/docs/8.x 
- CRUD + Auth : https://www.codecheef.org/article/laravel-6-rest-api-with-jwt-authentication-with-crud 
- Relationship : https://appdividend.com/2022/01/21/laravel-many-to-many-relationship/ 

