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

    public function sendMessage(string $message): bool
    {
        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            if ($response->successful()) {
                Log::info('Telegram message sent successfully', [
                    'message' => $message,
                    'response' => $response->json()
                ]);
                return true;
            }

            Log::error('Failed to send Telegram message', [
                'message' => $message,
                'response' => $response->json()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception while sending Telegram message', [
                'message' => $message,
                'error' => $e->getMessage()
            ]);
            return false;
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
        $message .= "\n\n🔗 <a href='" . route('todos.show', $task->id) . "'>Открыть задачу</a>";
        
        return $message;
    }
} 