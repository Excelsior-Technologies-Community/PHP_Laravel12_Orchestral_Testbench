<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DatabaseTest extends TestCase
{
    public function test_database_insert()
    {
        \DB::table('products')->insert([
            'name' => 'Test Product'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product'
        ]);

        echo "\nDatabase Insert Test Passed\n";
    }
}
