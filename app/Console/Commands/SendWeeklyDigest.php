<?php

namespace App\Console\Commands;

use App\Mail\WeeklyStatsDigest;
use App\Models\Click;
use App\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-weekly-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the weekly statistics digest.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gathering stats...');
        $stats = [
            'total_links' => Link::count(),
            'total_clicks' => Click::count(),
            'last_click' => Click::latestClick(),
            'top_links' => Link::topLinks(),
        ];

        $recipient = 'admin@example.com';
        Mail::to($recipient)->queue(new WeeklyStatsDigest($stats));

        $this->info("Weekly digest sent successfully to {$recipient}!");    }
}
