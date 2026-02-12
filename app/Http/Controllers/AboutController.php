<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'about')->first();

        return view('about', compact('page'));
    }
}