# Ruwork Skolkovo API Client

## Установка

```bash
$ composer require ruwork/skolkovo-client guzzlehttp/psr7 php-http/guzzle6-adapter
```

Вместо `guzzlehttp/psr7` вы можете использовать [любую имплементацию PSR-7 сообщений](https://packagist.org/providers/psr/http-message-implementation), например, [zendframework/zend-diactoros](https://packagist.org/packages/zendframework/zend-diactoros).

Вместо `php-http/guzzle6-adapter` вы можете использовать [любую имплементацию HTTP-клиента](https://packagist.org/providers/php-http/client-implementation), например, [cURL client](https://packagist.org/packages/php-http/curl-client) или [Socket client](https://packagist.org/packages/php-http/socket-client).

## Использование
 
### Инициализация клиента

```php
<?php

declare(strict_types=1);

use Ruwork\SkolkovoClient\SkolkovoClient;
use Ruwork\SkolkovoClient\TokenStorage\FileTokenStorage;
use Ruwork\SkolkovoClient\Definition\SkolkovoDefinition;

$tokenStorage = new FileTokenStorage('path/to/token.json');

$client = new SkolkovoClient([
    'client_id' => 'client_id',
    'client_secret' => 'client_secret',
], [], new SkolkovoDefinition($tokenStorage));
```

### Получение ссылки для OAuth

```php
<?php

declare(strict_types=1);

$url = $client->generateLoginUrl('your/redirect/url');
```

### Получение пользователя после авторизации OAuth

```php
<?php

declare(strict_types=1);

use Ruwork\SkolkovoClient\TokenStorage\InstantTokenStorage;

$code = $_GET['code'];

$token = $client->oauthTokenCode()
    ->setCode($code)
    ->setRedirectUri('your/redirect/url')
    ->getResult();

$apiData = $client->info()
    ->setTokenStorage(new InstantTokenStorage($token))
    ->getResult();

var_dump($apiData['AccessingUser']);
```

### Отправка свободного запроса

```php
<?php

declare(strict_types=1);

use Ruwork\SkolkovoClient\TokenStorage\InstantTokenStorage;

$client->request([
    'method' => 'GET',
    // адрес на стороне сервиса, обязательный параметр
    'endpoint' => '/oauth/token',
    // добавлять заголовки авторизации?
    'authenticate' => true,
    'data' => [
        'key' => 'value',
    ],
    'headers' => [
        'header' => 'value',
    ],
]);
```

### Генерация токена по имени пользователя и паролю

```php
<?php

declare(strict_types=1);

$token = $client
    ->oauthTokenPassword()
    ->setUsername('username')
    ->setPassword('password')
    ->getResult();

$tokenStorage->set($token);
```
