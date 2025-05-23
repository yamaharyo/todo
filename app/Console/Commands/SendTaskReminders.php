<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Todo;
use App\Jobs\SendTelegramReminder;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send reminders for tasks';

    public function handle()
    {
        try {
            $tasks = Todo::with('board')
                ->where('completed', false)
                ->whereNotNull('reminder_at')
                ->where('reminder_at', '<=', now())
                ->get();

            $this->info("Found {$tasks->count()} tasks to remind");

            foreach ($tasks as $task) {
                try {
                    $this->info("Processing task #{$task->id}: {$task->title}");
                    
                    // Отправляем задачу в очередь
                    SendTelegramReminder::dispatch($task)
                        ->onQueue('reminders')
                        ->delay(now()->addSeconds(5));
                    
                    $this->info("Reminder queued for task #{$task->id}");
                    
                    // Сбрасываем напоминание
                    $task->update(['reminder_at' => null]);
                    
                } catch (\Exception $e) {
                    $this->error("Error processing task #{$task->id}: " . $e->getMessage());
                    Log::error("Error queueing reminder for task #{$task->id}", [
                        'error' => $e->getMessage(),
                        'task' => $task->toArray()
                    ]);
                }
            }

            $this->info('Reminders processing completed!');
        } catch (\Exception $e) {
            $this->error("Error in reminder processing: " . $e->getMessage());
            Log::error("Error in reminder processing", [
                'error' => $e->getMessage()
            ]);
        }
    }
} 