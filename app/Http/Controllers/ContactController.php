<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'contact')->first();

        return view('contact', compact('page'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $otp = rand(100000, 999999);
        
        Session::put('otp_code', $otp);
        Session::put('otp_email', $request->email);
        Session::put('otp_expires_at', now()->addMinutes(15));

        // Construction du template HTML Pro
        // Remplace bien 'ton-domaine.com' par ton vrai lien
        $logoUrl = "http://37.187.183.97//images/logo_fix_tr.png";
        
        $htmlContent = "
        <div style='font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px 10px;'>
            <div style='max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
                <div style='background-color: #ffffff; padding: 25px; text-align: center; border-bottom: 3px solid #1e3a8a;'>
                    <img src='$logoUrl' alt='Armature Business' style='max-height: 50px;'>
                    <div style='color: #1e3a8a; font-size: 12px; font-weight: bold; letter-spacing: 2px; margin-top: 10px;'>ARMATURE BUSINESS</div>
                </div>
                <div style='padding: 40px 30px; text-align: center;'>
                    <h2 style='color: #1e293b; margin-top: 0;'>Vérification de sécurité</h2>
                    <p style='color: #64748b; font-size: 16px;'>Utilisez le code ci-dessous pour valider votre demande de rendez-vous :</p>
                    <div style='background: #f1f5f9; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px dashed #1e3a8a;'>
                        <span style='font-size: 32px; font-weight: bold; letter-spacing: 10px; color: #1e3a8a;'>$otp</span>
                    </div>
                    <p style='color: #94a3b8; font-size: 13px;'>Ce code expirera dans 15 minutes.</p>
                </div>
                <div style='background-color: #f1f5f9; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px;'>
                    © 2026 Armature Business. Tous droits réservés.
                </div>
            </div>
        </div>";

        try {
            // Utilisation de Mail::html pour forcer le rendu HTML
            Mail::html($htmlContent, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Votre code de confirmation - Armature Business');
            });
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Erreur OTP : " . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Erreur d'envoi"]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $storedOtp = Session::get('otp_code');
        $expiresAt = Session::get('otp_expires_at');

        if ($storedOtp && $request->code == $storedOtp && now()->lessThan($expiresAt)) {
            Session::put('email_verified', true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Code incorrect']);
    }

    
}