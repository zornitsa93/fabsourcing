<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MethodStep extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'description', 'number', 'sort_order'];

    public array $translatable = ['title', 'description'];
}
