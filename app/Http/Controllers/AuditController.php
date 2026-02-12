<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calender;
use App\Models\InformationCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Mail\ConfirmationRendezVous;
use Illuminate\Support\Facades\Mail;

class AuditController extends Controller
{
    public function getOffDays()
    {
        try {
            // On récupère les dates et on s'assure qu'elles sont au format string Y-m-d
            $dates = Calender::all()->pluck('date_off')->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });
            return response()->json($dates);
        } catch (\Exception $e) {
            Log::error("Erreur Calendrier OFF: " . $e->getMessage());
            return response()->json([], 500); // On renvoie un tableau vide
        }
    }

    public function getAvailableSlots(Request $request)
    {
        try {
            $date = $request->query('date');
            $targetDate = Carbon::parse($date);
            $now = Carbon::now();

            $slots = [];
            $current = Carbon::createFromTimeString('09:00');
            $end = Carbon::createFromTimeString('18:00');

            while ($current < $end) {
                $timeString = $current->format('H:i');

                // Logique de filtrage du temps réel
                if ($targetDate->isToday()) {
                    // On n'ajoute que si l'heure du créneau est après l'heure actuelle
                    if ($timeString > $now->format('H:i')) {
                        $slots[] = $timeString;
                    }
                } else {
                    $slots[] = $timeString;
                }
                $current->addMinutes(30);
            }

            $booked = InformationCustomer::whereDate('meeting_date', $date)
                        ->pluck('meeting_hour')
                        ->map(fn($t) => Carbon::parse($t)->format('H:i'))
                        ->toArray();

            $available = array_values(array_diff($slots, $booked));

            return response()->json($available);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function hasSlots(Request $request)
    {
        try {
            $date = $request->query('date');
            $targetDate = \Carbon\Carbon::parse($date)->startOfDay();
            $now = \Carbon\Carbon::now(); // Il est 23h25 dans ton test

            // 1. Si la date est avant aujourd'hui -> Grisé
            if ($targetDate->isPast() && !$targetDate->isToday()) {
                return response()->json(['available' => false]);
            }

            // 2. Génération des créneaux de la journée
            $allSlots = [];
            $current = \Carbon\Carbon::createFromTimeString('09:00');
            $end = \Carbon\Carbon::createFromTimeString('18:00');

            while ($current < $end) {
                $timeString = $current->format('H:i');
                
                // FILTRE CRUCIAL : Si c'est aujourd'hui, on ne garde que ce qui est après 23h25
                if ($targetDate->isToday()) {
                    if ($timeString > $now->format('H:i')) {
                        $allSlots[] = $timeString;
                    }
                } else {
                    $allSlots[] = $timeString;
                }
                $current->addMinutes(30);
            }

            // 3. Vérification finale
            // À 23h25, $allSlots sera VIDE. Donc on renvoie false -> Case 10 devient Grise.
            if (empty($allSlots)) {
                return response()->json(['available' => false]);
            }

            // 4. On retire les réservations déjà en base (InformationCustomer)
            $booked = \App\Models\InformationCustomer::whereDate('meeting_date', $date)
                ->pluck('meeting_hour')
                ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
                ->toArray();

            $remaining = array_diff($allSlots, $booked);

            return response()->json(['available' => count($remaining) > 0]);
            
        } catch (\Exception $e) {
            return response()->json(['available' => false], 500);
        }
    }

    public function submit(Request $request)
    {
        try {
            // 1. Validation des données reçues (Optionnel mais recommandé)
            $validated = $request->validate([
                'meeting_date' => 'required|date',
                'meeting_hour' => 'required',
                'nom'          => 'required|string|max:255',
                'prenom'       => 'required|string|max:255',
                'email'        => 'required|email',
                'tel'          => 'required',
                // Ajoute ici les autres validations si nécessaire
            ]);

            // 2. Création de l'enregistrement
            $info = new InformationCustomer();
            
            // Identité & Contact
            $info->nom = $request->nom;
            $info->prenom = $request->prenom;
            $info->email = $request->email;
            $info->tel = $request->tel;
            
            // Rendez-vous
            $info->meeting_date = $request->meeting_date;
            $info->meeting_hour = $request->meeting_hour;
            
            // Entreprise (Étape 3)
            $info->company_name     = $request->company_name;
            $info->company_type     = $request->company_type;
            $info->company_activity = $request->company_activity;
            $info->marge_theorique  = $request->marge_theorique;
            $info->ca               = $request->ca;
            $info->employees_number = $request->employees;
            $info->bilan            = $request->bilan;
            
            // Objectifs (Étape 4)
            $info->user_expectations = $request->user_expectations;
            $info->rdv_objective     = $request->rdv_objective;

            // Champs par défaut de ta table
            $info->form_type = 'audit_premium';
            $info->status = 'new';

            $info->save();

           
            //  ENVOI DU MAIL
            if ($info->email) {
                // On envoie l'objet $info à ton Mailable
                Mail::to($info->email)->send(new ConfirmationRendezVous($info));
            }

            return response()->json([
                'success' => true, 
                'message' => 'Rendez-vous confirmé avec succès ! Un mail vous a été envoyé.'
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur enregistrement Audit: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erreur : ' . $e->getMessage()
                //'Erreur technique lors de l\'enregistrement.'
            ], 500);
        }
    }
}