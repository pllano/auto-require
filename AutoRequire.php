<?php
/**
 * This file is part of the AutoRequire
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/pllano/auto-require
 * @version 1.0.1
 * @package pllano/auto-require
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * An example of a general-purpose implementation that includes the optional
 * functionality of allowing multiple base directories for a single namespace
 * prefix.
 *
 * Given a foo-bar package of classes in the file system at the following
 * paths ...
 *
 *     /path/to/packages/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 *
 * ... add the path to the class files for the \Foo\Bar\ namespace prefix
 * as follows:
 *
 *      <?php
 *      // instantiate the loader
 *      $loader = new \Example\Psr4AutoloaderClass;
 *
 *      // register the autoloader
 *      $loader->register();
 *
 *      // register the base directories for the namespace prefix
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\Quux class from /path/to/packages/foo-bar/src/Qux/Quux.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\QuuxTest class from /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */

namespace AutoRequire;

class Autoloader
{
 
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();
 
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
 
    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
 
    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }
 
    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // never found a mapped file
        return false;
    }
 
    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir
                  . str_replace('\\', '/', $relative_class)
                  . '.php';

            // if the mapped file exists, require it
            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
 
