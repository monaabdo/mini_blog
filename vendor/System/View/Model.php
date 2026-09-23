<?php 
declare(strict_types=1);
namespace System;
use System\Application;
abstract class Model{
    protected string $table;
    public function __construct(protected Application $app){

    }
    public function __get(string $key):mixed
    {
        return $this->app->getFile($key);
    }
    public function __call(string $method,array $arguments)
    {
        return call_user_func_array([$this->app->db,$method],$arguments);
    }
    public function get($id)
    {
        return $this->where('id=?',$id)->fetch($this->table);
    }
    public function all()
    {
        return $this->fetchAll($this->table);
    }
}