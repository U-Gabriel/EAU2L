<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog'; // Nom de ta table
    protected $primaryKey = 'id_blog'; // Ta clé primaire spécifique
    
    // Désactiver les timestamps automatiques (Laravel cherche created_at par défaut)
    // On utilisera tes colonnes date_creation à la place
    public $timestamps = false;

    // Relation avec les images
    public function pictures()
    {
        return $this->hasMany(PictureBlog::class, 'id_blog', 'id_blog');
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->description));
        $minutes = ceil($words / 200);
        return ($minutes < 2) ? 2 : $minutes;
    }
}