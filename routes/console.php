<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\MakeTestbenchFeature;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:testbench-feature {name}', function ($name) {
    (new MakeTestbenchFeature())->handle();
})->purpose('Create a new feature test class in tests/Feature');