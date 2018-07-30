<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanDetail extends Model
{
  protected $table = 'ptg.tagihandetail';
  const UPDATED_AT = 'lastupdatedon';
  const CREATED_AT = 'createdon';
  
  // free data object
  public $tag = null;
}
