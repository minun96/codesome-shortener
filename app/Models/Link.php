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
            return self::create([
                'long_url'   => $data['long_url'],
                'short_code' => $data['short_code'],
            ]);
        } 

        $link = self::create([
            'long_url'   => $data['long_url'],
            'short_code' => uniqid(), // solo per placeholder
        ]);
        // lo creo con l'id e aggiungo la parte che manca
        // non devo fare query a db ma garantisco unicità
        $uniquePart = gmp_strval(gmp_init($link->id, 10), 62); //parto dall'id in base 10 e lo converto in base 62
        $offset = 8 - strlen($uniquePart);
        $randomPart = ($offset > 0) ? Str::random($offset) : '';
        $link->short_code = $randomPart . $uniquePart;
        $link->save(); 
        return $link;
    }

    public function registerClick(string $ip): Click
    {
        return $this->clicks()->create([
            'ip_address' => $ip,
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
