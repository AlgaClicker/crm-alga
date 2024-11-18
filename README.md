# CRM Alga Corp

[доска кабан](https://github.com/users/AlgaClicker/projects/1/views/1)

[Экспорт эндпоинтов Postman ](https://github.com/AlgaClicker/crm-alga/tree/main/support) 

## Структура БэкЭнда
```
.
├── Application
│   ├── Core - Ядро
│   └── Lumen - Lumen Framework
├── Domain - Домен
│   ├── Contracts - Контракты
│   ├── Entities - Сущности
│   └── Services - Сервисы
├── Infrastructure
│   ├── Doctrine 
│   ├── Repositories
│   └── Util
└── routes - Роуты
```
 
## Контейнеры
    - api 8888/tcp - БэкЭнд Laravel Lumen DDD 
    - websockets 6001/tcp - TODO: Реализовать очереди и публикацию по средствам socket.io + lumen
    - front 80/tcp - Фронтэнд Node 16 Vue.JS 3 (vite)
    - nginx
    - memcached
    - redis 
    - postgres

## Первоначальный запуск проекта (debian/ubuntu)

Установить docker-compose и make:
``` 
apt install docker-compose
apt install make
apt install git
```
скопировать репозиторий и перейти в папку с проектом:
```
git clone git@github.com:AlgaClicker/crm-alga.git
cd crm_back
```

Запустить сборку проекта:
```
make init
```
UI http://127.0.0.1/ 
Учетная запись:
```
    Пользователь: admin
    Пароль: adminpass
```

## Команты make
    Сборка контейнеров Docker 
    - make init  (build,install,up)
    Запуск контейнеров 
    - make up
    Остановка Контейнеров 
    - make down 
    Перезапуск контейнеров
    - make restart

    Запуск локально бэкэнда из папки backend  "php -S 0.0.0.0:8888 -t Application/Lumen/public/"

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
