<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestTelegramRaw extends Command
{
    protected $signature = 'telegram:test-raw';
    protected $description = 'Test Telegram API directly';

    public function handle()
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        $this->info('Testing Telegram API directly...');
        
        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => '🔔 Тестовое сообщение (прямой запрос)',
                'parse_mode' => 'HTML'
            ]);

            $this->info('Response status: ' . $response->status());
            $this->info('Response body: ' . $response->body());
            
            if ($response->successful()) {
                $this->info('Message sent successfully!');
            } else {
                $this->error('Failed to send message');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 