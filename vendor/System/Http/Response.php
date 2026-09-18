<?php 
declare(strict_types=1);
namespace System\Http;

use System\Application;

class Response
{
    private array $headers = [];
    private string $content='';
    public function __construct(private Application $app)
    {
        
    }
    public function setOutPut($content)
    {
        $this->content = $content;
    }
    public function setHeader($header , $value)
    {
        $this->headers[$header] = $value;
    }
    public function send()
    {
        $this->sendHeaders();
        $this->sendOutPut();
    }
    private function sendHeaders()
    {
        foreach($this->headers as $header=>$value)
        {
            header($header.':'.$value);
        }
    }
    private function sendOutPut():void
    {
        echo $this->content;
    }

}