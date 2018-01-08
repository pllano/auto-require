# AutoRequire
Автоматическая загрузка пакетов согласно PSR4 если autoload.php от Composer недоступен
## Использование
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
if (!file_exists(__DIR__ . '/../vendor/autoload.php')){
    require __DIR__ . '/../vendor/autoload.php';
} else {
    // Если autoload.php не найден, подключаем автозагрузчик пакетов
    // Указываем путь к папке vendor
    $vendor = __DIR__ . '/vendor';
    // Указываем путь к auto_require.json
    $json = __DIR__ . '/vendor/auto_require.json';
    // Запускаем проверку или загрузку пакетов
    $load = $require->run($vendor, $json);
    if (count($load) >= 1) {
        foreach($load as $value)
        {
            if (isset($value["files"]) && isset($value["dir"])) {
                // Подключаем файл если этого требует пакет
                require $vendor.''.$value["dir"].'/'.$value["files"];
            }
            if (isset($value["namespace"]) && isset($value["dir"])) {
                // Регистрируем базовый каталог и префикс пространства имен
                // register the base directories for the namespace prefix
                $require->addNamespace($value["namespace"], $vendor.''.$value["dir"]);
            }
        }
    }
}
```
Подключаем локальные пакеты
```php
$require->addNamespace('ApiShop', __DIR__ . '/app/classes');
```
## auto_require.json
- `namespace` - Пространство имен
- `dir` - Директория в которой после распаковки архива будут файлы пакета
- `link` - Прямая ссылка на zip архив пакета
- `name` - Название директории пакета
- `vendor` - Название директории вендора
- `version` - Версия пакета
```json
{
    "require": [
        {
            "namespace": "Vendor\\NameClass",
            "dir": "/vendor/name-class/src",
            "link": "https://github.com/vendor/name-class/archive/1.0.1.zip",
            "name": "name-class",
            "version": "1.0.1",
            "vendor": "vendor"
        }
    ]
}
```
https://raw.githubusercontent.com/pllano/auto-require/master/auto_require.json
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
