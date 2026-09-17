<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('public.about');
    }

    public function facilities()
    {
        return view('public.facilities');
    }

    public function mess()
    {
        return view('public.mess');
    }

    public function rules()
    {
        return view('public.rules');
    }

    public function notices()
    {
        return view('public.notices');
    }

    public function events()
    {
        return view('public.events');
    }

    public function gallery()
    {
        return view('public.gallery');
    }

    public function downloads()
    {
        return view('public.downloads');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'category' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        return back()->with('success', 'Thank you for reaching out to HITAM Hostel Administration. We have received your inquiry and will revert within 24 business hours.');
    }
}
