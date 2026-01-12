<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    // Тег принадлежит многим постам (многие-ко-многим)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
