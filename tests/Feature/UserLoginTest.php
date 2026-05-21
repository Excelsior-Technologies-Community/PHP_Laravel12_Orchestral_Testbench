<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLoginTest extends TestCase
{
    public function test_user_can_login_with_correct_credentials()
    {
   
        Route::post('/login', function (Request $request) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect('/dashboard'); 
            }
            return response('Unauthorized', 401); 
        });

       
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

       
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

       
        $response->assertStatus(302);
        
       
        $this->assertAuthenticatedAs($user);
    }
}