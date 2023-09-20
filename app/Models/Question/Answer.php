<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property int $number
 * @property string $question
 * @property string $content
 * @property double $score
 * @property string $created_by
 * @property string $updated_by
 */
class Answer extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'score' => 'double'
    ];
}
