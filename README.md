# Todo Application

## Требования к системе

- Windows 10/11
- PHP 8.2 или выше
- Composer
- Node.js и npm
- Git
- XAMPP (или другой веб-сервер с поддержкой PHP)

## Установка и настройка

### 1. Установка необходимого ПО

1. **Установка XAMPP**
   - Скачайте XAMPP с [официального сайта](https://www.apachefriends.org/)
   - Установите, выбрав компоненты: Apache, MySQL, PHP
   - Запустите XAMPP Control Panel и запустите Apache

2. **Установка Composer**
   - Скачайте установщик Composer с [официального сайта](https://getcomposer.org/download/)
   - Запустите установщик и следуйте инструкциям
   - Проверьте установку: `composer --version`

3. **Установка Node.js**
   - Скачайте Node.js с [официального сайта](https://nodejs.org/)
   - Установите, выбрав опцию "Automatically install the necessary tools"
   - Проверьте установку: `node --version` и `npm --version`

### 2. Настройка PHP

1. Откройте файл `C:\xampp\php\php.ini`
2. Найдите и раскомментируйте следующие расширения (уберите точку с запятой в начале строки):
   ```ini
   extension=zip
   extension=pdo_sqlite
   extension=sqlite3
   ```
3. Сохраните файл и перезапустите Apache в XAMPP Control Panel

### 3. Клонирование и настройка проекта

1. **Клонирование репозитория**
   ```bash
   git clone <url-репозитория>
   cd todo
   ```

2. **Установка зависимостей**
   ```bash
   composer install
   npm install
   ```

3. **Настройка окружения**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Создание базы данных**
   - Создайте пустой файл `database/database.sqlite`
   - Выполните миграции:
     ```bash
     php artisan migrate
     php artisan session:table
     php artisan migrate
     ```

### 4. Запуск проекта

1. **Запуск сервера Laravel**
   ```bash
   php artisan serve
   ```

2. **Запуск фронтенда (в отдельном терминале)**
   ```bash
   npm run dev
   ```

3. Откройте браузер и перейдите по адресу: `http://localhost:8000`

## Возможные проблемы и их решения

### 1. Ошибка с расширением zip
Если при выполнении `composer install` возникает ошибка с zip:
- Убедитесь, что расширение zip включено в php.ini
- Перезапустите Apache

### 2. Ошибка с базой данных SQLite
Если возникает ошибка с базой данных:
- Проверьте права доступа к папке database
- Убедитесь, что файл database.sqlite создан
- Проверьте, что расширения pdo_sqlite и sqlite3 включены в php.ini

### 3. Ошибка с сессиями
Если возникают проблемы с сессиями:
- Убедитесь, что таблица sessions создана
- Проверьте права на запись в папку storage/framework/sessions

## Структура проекта

```
todo/
├── app/            # Основной код приложения
├── bootstrap/      # Файлы загрузки фреймворка
├── config/         # Конфигурационные файлы
├── database/       # Миграции и сиды
├── public/         # Публичные файлы
├── resources/      # Файлы ресурсов (views, assets)
├── routes/         # Маршруты приложения
├── storage/        # Файлы хранилища
└── tests/          # Тесты
```

## Разработка

### Запуск тестов
```bash
php artisan test
```

### Компиляция ассетов
```bash
npm run build
```

## Лицензия

MIT
