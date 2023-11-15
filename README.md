# SE_laravel_test

<h2>Запуск проекта:</h2>

1. Запустить контейнеры:

```docker-compose up -d```

2. Установить composer зависимости:

```docker-compose exec app composer install```

3. Выполнить миграции для базы данных:

```docker-compose exec app php artisan migrate```

4. Сгенерировать ключи для Laravel Passport:

```docker-compose exec app php artisan passport:install```

5. Добавить админа, пользователей и заметки в базу данных:

```docker-compose exec app php artisan db:seed```

<h2>Прочее:</h2>
/database/seeders/DatabaseSeeder.php - здесь данные админа и пользователей для проверки.

Авторизация через Bearer token, получить по POST /api/login

http://localhost:8025 - Проверить письма
http://localhost/api/documentation - Документация

Endpoint PUT /api/notes/{id} можно проверить через <b>POST</b> /api/notes/{id} с дополнительным параметром <b>_method="PUT"</b>
