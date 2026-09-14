<?php 
declare(strict_types=1);
if(! function_exists('pre'))
    {
        function pre($value)
        {
            echo "<pre>".print_r($value)."</pre>";
        }
    }