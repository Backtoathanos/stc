<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteRate extends Model
{
    protected $table = 'site_rates';
    
    protected $fillable = [
        'site_id',
        'category',
        'basic',
        'da'
    ];
    
    protected $casts = [
        'basic' => 'decimal:2',
        'da' => 'decimal:2',
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

