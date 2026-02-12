<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBlock extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_block';

    protected $fillable = [
        'id_page', 'type', 'content', 'image_path', 'position', 'is_active',
    ];

    // Relation inverse vers Page
    public function page()
    {
        return $this->belongsTo(Page::class, 'id_page');
    }
}
