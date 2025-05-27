# Установка Todo-приложения на Windows

## Шаг 1: Установка необходимого ПО

### 1. Установка PHP 8.2
1. Перейдите на [официальный сайт PHP](https://windows.php.net/download/)
2. Скачайте ZIP-архив "VS16 x64 Thread Safe"
3. Создайте папку `C:\php`
4. Распакуйте содержимое архива в `C:\php`
5. Добавьте PHP в PATH:
   - Нажмите Win + R
   - Введите `sysdm.cpl`
   - Перейдите на вкладку "Дополнительно"
   - Нажмите "Переменные среды"
   - В разделе "Переменные среды пользователя" найдите PATH
   - Нажмите "Изменить"
   - Нажмите "Создать"
   - Введите `C:\php`
   - Нажмите OK во всех окнах
6. Создайте файл `php.ini`:
   - В папке `C:\php` найдите `php.ini-development`
   - Создайте копию и переименуйте в `php.ini`
   - Откройте `php.ini` в текстовом редакторе
   - Найдите и раскомментируйте (уберите точку с запятой в начале строки) следующие строки:
     ```ini
     extension=pdo_sqlite
     extension=openssl
     extension=fileinfo
     extension=mbstring
     extension=tokenizer
     extension=xml
     extension=ctype
     extension=json
     ```
   - Найдите строку `date.timezone` и установите:
     ```ini
     date.timezone = Europe/Moscow
     ```

### 2. Установка Composer
1. Перейдите на [getcomposer.org](https://getcomposer.org/download/)
2. Скачайте и запустите Composer-Setup.exe
3. При установке укажите путь к PHP: `C:\php\php.exe`
4. Завершите установку

### 3. Установка Node.js
1. Перейдите на [nodejs.org](https://nodejs.org/)
2. Скачайте LTS версию
3. Запустите установщик
4. Следуйте инструкциям установщика

### 4. Установка Git
1. Перейдите на [git-scm.com](https://git-scm.com/download/win)
2. Скачайте установщик для Windows
3. Запустите установщик
4. Используйте рекомендуемые настройки

## Шаг 2: Клонирование проекта

1. Откройте командную строку (cmd.exe)
2. Перейдите в папку, где хотите разместить проект:
   ```cmd
   cd C:\Projects
   ```
3. Клонируйте репозиторий:
   ```cmd
   git clone https://github.com/yamaharyo/todo.git
   ```
4. Перейдите в папку проекта:
   ```cmd
   cd todo
   ```
5. Переключитесь на ветку windows-support:
   ```cmd
   git checkout windows-support
   ```

## Шаг 3: Установка проекта

1. Установите зависимости PHP:
   ```cmd
   composer install
   ```

2. Установите зависимости Node.js:
   ```cmd
   npm install
   ```

3. Создайте файл .env:
   ```cmd
   copy .env.example .env
   ```

4. Сгенерируйте ключ приложения:
   ```cmd
   php artisan key:generate
   ```

5. Создайте базу данных SQLite:
   ```cmd
   type nul > database\database.sqlite
   ```

6. Выполните миграции:
   ```cmd
   php artisan migrate
   ```

7. Создайте символическую ссылку для хранилища:
   ```cmd
   php artisan storage:link
   ```

## Шаг 4: Настройка Telegram бота

1. Откройте Telegram
2. Найдите [@BotFather](https://t.me/BotFather)
3. Отправьте команду `/newbot`
4. Следуйте инструкциям для создания бота
5. Скопируйте полученный токен
6. Найдите [@userinfobot](https://t.me/userinfobot)
7. Отправьте ему любое сообщение
8. Скопируйте ваш chat_id
9. Откройте файл `.env` в текстовом редакторе
10. Найдите и отредактируйте следующие строки:
    ```
    TELEGRAM_BOT_TOKEN=ваш_токен_бота
    TELEGRAM_CHAT_ID=ваш_chat_id
    ```

## Шаг 5: Запуск проекта

1. Откройте три окна командной строки

2. В первом окне запустите сервер:
   ```cmd
   php artisan serve
   ```

3. Во втором окне запустите Vite:
   ```cmd
   npm run dev
   ```

4. В третьем окне запустите обработчик очередей:
   ```cmd
   php artisan queue:work
   ```

5. Откройте браузер и перейдите по адресу: http://localhost:8000

## Возможные проблемы

### 1. Ошибка "PHP не найден"
- Проверьте, что PHP добавлен в PATH
- Перезапустите командную строку
- Проверьте установку: `php -v`

### 2. Ошибка с правами доступа
- Запустите командную строку от имени администратора
- Убедитесь, что у вас есть права на запись в папки:
  - storage/
  - bootstrap/cache/
  - database/

### 3. Ошибка с SQLite
- Проверьте, что расширение pdo_sqlite включено в php.ini
- Убедитесь, что файл database/database.sqlite существует
- Проверьте права доступа к файлу

### 4. Ошибка с Node.js
- Удалите папку node_modules и package-lock.json
- Выполните npm install заново
- Проверьте версию Node.js: `node -v`

### 5. Ошибка с Composer
- Очистите кэш: `composer clear-cache`
- Удалите папку vendor: `rmdir /s /q vendor`
- Выполните composer install заново

## Полезные команды

```cmd
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Перезапуск очередей
php artisan queue:restart

# Проверка статуса
php artisan about
```

## Дополнительная помощь

Если у вас возникли проблемы:
1. Проверьте, что все программы установлены корректно
2. Убедитесь, что все команды выполняются в правильной папке
3. Проверьте права доступа к папкам
4. Перезапустите командную строку после изменения PATH
5. Проверьте логи в `storage/logs/laravel.log` 