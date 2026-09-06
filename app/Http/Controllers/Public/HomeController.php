<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function index()
    {
        return view('public.home');
    }
}
