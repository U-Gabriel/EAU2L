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
        $now = now(); // Heure actuelle du serveur

        // 1. Tous les rendez-vous pour la liste
        $meetings = \App\Models\InformationCustomer::orderBy('meeting_date', 'asc')
            ->orderBy('meeting_hour', 'asc')
            ->paginate(15);

        // 2. Le VRAI prochain rendez-vous (Date future OU (Aujourd'hui ET Heure future))
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

        $calendarDays = \App\Models\Calender::where('date_off', '>=', now()->toDateString()) // Filtre : Date >= aujourd'hui
        ->orderBy('date_off', 'asc')
        ->get();

        return view('admin.planning', compact('meetings', 'nextMeeting', 'calendarDays'));
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