<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'contact')->first();

        return view('contact', compact('page'));
    }

    
}