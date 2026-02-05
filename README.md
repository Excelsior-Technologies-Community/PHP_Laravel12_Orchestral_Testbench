# PHP_Laravel12_Orchestral_Testbench


## Project Description

PHP_Laravel12_Orchestral_Testbench is a beginner-friendly Laravel 12 project that demonstrates how to set up and use **Orchestral Testbench** for testing Laravel packages.

Orchestral Testbench provides a lightweight, in-memory Laravel application that allows developers to write and run package tests without installing the package into a full Laravel application.

This project focuses on configuring Testbench correctly, setting up PHPUnit, and verifying that the testing environment works as expected using modern Laravel testing practices.

The project uses **Laravel 12**, **PHPUnit 11**, and **Orchestral Testbench v10**.

---

## Project Objectives

- Understand the purpose of Orchestral Testbench
- Learn how to configure Testbench with Laravel 12
- Set up a proper PHPUnit testing environment
- Use an in-memory SQLite database for testing
- Create and run unit tests for Laravel packages
- Prepare a clean base project for future package development


## Technology Used

- PHP 8.2+ – Backend programming language  
- Laravel 12 – PHP web application framework  
- Orchestral Testbench v10 – Lightweight Laravel environment for package testing  
- PHPUnit 11 – Unit testing framework  
- SQLite (In-Memory) – Fast database used only for testing  
- Composer – Dependency management tool  


---


# Project Setup


---



## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_Orchestral_Testbench "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Orchestral_Testbench

```

Make sure Laravel 12 is installed successfully.




## STEP 2: Setup Database (Optional)

### Open .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Orchestral_Testbench
DB_USERNAME=root
DB_PASSWORD=

```

### Create Database

```
laravel12_Orchestral_Testbench

```

### Then run:

```
php artisan migrate

```
NOTE:
Database setup is NOT required because Testbench uses an in-memory SQLite database for testing.



## STEP 3: Install Orchestral Testbench

### Now install Testbench (Laravel 12 compatible version):

```
composer require --dev orchestra/testbench:^10.0

```

--dev means → only for testing (not production)




## STEP 4: Create Test Folder Structure

### Run these commands one by one (check if these folders already exist):

```
mkdir tests
mkdir tests/Unit
mkdir tests/Feature

```

### Your folder structure now looks like:

```
tests
├── TestCase.php
├── Unit
│   └── DemoPackageTest.php (or any real test)
├── Feature (optional / can be empty)



```



## STEP 5: Create Base TestCase (IMPORTANT)

### Create file  (check if these folders already exist):

```
tests/TestCase.php

```

### Open tests/TestCase.php:

```
<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * This TestCase bootstraps a fake Laravel app
 * for package testing
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Called before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Any setup logic goes here
    }

    /**
     * Register package service providers
     * (Leave empty for now)
     */
    protected function getPackageProviders($app)
    {
        return [
            // Example:
            // \Vendor\Package\PackageServiceProvider::class,
        ];
    }

    /**
     * Setup test environment
     */
    protected function getEnvironmentSetUp($app)
    {
        // Use SQLite in-memory database for tests
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}

```

This file is the heart of Testbench




## STEP 6: Configure PHPUnit

### create file:

```
phpunit.xml

```

### Open phpunit.xml

```

<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    bootstrap="vendor/autoload.php"
    colors="true">

    <testsuites>
        <testsuite name="Unit Tests">
            <directory suffix="Test.php">tests/Unit</directory>
        </testsuite>
    </testsuites>

    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="testbench"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
    </php>

</phpunit>

```



## STEP 7: Run Testbench Tests

### Run this command:

```
php vendor/bin/phpunit

```

✔ Output should look like:

OK (1 test, 1 assertion)


SUCCESS! Orchestral Testbench is working


## So you can see this type:


<img width="1454" height="282" alt="Screenshot 2026-02-05 105540" src="https://github.com/user-attachments/assets/ef76f53c-2d61-43f5-91b6-5eecd188fdc3" />



---

# Project Folder Structure:

```

PHP_Laravel12_Orchestral_Testbench
├── app
├── vendor
├── tests
│   ├── TestCase.php
│   ├── Unit
│   │   └── DemoPackageTest.php
│   └── Feature
├── phpunit.xml
├── composer.json
└── artisan

```
