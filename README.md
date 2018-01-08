# auto-require
AutoRequire - Автоматическая загрузка пакетов согласно PSR4 если autoload.php от Composer недоступен
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
    // Запускаем проверку или загрузку пакетов
    $load = $require->run($vendor, $json);
    if (count($load) >= 1) {
        foreach($load as $value)
        {
            // Регистрируем базовые каталоги и префиксы пространства имен
            $require->addNamespace($value["namespace"], $vendor.''.$value["dir"]);
        }
    }
}
```
## auto_require.json
- `namespace` - Пространство имен
- `dir` - Директория в которой после распаковки архива будут файлы пакета
- `link` - Прямая ссылка на zip архив пакета
- `name` - Название
- `version` - Версия пакета
- `vendor` - Вендор
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
            "dir": "/guzzlehttp/guzzle/src",
            "link": "https://github.com/guzzle/guzzle/archive/6.3.0.zip",
            "name": "guzzle",
            "version": "6.3.0",
            "vendor": "guzzlehttp"
        }
    ]
    
}
```
## public function run
```php
    // Ссылка на резервный файл auto_require.json
    protected $file_get = "https://raw.githubusercontent.com/pllano/auto-require/master/auto_require.json";
 
    public function run($dir = null, $json = null, $file_get = null)
    {
        if ($dir != null && $json != null) {
            
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            if ($file_get != null) {
                $this->file_get = $file_get;
            }
            if (!file_exists($json) && $this->file_get != null) {
                file_put_contents($json, file_get_contents($this->file_get));
            }
 
            $require = array();
 
            // Открываем файл json с параметрами класов
            $data = json_decode(file_get_contents($json), true);
 
             // Перебираем массив
            foreach($data["require"] as $value)
            {
                // Если папки класса нет
                if (!file_exists($dir."/".$value["vendor"].'/'.$value["name"])) {
                    // Если есть ссылка скачиваем архив
                    if (isset($value["link"])) {
 
                        file_put_contents($dir.'/'.$value["name"].".zip", file_get_contents($value["link"]));
 
                        $zip = new \ZipArchive;
                        $res = $zip->open($dir.'/'.$value["name"].".zip");
                        if ($res === TRUE) {
                            //$zip->renameName('currentname.txt','newname.txt');
                            $zip->extractTo($dir."/".$value["vendor"]);
                            $zip->close();
 
                            rename($dir."/".$value["vendor"].'/'.$value["name"]."-".$value["version"],
                                $dir."/".$value["vendor"].'/'.$value["name"]);
                            unlink($dir.'/'.$value["name"].".zip");
 
                        } else {
                            // echo 'failed';
                        }
                    }
                }
 
                // Возвращаем массив с параметрами
                $require[] = $value;
 
            }
 
            return $require;
 
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
