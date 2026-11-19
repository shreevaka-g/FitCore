<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use domain\Facades\MemberFacade;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // $allUsers = MemberFacade::allUsers();
            // $totalCount = $allUsers->count();

            return view('pages.admin.dashboard.index');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
