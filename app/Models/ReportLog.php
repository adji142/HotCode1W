<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
	protected $table    = 'report.reportlog';
	protected $fillable = ['recordownerid','userid','reportname','createdon'];
	public $timestamps  = false;
}
