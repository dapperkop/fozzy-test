Для тестирования следует создать отдельную БД, а также отдельный .env файл.

cp .env.example .env.testing

В .env файле для "тестового" окружения следует внести соответствующие изменения, прописать доступ к "тестовой" БД.
Также следует выполнить миграции и сиды:

```
$ artisan migrate --env=testing
$ artisan db:seed --env=testing
```

После этого можно будет выполнить тесты:

```
$ artisan test --env=testing --testsuite=Feature --filter=ApiTest
```