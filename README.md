
1. Клонировать проект
```sh
git clone
```

2. Установить зависимости
```sh
composer install
```

3. Создать файл .env
```sh
cp .env.example .env
```

4. Создать интеграцию в amocrm и заполнить поля в .env из созданной интеграции
```sh
AMOCRM_CLIENT_SECRET=секретный ключ
AMOCRM_CLIENT_ID=ID интеграции
AMOCRM_REDIRECT_URI=редирект, указанный при создании(пример - example.ru/auth)
AMOCRM_DOMAIN=ваш домен - https://взять здесь.amocrm.ru/
```

5. Сгенерировать ключ
```sh
php artisan key:generate
```


**Открываем главную страницу и нажимаем кнопку авторизоваться**

**Если все успешно появится форма, иначе код ошибки**
