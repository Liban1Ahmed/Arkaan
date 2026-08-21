<?php

namespace App\Http\Controllers;

use App\Models\SiteAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SiteAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        $isAdmin = SiteAdmin::where('user_id', $user->id)->exists();

        if (! $isAdmin) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['This account does not have admin access.'],
            ]);
        }

        $token = $user->createToken('site-admin-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}