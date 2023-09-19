<?php

namespace App\Models\Major;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, mixed $value)
 * @method static orderBy(string $column, string $direction)
 * @property string $id
 * @property string $name
 * @property string $code
 */
class Major extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
