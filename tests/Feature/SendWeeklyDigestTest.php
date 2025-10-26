<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Mail\WeeklyStatsDigest;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWeeklyDigestTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function weekly_digest_email_with_stats(): void
    {
        Link::factory(5)->create();
        Mail::fake();

        $this->artisan('app:send-weekly-digest');
        Mail::assertQueued(WeeklyStatsDigest::class);
        Mail::assertQueued(WeeklyStatsDigest::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });
    }
}
