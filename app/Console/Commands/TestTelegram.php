<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramNotificationService;

class TestTelegram extends Command
{
    protected $signature = 'telegram:test';
    protected $description = 'Test Telegram notifications';

    public function handle(TelegramNotificationService $telegramService)
    {
        $this->info('Testing Telegram configuration...');
        $this->info('Bot Token: ' . config('services.telegram.bot_token'));
        $this->info('Chat ID: ' . config('services.telegram.chat_id'));

        $result = $telegramService->sendMessage('🔔 Тестовое сообщение');
        
        if ($result) {
            $this->info('Message sent successfully!');
        } else {
            $this->error('Failed to send message');
        }
    }
} 