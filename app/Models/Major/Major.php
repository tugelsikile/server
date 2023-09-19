<?php

namespace App\Models\Major;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
}
