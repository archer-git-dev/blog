<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $img_src
 * @property integer $user_id
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'img_src',
        'user_id',
    ];

    // Пост принадлежит пользователю
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Пост имеет много комментариев
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    // Пост принадлежит многим тегам (многие-ко-многим)
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
