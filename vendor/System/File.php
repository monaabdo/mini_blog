<?php 
declare(strict_types=1);
namespace System;
class File{
    
    const DS = DIRECTORY_SEPARATOR;
    public function __construct(private string $root)
    {
        
    }
    public function exists_file(string $file): bool
    {
        return file_exists($this->to($file));
    }
    public function require_file(string $file): void
    {
        require $this->to($file);
    }
    public function toVendor(string $path): string
    {
        return $this->to('vendor/'.$path);
    }
    public function to(string $path): string
    {
        return $this->root.static::DS.str_replace(['/','\\'],static::DS,$path);
    }
}