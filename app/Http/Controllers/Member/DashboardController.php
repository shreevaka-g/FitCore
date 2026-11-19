<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function index()
    {
        try {

            return view('pages.member.dashboard.index');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
