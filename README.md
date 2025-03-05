## About The Project
This is a simple ToDo List project using Laravel and Vue.js, 
where you can add, edit, delete and mark as done the tasks.

## Setup of the project
- Clone the project
- Run `npm install`
- Run `npm run build`
- Run `composer install`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Run `php artisan serve`

## key considerations
- I did not create two separate projects for the frontend and backend (Api), Instead the frontend is integrated into the Laravel project. 
You can find the frontend setup in the **resources/js** folder and **welcome.blade.php**.
- I have installed vue-router for managing the routes in the frontend, and pinia for managing the state.

### About the usage of Pinia:
- I only used it in auth because i wanted to showcase the different ways of managing the state in Vue.js that I know of until now.

### About tests
- I have written some basic feature tests for AuthController, and TaskController.
- you can run the tests using `php artisan test` or `vendor/bin/phpunit`


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
