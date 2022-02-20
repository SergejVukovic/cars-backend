<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string $group
 *
 * @property Post[] $posts
 */
class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'value',
      'group'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
