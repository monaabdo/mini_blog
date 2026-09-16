<?php 
declare(strict_types=1);
namespace System;
use System\Application;

class Route{
    private array $routes = [];
    private string $notFound;
    public function __construct(private Application $app)
    {
        
    }
    public function addRoute(string $url, string $action, string $method = 'GET'): void
    {
        $route = [
            'url'           => $url,
            'pattern'       => $this->generatePattern($url),
            'action'        => $this->getAction($action),
            'method'        => $method
        ];
        $this->routes[] = $route;
    }
    private function generatePattern(string $url):string
    {
        $pattern = '#';
        $pattern.=str_replace([':text',':id'],['([a-zA-Z0-9-]+)','(\d+)'],$url);
        $pattern.='$#';
        return $pattern;
    }
    private function getAction(string $url):string
    {
        $action = str_replace('/','\\',$url);
        return strpos($action,'@') !== false ? $action : $action.'@index';
    }
    public function notFound(string $url):void
    {
        $this->notFound = $url;
    }
    public function getPrepareRoute()
    {
        foreach($this->routes as $route)
            {
                if($this->isMatching($route['pattern']))
                {
                    $args = $this->getArgsForm($route['pattern']);
                    list($controller,$method) = explode('@',$route['action']);
                    return [$controller, $method, $args];
                }
            }
            
    }
    private function isMatching(string $pattern):bool
    {
        return preg_match($pattern , $this->app->request->url());
    }
    private function getArgsForm(string $route):array
    {
        preg_match($route , $this->app->request->url(),$matches);  
        array_shift($matches);
        return $matches; 
    }
}
