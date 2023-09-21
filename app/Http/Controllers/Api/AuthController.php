<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\User;
use App\Libraries\ResponseBase;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials))
            return ResponseBase::error("Password salah", 403);

        return ResponseBase::success('Login berhasil', ['token' => $token, 'type' => 'bearer']);
    }

    public function register(AuthRequest $request)
    {
        try {
            $user = new User();
            $user->role_id = 1;
            $user->name = $request->name;
            $user->email = strtolower($request->email);
            $user->password = Hash::make($request->password);
            $user->save();

            return ResponseBase::success("Berhasil register!", $user);
        } catch (\Exception $e) {
            Log::error('Gagal register -> ' . $e->getFile() . ':' . $e->getLine() . ' => ' . $e->getMessage());
            return ResponseBase::error('Gagal register!', 409);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $authUser = $this->findOrCreateUser($user);
            $token = JWTAuth::fromUser($authUser);

            return ResponseBase::success('Login berhasil', ['token' => $token, 'type' => 'bearer']);
        } catch (\Exception $e) {
            Log::error('Gagal autentikasi google -> ' . $e->getFile() . ':' . $e->getLine() . ' => ' . $e->getMessage());
            return ResponseBase::error("Gagal autentikasi google : " . $e->getMessage(), 403);
        }
    }

    private function findOrCreateUser($googleUser)
    {
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            return $user;
        } else {
            try {
                $user = new User();
                $user->role_id = 1;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->save();

                return $user;
            } catch (\Exception $e) {
                Log::error('Gagal register -> ' . $e->getFile() . ':' . $e->getLine() . ' => ' . $e->getMessage());
                return ResponseBase::error('Gagal register!', 409);
            }
        }
    }

    public function logout()
    {
        JWTAuth::invalidate();

        return ResponseBase::success('Logout berhasil');
    }
}
