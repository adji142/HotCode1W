<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reportcustom extends Model
{
    protected $table = 'report.customreport';
    public $dates    = ['createdon','lastupdatedon'];

    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    public function group()
    {
        return $this->belongsTo('App\Models\CustomreportGroup','customreportgroupid'); // Harus didefinisikan table pivotnya karena gak pake pemisah underscore
    }
}