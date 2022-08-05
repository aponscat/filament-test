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
        (new Generator (
        folder: app_path().config('editora-generator.modelsPathSuffix')
        , stubName: app_path().config('editora-generator.modelStub')
        , fileName: $this->className."Model.php"
        ))->replace('$classID', $this->classID)
        ->replace('$className', $this->className.'Model')
        ->save();

        $path=app_path().config('editora-generator.resourcesPathSuffix');
        FileManager::createDiretoryStructure($path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR);

        // Create
        $stubName=app_path().config('editora-generator.createStub');
        echo "Saving file ".$path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'Create'.$this->className.".php with stub $stubName\n";
        $stub=(new StubManager($stubName))
          ->replace('$resourceName', $this->classResourceName)
          ->replace('$className', 'Create'.$this->className)
          ->get();
        FileManager::save($path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'Create'.$this->className.".php", $stub);

        // Edit
        $stubName=app_path().config('editora-generator.editStub');
        echo "Saving file ".$path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'Edit'.$this->className.".php with stub $stubName\n";
        $stub=(new StubManager($stubName))
          ->replace('$resourceName', $this->classResourceName)
          ->replace('$className', 'Edit'.$this->className)
          ->get();
        FileManager::save($path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'Edit'.$this->className.".php", $stub);
   
        // List
        $stubName=app_path().config('editora-generator.listStub');
        echo "Saving file ".$path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'List'.Str::of($this->className)->plural().".php with stub $stubName\n";
        $stub=(new StubManager($stubName))
          ->replace('$resourceName', $this->classResourceName)
          ->replace('$className', 'List'.Str::of($this->className)->plural())
          ->get();
        FileManager::save($path.$this->classResourceName.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.'List'.Str::of($this->className)->plural().".php", $stub);
        

        $path=app_path().config('editora-generator.resourcesPathSuffix');
        FileManager::createDiretoryStructure($path);
        $stubName=app_path().config('editora-generator.resourceStub');
        echo "Saving file ".$path.$this->classResourceName.".php with stub $stubName\n";
        $stub=(new StubManager($stubName))
          ->replace('$classID', $this->classID)
          ->replace('$className', $this->classResourceName)
          ->replace('$modelName', $this->className.'Model')
          ->replace('$crudClassName', $this->className)
          ->replace('$classSlug', $this->classSlug)
          ->replace('$pluralCrudClassName', Str::of($this->className)->plural())
          ->replace($stub, '$classTag', Str::of($this->classTag)->camel())
          ->get();

        FileManager::save($path.$this->classResourceName.'.php', $stub);
        echo ("Editora structure created successfully.\n");
    }
}
