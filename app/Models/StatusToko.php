<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusToko extends Model
{
  protected $table = 'mstr.statustoko';
  const UPDATED_AT = 'lastupdatedon';
  const CREATED_AT = 'createdon';

  public function toko()
  {
    return $this->belongsTo('App\Models\Toko','tokoid');
  }
}
