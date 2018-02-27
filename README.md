# AutoRequire
## Autoload to PSR-0 and PSR-4 standards without Composer
```php
define("BASE_PATH", dirname(__FILE__));
$vendor_dir = '';

// Looking for the path to the vendor folder
if (file_exists(BASE_PATH . '/vendor')) {
    $vendor_dir = BASE_PATH . '/vendor';
} elseif (BASE_PATH . '/../vendor') {
    $vendor_dir = BASE_PATH . '/../vendor';
}

// Specify the path to the file AutoRequire
$autoRequire = $vendor_dir.'/AutoRequire.php';
// Specify the path to the file auto_require.json
$auto_require = $vendor_dir.'/auto_require.json';
 
if (file_exists($autoRequire) && file_exists($auto_require)) {

    // Connect \Pllano\AutoRequire\Autoloader
    require $autoRequire;
    // instantiate the loader
    $require = new \Pllano\AutoRequire\Autoloader();
    // Start AutoRequire\Autoloader
    $require->run($vendor_dir, $auto_require);
    
}
```
The same with the minimum code
```php
// Connect the AutoRequire file
require __DIR__ . '/../vendor/AutoRequire.php';
// Start Autoloader
(new \AutoRequire\Autoloader)->run(__DIR__ . '/../vendor', __DIR__ . '/../vendor/auto_require.json');
```
## auto_require.json
- `namespace` - Namespace
- `files` - File name
- `dir` - Директория в которой после распаковки архива будут файлы пакета
- `link` - Direct link to the zip package archive
- `name` - Directory name package
- `vendor` - Directory name vendor
- `version` - Package version
## Examples
### Your Class
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
### Connect file
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
### Connect package
```json
{
    "require": [
        {
            "namespace": "VendorName\\ClassName",
            "dir": "/vendor-name/class-name/src",
            "link": "https://github.com/vendor-name/class-name/archive/1.0.1.zip",
            "git": "",
            "name": "class-name",
            "version": "1.0.1",
            "vendor": "vendor-name",
            "state": 1,
            "system_package": 1,
            "settings": {
                "debug": 0
            }
        }
    ]
}
```
### Connect Slim 4
```json
"slim.slim": {
    "namespace": "Slim",
    "dir": "\/slim\/Slim\/Slim",
    "link": "https:\/\/github.com\/slimphp\/Slim\/archive\/4.x.zip",
    "git": "https:\/\/github.com\/slimphp\/Slim",
    "name": "Slim",
    "version": "4.x",
    "vendor": "slim",
    "state": 1,
    "system_package": 1,
    "settings": {
        "debug": 0,
        "displayErrorDetails": 0,
        "addContentLengthHeader": 0,
        "determineRouteBeforeAppMiddleware": 1
    }
		}
```
<a name="feedback"></a>
## Support, feedback, news
Contact: open.source@pllano.com

- [issues](https://github.com/pllano/auto-require/issues) 
- [Commits](https://github.com/pllano/auto-require/commits/master) 
- [RSS](https://github.com/pllano/auto-require/commits/master.atom)

License
-------
The MIT License (MIT). Please see [LICENSE](https://github.com/pllano/auto-require/blob/master/LICENSE) for more information.
