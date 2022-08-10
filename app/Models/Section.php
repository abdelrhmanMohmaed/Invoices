<?php

namespace App\Models;

use App\Observers\SectionObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Section::observe(SectionObserver::class);
    }
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
