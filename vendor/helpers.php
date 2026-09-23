<?php 
declare(strict_types=1);
use System\Application;
if(! function_exists('pre'))
{
    function pre($value)
    {
            echo "<pre>".print_r($value)."</pre>";
    }
}
if(! function_exists('array_get'))
{
    function array_get(array $array , mixed $key, mixed $default=null)
    {
        return isset($array[$key])? $array[$key] : $default;
    }
}
if(! function_exists('_e'))
{
    function _e(string $value)
    {
        return htmlspecialchars($value);
    }
}
if(! function_exists('assets'))
{
    function exists($path)
    {
        $app = Application::getInstance();
        return $app->url->link('public/'.$path);
    }
}