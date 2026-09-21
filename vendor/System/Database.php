<?php 
declare(strict_types=1);
namespace System;
use System\Application;
use PDO;
use PDOException;

class Database
{
    private static $connection;
    private string $table;
    private array $data;
    private array $bindings =[];
    private int $lastID;
    private array $wheres = [];
    private array $selects = [];
    private array $joins = [];
    private int $limit;
    private int $offset;
    private array $orderBy = [];    
    private array $havings = [];
    private array $groupBy =[];
    private int $rows = 0;
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
            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$connection->exec('SET NAMES utf8');
        }catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }
    public function connection()
    {
        return static::$connection;
    }
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }
    public function from(string $table)
    {
        return $this->setTable($table);
    }
    public function data(mixed $key , mixed $value)
    {
        if(is_array($key))
        {
            $this->data = array_merge($this->data , $key);
            $this->addToBindings($key);
        }
        else{
            $this->data[$key] = $value;
            $this->addToBindings($key);
        }
        
        return $this;
    }
    public function insert(string $table ='')
    {
        if($table) $this->table($table);
           
        $sql = "INSERT INTO $this->table SET";
        $sql .= $this->setFields();
        $this->query($sql , $this->bindings);
        $this->lastID = $this->connection()->lastInsertId();
        $this->reset();
        return $this;
    }
    public function update($table = '')
    {
        if($table) $this->table($table);
           
        $sql = "UPDATE $this->table SET";
        $sql .= $this->setFields();

        if($this->wheres)
        {
            $sql .=" WHERE ".implode(" ",$this->wheres);
        }

        $this->query($sql , $this->bindings);
        $this->reset();
        return $this;
    }
    private function addToBindings(mixed $value)
    {
        if(is_array($value))
        {
            $this->bindings = array_merge($this->bindings , array_values($value));
        }
        else{
            $this->bindings[] = _e($value);
        }
       
    }
    public function lastID():int
    {
        return $this->lastID;
    }
    public function query(...$bindings)
    {
        $sql = array_shift($bindings);
        if(count($bindings) == 1 && is_array($bindings[0]))
        {
            $bindings = $bindings[0];
        }
        try{
            $query = $this->connection()->prepare($sql);
            foreach($bindings as $key => $value)
            {
                $query->bindValue($key+1, $value);
            }
            $query->execute();
            return $query;
        }catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }
    private function table(string $table)
    {
        $this->table = $table;
    }
    public function where(...$bindings)
    {
        $sql = array_shift($bindings);
        $this->addToBindings($bindings);
        $this->wheres[] = $sql;
        return $this;
    }
    private function setFields():string
    {
        $sql = '';
        foreach($this->data as $key => $value)
        {
            $sql .="`$key ` =? , ";
            $this->addToBindings($value);
        }
        $sql = rtrim($sql , ", ");
        return $sql;
    }
    public function select( string $select)
    {
        $this->selects[] = $select;
        return $this;
    }
    public function join(string $join)
    {
        $this->joins[] = $join;
        return $this;
    }
    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }
    public function fetch($table = '')
    {
        if($table) $this->table($table);
        $sql = $this->fetchStatement();
        $result= $this->query($sql,$this->bindings)->fetch();
        $this->reset();
        return $result;
    }
    public function fetchAll($table = '')
    {
        if($table) $this->table($table);
        $sql = $this->fetchStatement();
        $results= $this->query($sql,$this->bindings)->fetchAll();
        $this->rows = $results->rowCount();
        $this->reset();
        return $results;

    }
    public function rows()
    {
        return $this->rows;
    }
    public function delete(string $table = '')
    {
        if($table) $this->table($table);
        $sql = "DELETE FROM $this->table ";
        if($this->wheres)
        {
            $sql .=" WHERE ".implode(" ",$this->wheres);
        }

        $this->query($sql , $this->bindings);
        $this->reset();
        return $this;

    }
    public function orderBy($column , $sort = "ASC")
    {
        $this->orderBy = [$column,$sort];
        return $this;
    }
    private function fetchStatement():string
    {
        $sql = "SELECT ";
        if($this->selects)
        {
            $sql .=implode(",",$this->selects);
        }
        else
        {
            $sql .="*";
        }
        $sql .=" FROM $this->table ";
        if($this->joins)
        {
            $sql .= implode(" ", $this->joins);
        }
        if($this->wheres)
        {
            $sql .=" WHERE ".implode(" ",$this->wheres);
        }
        if($this->limit)
        {
            $sql .=" Limit $this->limit";
        }
        if($this->offset)
        {
            $sql .=" OFFSET $this->offset";
        }
        if($this->orderBy)
        {
            $sql .=" orderBy ".implode(" ",$this->orderBy);
        }
        return $sql;
    }
    private function reset()
    {
        $this->limit = null;
        $this->table = null;
        $this->offset = null;
        $this->data = [];
        $this->joins = [];
        $this->wheres = [];
        $this->orderBy = [];
        $this->havings = [];
        $this->groupBy = [];
        $this->selects = [];
        $this->bindings = [];
    }
}