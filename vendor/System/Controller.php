<?php 
declare(strict_types=1);
namespace System;
use System\Application;
abstract class Controller{
    public function __construct(private Application $app){

    }
    public function __get(string $key):mixed
    {
        return $this->app->getFile($key);
    }
}