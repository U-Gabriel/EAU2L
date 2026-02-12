<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use App\Models\Calender;
use App\Models\InformationCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistiques Globales
        $totalViews = Movement::count();
        $uniqueVisitors = Movement::distinct('visitor_ip')->count();

        // 2. Derniers mouvements (pour le flux en direct)
        $movements = Movement::latest()->take(5)->get();

        // 3. Page la plus visitée
        $topPage = Movement::select('concerning_page', DB::raw('count(*) as views'))
            ->groupBy('concerning_page')
            ->orderByDesc('views')
            ->first();

        // 4. Jours d'indisponibilité (pour éviter l'erreur Undefined variable)
        $calendarDays = Calender::where('date_off', '>=', now()->toDateString())
            ->orderBy('date_off', 'asc')
            ->get();

        // Meeting
        $now = now();
    
    // On récupère le VRAI prochain rendez-vous
    $nextMeeting = \App\Models\InformationCustomer::where(function($query) use ($now) {
            $query->where('meeting_date', '>', $now->toDateString())
                ->orWhere(function($q) use ($now) {
                    $q->where('meeting_date', $now->toDateString())
                        ->where('meeting_hour', '>', $now->toTimeString());
                });
        })
        ->where('status', '!=', 'cancelled')
        ->orderBy('meeting_date', 'asc')
        ->orderBy('meeting_hour', 'asc')
        ->first();

        $totalVisits = Movement::distinct('visitor_ip')->count();
        $totalContacts = InformationCustomer::count();
        $conversionRate = $totalVisits > 0 ? number_format(($totalContacts / $totalVisits) * 100, 1) : 0;

        // 5. On renvoie la vue avec TOUTES les données
        return view('admin.dashboard', compact(
            'totalViews', 
            'uniqueVisitors', 
            'movements', 
            'topPage', 
            'calendarDays',
            'nextMeeting',
            'conversionRate'
        ));
    
    }

    
}