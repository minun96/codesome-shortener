<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

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

    public static function createLink(array $data): self
    {
        if (isset($data['short_code'])) {
            $shortCode = $data['short_code'];
        } else {
            do {
                $shortCode = Str::random(7);
            } while (self::where('short_code', $shortCode)->exists());
        }

        return self::create([
            'long_url'   => $data['long_url'],
            'short_code' => $shortCode,
        ]);
    }

    public function registerClick(string $ip, array $geoloc): void
    {
        $this->clicks()->create([
            'ip_address' => $ip,
            'country' => $geoloc['country'] ?? null,
            'city' => $geoloc['city'] ?? null,
        ]);
    }

    public static function topLinks(int $limit = 5) 
    {
        return self::withCount('clicks')
            ->orderBy('clicks_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function clicksCount() 
    {
        return $this->clicks()->count();
    }

    public function latestClick()
    {
        return $this->clicks()->latest()->first();
    }

    public function clicksSince(Carbon $date)
    {
        return $this->clicks()
            ->where('created_at', '>=', $date)->count();
    }

}
