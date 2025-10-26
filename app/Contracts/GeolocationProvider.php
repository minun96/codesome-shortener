<?php

namespace App\Contracts;

interface GeolocationProvider
{
    public function getGeolocation(string $ip): array;
}
