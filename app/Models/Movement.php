<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $table = 'movement_available';
    public $timestamps = false; // Car tu as déjà created_at en SQL par défaut

    protected $fillable = [
        'session_id', 'visitor_ip', 'concerning_page', 
        'referrer_url', 'user_agent', 'device_type', 'user_id'
    ];
}