<?php

namespace App\Editora\Generator;
use Str;

class StubManager
{
  private $stub='';
  private $stubName;
  private $transformations=[];

  public function __construct ($stubName)
  {
    $this->stubName=$stubName;
    $this->stub = FileManager::get($stubName);
  }

  public function replace ($variable, $value)
  {
    //echo "*** Replacing $variable to $value in $this->stubName\n";
    $this->stub=str_replace(["{{ $variable }}", "{{$variable}}"], $value, $this->stub);
    //echo $this->stub."\n";
    return $this;
  }

  public function get(): string
  {
    return $this->stub;
  }
}