<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KoreksiReturPenjualan extends Model
{
    //untuk keperluan synch
    //karena tidak semua nota yang masuk ke isadb00, hanya yang benar2 nota, bukan koreksi
    protected $table = 'pj.koreksireturpenjualan';
}
