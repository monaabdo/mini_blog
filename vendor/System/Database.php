<?php 
declare(strict_types=1);
namespace System;
use System\Application;
use PDO;
use PDOException;

class Database
{
    private static $connection;
    public function __construct(private Application $app)
    {
        if(! $this->isConnected())
            {
                $this->connect();
            }
    }
    private function isConnected():bool
    {
        return static::$connection instanceof PDO;
    }
    private function connect()
    {
        $connectionData = $this->app->file->require_file('config.php');
        extract($connectionData);
        try{
            static::$connection = new PDO('mysql:'.$server.';dbname='.$dbname,$dbuser,$dbpass);
        }catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }
    public function connection()
    {
        return static::$connection;
    }
}