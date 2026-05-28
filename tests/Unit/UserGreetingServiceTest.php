<?php

namespace Tests\Unit;

use App\Services\UserGreetingService;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UserGreetingServiceTest extends TestCase
{
    private UserGreetingService $greetingService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->greetingService = new UserGreetingService();
        Cache::flush(); // Clear cache before each test
    }
    
    /** @test */
    public function it_generates_morning_greeting()
    {
        // Mock the time to morning
        $this->travelTo(now()->setTime(9, 0, 0));
        
        $greeting = $this->greetingService->greet('John');
        
        $this->assertStringContainsString('Good morning', $greeting);
        $this->assertStringContainsString('John', $greeting);
        $this->assertStringContainsString('Welcome to our application!', $greeting);
    }
    
    /** @test */
    public function it_generates_afternoon_greeting()
    {
        $this->travelTo(now()->setTime(14, 0, 0));
        
        $greeting = $this->greetingService->greet('Jane');
        
        $this->assertStringContainsString('Good afternoon', $greeting);
        $this->assertStringContainsString('Jane', $greeting);
    }
    
    /** @test */
    public function it_generates_evening_greeting()
    {
        $this->travelTo(now()->setTime(19, 0, 0));
        
        $greeting = $this->greetingService->greet('Bob');
        
        $this->assertStringContainsString('Good evening', $greeting);
        $this->assertStringContainsString('Bob', $greeting);
    }
    
    /** @test */
    public function it_uses_custom_suffix_from_config()
    {
        Config::set('greeting.suffix', 'Have a great day!');
        
        $greeting = $this->greetingService->greet('Alice', 'morning');
        
        $this->assertStringContainsString('Have a great day!', $greeting);
    }
    
    /** @test */
    public function it_caches_greetings()
    {
        $name = 'CachedUser';
        
        // First call - should generate and cache
        $firstGreeting = $this->greetingService->getCachedGreeting($name);
        
        // Second call - should return from cache
        $secondGreeting = $this->greetingService->getCachedGreeting($name);
        
        $this->assertEquals($firstGreeting, $secondGreeting);
        
        // Verify cache exists
        $cacheKey = 'greeting_' . md5($name);
        $this->assertTrue(Cache::has($cacheKey));
    }
    
    /** @test */
    public function it_clears_cached_greeting()
    {
        $name = 'TestUser';
        $this->greetingService->getCachedGreeting($name);
        
        $cacheKey = 'greeting_' . md5($name);
        $this->assertTrue(Cache::has($cacheKey));
        
        $result = $this->greetingService->clearCachedGreeting($name);
        
        $this->assertTrue($result);
        $this->assertFalse(Cache::has($cacheKey));
    }
    
    /** @test */
    public function it_handles_bulk_greeting()
    {
        $names = ['John', 'Jane', 'Bob'];
        
        $results = $this->greetingService->bulkGreet($names);
        
        $this->assertCount(3, $results);
        $this->assertArrayHasKey('John', $results);
        $this->assertArrayHasKey('Jane', $results);
        $this->assertArrayHasKey('Bob', $results);
        
        foreach ($results as $greeting) {
            $this->assertIsString($greeting);
            $this->assertNotEmpty($greeting);
        }
    }
    
    /** @test */
    public function it_allows_custom_time_of_day_override()
    {
        $greeting = $this->greetingService->greet('Custom', 'night');
        
        $this->assertStringContainsString('Good night', $greeting);
    }
    
    /** @test */
    public function it_returns_default_greeting_for_invalid_time()
    {
        $greeting = $this->greetingService->greet('User', 'invalid_time');
        
        $this->assertStringContainsString('Hello', $greeting);
    }
    
    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }
}