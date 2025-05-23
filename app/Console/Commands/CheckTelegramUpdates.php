<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckTelegramUpdates extends Command
{
    protected $signature = 'telegram:check-updates';
    protected $description = 'Check Telegram bot updates';

    public function handle()
    {
        $botToken = config('services.telegram.bot_token');

        $this->info('Checking Telegram updates...');
        
        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getUpdates");
            
            $this->info('Response status: ' . $response->status());
            $this->info('Response body: ' . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['ok']) && $data['ok']) {
                    $this->info('Updates retrieved successfully!');
                    if (empty($data['result'])) {
                        $this->info('No updates found. Make sure you have sent a message to the bot.');
                    } else {
                        $this->info('Found ' . count($data['result']) . ' updates');
                    }
                } else {
                    $this->error('Failed to get updates');
                }
            } else {
                $this->error('Failed to get updates');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 