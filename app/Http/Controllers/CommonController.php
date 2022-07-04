<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function index()
    {
        return view('app.preview');
    }

    public function faq()
    {
        return view('app.faq');
    }
}
