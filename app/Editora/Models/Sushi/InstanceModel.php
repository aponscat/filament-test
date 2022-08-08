<?php

namespace App\Editora\Models\Sushi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Editora\Models\Sushi\ClassModel;
use DB;

class InstanceModel extends Model
{

  protected $fillable=['key_fields', 'status', 'publishing_begins', 'publishing_ends'];

  use \Sushi\Sushi;
  use HasFactory;
/*
  protected $rows = [
      [
          'key_fields' => 'NY',
          'status' => 'O',
          'publishing_begins' => '2022-01-01',
          'publishing_ends' => null,
          'class_id' => 22,
      ],
      [
          'key_fields' => 'CA',
          'status' => 'P',
          'publishing_begins' => '2022-01-02',
          'publishing_ends' => null,
          'class_id' => 22,
      ],
  ];
*/

  public function getRows ()
  {
    return json_decode(json_encode(DB::select("select * from omp_instances")), true);
  }

  public function scopeOfClass ($query)
  {
    return $query;
  }

  public function class()
  {
    return $this->belongsTo(ClassModel::class);
  }

}