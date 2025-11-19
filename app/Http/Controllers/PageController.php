<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function policies()
    {
        return view('pages.policies');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}