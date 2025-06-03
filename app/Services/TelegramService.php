<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage($message)
    {
        try {
            Log::info('TelegramService: Attempting to send message', [
                'message' => $message,
                'bot_token' => substr($this->botToken, 0, 5) . '...',
                'chat_id' => $this->chatId
            ]);

            $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
            $data = [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ];

            Log::info('TelegramService: Request data', [
                'url' => $url,
                'data' => $data
            ]);

            $response = Http::withoutVerifying()->post($url, $data);

            Log::info('TelegramService: Response received', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('TelegramService: Failed to send message', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to send Telegram message: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('TelegramService: Exception occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function sendTaskReminder($task)
    {
        $message = $this->formatTaskMessage($task);
        return $this->sendMessage($message);
    }

    protected function formatTaskMessage($task)
    {
        $message = "🔔 <b>Напоминание о задаче</b>\n\n";
        $message .= "📝 <b>{$task->title}</b>\n";
        
        if ($task->description) {
            $message .= "📋 {$task->description}\n";
        }
        
        if ($task->board) {
            $message .= "\n📋 Доска: {$task->board->name}\n";
        }
        
        $message .= "\n⏰ Время создания: " . $task->created_at->format('d.m.Y H:i');
        
        return $message;
    }
} 