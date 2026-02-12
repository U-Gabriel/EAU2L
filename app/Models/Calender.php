<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    protected $table = 'calender'; // Nom exact dans ta BDD
    protected $primaryKey = 'id_calender';
    public $incrementing = true;
    protected $fillable = [
        'date_off'
    ];
    public $timestamps = false; // Car tu n'as pas de created_at/updated_at
}