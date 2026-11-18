<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return view('pages.admin.dashboard.index');
        } else {
            return view('pages.user.dashboard.index');
        }
    }
}
