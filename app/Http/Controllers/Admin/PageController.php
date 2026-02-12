<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->firstOrFail();
        $blocks = PageBlock::where('id_page', $page->id_page)
                           ->orderBy('position', 'asc')
                           ->get()
                           ->groupBy('type');
        
        return view('admin.modifications', compact('page', 'blocks'));
    }

    public function updateBlock(Request $request, $id)
    {
        // 1. On augmente le temps d'exécution pour les fichiers lourds (vidéos)
        set_time_limit(600);

        $block = PageBlock::findOrFail($id);

        // 2. Mise à jour des textes (JSON)
        if ($request->has('content_json')) {
            $block->content = json_encode($request->input('content_json'));
        }

        // 3. Mise à jour du lien (colonne link)
        if ($request->has('link')) {
            $block->link = $request->input('link');
        }

        // 4. Gestion des Médias (Nouveau nom + Nettoyage de l'ancien)
        $mediaFields = ['image_path', 'video_path'];
        foreach ($mediaFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                
                // On mémorise l'ancien chemin pour le supprimer plus tard
                $oldRelativePath = $block->$field; 
                $oldFullPath = $oldRelativePath ? public_path($oldRelativePath) : null;

                // On définit le dossier de destination
                // Si on a déjà un dossier on le garde, sinon on utilise des dossiers par défaut
                $folder = $oldRelativePath ? dirname($oldRelativePath) : ($field == 'video_path' ? 'videos' : 'images/goals');
                $destination = public_path($folder);

                // On s'assure que le dossier existe sur le VPS
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }

                // --- CRÉATION DU NOUVEAU FICHIER ---
                // On ajoute un timestamp pour garantir un nom unique et forcer le rafraîchissement
                $newFileName = time() . '_' . $file->getClientOriginalName();
                
                // On déplace le fichier vers sa nouvelle maison
                $file->move($destination, $newFileName);
                
                // On définit les droits pour que le serveur Web puisse lire le fichier
                chmod($destination . '/' . $newFileName, 0644);

                // On met à jour le nouveau chemin dans l'objet avant la sauvegarde
                $block->$field = $folder . '/' . $newFileName;

                // --- SUPPRESSION DE L'ANCIEN FICHIER ---
                // On ne supprime l'ancien que si le nouveau a bien été placé
                if ($oldFullPath && file_exists($oldFullPath) && is_file($oldFullPath)) {
                    // On vérifie que ce n'est pas le même fichier qu'on vient de créer (sécurité)
                    if ($oldFullPath !== $destination . '/' . $newFileName) {
                        @unlink($oldFullPath);
                    }
                }
            }
        }

        // 5. Sauvegarde finale en base de données
        $block->save();

        return back()->with([
            'success' => 'Mise à jour réussie ! Le nouveau fichier a été activé et l\'ancien supprimé.',
            'last_updated' => $id
        ]);
    }
}