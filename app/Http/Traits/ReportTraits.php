<?php
namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\ReportLog;

trait ReportTraits {
    public function insert_reportlog($recordownerid,$userid,$reportname)
    {
        $reportlog = ReportLog::create([
            'recordownerid' => $recordownerid,
            'userid'        => $userid,
            'reportname'    => $reportname,
            'createdon'     => Carbon::now(),
        ]);
    }
}