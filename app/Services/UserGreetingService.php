<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UserGreetingService
{
    /**
     * Generate a personalized greeting for a user
     */
    public function greet(string $name, ?string $timeOfDay = null): string
    {
        $timeOfDay = $timeOfDay ?? $this->determineTimeOfDay();
        
        $greetings = [
            'morning' => 'Good morning',
            'afternoon' => 'Good afternoon',
            'evening' => 'Good evening',
            'night' => 'Good night'
        ];
        
        $greeting = $greetings[$timeOfDay] ?? 'Hello';
        $suffix = Config::get('greeting.suffix', 'Welcome to our application!');
        
        return sprintf('%s, %s! %s', $greeting, $name, $suffix);
    }
    
    /**
     * Get greeting with caching
     */
    public function getCachedGreeting(string $name, int $ttl = 3600): string
    {
        $cacheKey = 'greeting_' . md5($name);
        
        return Cache::remember($cacheKey, $ttl, function () use ($name) {
            return $this->greet($name);
        });
    }
    
    /**
     * Clear cached greeting for a user
     */
    public function clearCachedGreeting(string $name): bool
    {
        $cacheKey = 'greeting_' . md5($name);
        return Cache::forget($cacheKey);
    }
    
    /**
     * Determine time of day based on current hour
     */
    private function determineTimeOfDay(): string
    {
        $hour = now()->hour;
        
        if ($hour >= 5 && $hour < 12) {
            return 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            return 'evening';
        } else {
            return 'night';
        }
    }
    
    /**
     * Bulk greet multiple users
     */
    public function bulkGreet(array $names): array
    {
        $results = [];
        foreach ($names as $name) {
            $results[$name] = $this->greet($name);
        }
        return $results;
    }
}