<?php 
declare(strict_types = 1);
namespace System;
use System\Application;

class Loader{
    private array $controllers =[];
    private array $models = [];
    public function __construct(private Application $app)
    {
        throw new \Exception('Not implemented');
    }
    public function action(string $controller , string $method , array $args):mixed
    {
        $object = $this->controller($controller);
        return call_user_func([$object,$method],$args);
    }
    public function controller(string $controller) :object
    {
        $controller = $this->getControllerName($controller);
        if(! $this->hasController($controller))
            {
                $this->addController($controller);
            }
            return $this->getController($controller);
    }
    private function hasController(string $controller):bool
    {
        return array_key_exists($controller , $this->controllers);
    }
    private function addController(string $controller):void
    {
        
        $object = new $controller($this->app);
        $this->controllers[$controller] = $object;
    }
    private function getController(string $controller):object
    {
        return $this->controllers[$controller];
    }
    private function getControllerName(string $controller):string
    {
        $controller = "Controller";
        $controller .= "App\\Controllers\\".$controller;
        return str_replace('/','\\',$controller);
    }
    public function model(string $model):object
    {
        $model = $this->getModelName($model);
        if(! $this->hasModel($model))
            {
                $this->addModel($model);
            }
            return $this->getModel($model);
    }
    private function getModelName(string $model):string
    {
        $model = "Model";
        $model .= "App\\Models\\".$model;
        return str_replace('/','\\',$model);
    }
    private function hasModel(string $model):bool
    {
        return array_key_exists($model , $this->models);
    }
    private function addModel(string $model):void
    {
        $object = new $model($this->app);
        $this->models[$model] = $object;
    }
    private function getModel(string $model):object
    {
        return $this->models[$model];
    }
}