@echo off
echo Установка проекта на Windows...

REM Проверка наличия PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo PHP не найден. Пожалуйста, установите PHP 8.2 или выше.
    exit /b 1
)

REM Проверка наличия Composer
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo Composer не найден. Пожалуйста, установите Composer.
    exit /b 1
)

REM Проверка наличия Node.js
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo Node.js не найден. Пожалуйста, установите Node.js.
    exit /b 1
)

REM Проверка наличия Git
where git >nul 2>nul
if %errorlevel% neq 0 (
    echo Git не найден. Пожалуйста, установите Git.
    exit /b 1
)

echo Установка зависимостей PHP...
call composer install

echo Установка зависимостей Node.js...
call npm install

echo Создание файла .env...
if not exist .env (
    copy .env.example .env
)

echo Генерация ключа приложения...
call php artisan key:generate

echo Создание базы данных SQLite...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
)

echo Выполнение миграций...
call php artisan migrate

echo Создание символической ссылки для хранилища...
call php artisan storage:link

echo Установка завершена!
echo.
echo Для запуска проекта выполните следующие команды в разных терминалах:
echo 1. php artisan serve
echo 2. npm run dev
echo 3. php artisan queue:work
echo.
echo Затем откройте браузер и перейдите по адресу: http://localhost:8000 