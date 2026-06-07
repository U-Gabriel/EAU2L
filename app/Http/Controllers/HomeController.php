<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')
                    ->with(['blocks' => function($query) {
                        $query->where('is_active', true)
                            ->where('is_hidden', false)
                            ->orderBy('position', 'asc');
                    }])
                    ->firstOrFail();

        $beforeHome = $page->blocks->where('type', 'before_home')->first();

        $situations = $page->blocks->where('type', 'situations')->sortBy('position');

        $videoBlock = $page->blocks->where('type', 'video_presentation')->first();

        // On récupère la collection des objectifs (plusieurs possibles)
        $goals = $page->blocks->where('type', 'goals');
        
        // On récupère le bloc de fin d'engagement
        $meetGoals = $page->blocks->where('type', 'meet_goals')->first();

        $testimonials = $page->blocks->where('type', 'proove')->sortBy('position');

        // Ajout de la FAQ
        $faqs = $page->blocks->where('type', 'faq')->sortBy('position');

        return view('home', compact('page', 'beforeHome', 'situations', 'videoBlock', 'goals', 'meetGoals', 'testimonials', 'faqs'));
    }

}
