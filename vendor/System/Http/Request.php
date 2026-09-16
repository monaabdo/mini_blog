<?php 
declare(strict_types=1);

namespace System\Http;

class Request{
    private string $url;
    private string $baseUrl;
    private array $files = [];
    public function prepareUrl(): void
    {
        $script = dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');
        if(strpos($requestUri,'?') !== false)
        {
            list($requestUri, $queryString) = explode('?', $requestUri);
        }
        $this->url = rtrim(preg_replace('#^'.preg_quote($script, '#').'#','',$requestUri),'/');
        if(! $this->url)
        {
            $this->url = '/';
        }
        $this->baseUrl = $this->server('REQUEST_SCHEME').'://'.$this->server('HTTP_HOST').$script.'/';
    }
    public function get(string $key , mixed $value):mixed
    {
        $value = array_get($_GET,$key,$value);
        if(is_array($value))
        {
            $value = array_filter($value);
        }
        else{
            $value = trim($value);
        }
        return $value;
    }
    public function post(string $key, mixed $default = null):mixed
    {
        // just remove any white space if there is a value
        $value = array_get($_POST, $key, $default);
        if (is_array($value)) {
            $value = array_filter($value);
        } else {
            $value = trim($value);
        }

        return $value;
    }
    public function setPost(string $key , mixed $value): void
    {
        $_POST[$key] = $value;
    }
    public function server(string $key, mixed $default = null): mixed
    {
        return array_get($_SERVER, $key, $default);
    }
    public function method(): string
    {
        return $this->server('REQUEST_METHOD');
    }
    public function referer():string
    {
        return $this->server('HTTP_REFERER');
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }
    public function url():string
    {
        return $this->url;
    }
}