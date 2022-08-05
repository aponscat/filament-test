<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Editora\Generator\FilamentEditoraClassGenerator;
use Str;
use DB;

class EditoraCleanCommand extends Command
{
    protected $signature = 'clean:editora';
    protected $description = 'Remove files of Editora Backoffice';

    public function handle()
    {
        FilamentEditoraClassGenerator::removeOldFiles();
        echo ("Editora structure deleted successfully.\n");
    }


  
}
