<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Task extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Attribute casts.
     *
     * @var array
     */
    protected $casts = [
        'is_completed' => 'boolean',
    ];
     // Relation avec l'utilisateur (pour JWT)
         public function user()
         {
             return $this->belongsTo(User::class);
         }
}
