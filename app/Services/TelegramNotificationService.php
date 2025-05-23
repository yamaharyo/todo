<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');

        if (empty($this->botToken) || empty($this->chatId)) {
            Log::error('Telegram configuration is missing', [
                'bot_token' => empty($this->botToken) ? 'missing' : 'set',
                'chat_id' => empty($this->chatId) ? 'missing' : 'set'
            ]);
            throw new \RuntimeException('Telegram configuration is missing');
        }
    }

    public function sendMessage($message)
    {
        try {
            if (empty($this->botToken) || empty($this->chatId)) {
                Log::error('Telegram configuration is missing');
                return false;
            }

            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            if (!$response->successful()) {
                Log::error('Telegram notification failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram notification error', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendTaskReminder($task)
    {
        $message = "🔔 <b>Напоминание о задаче</b>\n\n";
        $message .= "📝 <b>{$task->title}</b>\n";
        if ($task->description) {
            $message .= "📋 {$task->description}\n";
        }
        if ($task->board) {
            $message .= "\nДоска: {$task->board->name}\n";
        }
        $message .= "Создана: " . $task->created_at->format('d.m.Y') . " в " . $task->created_at->format('H:i');

        return $this->sendMessage($message);
    }
} 