<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    /**
     * Boys Hostel Information Page
     */
    public function boys()
    {
        return view('public.hostels.boys');
    }

    /**
     * Girls Hostel Information Page
     */
    public function girls()
    {
        return view('public.hostels.girls');
    }

    /**
     * New Boys Hostel (Upcoming) Page
     */
    public function newBoys()
    {
        return view('public.hostels.new-boys');
    }
}
