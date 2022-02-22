<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string $image_path
 * @property string $image_url
 * @property int $index
 * @property boolean $main
 *
 * @property Post $post
 */
class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
      'post_id',
      'image_path',
      'image_url',
      'index',
      'main'
    ];

    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
