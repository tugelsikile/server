<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property int $number
 * @property string $course_topic
 * @property string $content
 * @property string $type
 * @property double $max_score
 * @property double $min_score
 * @property string $created_by
 * @property string $updated_by
 */
class Question extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'min_score' => 'double',
        'max_score' => 'double'
    ];
}
