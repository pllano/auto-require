# AutoRequire - Автозагрузка PSR-0 и PSR-4
## Автозагрузка по стандартам PSR-0 и PSR-4 если Composer не установлен
Если Composer не установлен и 'autoload.php' недоступен AutoRequire позволяет автоматически загружать требуемые классы в момент их вызова, без использования таких конструкций как require и include. Все классы под полным вашим контролем.
## Оба стандарта автозагрузки PSR-0 и PSR-4 работают одновременно
Теперь вы можете одновременно использовать оба класса автозагрузки PSR-0 и PSR-4 - с AutoRequire они работают одновременно.
## Загрузка с любых источников
Нет проблем если библиотека опубликована на стороннем ресурсе. Вы можете указать ссылку на любой zip архив доступен по прямой ссылке.
## Использование
Для AutoRequire необходимо указать диреторию vendor_dir и ссылку на файл auto_require.json
```php
// Подключаем \AutoRequire\Autoloader
// Connect \AutoRequire\Autoloader
 
require __DIR__ . '/vendor/AutoRequire.php';
 
// instantiate the loader
// Создать экземпляр загрузчика
$require = new \AutoRequire\Autoloader;
 
// register the autoloader
// Зарегистрировать автозагрузчик
$require->register();
 
// Проверяем доступность autoload.php
// Check Availability autoload.php
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
 
    require __DIR__ . '/../vendor/autoload.php';
 
} else {
    // Если autoload.php не найден, подключаем автозагрузчик пакетов
 
    // Указываем путь к папке vendor
    $vendor_dir = __DIR__ . '/vendor';
 
    // Указываем путь к auto_require.json
    $json_uri = __DIR__ . '/vendor/auto_require.json';
 
    // Запускаем проверку или загрузку пакетов
    $load = $require->run($vendor_dir, $json_uri);
 
    if (count($load) >= 1) {
        foreach($load as $value)
        {
 
            if (isset($value["files"]) && isset($value["dir"])) {
                // Подключаем файл если этого требует пакет
                require $vendor_dir.''.$value["dir"].'/'.$value["files"];
            }
 
            if (isset($value["autoloading"])&& isset($value["replace_name"]) && isset($value["dir"])) {
 
                if ($value["autoloading"] == "psr-0") {
                    // Регистрируем базовый каталог и префикс пространства имен PSR-0
                    $require->setAutoloading($value["replace_name"], $vendor_dir.''.$value["dir"]);
                }
 
            } elseif (isset($value["namespace"]) && isset($value["dir"])) {
 
                // Регистрируем базовый каталог и префикс пространства имен PSR-4
                // register the base directories for the namespace prefix
                $require->addNamespace($value["namespace"], $vendor_dir.''.$value["dir"]);
 
            }
        }
    }
}
```
Если необходимо подключить локальные пакеты из другой директории, укажите их явно
```php
$require->addNamespace('YourName', __DIR__ . '/your-name/your-class');
```
или переместите в директорию vendor_dir и подключите из auto_require.json
```json
{
    "require": [
        {
            "namespace": "YourName\\YourClass",
            "dir": "/your-name/your-class"
        }
    ]
}
```
Подключить файл
```json
{
    "require": [
        {
            "files": "file_name.php",
            "dir": "/your-name/your-dir"
        }
    ]
}
```
## auto_require.json
- `namespace` - Пространство имен
- `files` - Название файла
- `dir` - Директория в которой после распаковки архива будут файлы пакета
- `link` - Прямая ссылка на zip архив пакета
- `name` - Название директории пакета
- `vendor` - Название директории вендора
- `version` - Версия пакета
 
