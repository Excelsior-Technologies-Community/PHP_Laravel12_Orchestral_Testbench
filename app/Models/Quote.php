<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['quote', 'author', 'category', 'is_active', 'views'];
    
    public function incrementViews()
    {
        $this->increment('views');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
