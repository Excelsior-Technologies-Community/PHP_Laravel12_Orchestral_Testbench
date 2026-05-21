<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        $this->setupDatabase();
    }

    protected function setupDatabase()
    {
        
        Schema::create('products', function ($table) {
            $table->id();
            $table->string('name');
        });

       
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}