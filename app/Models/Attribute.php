<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 *
 * @property Post[] $posts
 * @property Attribute $parent
 */
class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'parent_id'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Attribute::class, 'parent_id', 'id');
    }

}
