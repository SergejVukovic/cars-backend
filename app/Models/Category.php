<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $parent_id
 * @property string $slug
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'parent_id',
        'slug'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
