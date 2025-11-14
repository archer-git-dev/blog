<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $user_id
 * @property integer $post_id
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'post_id',
        'user_id',
    ];

    // Комментарий принадлежит посту
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Комментарий принадлежит пользователю
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
