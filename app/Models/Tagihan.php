<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
  protected $table = 'ptg.tagihan';
  const UPDATED_AT = 'lastupdatedon';
  const CREATED_AT = 'createdon';

  // free data object
  public $tag = null;
}
