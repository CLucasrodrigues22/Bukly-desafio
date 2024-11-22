# 📌 Bukly v2

This repository was created as part of a technical assessment for candidates. The objective is to evaluate your skills in Laravel's basic functionalities, with a focus on using automated tests.

The application already includes several automated tests. Your task is to follow the provided instructions, implement the required functionalities, and ensure that all tests pass. It is important to follow the order of the tests, as functionalities developed in earlier steps may be used in subsequent tests.

For the last file, `h_ShareReservationTest`, the approach is different: the functionality is already implemented, and your challenge will be to create the necessary tests to validate it.

Below, we provide a guide to set up the application using Laravel Sail. If you prefer, you can also use PHP locally. The only dependencies required are PHP and Composer, as the database is configured to use SQLite.

### 📋 Requirements

-   PHP v8.2 or higher

### 🔧 How to install

-   Clone the project

    ```bash
        git clone git@github.com:RioTera-Ltda/Test-Laravel-v2.git
    ```

-   Copy the .env.example file

    -   If using linux: cp .env.example .env
    -   If you are on windows, open the file in a code editor and save it again as .env

-   Installing Composer Dependencies using Docker

    ```bash
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v "$(pwd):/var/www/html" \
            -w /var/www/html \
            laravelsail/php83-composer:latest \
            composer install --ignore-platform-reqs
    ```

-   You can configure a shell alias

    ```bash
        alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
    ```

-   Run the system using Docker and Sail

    ```bash
        sail up -d
    ```

-   Create a new key for the application

    ```bash
        sail artisan key:generate
    ```

-   Run Migrations with Seeders

    ```bash
        sail artisan migrate:fresh --seed
    ```

-   Installing Node Dependencies

    ```bash
        sail npm install
    ```

-   Build the Application

    ```bash
        sail npm run build
    ```

### 📦 Development Tools

-   Run Pest tests

    ```bash
        sail artisan test
    ```

-   Run Pest tests with Coverage

    ```bash
        sail artisan test --coverage
    ```

-   Run pint command to fix the code style of PHP files

    ```bash
        sail exec laravel.test ./vendor/bin/pint
    ```

-   Run prettier command to fix the code style of JS files

    ```bash
        sail exec laravel.test npx prettier . --write
    ```

## 🚀 Okay, good job!
