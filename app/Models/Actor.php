<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{

    protected $table='actor';
    protected $primaryKey = 'actor_id';
    use HasFactory;
}
