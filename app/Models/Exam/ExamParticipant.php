<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property string $exam
 * @property string $user
 */
class ExamParticipant extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
