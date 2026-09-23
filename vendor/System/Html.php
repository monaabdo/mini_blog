<?php 
declare(strict_types=1);
namespace System;
use System\Application;
class Html{
    private string $title;
    private string $description;
    private string $keywords;
    public function __construct(private Application $app)
    {
        throw new \Exception('Not implemented');
    }
    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    
    public function getTitle():string
    {
        return $this->title;
    }

    
    public function setKeywords(string $keywords):void
    {
        $this->keywords = $keywords;
    }

    public function getKeywords():string
    {
        return $this->keywords;
    }

    public function setDecription(string $description):void
    {
        $this->description = $description;
    }
    public function getDescription():string
    {
        return $this->description;
    }
}