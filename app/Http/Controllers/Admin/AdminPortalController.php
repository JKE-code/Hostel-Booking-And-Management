<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPortalController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function allocations()
    {
        return view('admin.allocations');
    }

    public function leaves()
    {
        return view('admin.leaves');
    }

    public function complaints()
    {
        return view('admin.complaints');
    }
}
