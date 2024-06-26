<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
  use HasFactory;

  protected $fillable = ['max_month', 'max_day', 'indirect', 'direct', 'right', 'left'];
}
