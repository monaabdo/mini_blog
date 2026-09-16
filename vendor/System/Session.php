<?php 
declare(strict_types=1);
namespace System;
use System\Application;
class Session{
    public function __construct(private Application $app)
    {
        
    }
    public function set(string $key , mixed $value)
    {
        $_SESSION[$key] = $value;
    }
    public function start():void
    {
        ini_set('session.use_only_cookies', '1');

        if (! session_id()) {
            session_start();
        }
    }
    public function get(string $key, mixed $defualt = null):mixed
    {
        return array_get($_SESSION, $key , $defualt);
    }
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    public function pull(string $key):mixed
    {
        $value = $this->get($key);
        $this->remove($key);
        return $value;
    }
    public function all():array
    {
        return $_SESSION;
    }
    public function destroy():void
    {
        session_destroy();
        unset($_SESSION);
    }
}