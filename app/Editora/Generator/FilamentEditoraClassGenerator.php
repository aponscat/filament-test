<?php

namespace App\Editora\Generator;

use Str;

class FilamentEditoraClassGenerator 
{

  public function __construct (
    private int $classID
  , private string $classTag
  , private string $groupName
  , private int $groupOrder
  , private array $schema)
  {
    $this->classTitle=$classTag;
    $this->className="Editora$classTag";
    $this->classResourceName="Editora$classTag"."Resource";
    $this->classSlug="editora-".strtolower($classTag);
  }

  public static function removeOldFiles ()
  {
    $path=app_path().config('editora-generator.resourcesPathSuffix');
    FileManager::removeOldFiles($path, 'Editora');
    $path=app_path().config('editora-generator.modelsPathSuffix');
    FileManager::removeOldFiles($path);
  }

  public function generate ()
  {
    // Model
    (new Generator (
      folder: app_path().config('editora-generator.modelsPathSuffix')
      , stubName: app_path().config('editora-generator.modelStub')
      , fileName: $this->className."Model.php"
      ))->replace('$classID', $this->classID)
      ->replace('$className', $this->className.'Model')
      ->save();

      // Create
      (new Generator (
      folder: app_path().config('editora-generator.resourcesPathSuffix').$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR
      , stubName: app_path().config('editora-generator.createStub')
      , fileName: 'Create'.$this->className.'.php'
      ))
      ->replace('$resourceName', $this->classResourceName)
      ->replace('$className', 'Create'.$this->className)
      ->save();

      // View
      (new Generator (
      folder: app_path().config('editora-generator.resourcesPathSuffix').$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR
      , stubName: app_path().config('editora-generator.viewStub')
      , fileName: 'View'.$this->className.'.php'
      ))
      ->replace('$resourceName', $this->classResourceName)
      ->replace('$className', 'View'.$this->className)
      ->save();
        
      // Edit
      (new Generator (
      folder: app_path().config('editora-generator.resourcesPathSuffix').$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR
      , stubName: app_path().config('editora-generator.editStub')
      , fileName: 'Edit'.$this->className.'.php'
      ))
      ->replace('$resourceName', $this->classResourceName)
      ->replace('$className', 'Edit'.$this->className)
      ->save();

      // List
      (new Generator (
      folder: app_path().config('editora-generator.resourcesPathSuffix').$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR
      , stubName: app_path().config('editora-generator.listStub')
      , fileName: 'List'.Str::of($this->className)->plural().'.php'
      ))
      ->replace('$resourceName', $this->classResourceName)
      ->replace('$className', 'List'.Str::of($this->className)->plural())
      ->save();

      // Resource
      (new Generator (
      folder: app_path().config('editora-generator.resourcesPathSuffix')
      , stubName: app_path().config('editora-generator.resourceStub')
      , fileName: $this->classResourceName.'.php'
      ))
      ->replace('$classID', $this->classID)
      ->replace('$className', $this->classResourceName)
      ->replace('$modelName', $this->className.'Model')
      ->replace('$crudClassName', $this->className)
      ->replace('$classSlug', $this->classSlug)
      ->replace('$pluralCrudClassName', Str::of($this->className)->plural())
      ->replace('$classTag', Str::of($this->classTag)->camel())
      ->replace('$classTitle', $this->classTitle)
      ->replace('$groupName', $this->groupName)
      ->replace('$groupOrder', $this->groupOrder)
      ->replace('$schema', $this->formSchemaGenerator())
      ->save();
  }

  private function formSchemaGenerator ()
  {
    $result='';
    $i=0;
    $result.='Forms\Components\Tabs::make("Data")->tabs([';
    foreach ($this->schema['form'] as $tab_name => $attributes)
    {
      $result.='Forms\Components\Tabs\Tab::make("'.$tab_name.'")->schema(
        [
          '.implode(",\n\t\t\t", $attributes).'
        ],
      ),';

    }
    $result.='])';
    return $result;
  }
    
}