<?php 
declare(strict_types=1);
namespace System;
use System\File;
class Application{
    private array $container =[];
    private static $instance;
    private function __construct(File $file)
    {
        $this->share('file',$file);
        $this->registerClasses();
        $this->loadHelpers();

    }
    public static function getInstance($file = null)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static($file);
        }
        return static::$instance;
    }
    public function run()
    {
        $this->session->start();
        $this->request->prepareUrl();
        $this->file->require_file('App/index.php');
        $this->route->getPrepareRoute();
        list($controller,$method,$args) = $this->route->getPrepareRoute();
    }
    public function share(string $key , mixed $value): void
    {
        $this->container[$key] = $value;
    }
    private function registerClasses()
    {
        spl_autoload_register([$this,'loadClasses']);
    }
    public function loadClasses(string $className): void
    {
       
        if(strpos($className , 'App') === 0)
        {
            $fileName = $className.'.php';
        }else{
            $fileName = 'vendor/'.$className.'.php';
        }
        if($this->file->exists_file($fileName))
        {
            $this->file->require_file($fileName);
        }
    }
    public function loadHelpers():void
    {
        $this->file->require_file('vendor/helpers.php');
    }
    public function getFile(string $key): mixed
    {
        if(! $this->isSharing($key))
        {
            if($this->isCoreAlias($key))
            {
                $this->share($key,$this->createNewObject($key));
            }else{
                die($key." Not Found");
            }
        }
        return $this->container[$key];
    }
    public function isSharing(string $key):bool
    {
        return isset($this->container[$key]);
    }
    private function isCoreAlias(string $alias):bool
    {
        $coreClass = $this->coreClasses();
        return isset($coreClass[$alias]);
    }
    private function createNewObject(string $alias)
    {
        $coreClasses = $this->coreClasses();

        $object = $coreClasses[$alias];

        return new $object($this);
    }
    private function coreClasses():array
    {
        return [
            'request'       => 'System\\Http\\Request',
            'response'      => 'System\\Http\\Response',
            'session'       => 'System\\Session',
            'route'         => 'System\\Route',
            'cookie'        => 'System\\Cookie',
            'load'          => 'System\\Loader',
            'html'          => 'System\\Html',
            'db'            => 'System\\Database',
            'view'          => 'System\\View\\ViewFactory',
            'url'           => 'System\\Url',
            'validator'     => 'System\\Validation',
            'pagination'    => 'System\\Pagination',
       ];
    }
    public function __get(string $key): mixed
    {
        return $this->getFile($key);
    }
}