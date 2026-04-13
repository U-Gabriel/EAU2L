<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // On récupère les articles avec leurs images associées
        // On trie par date_creation croissant comme demandé
        $posts = Blog::with('pictures')
                    ->orderBy('date_creation', 'desc')
                    ->orderBy('id_blog', 'desc')
                    ->paginate(20);

        // On récupère le bloc par son type précis
        $logoBlock = \DB::table('page_blocks')
                    ->where('type', 'logo_armature_effect')
                    ->first();

        return view('blog', compact('posts', 'logoBlock'));
    }

    public function show($id)
    {
        // On récupère l'article avec ses images par son ID
        $post = \App\Models\Blog::with('pictures')->findOrFail($id);

        return view('blog-detail', compact('post'));
    }
}