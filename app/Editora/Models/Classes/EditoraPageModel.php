<?php

namespace App\Editora\Models\Classes;
use App\Editora\Models\InstanceModel;

class EditoraPageModel extends InstanceModel
{
  public function scopeOfClass ($query)
  {
    return $query->where('class_id', 22);
  }
}