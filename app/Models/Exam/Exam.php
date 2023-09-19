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
 */
class Exam extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
