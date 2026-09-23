<?php 
declare(strict_types=1);
namespace System;
use System\Application;
class Cookie{
    public function __construct(private Application $app)
    {

    }
    public function set(string $key , mixed $value,int $houres =1800)
    {
        setcookie($key, $value , time() + $houres * 3600,'','',false,true);
    }
    public function get(string $key, mixed $defualt = null):mixed
    {
        return array_get($_COOKIE, $key , $defualt);
    }
    public function has(string $key): bool
    {
        return array_key_exists($key,$_COOKIE);
    }
    public function remove(string $key): void
    {
        setcookie($key,null,-1);
        unset($_COOKIE[$key]);
    }
    public function all():array
    {
        return $_COOKIE;
    }
    public function destroy():void
    {
        foreach(array_keys($this->all()) as $key)
        {
            $this->remove($key);
        }
        unset($_COOKIE);
    }
}