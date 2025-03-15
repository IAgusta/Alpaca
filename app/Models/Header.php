<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'text', 'description'];

    // Relationship with ModuleContent
    public function content()
    {
        return $this->belongsTo(ModuleContent::class, 'content_id', 'id');
    }
}
