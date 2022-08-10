<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