После распаковки архива с github директория пакета имеет название class-name-1.0.1, для этого необходимо указывать название пакета и версию, что бы AutoRequire мог переименовать директорию пакета в class-name
```json
{
    "require": [
        {
            "namespace": "VendorName\\ClassName",
            "dir": "/vendor-name/class-name/src",
            "link": "https://github.com/vendor-name/class-name/archive/1.0.1.zip",
            "name": "class-name",
            "version": "1.0.1",
            "vendor": "vendor-name"
        }
    ]
}
```
## Стандарты автозагрузки PSR-0 + PSR-4
Добавлена поддержка стандарта автозагрузки PSR-0
- `autoloading` - активировать режим "psr-0"
- `replace_name` - указать имя вендора которое используется во всех классах, пример "Twig"
```json
{
    "require": [
        {
            "namespace": "Twig",
            "dir": "/twig/Twig/lib/Twig",
            "link": "https://github.com/twigphp/Twig/archive/v2.4.4.zip",
            "name": "Twig",
            "version": "2.4.4",
            "vendor": "twig",
            "autoloading": "psr-0",
            "replace_name": "Twig"
        }
    ]
}
```
### `auto_require.json` для API Shop
[`auto_require.json`](https://raw.githubusercontent.com/pllano/auto-require/master/auto_require.json) для API Shop

```json
{
    "require": [
        {
            "namespace": "jsonDb",
            "dir": "/pllano/json-db/src",
            "link": "https://github.com/pllano/json-db/archive/1.0.7.zip",
            "name": "json-db",
            "version": "1.0.7",
            "vendor": "pllano"
        }, {
            "namespace": "routerDb",
            "dir": "/pllano/router-db/src",
            "link": "https://github.com/pllano/router-db/archive/1.0.3.zip",
            "name": "router-db",
            "version": "1.0.3",
            "vendor": "pllano"
        }, {
            "namespace": "Psr\\Http\\Message",
            "dir": "/psr/http-message/src",
            "link": "https://github.com/php-fig/http-message/archive/1.0.zip",
            "name": "http-message",
            "version": "1.0",
            "vendor": "psr"
        }, {
            "namespace": "Defuse\\Crypto",
            "dir": "/defuse/php-encryption/src",
            "link": "https://github.com/defuse/php-encryption/archive/v2.1.0.zip",
            "name": "php-encryption",
            "version": "2.1.0",
            "vendor": "defuse"
        }, {
            "namespace": "GuzzleHttp",
            "files": "functions_include.php",
            "dir": "/guzzlehttp/guzzle/src",
            "link": "https://github.com/guzzle/guzzle/archive/6.3.0.zip",
            "name": "guzzle",
            "version": "6.3.0",
            "vendor": "guzzlehttp"
        }, {
            "namespace": "Twig",
            "dir": "/twig/Twig/lib/Twig",
            "link": "https://github.com/twigphp/Twig/archive/v2.4.4.zip",
            "name": "Twig",
            "version": "2.4.4",
            "vendor": "twig",
            "autoloading": "psr-0"
        }
    ]
}
```
### Загружайте из `master` репозиториев [`auto_require_master.json`](https://raw.githubusercontent.com/pllano/auto-require/master/auto_require_master.json)

```json
{
    "require": [
        {
            "namespace": "jsonDB",
            "dir": "/pllano/json-db/src",
            "link": "https://github.com/pllano/json-db/archive/master.zip",
            "name": "json-db",
            "version": "master",
            "vendor": "pllano"
        }, {
            "namespace": "RouterDb",
            "dir": "/pllano/router-db/src",
            "link": "https://github.com/pllano/router-db/archive/master.zip",
            "name": "router-db",
            "version": "master",
            "vendor": "pllano"
        }, {
            "namespace": "Psr\\Http\\Message",
            "dir": "/psr/http-message/src",
            "link": "https://github.com/php-fig/http-message/archive/master.zip",
            "name": "http-message",
            "version": "master",
            "vendor": "psr"
        }, {
            "namespace": "Psr\\Container",
            "dir": "/psr/container/src",
            "link": "https://github.com/php-fig/container/archive/master.zip",
            "name": "container",
            "version": "master",
            "vendor": "psr"
        }, {
            "namespace": "Psr\\Log",
            "dir": "/psr/log/Psr/Log",
            "link": "https://github.com/php-fig/log/archive/master.zip",
            "name": "log",
            "version": "master",
            "vendor": "psr"
        }, {
            "namespace": "Defuse\\Crypto",
            "dir": "/defuse/php-encryption/src",
            "link": "https://github.com/defuse/php-encryption/archive/master.zip",
            "name": "php-encryption",
            "version": "master",
            "vendor": "defuse"
        }, {
            "namespace": "GuzzleHttp",
            "files": "functions_include.php",
            "dir": "/guzzlehttp/guzzle/src",
            "link": "https://github.com/guzzle/guzzle/archive/master.zip",
            "name": "guzzle",
            "version": "master",
            "vendor": "guzzlehttp"
        }, {
            "namespace": "GuzzleHttp\\Promise",
            "files": "functions_include.php",
            "dir": "/guzzlehttp/promises/src",
            "link": "https://github.com/guzzle/promises/archive/master.zip",
            "name": "promises",
            "version": "master",
            "vendor": "guzzlehttp"
        }, {
            "namespace": "GuzzleHttp\\Psr7",
            "files": "functions_include.php",
            "dir": "/guzzlehttp/psr7/src",
            "link": "https://github.com/guzzle/psr7/archive/master.zip",
            "name": "psr7",
            "version": "master",
            "vendor": "guzzlehttp"
        }, {
            "namespace": "Twig",
            "dir": "/twig/Twig/lib/Twig",
            "link": "https://github.com/twigphp/Twig/archive/master.zip",
            "name": "Twig",
            "version": "master",
            "vendor": "twig",
            "autoloading": "psr-0",
            "replace_name": "Twig"
        }, {
            "namespace": "Slim",
            "dir": "/slim/Slim/Slim",
            "link": "https://github.com/slimphp/Slim/archive/3.x.zip",
            "name": "Slim",
            "version": "3.x",
            "vendor": "slim"
        }
    ]
}
```

<a name="feedback"></a>
## Поддержка, обратная связь, новости
Общайтесь с нами через почту open.source@pllano.com

Если вы нашли баг в работе AutoRequire загляните в
[issues](https://github.com/pllano/auto-require/issues), возможно, про него мы уже знаем и
чиним. Если нет, лучше всего сообщить о нём там. Там же вы можете оставлять свои
пожелания и предложения.

За новостями вы можете следить по
[коммитам](https://github.com/pllano/auto-require/commits/master) в этом репозитории.
[RSS](https://github.com/pllano/auto-require/commits/master.atom).

Лицензия
-------

The MIT License (MIT). Please see [LICENSE](https://github.com/pllano/auto-require/edit/master/LICENSE) for more information.
