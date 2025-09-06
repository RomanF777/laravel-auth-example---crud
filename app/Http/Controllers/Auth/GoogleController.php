<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    // Редирект на Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback от Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google user data', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);
            
            Auth::logout();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            \Log::info('Found user in DB', [
                'user_id' => $user?->id,
                'current_google_id' => $user?->google_id
            ]);
            
            if ($user) {
                \DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'google_id' => $googleUser->getId(),
                        'email_verified_at' => now(),
                    ]);
                
                $user = User::find($user->id);
                \Log::info('After update', [
                    'new_google_id' => $user->google_id
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()),
                    'email_verified_at' => now(),
                ]);
                \Log::info('New user created', [
                    'google_id' => $user->google_id
                ]);
            }
            
            Auth::login($user);
            
            \Log::info('Final auth user', [
                'google_id' => Auth::user()->google_id
            ]);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            \Log::error('Google auth error', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors([
                'google' => 'Ошибка аутентификации через Google'
            ]);
        }
    }
}
