<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property string $name
 * @property string $exam
 * @property string $code
 * @property string $token
 * @property object $meta
 * @property string $created_by
 * @property string $updated_by
 */
class ExamClient extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'meta' => 'object',
    ];
}
