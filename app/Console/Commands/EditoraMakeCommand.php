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
        $add='';
        //$add=' and c.id=22';
        $classes=DB::select("
        select c.*, g.caption group_name
        from omp_classes c
        , omp_class_groups g
        where c.grp_id=g.id
        $add
        order by g.ordering, grp_order");
        foreach ($classes as $class)
        {
            $id=$class->id;
            $tag=Str::of($class->name_en)->camel()->ucfirst();
            $groupName=Str::of($class->group_name)->camel()->ucfirst();
            $groupOrder=$class->grp_order;
            //echo "$id $tag $groupName $groupOrder\n";

            $tabs=DB::select("
            select t.*, count(*) num_attributes
            from omp_class_attributes ca
            , omp_tabs t
            where ca.tab_id=t.id
            and ca.atri_id is not null
            and ca.atri_id >1 
            and ca.class_id=$id
            group by t.id
            order by t.ordering
            ");

            $schema=[];
            $schema['form']=[];
            foreach ($tabs as $tab)
            {
                $tab_id=$tab->id;
                $tab_name=$tab->name.' ('.$tab->num_attributes.')';

                $attributes=DB::select("
                select a.id, a.name, a.tag, a.caption, a.type, a.lookup_id, a.language, ca.mandatory 
                from omp_class_attributes ca
                , omp_attributes a
                where 1=1
                and ca.atri_id is not null
                and ca.class_id=$id
                and ca.atri_id=a.id
                and ca.tab_id=$tab_id
                and a.id>1
                order by ca.tab_id, ca.fila, ca.columna
                ");
                foreach ($attributes as $attribute)
                {
                    $component=$this->mapEditoraTypeToFilamentComponent($attribute);
                    if ($attribute->mandatory=='Y')
                    {
                        $component.='->required()';
                    }
                    $schema['form'][$tab_name][]=$component;
                }
            }


            (new FilamentEditoraClassGenerator($id, $tag, $groupName, $groupOrder, $schema))->generate();
        }

        echo ("Editora structure created successfully.\n");
    }

    protected function mapEditoraTypeToFilamentComponent ($attribute): string
    {
        $extra='';
        $map=['A'=> 'RichEditor'
        , 'C' => 'TextArea'
        , 'D' => 'DateTimePicker' 
        , 'E' => 'TextInput' 
        , 'F' => 'FileUpload'
        , 'I' => 'FileUpload'
        , 'K' => 'TextInput' 
        , 'L' => 'TextInput' 
        , 'N' => 'TextInput'
        , 'O' => 'TextInput'
        , 'T' => 'TextInput'
        , 'U' => 'TextInput' 
        , 'Y' => 'TextInput'
        , 'Z' => 'TextInput'
        ];
        if (isset($map[$attribute->type]))
        {
            $componentType=$map[$attribute->type];
        }
        else
        {
            $componentType='TextInput';
        }

        if ($attribute->type=='I')
        {
            $extra='->image()';
        }
        if ($attribute->type=='U')
        {
            $extra='->url()';
        }

        return 'Forms\\Components\\'.$componentType.'::make("'.$attribute->name.'")->label("'.$attribute->caption.'")'.$extra;
    }


  
}
