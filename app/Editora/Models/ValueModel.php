<?php

namespace App\Editora\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueModel extends Model
{
  protected $table='omp_values';
  protected $fillable=['inst_id', 'atri_id', 'text_val', 'date_val'];
  public $timestamps = false;

  use HasFactory;

}