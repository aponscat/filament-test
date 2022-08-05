<?php

namespace App\Editora\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
  protected $table='omp_classes';
  protected $fillable=['name', 'tag'];
  public $timestamps = false;

  use HasFactory;

}