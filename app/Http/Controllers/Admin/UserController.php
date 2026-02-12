<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // On récupère tous les utilisateurs depuis la base de données
        $users = User::all();

        // On retourne la vue située dans resources/views/admin/users.blade.php
        // compact('users') permet d'envoyer la variable à la vue
        return view('admin.users', compact('users'));
    }

   public function store(Request $request)
    {
        // Définition des règles
        $rules = [
            'pseudo'   => 'required|string|min:3|max:255|unique:person,pseudo', 
            'mail'     => 'nullable|email|unique:person,mail',
            'password' => 'required|string|min:8',
            'id_role'  => 'required|integer'
        ];

        // Personnalisation des messages (le "Friendly" pro)
        $messages = [
            'pseudo.required' => 'Le pseudo est obligatoire.',
            'pseudo.min'      => 'Le pseudo doit faire au moins 3 caractères.',
            'pseudo.unique'   => 'Désolé, ce pseudo est déjà utilisé.',
            'mail.email'      => 'Le format de l’email n’est pas valide.',
            'mail.unique'     => 'Cet email est déjà lié à un compte.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'    => 'Sécurité : le mot de passe doit contenir au moins 8 caractères.',
        ];

        // Lancement de la validation
        $validated = $request->validate($rules, $messages);

        try {
            \App\Models\User::create([
                'pseudo'   => $validated['pseudo'],
                'mail'     => $validated['mail'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'id_role'  => $validated['id_role'],
            ]);

            return redirect()->back()->with('success', "Le compte de {$validated['pseudo']} est prêt !");

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['db' => "Erreur de base de données : " . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        // On vérifie que c'est bien l'utilisateur connecté qui modifie son propre compte
        if (auth()->id() != $id) {
            return redirect()->back()->withErrors(['auth' => "Vous ne pouvez modifier que votre propre compte."]);
        }

        $rules = [
            'pseudo' => 'required|string|min:3|max:255|unique:person,pseudo,' . $id . ',id_person',
            'mail'   => 'nullable|email|unique:person,mail,' . $id . ',id_person',
            'password' => 'nullable|string|min:8', // Optionnel pour la modification
        ];

        $validated = $request->validate($rules);

        try {
            $user = User::findOrFail($id);
            $user->pseudo = $validated['pseudo'];
            $user->mail = $validated['mail'];
            
            // On ne change le mot de passe que s'il est rempli
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->back()->with('success', "Votre profil a été mis à jour !");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['db' => "Erreur : " . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = \App\Models\User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', "L'utilisateur a été révoqué avec succès.");
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => "Impossible de supprimer cet utilisateur."]);
        }
    }
}