<?php

namespace App\Editora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstanceModel extends Model
{
  protected $table='omp_instances';
  protected $fillable=['key_fields', 'status', 'publishing_begins', 'publishing_ends'];
  const CREATED_AT = 'creation_date';
  const UPDATED_AT = 'update_date'; 

  use HasFactory;

  public function scopeOfClass ($query, $classID)
  {
    return $query->where('class_id', $classID);
  }

}