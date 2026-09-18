<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function room()
    {
        return view('student.room');
    }

    public function leaves()
    {
        return view('student.leaves');
    }

    public function complaints()
    {
        return view('student.complaints');
    }

    public function mess()
    {
        return view('student.mess');
    }

    public function profile()
    {
        return view('student.profile');
    }
}
