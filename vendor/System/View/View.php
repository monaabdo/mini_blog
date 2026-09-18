<?php 
declare(strict_types=1);
namespace System\View;

use Override;
use System\File;

class View implements ViewInterFace{
    private string $output;
    public function __construct(private File $file, private string $viewPath, private array $data = [])
    {
        $this->preparePath($viewPath);
    }
    private function preparePath(string $viewPath):void
    {
        $filePath = 'App/Views/'.$viewPath.'.php';
        $this->viewPath = $this->file->to($filePath);
        if(! $this->viewExists($filePath))
            {
                die($viewPath." This File Not Exists");
            }
    }
    private function viewExists($viewPath)
    {
        return $this->file->exists_file($viewPath);
    }
    #[Override]
    public function getOutput()
    {
        if(is_null($this->output))
        {
            ob_start();
            extract($this->data);
            require $this->viewPath;
            $this->output = ob_get_clean();
        }
        return $this->output;
    }
    #[Override]
    public function __tostring()
    {
        return $this->getOutput();
    }
}