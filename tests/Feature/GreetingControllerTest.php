<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\UserGreetingService;
use Illuminate\Support\Facades\Cache;

class GreetingControllerTest extends TestCase
{
    private UserGreetingService $greetingService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->greetingService = new UserGreetingService();
        Cache::flush();
    }
    
    /** @test */
    public function it_displays_greeting_form()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('User Greeting System');
        $response->assertSee('Your Name');
    }
    
    /** @test */
    public function it_generates_greeting_with_valid_name()
    {
        $response = $this->post('/greet', [
            'name' => 'John Doe'
        ]);
        
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHasNoErrors();
        
        $this->assertNotNull(session()->get('_old_input'));
    }
    
    /** @test */
    public function it_validates_required_name()
    {
        $response = $this->post('/greet', [
            'name' => ''
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }
    
    /** @test */
    public function it_validates_name_min_length()
    {
        $response = $this->post('/greet', [
            'name' => 'J'
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }
    
    /** @test */
    public function it_validates_name_max_length()
    {
        $longName = str_repeat('a', 101);
        $response = $this->post('/greet', [
            'name' => $longName
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }
    
    /** @test */
    public function it_handles_cache_clear_endpoint()
    {
        // First cache a greeting
        $name = 'CacheTestUser';
        $this->greetingService->getCachedGreeting($name);
        
        $cacheKey = 'greeting_' . md5($name);
        $this->assertTrue(Cache::has($cacheKey));
        
        // Clear the cache
        $response = $this->postJson('/clear-cache', [
            'name' => $name
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertFalse(Cache::has($cacheKey));
    }
    
    /** @test */
    public function it_handles_bulk_greeting_api()
    {
        $response = $this->postJson('/api/bulk-greet', [
            'names' => ['Alice', 'Bob', 'Charlie']
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'greetings' => [
                'Alice',
                'Bob',
                'Charlie'
            ]
        ]);
    }
    
    /** @test */
    public function it_validates_bulk_greeting_api_empty_array()
    {
        $response = $this->postJson('/api/bulk-greet', [
            'names' => []
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['names']);
    }
    
    /** @test */
    public function it_validates_bulk_greeting_api_max_items()
    {
        $manyNames = array_fill(0, 11, 'User');
        $response = $this->postJson('/api/bulk-greet', [
            'names' => $manyNames
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['names']);
    }
    
    /** @test */
    public function it_validates_bulk_greeting_api_string_items()
    {
        $response = $this->postJson('/api/bulk-greet', [
            'names' => [123, 456] // Numbers instead of strings
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['names.0', 'names.1']);
    }
    
    /** @test */
    public function it_respects_cache_flag()
    {
        $name = 'CacheFlagUser';
        
        // First request with cache enabled
        $response1 = $this->post('/greet', [
            'name' => $name,
            'use_cache' => true
        ]);
        $response1->assertStatus(302);
        
        // Verify cache was created
        $cacheKey = 'greeting_' . md5($name);
        $this->assertTrue(Cache::has($cacheKey));
    }
}