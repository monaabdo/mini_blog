<?php 
declare(strict_types=1);
namespace System;
use System\Application;
class Url{
    public function __construct(private Application $app)
    {
    }
    
    public function link(string $path):string
    {
        return $this->app->request->baseUrl() . trim($path, '/');
    }

    public function redirectTo(string $path):void
    {
        header('location:' . $this->link($path));

        exit;
    }  
}