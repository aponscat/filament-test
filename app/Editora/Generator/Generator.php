<?php

namespace App\Editora\Generator;
use Str;

class Generator 
{

  private StubManager $stubManager;

  public function __construct (private string $folder, string $stubName, private string $fileName) 
  {
    $this->stubManager= new StubManager($stubName);
  }

  public function replace ($variable, $value): Generator
  {
    $this->stubManager->replace($variable, $value);
    return $this;
  }

  public function save ()
  {
    FileManager::createDiretoryStructure($this->folder);
    FileManager::save($this->folder.$this->fileName, $this->stubManager->get());
    return $this;
  }

}