<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $post_id
 * @property int $attribute_id
 * @property string $value
 */

class AttributePost extends Model
{
    use HasFactory;

    protected $hidden = [
        'post_id',
        'attribute_id'
    ];
}
