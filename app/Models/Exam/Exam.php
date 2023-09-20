<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $created_by
 * @property string $updated_by
 * @property bool $random_answer
 * @property bool $random_question
 * @property bool $show_result
 */
class Exam extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'random_question' => 'boolean',
        'random_answer' => 'boolean',
        'show_result' => 'boolean',
    ];
}
