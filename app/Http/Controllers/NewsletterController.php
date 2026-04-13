<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'mail' => 'required|email|unique:person,mail'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette adresse email est déjà enregistrée ou invalide.'
            ], 422);
        }

        $pseudoPart = explode('@', $request->mail)[0];
        
        \DB::table('person')->insert([
            'mail' => $request->mail,
            'pseudo' => $pseudoPart . '_' . \Str::random(4),
            'id_role' => 1,
            'last_connexion' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}