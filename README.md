## Начало работы:
1. npm run dev
2. php artisan serve


## О логике приложения:
### API:
1. Основные (повторяющиеся) данные находятся: App\Console\Commands\SynkServicesCommand
2. Далее, подключение по разделам находится: App\Services\API 
3. Далее, уже вызываются в нужном контроллере

### Авторизация:
1. Вызов страницы авторизации проходит в App\Http\Controllers\Auth\IndexController
2. Далее идет форма, находящаяся в App\resources\views\auth\index.blade.php
3. Проходит на обработку ошибок в App\Http\Requests\Auth\StoreRequest
4. Дальше данные отправляются StoreController (находящийся в той же папке, что и IndexController)
5. Обрабатывает данные, проверяет их через сервис (App\Services\API\ApiAuthService)
6. При успешном ответе редиректит в App\Http\Controllers\Main\IndexController, при ошибке выводит ее в там же, где и форма

### Главная страница:
1. В начале идет проверка у middleware 'login' находящийся в App\Http\Middleware\LoginMiddleware
2. Обработка групп у товаров происходит в Groups\IndexController, берет их через сервис ApiGroupsService
3. Обработка данных товаров в Clothes\IndexController (не законченно, при желании можно вывести все товары), берет данные через сервис ApiClothesService
4. Главный контроллер Main\IndexController берет данные из ClothesList и Groups и возвращает главную страницу App\resources\views\main\index.blade.php


