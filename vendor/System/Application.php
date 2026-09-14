<?php 
declare(strict_types=1);
namespace System;
use System\File;
class Application{
    private array $container =[];
    public function __construct(File $file)
    {
        $this->share('file',$file);
        $this->registerClasses();
        $this->loadHelpers();
    }
    public function run()
    {
        $this->session->start();
    }
    public function share(string $key , mixed $value): void
    {
        $this->container[$key] = $value;
    }
    private function registerClasses()
    {
        spl_autoload_register([$this,'loadClasses']);
    }
    public function loadClasses(string $className): mixed
    {
       
        if(strpos($className , 'App') === 0)
        {
            $fileName = $this->file->to($className.'.php');
        }else{
            $fileName = $this->file->toVendor($className.'.php');
        }
        if($this->file->exists_file())
        {
            $this->file->require_file($fileName);
        }
    }
    public function loadHelpers():void
    {
        $this->file->require_file($this->file->toVendor('helpers.php'));
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
    public function __get(string $key): mixed
    {
        return $this->getFile($key);
    }
    public function isSharing(string $key):bool
    {
        return isset($this->container[$key]);
    }
    private function isCoreAlias($alias)
    {
        $coreClass = $this->coreClass();
        return isset($coreClass[$alias]);
    }
    private function createNewObject($class)
    {
        $coreClasses = $this->coreClasses();
        $object = $coreClasses[$class];
        return new $object($this);
    }
    private function coreClasses():array
    {
        return [

        ];
    }
}