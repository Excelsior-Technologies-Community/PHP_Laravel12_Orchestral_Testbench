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

        $this->setupDatabase();
    }

    protected function setupDatabase()
    {
        \Illuminate\Support\Facades\Schema::create('products', function ($table) {
            $table->id();
            $table->string('name');
        });
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
