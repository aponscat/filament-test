<?php

namespace App\Editora\Models\Sushi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ClassModel extends Model
{

  protected $fillable=['name', 'tag'];
  public $timestamps = false;

  use HasFactory;
  use \Sushi\Sushi;

  /*
  protected $rows = [
      [
        'id' => 22,
        'name' => 'Page',
      ],
      [
          'id' => '23',
          'name' => 'TestClass',
      ],
  ];
  */
  public function getRows ()
  {
    return json_decode(json_encode(DB::select("select * from omp_classes")), true);
  }
}