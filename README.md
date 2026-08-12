# Expenses Management System

## Introduction

An ongoing expense management application built with Laravel and a Vue frontend using [Inertia](https://inertiajs.com), Vue 3 (Composition API), TypeScript, Tailwind, and the [shadcn-vue](https://www.shadcn-vue.com) component library.

## Project Status

This project is **still ongoing / in active development**. Features and structure are continuously being added and refined.

## Architecture

The backend follows the **Service and Repository pattern**:

- **Controllers** — handle incoming HTTP requests and return responses.
- **Services** (`app/Services`) — business logic layer.
- **Repositories** (`app/Repositories`) — data access layer that interacts with Eloquent models.
- **Interfaces** (`app/Interfaces`) — contracts that define what each repository must implement.
- **RepositoryProvider** (`app/Providers/RepositoryProvider.php`) — binds each interface to its concrete repository implementation, so the application resolves the correct repository through dependency injection.

This separation keeps business logic decoupled from data access, making the codebase easier to test and maintain.

## Database

The application uses **ULIDs as primary keys** throughout the database. All tables (`users`, `passkeys`, `categories`, `budgets`, `expenses`, and their foreign-key columns such as `author_id`, `category_id`, and `user_id`) use `char(26)` ULID identifiers instead of auto-incrementing integers.

## Official Documentation

Documentation for the underlying Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

All contributions to the Starter Kits from now on should be made through [Maestro](https://github.com/laravel/maestro).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + Vue starter kit is open-sourced software licensed under the MIT license.
