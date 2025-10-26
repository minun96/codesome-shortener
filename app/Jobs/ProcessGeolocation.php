<?php

namespace App\Jobs;

use App\Contracts\GeolocationProvider;
use App\Models\Click;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessGeolocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Click $click)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GeolocationProvider $geolocationProvider): void
    {
        $geoloc = $geolocationProvider->getGeolocation($this->click->ip_address); // ip lo ho già acquisito
        $this->click->update([
            'country' => $geoloc['country'],
            'city' => $geoloc['city'],
        ]);
    }
}
