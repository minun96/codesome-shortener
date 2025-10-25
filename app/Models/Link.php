<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = ['short_url'];

    // questo accessor mi aiuta a ottenere ogni volta l'url completo
    public function getShortUrlAttribute(): string 
    {
        return url($this->short_code);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }
}
