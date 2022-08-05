<?php

namespace App\Editora\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstanceModel extends Model
{
  protected $table='omp_instances';
  protected $fillable=['key_fields', 'status', 'publishing_begins', 'publishing_ends'];
  const CREATED_AT = 'creation_date';
  const UPDATED_AT = 'update_date'; 

  use HasFactory;

  public function scopeOfClass ($query)
  {
    return $query;
  }

  public function class()
  {
    return $this->belongsTo(ClassModel::class);
  }
}