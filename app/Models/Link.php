<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Link extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['long_url', 'short_code'];
    protected $appends = ['short_url'];

    // questo accessor mi aiuta a ottenere ogni volta l'url completo dal db
    protected function shortUrl (): Attribute
    {
        return Attribute::make(
            get: fn () => url($this->short_code)
        );
    } 

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }
}
