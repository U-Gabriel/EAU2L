<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'meta_title', 'meta_description', 'is_active',
    ];

    protected $table = 'pages';

    public function blocks()
    {
        return $this->hasMany(PageBlock::class, 'id_page', 'id_page');
    }
}
