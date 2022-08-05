<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Editora\Generator\FileManager;
use App\Editora\Generator\StubManager;
use App\Editora\Generator\Generator;
use Str;

class EditoraResourceMakeCommand extends Command
{
    protected $signature = 'make:editora-resource';
    protected $description = 'Create a new editora resource class';

    protected $classResourceName='EditoraPageResource';
    protected $className='EditoraPage';
    protected $classSlug='editora-page';
    protected $classID=22;
    protected $classTag='Page';

    private function removeOldFiles()
    {
        $path=app_path().config('editora-generator.resourcesPathSuffix');
        FileManager::removeOldFiles($path, 'Editora');
        $path=app_path().config('editora-generator.modelsPathSuffix');
        FileManager::removeOldFiles($path);
    }

    public function handle()
    {
        $this->removeOldFiles();

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
        ->save();

        echo ("Editora structure created successfully.\n");
    }
}
