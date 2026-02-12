<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $pageSearch = $request->input('page');

        // 1. Requête principale pour le tableau
        $query = Movement::orderByRaw('created_at DESC');
        
        if ($date) { $query->whereDate('created_at', $date); }
        if ($pageSearch) { $query->where('concerning_page', 'like', '%' . $pageSearch . '%'); }
        
        $movements = $query->paginate(20);

        // 2. Calcul des KPIs (Séparés pour éviter l'erreur de colonne)
        $totalViews = Movement::query()
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->when($pageSearch, fn($q) => $q->where('concerning_page', 'like', '%' . $pageSearch . '%'))
            ->count();

        $uniqueVisitors = Movement::query()
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->when($pageSearch, fn($q) => $q->where('concerning_page', 'like', '%' . $pageSearch . '%'))
            ->distinct('visitor_ip')
            ->count('visitor_ip');

        // 3. Top pages (On simplifie le groupement)
        $pagesStats = Movement::select('concerning_page', DB::raw('count(*) as views'))
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->when($pageSearch, fn($q) => $q->where('concerning_page', 'like', '%' . $pageSearch . '%'))
            ->groupBy('concerning_page')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $topPage = $pagesStats->first() ?: (object)['concerning_page' => 'Aucune donnée', 'views' => 0];

        return view('admin.stats', compact('movements', 'totalViews', 'uniqueVisitors', 'pagesStats', 'topPage'));
    }
}