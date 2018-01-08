# auto-require
AutoRequire - Автоматическая загрузка (PSR4) пакетов без Composer
## Использование
```php
// Подключаем \AutoRequire\Autoloader
require __DIR__ . '/vendor/AutoRequire.php';
// Создать экземпляр загрузчика
$require = new \AutoRequire\Autoloader;
// Зарегистрировать автозагрузчик
$require->register();
 
// Проверяем наличие autoload.php
if (file_exists(__DIR__ . '/../vendor/autoload.php')){
    require __DIR__ . '/../vendor/autoload.php';
} else {
    // Если autoload.php не найден, подключаем автозагрузчик пакетов
    // Указываем путь к папке vendor
    $vendor = __DIR__ . '/vendor';
    // Указываем путь к auto_require.json
    $json = __DIR__ . '/vendor/auto_require.json';
 
    $load = $require->run($vendor, $json);
    if (count($load) >= 1) {
        foreach($load as $value)
        {
            // Регистрируем базовые каталоги и префиксы пространства имен
            $require->addNamespace($value["class"], __DIR__ . '/vendor'.$value["dir"]);
        }
    }
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
