<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Board;
use App\Models\Todo;
use App\Models\User;
use App\Services\TelegramNotificationService;

class TestTaskReminder extends Command
{
    protected $signature = 'task:test-reminder';
    protected $description = 'Create a test task and send reminder';

    public function handle(TelegramNotificationService $telegramService)
    {
        $this->info('Getting first user...');
        $user = User::first();
        if (!$user) {
            $this->error('No users found in database!');
            return;
        }

        $this->info('Creating test board...');
        $board = Board::create([
            'name' => 'Тестовая доска',
            'color' => '#ff0000',
            'user_id' => $user->id
        ]);
        $this->info('Board created with ID: ' . $board->id);

        $this->info('Creating test task...');
        $task = Todo::create([
            'title' => 'Тестовая задача для напоминания',
            'description' => 'Это тестовая задача для проверки уведомлений',
            'board_id' => $board->id,
            'user_id' => $user->id,
            'completed' => false,
            'reminder_at' => now()->addMinutes(1)
        ]);
        $this->info('Task created with ID: ' . $task->id);

        $this->info('Sending reminder...');
        $result = $telegramService->sendTaskReminder($task);
        
        if ($result) {
            $this->info('Reminder sent successfully!');
        } else {
            $this->error('Failed to send reminder');
        }
    }
} 