<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $attributes = [
        'options' => ''
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
