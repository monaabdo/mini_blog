<?php 
declare(strict_types=1);
namespace System\View;
interface ViewInterFace{
    public function getOutput();
    public function __tostring();
}