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

        $methodTitle = $page->blocks->where('type', 'method_title')->first();

        $methods = $page->blocks->where('type', 'method')->sortBy('position');

        // On récupère la collection des objectifs (plusieurs possibles)
        $goals = $page->blocks->where('type', 'goals');
        
        // On récupère le bloc de fin d'engagement
        $meetGoals = $page->blocks->where('type', 'meet_goals')->first();

        $testimonials = $page->blocks->where('type', 'proove')->sortBy('position');

        // Ajout de la FAQ
        $faqs = $page->blocks->where('type', 'faq')->sortBy('position');

        // --- BLOCS TARIFS ---
        $tarifTitle = $page->blocks->where('type', 'tarif_title')->first();
        $tarifTitleCard01 = $page->blocks->where('type', 'tarif_title_card_01')->first();
        $tarifCards01 = $page->blocks->where('type', 'tarif_card_01')->sortBy('position');
        $tarifCard02 = $page->blocks->where('type', 'tarif_card_02')->first();

        $usCompany = $page->blocks->where('type', 'us_company')->first();
        $detailsCompany = $page->blocks->where('type', 'details_company')->first();
        $cardCompanies = $page->blocks->where('type', 'card_company')->sortBy('position');

        return view('home', compact('page', 'beforeHome', 'situations', 'videoBlock', 'methodTitle', 'methods', 'goals', 'meetGoals', 'testimonials', 'faqs', 'tarifTitle', 'tarifTitleCard01', 'tarifCards01', 'tarifCard02', 'usCompany', 'detailsCompany', 'cardCompanies'));
    }

}
