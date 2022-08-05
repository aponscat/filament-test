<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Editora\Generator\FilamentEditoraClassGenerator;
use Str;
use DB;

class EditoraMakeCommand extends Command
{
    protected $signature = 'make:editora';
    protected $description = 'Create a new Editora Backoffice';

    public function handle()
    {
        $classes=DB::select("
        select c.*, g.caption group_name
        from omp_classes c
        , omp_class_groups g
        where c.grp_id=g.id
        order by g.ordering, grp_order");
        foreach ($classes as $class)
        {
            $id=$class->id;
            $tag=Str::of($class->name_en)->camel()->ucfirst();
            $groupName=Str::of($class->group_name)->camel()->ucfirst();
            $groupOrder=$class->grp_order;
            //echo "$id $tag $groupName $groupOrder\n";
            (new FilamentEditoraClassGenerator($id, $tag, $groupName, $groupOrder))->generate();
        }

        echo ("Editora structure created successfully.\n");
    }


  
}
