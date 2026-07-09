<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'image', 'is_available', 'price', 'has_discount', 'category', 'type'])]
class product extends Model
{
    use HasFactory;
}
