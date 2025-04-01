<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'image',
        'about',
        'birth_date',
        'phone',
        'social_media',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
