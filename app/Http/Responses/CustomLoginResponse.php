<?php

namespace App\Http\Responses;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        // Tambahkan flash message pada redirect
        return redirect()->intended(Fortify::redirects('login'))
            ->with('success', 'Login berhasil! Selamat datang kembali.');
    }
}