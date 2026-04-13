<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calender;
use App\Models\InformationCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Mail\ConfirmationRendezVous;
use Illuminate\Support\Facades\Mail;

use App\Models\PageBlock;

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
            // 1. On récupère seulement le TEXTE saisi par l'admin
            $emailBlock = \DB::table('page_blocks')->where('type', 'email_audit_confirmation')->first();
            $messageUser = $emailBlock ? $emailBlock->content : "Bonjour {prenom}, votre rendez-vous est confirmé.";

            // 2. On prépare les variables
            $vars = [
                '{prenom}'     => $info->prenom,
                '{nom}'        => $info->nom,
                '{societe}'    => $info->company_name,
                '{date}'       => \Carbon\Carbon::parse($info->meeting_date)->format('d/m/Y'),
                '{heure}'      => $info->meeting_hour,
                '{link_teams}' => 'https://teams.live.com/meet/93469201237491?p=WBX9LmrsbYkHqLKMT6'
            ];

            // 3. On remplace les balises dans le texte de l'utilisateur
            $textFinal = str_replace(array_keys($vars), array_values($vars), $messageUser);

            // 4. On injecte ce texte dans le "Moule" HTML pro
            $finalHtml = '
            <div style="font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; padding: 40px 10px; color: #334155;">
                <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    
                    <div style="background-color: #ffffff; padding: 30px; text-align: center; border-bottom: 4px solid #1e3a8a;">
                        <img src="http://37.187.183.97//images/logo_fix_tr.png" alt="Armature Business" style="max-height: 60px; width: auto; margin-bottom: 10px;">
                        <div style="color: #1e3a8a; font-size: 14px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Armature Business</div>
                    </div>

                    <div style="padding: 40px 30px; line-height: 1.8;">
                        <div style="font-size: 16px;">
                            ' . $textFinal . '
                        </div>
                        
                        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px dashed #e2e8f0;">
                            <p style="font-size: 14px; color: #64748b; margin-bottom: 20px;">Utilisez le lien ci-dessous pour vous connecter le jour J :</p>
                            <a href="'.$vars['{link_teams}'].'" style="background: #1e3a8a; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 10px; font-weight: 600; display: inline-block; transition: background 0.3s ease;">
                                Rejoindre la réunion Teams
                            </a>
                        </div>
                    </div>

                    <div style="background-color: #f1f5f9; padding: 25px; text-align: center; font-size: 12px; color: #94a3b8;">
                        <p style="margin: 0 0 8px 0;"><strong>Armature Business</strong> • Expertise & Audit Digital</p>
                        <p style="margin: 0;">© '.date('Y').' Armature Business. Tous droits réservés.</p>
                        <div style="margin-top: 15px; font-size: 10px; color: #cbd5e1;">
                            Cet email a été envoyé suite à votre demande de rendez-vous sur notre plateforme.
                        </div>
                    </div>
                </div>
            </div>';

            \Mail::html($finalHtml, function ($message) use ($info) {
                $message->to($info->email)->subject('Confirmation de votre Audit');
            });

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

    public function updateBlock(Request $request, $id)
    {
        $block = PageBlock::findOrFail($id);

        // --- GESTION DES TEXTES ET DU CONTENU ---
        if ($request->has('content')) {
            // Cas du Mail de confirmation (HTML de Quill)
            $block->content = $request->input('content');
        } 
        elseif ($request->has('content_json')) {
            // Cas des blocs classiques (Titre, descriptions, etc.)
            $block->content = json_encode($request->input('content_json'));
        }

        // --- GESTION DES MÉDIAS (IMAGE) ---
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // On stocke dans public/images
            $file->move(public_path('images'), $fileName);
            $block->image_path = 'images/' . $fileName;
        }

        // --- GESTION DES MÉDIAS (VIDÉO) ---
        if ($request->hasFile('video_path')) {
            $file = $request->file('video_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('videos'), $fileName);
            $block->video_path = 'videos/' . $fileName;
        }

        // --- VISIBILITÉ ---
        $block->is_hidden = $request->has('is_hidden') ? 1 : 0;

        $block->save();

        return redirect()->back()->with('success', 'Le bloc #' . $id . ' a été mis à jour avec succès.');
    }

    
}