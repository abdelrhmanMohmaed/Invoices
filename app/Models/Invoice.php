<?php

namespace App\Models;

use App\Observers\InvoicesObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Invoice::observe(InvoicesObserver::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function invoice_detail()
    {
        return $this->hasOne(InvoicesDetail::class);
    }

    public function invoice_attachments()
    {
        return $this->hasMany(InvoicesAttachment::class);
    }







    // public function getStatus()
    // {
    //     if ($this->value_status == 2)
    //         return "غير مدفوعه";
    //     else if ($this->value_status == 1)
    //         return "مدفوعه";
    // }
}
