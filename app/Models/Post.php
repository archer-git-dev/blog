<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

/**
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $img_src
 * @property int $user_id
 */
class Post extends Model
{
    use HasFactory, Searchable;

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

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
