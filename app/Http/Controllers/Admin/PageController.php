<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;      // AJOUTÉ : Pour DB::table
use Illuminate\Support\Facades\Auth;    // AJOUTÉ : Pour Auth::id()
use Illuminate\Support\Str;             // AJOUTÉ : Pour Str::slug()
use Illuminate\Support\Facades\Mail;

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

        // Si la checkbox est cochée, is_hidden = 1, sinon 0
        $block->is_hidden = $request->has('is_hidden') ? 1 : 0;

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

   
    public function updateLogo(Request $request, $id)
    {
        // 1. Validation : On accepte les formats standards
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $block = PageBlock::findOrFail($id);

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');

            // 2. Définition du dossier de destination
            $folder = 'images';
            $destination = public_path($folder);

            // Créer le dossier s'il n'existe pas
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0775, true, true);
            }

            // 3. Création d'un nom de fichier unique basé sur l'ID et le temps
            // On ne touche plus à l'ancien fichier, on en crée juste un nouveau
            $extension = $file->getClientOriginalExtension();
            
            // On nomme clairement selon l'usage (47 = PC, 48 = Mobile)
            $suffix = ($id == 47) ? 'pc_horizontal' : 'mobile_compact';
            $newFileName = 'logo_' . $suffix . '_' . time() . '.' . $extension;

            // 4. Déplacement du fichier vers /public/images/
            $file->move($destination, $newFileName);
            
            // 5. Construction du chemin pour la BDD (ex: /images/logo_pc_1740590000.png)
            $newPathForDb = '/' . $folder . '/' . $newFileName;

            // 6. Mise à jour de la base de données
            // L'ancien chemin est écrasé en BDD, mais le fichier reste sur le FTP
            $block->image_path = $newPathForDb;
            $block->save();

            return back()->with('success', 'Nouveau logo enregistré avec succès (Historique conservé).');
        }

        return back()->withErrors(['msg' => 'Aucun fichier détecté.']);
    }

    public function updateColor(Request $request, $id)
    {
        // 1. Validation : on vérifie que c'est bien un format hexadécimal (ex: #FFFFFF ou #FFFFFF1A)
        $request->validate([
            'content' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/'],
        ]);

        // 2. Mise à jour en base de données
        $updated = DB::table('page_blocks')
            ->where('id_block', $id)
            ->update([
                'content' => $request->input('content'),
                'updated_at' => now()
            ]);

        if ($updated) {
            return back()->with('success', 'La couleur a été mise à jour avec succès !');
        }

        return back()->with('error', 'Aucun changement détecté ou bloc introuvable.');
    }

   public function blogIndex()
    {
        // On récupère les articles du plus récent au plus ancien
        // On fait une jointure pour avoir l'image et le nom de l'auteur
        $articles = DB::table('blog')
            ->leftJoin('picture_blog', 'blog.id_blog', '=', 'picture_blog.id_blog')
            ->leftJoin('person', 'blog.id_person', '=', 'person.id_person')
            ->select('blog.*', 'picture_blog.path_location', 'person.pseudo as author_name')
            ->orderBy('blog.date_creation', 'desc')
            ->orderBy('blog.id_blog', 'desc')
            ->get();

        return view('admin.blog_index', compact('articles'));
    }

    public function blogCreate()
    {
        return view('admin.blog_create');
    }

   public function blogStore(Request $request)
    {

        $messages = [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 250 caractères.',
            'description.required' => 'Le contenu de l\'article ne peut pas être vide.',
            'main_picture.image' => 'Le fichier doit être une image.',
            'mai
            n_picture.max' => 'L\'image est trop lourde (max 5Mo).',
        ];
        // 1. Validation des données
        $request->validate([
            'title' => 'required|max:250',
            'description' => 'required',
            'main_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000'
        ], $messages);

        try {
            // 2. Récupération de l'auteur (Table person)
            $user = Auth::user();

            // Utilise 'name' ou 'pseudo' selon ce qui est stocké dans ta table users
            // On va tenter de trouver par pseudo, sinon par name, sinon le premier venu
            $person = DB::table('person')
                ->where('pseudo', $user->pseudo)
                ->orWhere('pseudo', $user->name) 
                ->first();

            if (!$person) {
                $person = DB::table('person')->first(); 
            }

            if (!$person) {
                throw new \Exception("Impossible d'insérer l'article : aucun utilisateur trouvé dans la table 'person'.");
            }

            // 3. Insertion de l'article
            $blogId = DB::table('blog')->insertGetId([
                'title'             => $request->title,
                'description'       => $request->description,
                'date_creation'     => now(),
                'date_modification' => now(),
                'id_person'         => $person->id_person, // On utilise l'ID de la table person
            ], 'id_blog');

            // 4. Gestion de l'image de couverture
            if ($request->hasFile('main_picture') && $blogId) {
                try {
                    $file = $request->file('main_picture');
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = uniqid() . '_' . Str::slug($request->title) . '.' . $extension;
                    
                    // On définit le dossier relatif et le chemin absolu
                    $relativeFolder = 'images/blog';
                    $destinationPath = public_path($relativeFolder);
                    
                    // On s'assure que le dossier existe
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true, true);
                    }

                    // On déplace le fichier
                    $file->move($destinationPath, $uniqueName);
                                        
                    // Chemin exact à enregistrer en BDD
                    $fullPathForDb = $relativeFolder . '/' . $uniqueName;

                    // INSERTION BDD
                    DB::table('picture_blog')->insert([
                        'title'         => $request->title,
                        'path_location' => $fullPathForDb,
                        'id_blog'       => $blogId
                    ]);

                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
            }
            return back()->with('success', true);

        } catch (\Exception $e) {
            // En cas d'erreur (BDD, droits, etc.), on affiche le message précis
            return back()->withInput()->withErrors(['error' => 'Erreur BDD : ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        // 1. Validation
        $request->validate([
            'title' => 'required|max:250',
            'description' => 'required',
            'main_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000'
        ]);

        // 2. Mise à jour de l'article (Table blog)
        DB::table('blog')->where('id_blog', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'date_modification' => now(),
        ]);

        // 3. Gestion de l'image si un nouveau fichier est envoyé
        if ($request->hasFile('main_picture')) {
            $file = $request->file('main_picture');
            $extension = $file->getClientOriginalExtension();
            $uniqueName = uniqid() . '_' . Str::slug($request->title) . '.' . $extension;
            $relativeFolder = 'images/blog';
            $destinationPath = public_path($relativeFolder);

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Supprimer l'ancienne image physiquement si elle existe
            $oldPicture = DB::table('picture_blog')->where('id_blog', $id)->first();
            if ($oldPicture && File::exists(public_path($oldPicture->path_location))) {
                File::delete(public_path($oldPicture->path_location));
            }

            $file->move($destinationPath, $uniqueName);
            $fullPathForDb = $relativeFolder . '/' . $uniqueName;

            // Mettre à jour ou Insérer dans picture_blog
            DB::table('picture_blog')->updateOrInsert(
                ['id_blog' => $id],
                [
                    'title' => $request->title,
                    'path_location' => $fullPathForDb
                ]
            );
        }

        return redirect()->back()->with('success_edit', 'L\'article a été mis à jour avec succès.');
    }

    public function blogDestroy($id)
    {
        try {
            // 1. Trouver l'image pour la supprimer du dossier public
            $picture = DB::table('picture_blog')->where('id_blog', $id)->first();
            if ($picture && $picture->path_location) {
                $fullPath = public_path($picture->path_location);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
                DB::table('picture_blog')->where('id_blog', $id)->delete();
            }

            // 2. Supprimer l'article
            DB::table('blog')->where('id_blog', $id)->delete();

            return back()->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    public function sendNewsletter($id)
    {
        // 1. Récupérer l'article et son image
        $article = DB::table('blog')->where('id_blog', $id)->first();
        
        if (!$article) {
            return back()->withErrors(['msg' => "L'article n'existe pas."]);
        }

        $picture = DB::table('picture_blog')->where('id_blog', $id)->first();
        
        // 2. Filtrer UNIQUEMENT les personnes avec le role 1 (Customers/Newsletter)
        $subscribers = DB::table('person')
            ->where('id_role', 1)
            ->whereNotNull('mail')
            ->get();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['msg' => "Aucun abonné (role 1) trouvé dans la base de données."]);
        }

        // 3. Envoi individuel
        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.newsletter', [
                    'title' => $article->title,
                    'description' => $article->description, // Contient le HTML de Quill
                    'image' => $picture ? $picture->path_location : null
                ], function($message) use ($subscriber) {
                    $message->to($subscriber->mail)
                            ->subject('Newsletter - Armature Business');
                });
            } catch (\Exception $e) {
                // On continue la boucle même si un mail échoue (ex: adresse invalide)
                continue;
            }
        }

        return back()->with('newsletter_sent', 'Newsletter envoyée avec succès à ' . $subscribers->count() . ' clients.');
    }
}