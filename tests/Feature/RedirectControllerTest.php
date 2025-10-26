<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RedirectControllerTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function short_link_redirect_and_record_click(): void
    {
        $link = Link::factory()->create([
            'long_url' => 'https://www.google.com',
        ]);

        Http::fake([
            'ip-api.com/*' => Http::response([
                'status'  => 'success',
                'country' => 'Italy',
                'city'    => 'Rome',
            ], 200),
        ]);
        
        $response = $this->get(route('redirect', ['short_code' => $link->short_code]));

        $response->assertRedirect($link->long_url);
        
        $this->assertDatabaseHas('clicks', [
            'link_id' => $link->id,
            'country' => 'Italy',
            'city'    => 'Rome',
        ]);
    }
}
