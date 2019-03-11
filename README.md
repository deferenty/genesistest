<p align="center">
    <h1 align="center">Test task/h1>
</p>

Для запуска задания неоходимо.
1. Скопировать или склонировать проект
2. Установить Yii2 и необходимые компоненты
```
composer install
```
3. Инициализировать приложение
```
php init
```
4. Настроить компонент db - соединение с MySQL базой данных (файл common/config/main-local.php)
5. Провести миграцию
```
php yii migrate/up create_phonebook_table
```
6. Настроить веб-сервер и файл hosts, при необходимости. Пример конфигурации nginx

```
    server {
        charset utf-8;
        client_max_body_size 128M;

        listen 127.0.0.1:80; ## listen for ipv4

        server_name genesis.test;
        root        /var/www/html/genesis.test/frontend/web/;
        index       index.php;

        access_log  /var/www/html/genesis.test/frontend/runtime/nginx-access.log;
        error_log   /var/www/html/genesis.test/frontend/runtime/nginx-error.log;

        location / {
            # Redirect everything that isn't a real file to index.php
            try_files $uri $uri/ /index.php$is_args$args;

        # deny accessing php files for the /assets directory
        location ~ ^/assets/.*\.php$ {
            deny all;
        }

        location ~ \.php$ {
    	    try_files $uri =404;
    	    include snippets/fastcgi-php.conf;
	    fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        }

        location ~* /\. {
            deny all;
        }
    }

    server {
        charset utf-8;
        client_max_body_size 128M;

        listen 127.0.0.1:80; ## listen for ipv4
        #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

        server_name api.genesis.test;
        root        /var/www/html/genesis.test/backend/web/;
        index       index.php;

        access_log  /var/www/html/genesis.test/backend/runtime/nginx-access.log;
        error_log   /var/www/html/genesis.test/backend/runtime/nginx-error.log;

        location / {
            # Redirect everything that isn't a real file to index.php
            try_files $uri $uri/ /index.php$is_args$args;
        }

        # deny accessing php files for the /assets directory
        location ~ ^/assets/.*\.php$ {
            deny all;
        }

        location ~ \.php$ {
    	    try_files $uri =404;
    	    include snippets/fastcgi-php.conf;
    	    fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        }

        location ~* /\. {
            deny all;
        }
    }
```
7. Иметь Redis в базовой конфигурации (обратить внимание, чтобы джобы не подхватывал не тот воркер)

Фронтенд часть реализована в frontend части Yii2 (табличка пользователей-телефов с фильтром по имени и сортировкой)
Страница доступна по адресу genesis.test/phonebook/index

API реализовано в backend части приложения.
Для отправки JSON-ов использовать somedomain.com/record/create
Принимает POST запросы.

Полученые запросы валидируются и складываются в очередь. Есть два варианта дальнейшей обработки.
1. Сохраненные модели сохраняется каждая отдельно запросом в базу данных
(класс backend\components\apisaver\SaveRecordJobSingle)
2. Выставляется задержка и количество моделей в одной группе. Они складируются в кеш
и записываются, по истечению времени задержки одним запросом к базе.

Для менеджера очередей и кеша использовался Redis. Зависимости настраиваются в 
backend/config/main.php в секции container

