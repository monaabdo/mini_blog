<?php 
declare(strict_types=1);
namespace System\View;

use System\Application;

class ViewFactory
{
    public function __construct(private Application $app)
    {
        throw new \Exception('Not implemented');
    }
    public function render(string $viewPath , array $data =[])
    {
        return new View($this->app->file , $viewPath , $data);
    }
}