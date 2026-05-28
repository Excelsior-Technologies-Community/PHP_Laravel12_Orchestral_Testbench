<?php

namespace App\Http\Controllers;

use App\Services\UserGreetingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GreetingController extends Controller
{
    protected UserGreetingService $greetingService;
    
    public function __construct(UserGreetingService $greetingService)
    {
        $this->greetingService = $greetingService;
    }
    
    /**
     * Show greeting form
     */
    public function index()
    {
        return view('greeting');
    }
    
    /**
     * Generate and display greeting
     */
    public function greet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|min:2',
            'use_cache' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $name = $request->input('name');
        $useCache = $request->boolean('use_cache', false);
        
        if ($useCache) {
            $greeting = $this->greetingService->getCachedGreeting($name);
        } else {
            $greeting = $this->greetingService->greet($name);
        }
        
        return view('greeting', [
            'greeting' => $greeting,
            'name' => $name,
            'usedCache' => $useCache
        ]);
    }
    
    /**
     * Clear cache for a user
     */
    public function clearCache(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid name'], 422);
        }
        
        $cleared = $this->greetingService->clearCachedGreeting($request->input('name'));
        
        return response()->json([
            'success' => $cleared,
            'message' => $cleared ? 'Cache cleared successfully' : 'Cache not found'
        ]);
    }
    
    /**
     * Bulk greet API endpoint
     */
    public function bulkGreet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'names' => 'required|array|min:1|max:10',
            'names.*' => 'required|string|max:100'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $greetings = $this->greetingService->bulkGreet($request->input('names'));
        
        return response()->json(['greetings' => $greetings]);
    }
}