<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validation des champs
        $credentials = $request->validate([
            'pseudo'   => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Tentative de connexion
        if (Auth::attempt(['pseudo' => $credentials['pseudo'], 'password' => $credentials['password']])) {
            
            $user = Auth::user();

            // 3. Vérification du rôle d'admin (id_role == 2)
            if ($user->id_role == 2) {
                $request->session()->regenerate();
                
                // MODIFICATION : On utilise le nom de la route pour inclure le préfixe secret
                return redirect()->intended(route('admin.dashboard'));
            }

            // Si c'est un utilisateur mais pas admin, on le déconnecte direct
            Auth::logout();
            return redirect()->back()->withErrors([
                'login' => 'Accès refusé : vous n’avez pas les droits administrateur.',
            ]);
        }

        // 4. Échec de connexion (pseudo ou mot de passe faux)
        return redirect()->back()->withErrors([
            'login' => 'Identifiants incorrects.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // MODIFICATION : Rediriger vers la page de connexion secrète
        return redirect()->route('admin.login');
    }
}