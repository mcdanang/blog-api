# Laravel Blog API

This project is a RESTful API for a simple blog system built using Laravel 11. It demonstrates proficiency in Laravel, API development, authentication, database design, and adherence to best practices.

## Features

- User authentication using Laravel Sanctum
- CRUD operations for blog posts
- User registration and management
- Queued welcome email for new user registration
- API documentation

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL 5.7 or higher
- Laravel 11

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/mcdanang/blog-api.git
   cd -blog-api
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Copy the example env file and make the required configuration changes in the .env file:
   ```
   cp .env.example .env
   ```

4. Generate a new application key:
   ```
   php artisan key:generate
   ```

5. Run the database migrations (Set the database connection in .env before migrating):
   ```
   php artisan migrate
   ```

6. Start the local development server:
   ```
   php artisan serve
   ```

You can now access the server at http://localhost:8000

## Database Configuration

In the .env file, add database information to allow Laravel to connect to the database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_blog
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Replace `your_mysql_password` with your actual MySQL password.

## API Endpoints

- POST /api/register (register a new user)
- POST /api/login (login a user)
- POST /api/logout (logout a user)
- GET /api/users/{id} (get a specific user)
- GET /api/posts (list all posts)
- GET /api/posts/{id} (get a specific post)
- POST /api/posts (create a new post)
- PATCH /api/posts/{id} (update an existing post)
- DELETE /api/posts/{id} (delete a post)

## Running Tests

To run the tests for this application, execute:

```
php artisan test
```

## Queue Worker

To process queued jobs (like sending welcome emails), run:

```
php artisan queue:work
```

## Manual Email Job Dispatch

To manually dispatch the welcome email job (for testing purposes), use:

```
php artisan email:welcome {user_id}
```

Replace {user_id} with the ID of the user you want to send the welcome email to.

## API Documentation

API documentation is available at [insert link or path to your API documentation].

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail via [insert contact method]. All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).