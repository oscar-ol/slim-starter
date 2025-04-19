# Slim Starter

Slim Starter is a base template for building PHP applications using the Slim microframework v4, but with an architecture inspired by Laravel.

This project includes helpers, libraries, and a folder organization that facilitates professional development of APIs or lightweight web applications, keeping the code clean and easy to maintain.

## Features

- **Slim Framework**: A fast and flexible microframework for PHP.
- **Laravel-inspired architecture**: Organized folder structure, helpers, and centralized configuration.
- **Twig**: Template engine for rendering views.
- **Eloquent ORM**: Database management with Laravel's ORM.
- **Dotenv**: Environment variable management.
- **CSRF Protection**: Middleware to protect against CSRF attacks.
- **Error handling**: Custom views for 404 and 500 errors.
- **Support for migrations and seeders**: Using Phinx to manage the database.
- **Mailer Service**: Simplified email sending with PHPMailer integration.
- **Testing with Pest**: Write expressive and maintainable tests.
- **Static analysis with PHPStan**: Ensure code quality and type safety.
- **Code formatting with Pint**: Maintain consistent code style.
- **Modular structure**: Easily extendable and maintainable codebase.

## Requirements

- PHP >= 8.1
- Composer
- Web server (Apache, Nginx, or PHP's built-in server)
- Compatible database (MySQL, SQLite, etc.)

## Installation

1. Run this command to install:
   ```bash
   composer create-project oscar-ol/slim-starter [my-app-name]
   ```
2. Navigate to the project directory:
   ```bash
   cd [my-app-name]
   ```

3. Configure the environment variables in the `.env` file as needed.

## Usage

### Start the development server
```bash
php -S localhost:8080 -t public/
```
or
```bash
composer start
```

### Project structure
- **app/**: Contains controllers, middlewares, and exceptions.
- **config/**: Configuration files.
- **database/**: Migrations and seeders.
- **helpers/**: Global functions to facilitate development.
- **public/**: Application entry point.
- **resources/views/**: Twig templates.
- **routes/**: Web and API route definitions.
- **storage/**: Cache and other generated files.
- **tests/**: Unit and feature tests using Pest.

### Useful commands

- **Migrations**:
  ```bash
  vendor/bin/phinx create MigrationName
  vendor/bin/phinx migrate
  vendor/bin/phinx rollback
  ```

- **Seeders**:
  ```bash
  vendor/bin/phinx seed:run
  ```

  Additional information about Phinx: [Phinx Documentation](https://phinx.org/)

### Composer Tools

This project includes several Composer tools to improve code quality and maintainability:

- **Rector**: For automated code refactoring.
  ```bash
  composer tools:rector
  ```

- **PHPStan**: For static code analysis.
  ```bash
  composer tools:phpstan
  ```

- **Pint**: For code formatting.
  ```bash
  composer tools:pint
  ```

- **Pest**: For running tests.
  ```bash
  composer tools:pest
  ```

- **Clear view cache**:
  ```bash
  composer view:clear
  ```

You can run all tools at once using:
```bash
composer tools
```

## Contributing

If you want to contribute to this project, please open an issue or submit a pull request. All help is welcome!

## License

This project is licensed under the MIT License.
