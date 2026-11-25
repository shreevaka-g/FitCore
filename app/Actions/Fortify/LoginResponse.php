<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }

        auth()->logout();

        return redirect()->route('login')
            ->withErrors([
                'email' => 'You are not authorized to access the system.',
            ]);
    }
}
