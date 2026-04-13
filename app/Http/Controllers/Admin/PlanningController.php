<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use App\Models\Calender;
use App\Models\InformationCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningController extends Controller
{
    public function index()
    {
    $now = now();
    $todayStr = $now->toDateString();
    $timeStr = $now->toTimeString();

    // 1. AUJOURD'HUI (Futur proche)
    $todayFuture = InformationCustomer::where('meeting_date', $todayStr)
        ->where('meeting_hour', '>', $timeStr)
        ->orderBy('meeting_hour', 'asc')
        ->get();

    // 2. PROCHAINEMENT (Strictement après aujourd'hui)
    $otherFuture = InformationCustomer::where('meeting_date', '>', $todayStr)
        ->orderBy('meeting_date', 'asc')
        ->orderBy('meeting_hour', 'asc')
        ->get();

    // 3. LE PROCHAIN (Pour la carte bleue en haut)
    $nextMeeting = $todayFuture->first() ?? $otherFuture->first();

    // 4. TOUT L'HISTORIQUE (Pour le modal Archives - avec pagination)
    $meetings = InformationCustomer::orderBy('meeting_date', 'desc')
        ->orderBy('meeting_hour', 'desc')
        ->paginate(20);

    $calendarDays = Calender::where('date_off', '>=', $todayStr)
        ->orderBy('date_off', 'asc')
        ->get();

    return view('admin.planning', compact('meetings', 'nextMeeting', 'calendarDays', 'todayFuture', 'otherFuture'));
    }

    public function storeCalendar(Request $request)
    {
        $request->validate([
            'date_off' => 'required|date'
        ]);

        // Vérification de doublon
        $exists = \App\Models\Calender::where('date_off', $request->date_off)->exists();
        
        if ($exists) {
            return back()->with('error', 'Cette date est déjà marquée comme non travaillée.');
        }

        \App\Models\Calender::create([
            'date_off' => $request->date_off
        ]);

        return back()->with('success', 'Votre jour de repos a bien été pris en compte !');
    }

    // AJOUTE CETTE MÉTHODE POUR SUPPRIMER UN JOUR OFF
    public function destroyCalendar($id)
    {
        $day = Calender::findOrFail($id);
        $day->delete();

        return back()->with('success', 'Le jour est de nouveau disponible.');
    }
}