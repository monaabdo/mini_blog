<?php
declare(strict_types=1);

use System\Application;
use System\File;

require __DIR__."/vendor/System/File.php";
require __DIR__."/vendor/System/Application.php";

$file = new File(__DIR__);

$app = Application::getInstance($file);

$app->run();




