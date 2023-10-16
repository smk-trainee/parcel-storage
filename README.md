![title](/assets/title.png)

Привет, это тестовое задание на позицию стажера php-разработчика. Оно может находится в
приватном репозитории (скрытом) - чтобы все было по честному и никто не смог воспользоваться
результатами сделанной тобой работы. Твой труд будет виден только тебе и нашей команде менторов. 
Добавь их: livsi, blackberryJam, smk-trainee

Тебе не прийдется создавать каркас приложения самостоятельно - мы уже сделали это
для тебя.

На локальной машине нужен установленный __git__,  __docker__ и __docker compose__
Если к тебя их еще нет - тебе пригодятся следующие ссылки:

- https://docs.docker.com/engine/install/
- https://docs.docker.com/engine/install/linux-postinstall/ (для линукс-пользователей)
- https://git-scm.com/downloads


Для того, чтобы разрабатывать - клонируй код на свой комп и можно начинать
работу. Часть кода уже написана - ее при необходимости можно смело менять, 
но большую часть тебе прийдется написать самостоятельно.

Запуск приложения производится консольной командой:
```
docker compose up
```

После запуска - api-doc будет доступен по адресу http://localhost:8080/

![title](/assets/apidoc.png)

## Задание

Необходимо доработать сервис хранения информации о посылках (Parcels) на PHP (Symfony). 

Сервис должен реализовывать REST API для:
1. создания посылки
2. поиск посылки по телефону отправителя
3. поиск посылки по фио получателя
3. удаления посылки

У сущности Посылка(Parcel) есть следующие свойства:
- отправитель
    - фио
    - телефон
    - адрес отправления (страна, город, улица, дом, квартира)
- получатель
    - фио
    - телефон
    - адрес получения (страна, город, улица, дом, квартира)
- габариты (длина * ширина * высота + вес)
- оценочная стоимость

Посылки должны сохранятся в базе данных PostgreSQL (в конфигурации docker-compose.yaml - она уже есть).

## Что нужно сделать

1. Добавить нужные Entity (https://symfony.com/doc/current/doctrine.html#creating-an-entity-class)
2. Добавить сервис(ы) с необходимыми методами
3. В контроллеры добавить вызовы нужных методов сервисов
4. Написать автотесты уровня unit (https://symfony.com/doc/current/testing.html#unit-tests) для сервисов
5. Привести в соответствие api документацию разработанному решению
6. Сделать пулл-реквест из ветки, в которой разрабатывалось решение в main ветку - чтобы ревьюверу удобно было смотреть изменения

