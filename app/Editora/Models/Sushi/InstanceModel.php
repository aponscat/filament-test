<?php

namespace App\Editora\Models\Sushi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Editora\Models\Sushi\ClassModel;
use App\Editora\Models\InstanceModel as UnderlyingInstanceModel;
use DB;

class InstanceModel extends Model
{

  protected $fillable=['metadata_id'
  , 'class_id'
  , 'metadata_internal_name'
  , 'metadata_status'
  , 'metadata_publishing_begins'
  , 'metadata_publishing_ends'];

  use \Sushi\Sushi;
  use HasFactory;

  public function getInstanceMetaData ($row): Array
  {
    $result=[];
    $result['metadata_id']=$row->id;
    $result['metadata_internal_name']=$row->key_fields;
    $result['metadata_status']=$row->status;
    $result['metadata_publishing_begins']=$row->publishing_begins;
    $result['metadata_publishing_ends']=$row->publishing_ends;
    $result['class_id']=$row->class_id;

    return $result;
  }

  public function getRows ()
  {
    $result=[];
    $rows=DB::select("select * from omp_instances");
    foreach ($rows as $row)
    {
      $result[]=$this->getInstanceMetaData($row);
    }
    return $result;
  }

  public function scopeOfClass ($query)
  {
    return $query;
  }

  public function class()
  {
    return $this->belongsTo(ClassModel::class);
  }

  public function save(array $options=[])
  {
    $instance=UnderlyingInstanceModel::find($this->metadata_id);
    $instance->id=$this->metadata_id;
    $instance->status=$this->metadata_status;
    $instance->key_fields=$this->metadata_internal_name;
    $instance->publishing_begins=$this->metadata_publishing_begins;
    $instance->publishing_ends=$this->metadata_publishing_ends;
    $instance->save();

  }

}