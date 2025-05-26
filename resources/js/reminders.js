// Глобальные функции
window.openReminderModal = function(todoId) {
    const modal = document.getElementById('reminderModal');
    const form = document.getElementById('reminderForm');
    form.action = `/todos/${todoId}/reminder`;
    
    // Устанавливаем минимальную дату как сегодня
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('reminder_date').min = today;
    
    modal.classList.remove('hidden');
};

window.closeReminderModal = function() {
    const modal = document.getElementById('reminderModal');
    modal.classList.add('hidden');
};

// Функция для отправки напоминания через Telegram
async function sendReminder(taskId) {
    console.log(`Attempting to send reminder for task ${taskId}`);
    try {
        const response = await fetch(`/todos/${taskId}/send-reminder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`Failed to send reminder: ${errorData.error || response.statusText}`);
        }
        
        console.log('Reminder sent successfully');
    } catch (error) {
        console.error('Error sending reminder:', error);
    }
}

// Функция для планирования отправки напоминания
function scheduleReminder(taskId, reminderTime) {
    try {
        if (!reminderTime) {
            console.error(`No reminder time provided for task ${taskId}`);
            return;
        }

        console.log(`Scheduling reminder for task ${taskId} with time ${reminderTime}`);
        const now = new Date();
        
        // Преобразуем строку даты в объект Date с учетом локального времени
        console.log('Raw reminder time:', reminderTime);
        const [datePart, timePart] = reminderTime.split(' ');
        const [year, month, day] = datePart.split('-');
        const [hours, minutes, seconds] = timePart.split(':');
        
        const reminderDate = new Date(year, month - 1, day, hours, minutes, seconds);
        console.log('Parsed reminder date:', reminderDate);
        
        if (isNaN(reminderDate.getTime())) {
            throw new Error(`Invalid date format: ${reminderTime}`);
        }
        
        const delay = reminderDate.getTime() - now.getTime();
        
        console.log(`Current time: ${now.toLocaleString()}`);
        console.log(`Reminder time: ${reminderDate.toLocaleString()}`);
        console.log(`Delay in milliseconds: ${delay}`);
        
        if (delay > 0) {
            console.log(`Scheduling reminder for task ${taskId} at ${reminderDate.toLocaleString()}`);
            setTimeout(() => {
                console.log(`Time to send reminder for task ${taskId}`);
                sendReminder(taskId);
            }, delay);
        } else {
            console.log(`Reminder time ${reminderDate.toLocaleString()} is in the past`);
        }
    } catch (error) {
        console.error('Error scheduling reminder:', error, 'Time:', reminderTime);
    }
}

// Инициализируем обработчики событий после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    // Обработка отправки формы
    const reminderForm = document.getElementById('reminderForm');
    if (reminderForm) {
        reminderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const date = document.getElementById('reminder_date').value;
            const time = document.getElementById('reminder_time').value;
            
            if (!date || !time) {
                alert('Пожалуйста, выберите дату и время');
                return;
            }
            
            // Форматируем дату и время в нужный формат
            const reminderAt = `${date} ${time}:00`;
            
            console.log('Form data:', {
                date,
                time,
                reminderAt
            });
            
            // Получаем ID задачи из URL формы
            const taskId = this.action.split('/')[2];
            
            // Отправляем форму
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reminder_at: reminderAt
                })
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.error || 'Ошибка при установке напоминания');
                    }
                    return data;
                }
                throw new Error('Server did not return JSON');
            })
            .then(data => {
                if (data.success) {
                    // Закрываем модальное окно
                    closeReminderModal();
                    
                    // Планируем напоминание
                    scheduleReminder(taskId, reminderAt);
                    
                    // Показываем сообщение об успехе
                    alert(data.message || 'Напоминание успешно установлено');
                    
                    // Перезагружаем страницу через 1 секунду
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.error || 'Ошибка при установке напоминания');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Ошибка при установке напоминания');
            });
        });
    }

    // Инициализируем напоминания
    console.log('Initializing reminders...');
    const reminders = document.querySelectorAll('[data-reminder]');
    console.log(`Found ${reminders.length} reminders`);
    
    reminders.forEach(reminder => {
        const taskId = reminder.dataset.taskId;
        const reminderTime = reminder.dataset.reminder;
        console.log(`Initializing reminder for task ${taskId} at ${reminderTime}`);
        console.log('Reminder element:', reminder);
        console.log('Dataset:', reminder.dataset);
        scheduleReminder(taskId, reminderTime);
    });
});

// Добавляем обработчик для обновления страницы
window.addEventListener('beforeunload', function() {
    console.log('Page is being unloaded, reminders will be reinitialized on next load');
}); 