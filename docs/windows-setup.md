# Руководство по установке и настройке на Windows

## Содержание
1. [Требования](#требования)
2. [Установка необходимого ПО](#установка-необходимого-по)
3. [Настройка проекта](#настройка-проекта)
4. [Запуск проекта](#запуск-проекта)
5. [Настройка Telegram бота](#настройка-telegram-бота)
6. [Устранение неполадок](#устранение-неполадок)

## Требования

### Минимальные требования
- Windows 10/11
- 4 ГБ RAM
- 2 ГБ свободного места на диске
- Подключение к интернету

### Необходимое ПО
1. **PHP 8.2 или выше**
   - Скачайте с [официального сайта PHP](https://windows.php.net/download/)
   - Выберите версию "VS16 x64 Thread Safe"
   - Распакуйте архив в `C:\php`
   - Добавьте путь `C:\php` в переменную PATH
   - Скопируйте `php.ini-development` в `php.ini`
   - Включите необходимые расширения в `php.ini`:
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

2. **Composer**
   - Скачайте установщик с [getcomposer.org](https://getcomposer.org/download/)
   - Запустите установщик и следуйте инструкциям
   - Проверьте установку: `composer --version`

3. **Node.js и npm**
   - Скачайте LTS версию с [nodejs.org](https://nodejs.org/)
   - Запустите установщик и следуйте инструкциям
   - Проверьте установку: `node --version` и `npm --version`

4. **Git для Windows**
   - Скачайте с [git-scm.com](https://git-scm.com/download/win)
   - Запустите установщик и следуйте инструкциям
   - Проверьте установку: `git --version`

## Установка необходимого ПО

### 1. Установка PHP
1. Скачайте PHP для Windows
2. Распакуйте архив в `C:\php`
3. Добавьте путь в переменную PATH:
   - Откройте "Система" → "Дополнительные параметры системы" → "Переменные среды"
   - В разделе "Переменные среды пользователя" найдите PATH
   - Добавьте `C:\php`
4. Настройте php.ini:
   - Скопируйте `php.ini-development` в `php.ini`
   - Раскомментируйте необходимые расширения
   - Установите `date.timezone = Europe/Moscow`

### 2. Установка Composer
1. Скачайте Composer-Setup.exe
2. Запустите установщик
3. Укажите путь к PHP: `C:\php\php.exe`
4. Завершите установку

### 3. Установка Node.js
1. Скачайте Node.js LTS
2. Запустите установщик
3. Следуйте инструкциям установщика
4. Проверьте установку в командной строке

### 4. Установка Git
1. Скачайте Git для Windows
2. Запустите установщик
3. Используйте рекомендуемые настройки
4. Проверьте установку в командной строке

## Настройка проекта

1. Клонируйте репозиторий:
```bash
git clone https://github.com/your-username/todo.git
cd todo
```

2. Установите зависимости PHP:
```bash
composer install
```

3. Установите зависимости Node.js:
```bash
npm install
```

4. Создайте файл .env:
```bash
copy .env.example .env
```

5. Сгенерируйте ключ приложения:
```bash
php artisan key:generate
```

6. Создайте базу данных SQLite:
```bash
type nul > database/database.sqlite
```

7. Выполните миграции:
```bash
php artisan migrate
```

8. Создайте символическую ссылку для хранилища:
```bash
php artisan storage:link
```

## Запуск проекта

1. Запустите сервер разработки:
```bash
php artisan serve
```

2. В отдельном терминале запустите Vite:
```bash
npm run dev
```

3. В отдельном терминале запустите обработчик очередей:
```bash
php artisan queue:work
```

4. Откройте браузер и перейдите по адресу: http://localhost:8000

## Настройка Telegram бота

1. Создайте нового бота через [@BotFather](https://t.me/BotFather)
2. Получите токен бота
3. Отправьте боту любое сообщение
4. Получите ваш chat_id через [@userinfobot](https://t.me/userinfobot)
5. Откройте файл `.env` и установите значения:
   ```
   TELEGRAM_BOT_TOKEN=your_bot_token
   TELEGRAM_CHAT_ID=your_chat_id
   ```

## Устранение неполадок

### 1. Ошибки с правами доступа
- Убедитесь, что у пользователя есть права на запись в папки:
  - storage/
  - bootstrap/cache/
  - database/
- Запустите командную строку от имени администратора

### 2. Ошибки с SQLite
- Проверьте, что расширение pdo_sqlite включено в php.ini
- Убедитесь, что файл database/database.sqlite существует
- Проверьте права доступа к файлу

### 3. Ошибки с Node.js
- Удалите папку node_modules и package-lock.json
- Выполните npm install заново
- Проверьте версию Node.js (должна быть LTS)

### 4. Ошибки с Composer
- Очистите кэш: `composer clear-cache`
- Удалите папку vendor: `rmdir /s /q vendor`
- Выполните composer install заново

### 5. Ошибки с Git
- Проверьте настройки Git:
  ```bash
  git config --global user.name "Your Name"
  git config --global user.email "your.email@example.com"
  ```

### 6. Ошибки с PHP
- Проверьте версию PHP: `php -v`
- Проверьте загруженные расширения: `php -m`
- Проверьте настройки в php.ini

## Дополнительная информация

### Полезные команды
```bash
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Перезапуск очередей
php artisan queue:restart

# Проверка статуса
php artisan about
```

### Рекомендуемые инструменты
- [Laravel Sail](https://laravel.com/docs/sail) - для разработки
- [Laravel Telescope](https://laravel.com/docs/telescope) - для отладки
- [Laravel Dusk](https://laravel.com/docs/dusk) - для тестирования

### Полезные ссылки
- [Документация Laravel](https://laravel.com/docs)
- [Документация PHP](https://www.php.net/docs.php)
- [Документация Node.js](https://nodejs.org/docs)
- [Документация Git](https://git-scm.com/doc) 