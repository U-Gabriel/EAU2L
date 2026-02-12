<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationCustomer extends Model
{
    // On précise le nom exact de la table
    protected $table = 'information_customer';

    // On précise la clé primaire car ce n'est pas "id"
    protected $primaryKey = 'id_information_customer';

    // Laravel gère created_at et updated_at par défaut, c'est bon.
    protected $guarded = [];

    // Si tu utilises le format JSON pour "answers"
    protected $casts = [
        'answers' => 'array',
    ];
}