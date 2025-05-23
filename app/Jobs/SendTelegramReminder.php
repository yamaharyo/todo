<?php

namespace App\Jobs;

use App\Models\Todo;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTelegramReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 180, 360]; // Повторные попытки через 1, 3 и 6 минут
    public $timeout = 30;

    protected $task;

    public function __construct(Todo $task)
    {
        $this->task = $task;
    }

    public function handle(TelegramService $telegramService)
    {
        try {
            Log::info('Sending Telegram reminder', [
                'task_id' => $this->task->id,
                'title' => $this->task->title
            ]);

            $result = $telegramService->sendTaskReminder($this->task);

            if (!$result) {
                throw new \Exception('Failed to send Telegram reminder');
            }

            Log::info('Telegram reminder sent successfully', [
                'task_id' => $this->task->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending Telegram reminder', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Telegram reminder job failed', [
            'task_id' => $this->task->id,
            'error' => $exception->getMessage()
        ]);
    }
} 