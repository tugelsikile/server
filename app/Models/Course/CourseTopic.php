<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property string $course
 * @property string $name
 * @property string $code
 * @property string $created_by
 * @property string $updated_by
 */
class CourseTopic extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
