<?php

namespace App\Editora\Models\Sushi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Editora\Models\Sushi\ClassModel;
use App\Editora\Models\InstanceModel as UnderlyingInstanceModel;
use App\Editora\Models\ValueModel;
use DB;

class InstanceModel extends Model
{
  protected $fillable=['id'
  , 'class_id'
  , 'metadata_internal_name'
  , 'metadata_status'
  , 'metadata_publishing_begins'
  , 'metadata_publishing_ends'
  ];

  protected $reserved=['id'
  ,'class_id'
  ,'metadata_internal_name'
  ,'metadata_status'
  ,'metadata_publishing_begins'
  ,'metadata_publishing_ends'
  ];

  protected $classFilter=null;

  use \Sushi\Sushi;
  use HasFactory;

  public function getInstanceMetaData ($row): Array
  {
    $result=[];
    $result['id']=$row->id;
    $result['class_id']=$row->class_id;
    $result['metadata_internal_name']=$row->key_fields;
    $result['metadata_status']=$row->status;
    $result['metadata_publishing_begins']=$row->publishing_begins;
    $result['metadata_publishing_ends']=$row->publishing_ends;

    return $result;
  }

  public function getFullInstanceData ($row): Array
  {
    return array_merge (
      $this->getInstanceMetaData($row)
    , $this->getInstanceData($row->class_id, $row->id)
    );
  }

  public function getRows ()
  {
    $result=[];
    $conditions='';

    if (!$this->classFilter)
    {
      $rows=DB::select("select * from omp_instances");
    }
    else
    {
      $rows=DB::select("select * from omp_instances where class_id=?", [$this->classFilter]);
    }
    
    if ($rows)
    {
      foreach ($rows as $row)
      {
        $result[]=$this->getFullInstanceData($row);
      }  
    }
    return $result;
  }

  public function save(array $options=[])
  {
    $instance=UnderlyingInstanceModel::find($this->id);
    $instance->id=$this->id;
    $instance->status=$this->metadata_status;
    $instance->key_fields=$this->metadata_internal_name;
    $instance->publishing_begins=$this->metadata_publishing_begins;
    $instance->publishing_ends=$this->metadata_publishing_ends;
    $instance->save();
    $this->saveValues($options);
  }

  public function saveValues (array $options=[])
  {
    ValueModel::where('inst_id', $this->id)->delete();
    foreach ($this->attributes as $attribute_name=>$val)
    {
      if (!in_array($attribute_name, $this->reserved))
      {
        if ($val!=null)
        {
          $atri_id=$this->getAtriIDFromName($attribute_name);
          ValueModel::create(['inst_id'=>$this->id, 'atri_id'=>$atri_id, 'text_val'=>$val]);
        }
      }
    }
  }

  public function getAtriIDFromName (string $attribute_name)
  {
    $result=DB::selectOne("
    select a.id
    from omp_attributes a
    where 1=1
    and a.name=?
    ", [$attribute_name]);
    return $result->id;
  }

  public function getInstanceAttributes () {
    $result=[];
    if ($this->classFilter)
    {
      $result=DB::select("
      select a.id, a.name, a.tag, a.caption, a.type, a.lookup_id, a.language, ca.mandatory 
      from omp_class_attributes ca
      , omp_attributes a
      where 1=1
      and ca.atri_id is not null
      and ca.class_id=".$this->classFilter."
      and ca.atri_id=a.id
      and a.id>1
      order by ca.tab_id, ca.fila, ca.columna
      ");
    }
    return $result;
  }

  public function getMergeFillable (): array
  {
    $result=[];

    if ($this->classFilter)
    {
      $attributes=$this->getInstanceAttributes();
      if ($attributes)
      {
        foreach ($attributes as $attribute)
        {
          $result[]=$attribute->name;
        }  
      }
    }
    return $result;    
  }


  public function getInstanceData (int $class_id, int $id): array
  {
    $result=[];

    if ($this->classFilter)
    {
      $attributes=$this->getInstanceAttributes();
      if ($attributes)
      {
        foreach ($attributes as $attribute)
        {
          $type='t';
          if ($attribute->type=='D')
          {
            $type='d';
          }
          $result[$attribute->name]=$this->getAttributeValueOrNull($id, $attribute->id, $type);
        }  
      }
    }
    return $result;
  }

  public function getAttributeValueOrNull (int $id, int $atri_id, string $type='t')
  {
    $row=DB::selectOne("
    select v.text_val, v.date_val
    from omp_values v 
    where v.inst_id=$id
    and v.atri_id=$atri_id
    ");
    if ($row)
    {
      if ($type=='t')
      {
        return $row->text_val;
      }
      else
      {
        return $row->date_val;
      }
    }
    return null;
  }


  public function class()
  {
    return $this->belongsTo(ClassModel::class);
  }

}