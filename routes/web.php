<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('/home', 'HomeController@index');

// Begin - Sync
// Beware its doesnt have security!!

Route::get('transaksi/notapembelian/get', 'NotaPembelianController@getFrom11')->name('notapembelian.getfrom11');
Route::get('transaksi/orderpenjualan/ajuanget', 'OrderPenjualanController@getAcc11')->name('orderpenjualan.ajuanget');

// API
Route::get('api/transaksi/returpembelian', 'ReturPembelianController@apiReturPembelian')->name('returpembelian.apireturpembelian');
Route::get('api/transaksi/returpembelian/insert', 'ReturPembelianController@apiInsertReturPembelian')->name('returpembelian.apiinsertreturpembelian');
Route::get('api/notapembeliandetail/koreksi', 'NotaPembelianController@simpanAPIKoreksi')->name('notapembelian.detail.apikoreksi');
Route::get('api/kartupiutang/get', 'KartuPiutangController@apiGetKartuPiutang')->name('kartupiutang.apigetkartupiutang');
Route::post('api/tagihan/synch', 'TagihanController@apiSynch')->name('tagihan.apisynch');

Route::any('api/kartupiutang/{dname}/{subd?}', 'KartuPiutangController@api')->name('kartupiutang.api');
Route::any('api/salesorder/{dname}/{subd?}', 'SalesOrderController@api')->name('salesorder.api');
Route::any('api/tos/{dname}', 'TOSController@api')->name('tos.api');

//Synch
Route::get('synch/login', 'SynchController@login')->name('synch.login');

//Approval Mgmt Via Link Email
Route::get('approvalmgmt/email', 'ApprovalmgmtController@email')->name('approvalmgmt.email');
Route::get('approvalmgmt/simpan/status', 'ApprovalmgmtController@simpanStatus')->name('approvalmgmt.simpan');
// End - END

//TOS Storage
Route::get('image/tos/{id}/{sub}', 'TOSController@image')->name('tos.image');

//Storage Files
Route::get('files/{id}', 'StorageController@files')->name('storage.files');

// Nambahin Logout
Route::get('/logout','Auth\LoginController@logout')->name('logout');
// Cek Login belom
Route::group(['middleware' => ['auth','acl:web']],function(){
    Route::get('/','HomeController@index')->name('home');
    Route::get('/changepassword','UserController@changePassword')->name('setting.changepassword');
    Route::post('/changepassword','UserController@changePasswordSave')->name('setting.changepassword');

    // Lil bit Special :)~
    Route::get('getData','HomeController@getData')->name('home.data');
    Route::post('setsubcabang','HomeController@setSubcabang')->name('home.setsubcabang');

    // Public Function
    // Lookup Function
    Route::post('lookup/ceksubcabang', 'MasterController@cekSubcabang')->name('mstr.subcabang');
    Route::get('lookup/getsupplier/{recowner}/{query?}', 'MasterController@getSupplier')->name('lookup.getsupplier');
    // Route::get('lookup/getbarang/{query}/{type?}', 'MasterController@getBarang')->name('lookup.getbarang');
    Route::post('lookup/getbarang', 'MasterController@getBarang')->name('lookup.getbarang');
    Route::post('lookup/getstaff', 'MasterController@getStaff')->name('lookup.getstaff');
    Route::get('lookup/getnpbd/{query}', 'MasterController@getNotaPembelianDetail')->name('lookup.getnpbd');
    Route::post('lookup/getnpjd', 'MasterController@getNotaPenjualanDetail')->name('lookup.getnpjd');
    Route::get('lookup/getkategoriretur/{query?}', 'MasterController@getKategoriRetur')->name('lookup.getkategoriretur');
    Route::get('lookup/getsubcabang/{query}', 'MasterController@getSubCabang')->name('lookup.getsubcabang');
    Route::get('lookup/getsalesman/{recowner}/{query}', 'MasterController@getSalesman')->name('lookup.getsalesman');
    Route::get('lookup/gettoko/{query?}', 'MasterController@getToko')->name('lookup.gettoko');
    Route::get('lookup/gettokodetail/{id}', 'MasterController@getTokoDetail')->name('lookup.gettokodetail');
    Route::get('lookup/getexpedisi/{recowner}/{query?}', 'MasterController@getExpedisi')->name('lookup.getexpedisi');
    Route::get('lookup/gettempokirim/{id}/{idtoko}', 'MasterController@getTempoKirim')->name('orderpenjualan.gettempokirim');
    Route::get('lookup/gethargabmk/{idstock}/{statusbmk?}', 'MasterController@getHargaBMK')->name('orderpenjualan.gethargabmk');
    Route::get('lookup/gethpp/{idstock}', 'MasterController@getHPP')->name('lookup.gethpp');
    Route::get('lookup/getriwayatorder/{idstock}/{idtoko}', 'MasterController@getRiwayatOrder')->name('orderpenjualan.getriwayatorder');
    Route::get('lookup/getqtystockgudang/{tgl}/{stockid}/{recowner}', 'MasterController@getQtyStockGudang')->name('lookup.getqtystockgudang');
    Route::get('lookup/gethitungbo/{stockid}', 'MasterController@getHitungBO')->name('lookup.gethitungbo');
    Route::post('lookup/getsopir', 'MasterController@getSopir')->name('lookup.karyawansopir');
    Route::post('lookup/getkernet', 'MasterController@getKernet')->name('lookup.karyawankernet');
    Route::post('lookup/armadakirim', 'MasterController@getArmadaKirim')->name('lookup.armadakirim');
    Route::post('lookup/getpj', 'MasterController@getPj')->name('lookup.karyawangetpj');
    Route::post('lookup/getsuratjalan', 'MasterController@getSuratJalan')->name('lookup.sj');

    // Permission
    Route::get('security/permission','PermissionController@index')->name('permission.index');
    Route::get('security/permission/sidebar','PermissionController@sidebar')->name('permission.sidebar');

    // Role
    Route::get('security/role','RoleController@index')->name('role.index');
    Route::get('security/role/lihat/{id}','RoleController@lihat')->name('role.lihat');
    Route::get('security/role/tambah','RoleController@form')->name('role.tambah');
    Route::get('security/role/ubah/{id}','RoleController@form')->name('role.ubah');
    Route::get('security/role/user/{id}','RoleController@formuser')->name('role.user');
    Route::post('security/role/tambah','RoleController@save')->name('role.tambah');
    Route::post('security/role/ubah/{id}','RoleController@save')->name('role.ubah');
    Route::post('security/role/user/{id}','RoleController@saveuser')->name('role.user');
    Route::delete('security/role/hapus/{id}','RoleController@delete')->name('role.hapus');

    // Access
    Route::get('security/access','UserAccessController@index')->name('access.index');
    Route::get('security/access/view/{id}','UserAccessController@view')->name('access.view');
    Route::get('security/access/ubah/{id}','UserAccessController@ubah')->name('access.ubah');
    Route::post('security/access/tambah/user','UserAccessController@saveUser')->name('access.tambah.user');
    Route::delete('security/access/delete/{idsub}/user/{id}','UserAccessController@deleteUser')->name('access.delete.user');

    // User
    Route::get('security/user','UserController@index')->name('user.index');
    Route::get('security/user/data','UserController@getData')->name('user.data');
    Route::get('security/user/tambah','UserController@tambah')->name('user.tambah');
    Route::post('security/user/tambah','UserController@simpan')->name('user.tambah');
    Route::get('security/user/ubah/{id}','UserController@ubah')->name('user.ubah');
    Route::post('security/user/ubah/{id}','UserController@simpan')->name('user.ubah');
    Route::post('security/user/hapus','UserController@hapus')->name('user.hapus');

    // Approval Group
    Route::get('approvalgrp', 'ApprovalgrpController@index')->name('approvalgrp.index');
    Route::get('approvalgrp/data', 'ApprovalgrpController@getData')->name('approvalgrp.data');
    Route::post('approvalgrp/data/detail', 'ApprovalgrpController@getDataDetail')->name('approvalgrp.data.detail');
    Route::post('approvalgrp/header', 'ApprovalgrpController@getHeader')->name('approvalgrp.header');
    Route::post('approvalgrp/detail', 'ApprovalgrpController@getDetail')->name('approvalgrp.detail');
    Route::post('approvalgrp/roles', 'ApprovalgrpController@simpanDetail')->name('approvalgrp.roles');

    // Approval Manajemen
    Route::get('approvalmgmt', 'ApprovalmgmtController@index')->name('approvalmgmt.index');
    Route::get('approvalmgmt/data', 'ApprovalmgmtController@getData')->name('approvalmgmt.data');
    Route::post('approvalmgmt/data/detail', 'ApprovalmgmtController@getDataDetail')->name('approvalmgmt.data.detail');
    Route::post('approvalmgmt/header', 'ApprovalmgmtController@getHeader')->name('approvalmgmt.header');
    Route::post('approvalmgmt/header/data', 'ApprovalmgmtController@getHeaderData')->name('approvalmgmt.header.data');
    Route::post('approvalmgmt/detail', 'ApprovalmgmtController@getDetail')->name('approvalmgmt.detail');
    Route::post('approvalmgmt/simpan/status', 'ApprovalmgmtController@simpanStatus')->name('approvalmgmt.simpan');

    // Master
    Route::get('master/cabang', 'MasterController@index')->name('cabang.index');
    Route::get('master/cabang/data', 'MasterController@getData')->name('cabang.data');
    Route::get('master/gudang', 'MasterController@index')->name('gudang.index');
    Route::get('master/gudang/data', 'MasterController@getData')->name('gudang.data');
    Route::get('master/tujuanexpedisi', 'MasterController@index')->name('tujuanexpedisi.index');
    Route::get('master/tujuanexpedisi/data', 'MasterController@getData')->name('tujuanexpedisi.data');
    Route::get('master/supplier', 'MasterController@index')->name('pemasok.index');
    Route::get('master/supplier/data', 'MasterController@getData')->name('pemasok.data');
    Route::get('master/expedisi', 'MasterController@index')->name('expedisi.index');
    Route::get('master/expedisi/data', 'MasterController@getData')->name('expedisi.data');
    Route::get('master/kolektor', 'MasterController@index')->name('kolektor.index');
    Route::get('master/kolektor/data', 'MasterController@getData')->name('kolektor.data');
    Route::get('master/targetsales', 'MasterController@index')->name('targetsales.index');
    Route::get('master/targetsales/data', 'MasterController@getData')->name('targetsales.data');
    Route::get('master/targetsales/detail', 'MasterController@getDataDetail')->name('targetsales.detail');
    Route::get('master/kelompokbarang', 'MasterController@index')->name('kelompokbarang.index');
    Route::get('master/kelompokbarang/data', 'MasterController@getData')->name('kelompokbarang.data');
    Route::get('master/toko', 'MasterController@index')->name('toko.index');
    Route::get('master/toko/data', 'MasterController@getData')->name('toko.data');
    Route::get('master/tokoaktifpasif/data', 'MasterController@getData')->name('tokoaktifpasif.data');
    Route::post('master/tokoaktifpasif/tambah', 'MasterController@tambah')->name('tokoaktifpasif.tambah');
    Route::post('master/tokoaktifpasif/hapus', 'MasterController@hapus')->name('tokoaktifpasif.hapus');
    Route::get('master/masterstok', 'MasterController@index')->name('masterstok.index');
    Route::get('master/masterstok/data', 'MasterController@getData')->name('masterstok.data');
    Route::get('master/hpp', 'MasterController@index')->name('hpp.index');
    Route::get('master/hpp/data', 'MasterController@getData')->name('hpp.data');
    Route::get('master/hpp/detail', 'MasterController@getDataDetail')->name('hpp.detail');
    Route::get('master/hppa/table', 'MasterController@index')->name('hppatable.index');
    Route::get('master/hppa/table/data', 'MasterController@getData')->name('hppatable.data');
    Route::get('master/hppa/table/detail', 'MasterController@getDataDetail')->name('hppatable.detail');
    Route::get('master/hppa/proseshpp', 'MasterController@index')->name('hppaproseshpp.index');
    Route::get('master/hppa/proseshpp/data', 'MasterController@getData')->name('hppaproseshpp.data');
    Route::get('master/numerator', 'MasterController@index')->name('numerator.index');
    Route::get('master/numerator/data', 'MasterController@getData')->name('numerator.data');
    Route::get('master/numerator/edit', 'MasterController@edit')->name('numerator.edit');
    Route::get('master/hargajual', 'MasterController@index')->name('hargajual.index');
    Route::get('master/hargajual/data', 'MasterController@getData')->name('hargajual.data');
    Route::get('master/hargajual/detail', 'MasterController@getDataDetail')->name('hargajual.detail');
    Route::get('master/hargajual/subdetail', 'MasterController@getDataSubDetail')->name('hargajual.subdetail');
    Route::get('master/tglserver', 'MasterController@getServerDate')->name('mstr.tglserver');
    Route::get('master/tokohakakses/data', 'MasterController@getData')->name('tokohakakses.data');

    // Master Toko Draft
    Route::get('master/tokodraft', 'TokodraftController@index')->name('tokodraft.index');
    Route::post('master/tokodraft/data', 'TokodraftController@getData')->name('tokodraft.data');
    Route::get('master/tokodraft/tambah', 'TokodraftController@form')->name('tokodraft.tambah');
    //Route::get('master/tokodraft/ubah/{id}', 'TokodraftController@form')->name('tokodraft.ubah');
    Route::post('master/tokodraft/simpan', 'TokodraftController@simpan')->name('tokodraft.simpan');
    Route::post('master/tokodraft/hapus', 'TokodraftController@hapus')->name('tokodraft.hapus');
    Route::get('master/tokodraft/getkota/{id}', 'TokodraftController@getkota')->name('tokodraft.getkota');
    Route::get('master/tokodraft/getkecamatan/{id}', 'TokodraftController@getkecamatan')->name('tokodraft.getkecamatan');
    Route::post('master/tokodraft/approve', 'TokodraftController@approve')->name('tokodraft.approve');
    Route::get('master/tokodraft/cekktp/{ktp}', 'TokodraftController@cekktp')->name('tokodraft.cekktp');
    Route::get('master/tokodraft/viewfotoktp/{id}', 'TokodraftController@viewfotoktp')->name('tokodraft.viewfotoktp');
    Route::get('master/tokodraft/rejected', 'TokodraftController@rejected')->name('tokodraft.rejected');
    Route::get('master/tokodraft/batalrejected/{id}', 'TokodraftController@batalrejected')->name('tokodraft.batalrejected');
    Route::get('master/tokodraft/tdhistory', 'TokodraftController@tdhistory')->name('tokodraft.tdhistory');
    Route::get('master/tokodraft/tddetail', 'TokodraftController@tddetail')->name('tokodraft.tddetail');

    // Master Toko
    Route::get('master/toko/ubah/{kodetoko}', 'TokodraftController@form')->name('toko.ubah');

    // Master Toko JW
    Route::get('master/tokojw', 'TokoJWController@index')->name('tokojw.index');
    Route::get('master/tokojw/data', 'TokoJWController@getData')->name('tokojw.data');
    Route::get('master/tokojw/datajw', 'TokoJWController@getDataJW')->name('tokojw.datajw');
    Route::get('master/tokojw/datastatus', 'TokoJWController@getDataStatus')->name('tokojw.datastatus');
    Route::get('master/tokojw/tambah', 'TokoJWController@tambah')->name('tokojw.tambah');
    Route::get('master/tokojw/cektglaktif', 'TokoJWController@cektglaktif')->name('tokojw.cektglaktif');
    Route::post('master/tokojw/simpan','TokoJWController@simpan')->name('tokojw.simpan');

    // Order Pembelian
    Route::get('transaksi/orderpembelian', 'OrderPembelianController@index')->name('orderpembelian.index');
    Route::get('transaksi/orderpembelian/data', 'OrderPembelianController@getData')->name('orderpembelian.data');
    
    Route::get('transaksi/orderpembelian/tambah', 'OrderPembelianController@tambah')->name('orderpembelian.tambah');
    Route::post('transaksi/orderpembelian/tambah', 'OrderPembelianController@simpan')->name('orderpembelian.tambah');
    Route::post('transaksi/orderpembelian/ubah', 'OrderPembelianController@ubah')->name('orderpembelian.ubah');
    Route::post('transaksi/orderpembelian/kewenangan', 'OrderPembelianController@kewenangan')->name('orderpembelian.kewenangan');
    Route::post('transaksi/orderpembeliandetail/data', 'OrderPembelianController@getDataDetail')->name('orderpembelian.detail.data');
    Route::post('transaksi/orderpembeliandetail', 'OrderPembelianController@getDetail')->name('orderpembelian.detail.detail');
    Route::post('transaksi/orderpembeliandetail/tambah', 'OrderPembelianController@simpanDetail')->name('orderpembelian.detail.tambah');
    Route::post('transaksi/orderpembeliandetail/ubah', 'OrderPembelianController@ubahDetail')->name('orderpembelian.detail.ubah');
    Route::get('transaksi/orderpembelian/ceksbp', 'OrderPembelianController@ceksbp')->name('orderpembelian.ceksbp');
    Route::post('transaksi/orderpembelian/sync11', 'OrderPembelianController@sync11')->name('orderpembelian.sync');
    Route::post('transaksi/orderpembelian/approval', 'OrderPembelianController@simpanApproval')->name('orderpembelian.approval');
    Route::get('transaksi/orderpembelian/oh/pdf', 'OrderPembelianController@cetakOhPdf')->name('orderpembelian.cetakoh.pdf');
    Route::get('transaksi/orderpembelian/oh/excel', 'OrderPembelianController@cetakOhExcel')->name('orderpembelian.cetakoh.excel');

    Route::get('transaksi/orderpembelian/stoktipis/data', 'OrderPembelianController@getDataDetailStokTipis')->name('orderpembelian.stoktipis.data');
    Route::post('transaksi/orderpembelian/stoktipis/cek', 'OrderPembelianController@cekStokTipis')->name('orderpembelian.stoktipis.cek');
    Route::get('transaksi/orderpembelian/stoktipis/generate', 'OrderPembelianController@stokTipis')->name('orderpembelian.stoktipis.tambah');
    Route::post('transaksi/orderpembelian/stoktipis/generate', 'OrderPembelianController@simpanStokTipis')->name('orderpembelian.stoktipis.tambah');
    Route::get('transaksi/orderpembelian/bo/data', 'OrderPembelianController@getDataDetailBo')->name('orderpembelian.bo.data');
    Route::get('transaksi/orderpembelian/bo/generate', 'OrderPembelianController@bo')->name('orderpembelian.bo.tambah');
    Route::post('transaksi/orderpembelian/bo/generate', 'OrderPembelianController@simpanBo')->name('orderpembelian.bo.tambah');
    // Route::get('transaksi/orderpembelian/getpenjualanbo/{stockid}/{tglmulai}/{tglakhir}', 'OrderPembelianController@getPenjualanBo')->name('orderpembelian.getpenjualanbo');
    // Route::get('transaksi/orderpembelian/getrataratajual/{stockid}/{tglstok}', 'OrderPembelianController@getRatarataJual')->name('orderpembelian.getrataratajual');
    // Route::get('transaksi/orderpembelian/gethitungbo/{stockid}', 'OrderPembelianController@getHitungBO')->name('lookup.gethitungbo');
    Route::get('transaksi/orderpembelian/cekbarang', 'OrderPembelianController@cekBarang')->name('orderpembelian.cekbarang');

    // Nota Pembelian
    Route::get('transaksi/notapembelian', 'NotaPembelianController@index')->name('notapembelian.index');
    Route::get('transaksi/notapembelian/data', 'NotaPembelianController@getData')->name('notapembelian.data');
    Route::post('transaksi/notapembelian/ubah', 'NotaPembelianController@simpan')->name('notapembelian.ubah');
    Route::post('transaksi/notapembeliandetail/data', 'NotaPembelianController@getDataDetail')->name('notapembelian.detail.data');
    Route::post('transaksi/notapembeliandetail', 'NotaPembelianController@getDetail')->name('notapembelian.detail.detail');
    Route::post('transaksi/notapembeliandetail/koreksi', 'NotaPembelianController@simpanKoreksi')->name('notapembelian.detail.koreksi');
    Route::post('transaksi/notapembeliandetail/ubah', 'NotaPembelianController@simpanDetail')->name('notapembelian.detail.ubah');
    Route::post('transaksi/notapembelian/ubah/kewenangan', 'NotaPembelianController@cekKewenangan')->name('notapembelian.kewenangan');

    // Barang Diterima Gudang Nota Pembelian
    // Route::get('transaksi/barangditerimagudang', 'NotaPembelianController@barangDiterimaGudang')->name('brgditerimagdg.index');

    // Retur Pembelian
    Route::get('transaksi/returpembelian', 'ReturPembelianController@index')->name('returpembelian.index');
    Route::get('transaksi/returpembelian/data', 'ReturPembelianController@getData')->name('returpembelian.data');
    Route::get('transaksi/returpembelian/tambah', 'ReturPembelianController@tambah')->name('returpembelian.tambah');
    Route::post('transaksi/returpembelian/tambah', 'ReturPembelianController@simpan')->name('returpembelian.tambah');
    Route::post('transaksi/returpembelian/detail/data', 'ReturPembelianController@getDataDetail')->name('returpembelian.detail.data');
    Route::post('transaksi/returpembelian/detail/detail', 'ReturPembelianController@getDetail')->name('returpembelian.detail.detail');
    Route::post('transaksi/returpembelian/detail/tambah', 'ReturPembelianController@simpanDetail')->name('returpembelian.detail.tambah');
    Route::post('transaksi/returpembelian/detail/koreksi', 'ReturPembelianController@simpanDetailKoreksi')->name('returpembelian.detail.koreksi');
    Route::post('transaksi/returpembelian/updatetglkirim', 'ReturPembelianController@updateTglKirim')->name('returpembelian.updatetglkirim');
    Route::post('transaksi/returpembelian/kewenangan', 'ReturPembelianController@kewenangan')->name('returpembelian.kewenangan');
    Route::get('transaksi/returpembelian/cetakmpr/{id}', 'ReturPembelianController@cetakmpr')->name('returpembelian.cetakmpr');
    Route::post('transaksi/returpembelian/sync11', 'ReturPembelianController@sync11')->name('returpembelian.sync11');

    // Order Penjualan
    Route::get('transaksi/orderpenjualan', 'OrderPenjualanController@index')->name('orderpenjualan.index');
    Route::get('transaksi/orderpenjualan/data', 'OrderPenjualanController@getData')->name('orderpenjualan.data');
    Route::get('transaksi/orderpenjualan/header', 'OrderPenjualanController@getHeaderDetail')->name('orderpenjualan.header');
    Route::get('transaksi/orderpenjualan/tambah', 'OrderPenjualanController@tambah')->name('orderpenjualan.tambah');
    Route::post('transaksi/orderpenjualan/tambah', 'OrderPenjualanController@simpan')->name('orderpenjualan.tambah');
    Route::post('transaksi/orderpenjualan/ubah', 'OrderPenjualanController@ubah')->name('orderpenjualan.ubah');
    Route::post('transaksi/orderpenjualan/kewenangan', 'OrderPenjualanController@kewenangan')->name('orderpenjualan.kewenangan');
    Route::post('transaksi/orderpenjualan/detail/data', 'OrderPenjualanController@getDataDetail')->name('orderpenjualan.detail.data');
    Route::post('transaksi/orderpenjualan/detail/detail', 'OrderPenjualanController@getDetail')->name('orderpenjualan.detail.detail');
    Route::post('transaksi/orderpenjualan/detail/tambah', 'OrderPenjualanController@simpanDetail')->name('orderpenjualan.detail.tambah');
    Route::post('transaksi/orderpenjualan/detail/cekkadaluwarsa', 'OrderPenjualanController@checkPILKadaluwarsa')->name('orderpenjualan.detail.cekkadaluwarsa');
    Route::get('transaksi/orderpenjualan/cekpil', 'OrderPenjualanController@cekDodo')->name('orderpenjualan.cekpil');

    Route::get('transaksi/orderpenjualan/copydooh', 'OrderPenjualanController@copydooh')->name('orderpenjualan.copydooh');
    Route::post('transaksi/orderpenjualan/insertdooh', 'OrderPenjualanController@insertDooh')->name('orderpenjualan.insertdooh');
    Route::get('transaksi/orderpenjualan/copydodo', 'OrderPenjualanController@copydodo')->name('orderpenjualan.copydodo');
    Route::post('transaksi/orderpenjualan/insertdodo', 'OrderPenjualanController@insertDodo')->name('orderpenjualan.insertdodo');
    Route::get('transaksi/orderpenjualan/riwayatjual/{tokoid}', 'OrderPenjualanController@riwayatJual')->name('orderpenjualan.riwayatjual');
    Route::get('transaksi/orderpenjualan/batalpil', 'OrderPenjualanController@batalPil')->name('orderpenjualan.batalpil');
    Route::get('transaksi/orderpenjualan/batalpildetail', 'OrderPenjualanController@batalPilDetail')->name('orderpenjualan.batalpildetail');
    Route::get('transaksi/orderpenjualan/cetakpickinglist/{id}', 'OrderPenjualanController@cetakPicking')->name('orderpenjualan.cetakpickinglist');
    Route::post('transaksi/orderpenjualan/ajuan', 'OrderPenjualanController@ajuan11')->name('orderpenjualan.ajuan');
    Route::post('transaksi/orderpenjualan/ajuanupdate', 'OrderPenjualanController@getAjuanUpdate')->name('orderpenjualan.ajuanupdate');
    Route::post('transaksi/orderpenjualan/updatenoso', 'OrderPenjualanController@updateNoSo')->name('orderpenjualan.updatenoso');
    Route::get('transaksi/orderpenjualan/cekbarang', 'OrderPenjualanController@cekBarang')->name('orderpenjualan.cekbarang');
    Route::get('transaksi/orderpenjualan/cekbarangopj', 'OrderPenjualanController@cekBarangopj')->name('orderpenjualan.cekbarangopj');
    Route::get('transaksi/orderpenjualan/cekmarkup', 'OrderPenjualanController@cekMarkup')->name('orderpenjualan.cekmarkup');

    // Sales Order
    Route::get('transaksi/salesorder', 'SalesOrderController@index')->name('salesorder.index');
    Route::match(['post', 'get'],'transaksi/salesorder/data', 'SalesOrderController@getData')->name('salesorder.data');
    //Route::get('transaksi/salesorder/data', 'SalesOrderController@getData')->name('salesorder.data');
    Route::get('transaksi/salesorder/header', 'SalesOrderController@getHeaderDetail')->name('salesorder.header');
    Route::get('transaksi/salesorder/closingso', 'SalesOrderController@closingSO')->name('salesorder.closingso');
    Route::get('transaksi/salesorder/tambah', 'SalesOrderController@tambah')->name('salesorder.tambah');
    Route::post('transaksi/salesorder/tambah', 'SalesOrderController@simpan')->name('salesorder.tambah');
    Route::post('transaksi/salesorder/ubah', 'SalesOrderController@ubah')->name('salesorder.ubah');
    Route::post('transaksi/salesorder/kewenangan', 'SalesOrderController@kewenangan')->name('salesorder.kewenangan');
    Route::post('transaksi/salesorder/hapus', 'SalesOrderController@hapusData')->name('salesorder.hapus');
    Route::post('transaksi/salesorder/detail/data', 'SalesOrderController@getDataDetail')->name('salesorder.detail.data');
    Route::post('transaksi/salesorder/detail/detail', 'SalesOrderController@getDetail')->name('salesorder.detail.detail');
    Route::post('transaksi/salesorder/detail/tambah', 'SalesOrderController@simpanDetail')->name('salesorder.detail.tambah');

    // Route::get('transaksi/salesorder/copydooh', 'SalesOrderController@copydooh')->name('salesorder.copydooh');
    // Route::post('transaksi/salesorder/insertdooh', 'SalesOrderController@insertDooh')->name('salesorder.insertdooh');
    // Route::get('transaksi/salesorder/copydodo', 'SalesOrderController@copydodo')->name('salesorder.copydodo');
    // Route::post('transaksi/salesorder/insertdodo', 'SalesOrderController@insertDodo')->name('salesorder.insertdodo');
    // Route::get('transaksi/salesorder/riwayatjual/{tokoid}', 'SalesOrderController@riwayatJual')->name('salesorder.riwayatjual');
    Route::get('transaksi/salesorder/batalpil', 'SalesOrderController@batalPil')->name('salesorder.batalpil');
    Route::get('transaksi/salesorder/batalpildetail', 'SalesOrderController@batalPilDetail')->name('salesorder.batalpildetail');
    // Route::get('transaksi/salesorder/cetakpickinglist/{id}', 'SalesOrderController@cetakPicking')->name('salesorder.cetakpickinglist');
    // Route::post('transaksi/salesorder/ajuan', 'SalesOrderController@ajuan11')->name('salesorder.ajuan');
    // Route::post('transaksi/salesorder/ajuanupdate', 'SalesOrderController@getAjuanUpdate')->name('salesorder.ajuanupdate');
    // Route::post('transaksi/salesorder/updatenoso', 'SalesOrderController@updateNoSo')->name('salesorder.updatenoso');
    // Route::get('transaksi/salesorder/cekbarang', 'SalesOrderController@cekBarang')->name('salesorder.cekbarang');
    // Route::get('transaksi/salesorder/cekmarkup', 'SalesOrderController@cekMarkup')->name('salesorder.cekmarkup');

    // Nota Penjualan
    Route::get('transaksi/notapenjualan', 'NotaPenjualanController@index')->name('notapenjualan.index');
    Route::get('transaksi/notapenjualan/data', 'NotaPenjualanController@getData')->name('notapenjualan.data');
    Route::post('transaksi/notapenjualandetail/nota', 'NotaPenjualanController@getDataNota')->name('notapenjualan.nota');
    Route::post('transaksi/notapenjualandetail/data', 'NotaPenjualanController@getDataNotaDetail')->name('notapenjualandetail.data');
    Route::post('transaksi/notapenjualandetailkoli/data', 'NotaPenjualanController@getDataNotaDetailKoli')->name('notapenjualandetailkoli.data');
    Route::get('transaksi/notapenjualan/tambah', 'NotaPenjualanController@tambah')->name('notapenjualan.tambah');
    Route::get('transaksi/notapenjualan/header', 'NotaPenjualanController@getHeaderDetail')->name('notapenjualan.header');
    Route::get('transaksi/notapenjualan/detail', 'NotaPenjualanController@getDataDetail')->name('notapenjualan.detail');

    //new
    Route::post('transaksi/notapenjualandetail', 'NotaPenjualanController@getDataDetailNota')->name('notapenjualan.detail.detail');
    Route::post('transaksi/notapenjualandetail/koreksi', 'NotaPenjualanController@simpanKoreksi')->name('notapenjualan.detail.koreksi');
    Route::post('transaksi/notapenjualandetail/editqty', 'NotaPenjualanController@simpanEditQty')->name('notapenjualan.detail.editqty');
     
    Route::get('transaksi/notapenjualan/koli', 'NotaPenjualanController@getDataDetailKoli')->name('notapenjualan.detailkoli');
    Route::post('transaksi/notapembelian/kewenangan', 'NotaPenjualanController@cekKewenangan')->name('notapenjualan.kewenangan');
    // Route::get('transaksi/notapenjualan/delete', 'NotaPenjualanController@deleteNota')->name('notapenjualan.delete');
    // Route::get('transaksi/notapenjualan/deletedetail', 'NotaPenjualanController@deleteNotaDetail')->name('notapenjualan.deletedetail');
    Route::get('transaksi/notapenjualan/cetakproforma', 'NotaPenjualanController@cetakProforma')->name('notapenjualan.cetakproforma');
    Route::get('transaksi/notapenjualan/cetaknota', 'NotaPenjualanController@cetakNota')->name('notapenjualan.cetaknota');
    Route::get('transaksi/notapenjualan/isichecker', 'NotaPenjualanController@isiCheckerNota')->name('notapenjualan.isichecker');
    Route::get('transaksi/notapenjualan/isitglterima', 'NotaPenjualanController@isiTglTerimaNota')->name('notapenjualan.isitglterima');
    // Route::get('transaksi/notapenjualan/hapustglterima', 'NotaPenjualanController@hapusTglTerimaNota')->name('notapenjualan.hapustglterima');
    Route::post('transaksi/notapenjualan/isichecker', 'NotaPenjualanController@submitCheckerNota')->name('notapenjualan.submitchecker');
    Route::post('transaksi/notapenjualan/isitglterima', 'NotaPenjualanController@submitTglTerimaNota')->name('notapenjualan.submittglterima');
    Route::post('transaksi/notapenjualan/changetipetransaksi', 'NotaPenjualanController@changeTipeTransaksi')->name('notapenjualan.changetipetransaksi');
    Route::post('transaksi/notapenjualan/tambah', 'NotaPenjualanController@addNota')->name('notapenjualan.add');

    // ACC Piutang
    Route::get('transaksi/accpiutang', 'AccpiutangController@index')->name('accpiutang.index');
    Route::get('transaksi/accpiutang/data', 'AccpiutangController@getData')->name('accpiutang.data');
    Route::get('transaksi/accpiutang/dataAppr', 'AccpiutangController@getDataAppr')->name('accpiutang.dataAppr');
    Route::get('transaksi/accpiutang/tglterimapil/delete', 'AccpiutangController@deleteTglTerimaPiL')->name('accpiutang.tglterimapil.delete');
    Route::get('transaksi/accpiutang/tglterimapil/terima', 'AccpiutangController@terimaTglTerimaPiL')->name('accpiutang.tglterimapil.terima');
    Route::get('transaksi/accpiutang/isi', 'AccpiutangController@getAccPiutangData')->name('accpiutang.acc.getdata');
    Route::post('transaksi/accpiutang/isi', 'AccpiutangController@isiAccPiutangData')->name('accpiutang.acc.isi');
    Route::post('transaksi/accpiutang/changepilihanacc', 'AccpiutangController@changePilihanAcc')->name('accpiutang.changepilihanacc');
    Route::post('transaksi/accpiutang/kewenangan', 'AccpiutangController@cekKewenangan')->name('accpiutang.kewenangan');
    Route::get('transaksi/accpiutang/rekappembayarantoko', 'AccpiutangController@rekapPembayaranToko')->name('accpiutang.rekappembayarantoko');
    Route::get('transaksi/accpiutang/rekappembayarantoko/excel', 'AccpiutangController@rekapPembayaranTokoExcel')->name('accpiutang.rekappembayarantoko.excel');
    Route::post('transaksi/accpiutang/update/pkp', 'AccpiutangController@updatePkp')->name('accpiutang.update.pkp');
    Route::get('transaksi/accpiutang/pkp/show', 'AccpiutangController@getCatatanPKP')->name('accpiutang.pkp.show');
    Route::get('transaksi/accpiutang/approverequest', 'AccpiutangController@approvalRequest')->name('accpiutang.approverequest');

    // Retur Penjualan
    Route::get('transaksi/returpenjualan', 'ReturPenjualanController@index')->name('returpenjualan.index');
    Route::get('transaksi/returpenjualan/data', 'ReturPenjualanController@getData')->name('returpenjualan.data');
    Route::get('transaksi/returpenjualan/tambah', 'ReturPenjualanController@tambah')->name('returpenjualan.tambah');
    Route::post('transaksi/returpenjualan/tambah', 'ReturPenjualanController@simpan')->name('returpenjualan.tambah');
    Route::post('transaksi/returpenjualan/tambah/detail', 'ReturPenjualanController@simpanDetail')->name('returpenjualan.tambah.detail');
    Route::post('transaksi/returpenjualan/header', 'ReturPenjualanController@getHeader')->name('returpenjualan.header');
    Route::post('transaksi/returpenjualan/detail', 'ReturPenjualanController@getDetail')->name('returpenjualan.detail');
    Route::post('transaksi/returpenjualandetail/data', 'ReturPenjualanController@getDataDetail')->name('returpenjualandetail.data');
    Route::post('transaksi/returpenjualandetail/koreksi', 'ReturPenjualanController@simpanDetailKoreksi')->name('returpenjualandetail.koreksi');
    Route::post('transaksi/returpenjualan/updatetglkirim', 'ReturPenjualanController@updateTglKirim')->name('returpenjualan.updatetglkirim');
    Route::post('transaksi/returpenjualan/kewenangan', 'ReturPenjualanController@cekKewenangan')->name('returpenjualan.kewenangan');
    // Route::post('transaksi/returpenjualan/hapus', 'ReturPenjualanController@hapus')->name('returpenjualan.hapus');
    Route::get('transaksi/returpenjualan/cetaknrj/{id}', 'ReturPenjualanController@cetaknrj')->name('returpenjualan.cetaknrj');
    Route::get('transaksi/returpenjualan/cetaksppb/{id}', 'ReturPenjualanController@cetaksppb')->name('returpenjualan.cetaksppb');
    Route::post('transaksi/returpenjualan/updatenrj', 'ReturPenjualanController@updatenrj')->name('returpenjualan.updatenrj');
    Route::post('transaksi/returpenjualan/updatesppb', 'ReturPenjualanController@updatesppb')->name('returpenjualan.updatesppb');

    // Surat Jalan
    Route::get('transaksi/suratjalan', 'SuratJalanController@index')->name('suratjalan.index');
    Route::post('transaksi/suratjalan/data', 'SuratJalanController@getData')->name('suratjalan.data');
    Route::post('transaksi/suratjalandetail/data', 'SuratJalanController@getDataDetail')->name('suratjalandetail.data');
    Route::post('transaksi/suratjalantitipan/data', 'SuratJalanController@getDataTitipan')->name('suratjalantitipan.data');
    Route::get('transaksi/suratjalan/view', 'SuratJalanController@getSuratJalanData')->name('suratjalan.view');
    Route::get('transaksi/suratjalandetail/view', 'SuratJalanController@getSuratJalanDetailData')->name('suratjalandetail.view');
    Route::get('transaksi/suratjalantitipan/view', 'SuratJalanController@getSuratJalanDetailTitipanData')->name('suratjalantitipan.view');
    Route::get('transaksi/suratjalan/nosj', 'SuratJalanController@getNoSj')->name('suratjalantitipan.nosj');
    Route::post('transaksi/suratjalantitipan/create', 'SuratJalanController@createSuratJalanDetailTitipan')->name('suratjalantitipan.create');
    Route::post('transaksi/suratjalan/create', 'SuratJalanController@createSuratJalan')->name('suratjalan.create');
    Route::post('transaksi/suratjalan/delete', 'SuratJalanController@deleteSuratJalan')->name('suratjalan.hapus');
    Route::post('transaksi/suratjalandetail/delete', 'SuratJalanController@deleteSuratJalanDetail')->name('suratjalandetail.hapus');
    Route::post('transaksi/suratjalantitipan/delete', 'SuratJalanController@deleteSuratJalanDetailTitipan')->name('suratjalantitipan.hapus');
    Route::post('transaksi/suratjalan/kewenangan', 'SuratJalanController@cekKewenangan')->name('suratjalan.kewenangan');
    Route::post('transaksi/suratjalan/cekkoli', 'SuratJalanController@cekKoli')->name('suratjalan.cekkoli');
    Route::get('transaksi/suratjalan/cetak', 'SuratJalanController@cetak')->name('suratjalan.cetak');

    // Pengiriman
    Route::get('transaksi/pengiriman', 'PengirimanController@index')->name('pengiriman.index');
    Route::get('transaksi/pengiriman/data', 'PengirimanController@getData')->name('pengiriman.data');
    Route::get('transaksi/pengiriman/view', 'PengirimanController@getPengiriman')->name('pengiriman.view');
    Route::post('transaksi/pengirimandetail/data', 'PengirimanController@getDataDetail')->name('pengirimandetail.data');
    Route::get('transaksi/pengirimandetail/view', 'PengirimanController@getPengirimanDetail')->name('pengirimandetail.view');
    Route::get('transaksi/pengiriman/nokirim', 'PengirimanController@getNoKirim')->name('pengiriman.nokirim');
    Route::post('transaksi/pengiriman/create', 'PengirimanController@createPengiriman')->name('pengiriman.create');
    Route::post('transaksi/pengirimandetail/create', 'PengirimanController@createPengirimanDetail')->name('pengirimandetail.create');
    Route::post('transaksi/pengiriman/update', 'PengirimanController@updatePengiriman')->name('pengiriman.update');
    Route::post('transaksi/pengiriman/delete', 'PengirimanController@deletePengiriman')->name('pengiriman.delete');
    Route::post('transaksi/pengirimandetail/delete', 'PengirimanController@deletePengirimanDetail')->name('pengirimandetail.delete');
    Route::post('transaksi/pengiriman/kewenangan', 'PengirimanController@cekKewenangan')->name('pengiriman.kewenangan');
    // Route::post('transaksi/pengiriman/suratjalan', 'PengirimanController@getSJ')->name('pengiriman.sj');
    Route::get('transaksi/pengiriman/cetak', 'PengirimanController@cetak')->name('pengiriman.cetak');

    //FixedRoute
    Route::get('transaksi/fixed-route', 'FixedRouteController@index')->name('fixed.index');
    Route::get('transaksi/fixed-route/data', 'FixedRouteController@getDataHeader')->name('fixed.data');
    Route::post('transaksi/fixed-route/detail/data', 'FixedRouteController@getDataDetail')->name('fixedroutedetail.data');
    Route::post('transaksi/fixed-route/detail', 'FixedRouteController@getDetail')->name('fixedroutedetail.detail');
    Route::post('transaksi/fixed-route/detail/kunjungan', 'FixedRouteController@getDataDetailKunjungan')->name('fixedroutedetail.kunjungan');
    Route::post('transaksi/fixed-route/detailkunjungan', 'FixedRouteController@getDetailKunjungan')->name('fixedroutedetailkunjungan.detail');
    Route::post('transaksi/fixed-route/detailkunjungan/hapus', 'FixedRouteController@hapusKunjungan')->name('fixedroutedetailkunjungan.hapus');
    Route::post('transaksi/fixed-route/detailkunjungan/cektglkunjungan', 'FixedRouteController@getCekTanggalKunjungan')->name('fixedroutedetailkunjungan.cektglkunjungan');
    Route::post('transaksi/fixed-route/cektokohakakses', 'FixedRouteController@cekTokohakakses')->name('fixedroute.cektokohakakses');
    Route::post('transaksi/fixed-route/getjumlahkunjungan', 'FixedRouteController@getJumlahKunjungan')->name('fixedroute.getjumlahkunjungan');
    Route::get('transaksi/cek/{tglkunjung}/{idtoko}','FixedRouteController@CekTanggalKunjungan')->name('fixedroutedetailkunjungan.cektanggalkunjungan');
    Route::post('transaksi/cektoko','FixedRouteController@CekTanggalKunjunganToko')->name('fixedroutedetailkunjungan.cektanggalkunjungantoko');
    
    Route::get('transaksi/fixed-route/tambahrecordkunjungan/{tglkunjung}/{idkaryawan}','FixedRouteController@formPilihToko')->name('fixedroute.tambahrecordkunjungan');
    Route::post('transaksi/fixed-route/tambahrecordkunjungan', 'FixedRouteController@cekRecordKunjungan')->name('fixedroute.tambahrecordkunjungan');
    Route::get('transaksi/fixed-route/tambahrecordkunjunganluarrencana/{tglkunjung}/{idkaryawan}','FixedRouteController@formLuarRencana')->name('fixedroute.tambahrecordkunjunganluarrencana');
    Route::post('transaksi/fixed-route/tambahrecordkunjunganluarrencana', 'FixedRouteController@cekRecordKunjunganLR')->name('fixedroute.tambahrecordkunjunganluarrencana');
    Route::get('transaksi/fixed-route/tambahtglkunjungan/{id}/{idkaryawan}/{bulan}','FixedRouteController@formFr')->name('fixedroute.tambahtglkunjungan');
    Route::post('transaksi/fixed-route/tambahtglkunjungan', 'FixedRouteController@tambahKunjungan')->name('fixedroute.tambahtglkunjungan');

    Route::get('transaksi/fixed-route/updatepertoko/{id}/{idkaryawan}/{bulan}','FixedRouteController@frUpdatePerToko')->name('fixedroute.updatepertoko');
    Route::post('transaksi/fixed-route/updatepertoko', 'FixedRouteController@ubahPerToko')->name('fixedroute.updatepertoko');

    Route::post('transaksi/fixed-route/ubah', 'FixedRouteController@ubah')->name('fixedroute.ubah');
    Route::post('transaksi/fixed-route/batalkunjungan', 'FixedRouteController@batalKunjungan')->name('fixedroute.batalkunjungan');
    Route::post('transaksi/fixed-route/cekedit', 'FixedRouteController@cekEdit')->name('fixedroute.cekedit');
    
    //FormTagih
    Route::get('transaksi/formtagih', 'FormTagihController@index')->name('ftagih.index');
    Route::post('transaksi/formtagih/data', 'FormTagihController@getData')->name('ftagih.data');
    Route::get('transaksi/formtagih/cek', 'FormTagihController@cek')->name('ftagih.cek');
    Route::get('transaksi/formtagih/print', 'FormTagihController@print')->name('ftagih.print');

    // Stock Opname
    Route::get('transaksi/stockopname', 'StockOpnameController@index')->name('opname.index');
    Route::get('transaksi/stockopname/header/data', 'StockOpnameController@getDataHeader')->name('opname.header.data');
    Route::post('transaksi/stockopname/detail/data', 'StockOpnameController@getDataDetail')->name('opname.detail.data');
    Route::get('transaksi/stockopname/view', 'StockOpnameController@getStop')->name('opname.view');
    Route::get('transaksi/stockopname/stock/view', 'StockOpnameController@getStock')->name('opname.stockview');
    Route::post('transaksi/stockopname/closing', 'StockOpnameController@closingStop')->name('opname.closing');
    Route::get('transaksi/stockopname/analisa', 'StockOpnameController@analisaClosingStop')->name('opname.analisaclosing');
    Route::post('transaksi/stockopname/cab', 'StockOpnameController@closeAllBarang')->name('opname.closeallbarang');
    Route::get('transaksi/stockopname/cfs', 'StockOpnameController@cetakCfs')->name('opname.cetakcfs');
    Route::get('transaksi/stockopname/dfs', 'StockOpnameController@cetakDfs')->name('opname.cetakdfs');
    Route::post('transaksi/stockopname/update', 'StockOpnameController@updateStop')->name('opname.update');
    Route::post('transaksi/stockopname/delete', 'StockOpnameController@deleteStop')->name('opname.delete');
    Route::post('transaksi/stockopname/kewenangan', 'StockOpnameController@cekKewenangan')->name('opname.kewenangan');
    Route::get('transaksi/stockopname/perbarang', 'StockOpnameController@rencanaStopPerBarang')->name('opname.rencanaperbarang');
    Route::get('transaksi/stockopname/{tipe}', 'StockOpnameController@rencanaStop')->name('opname.rencana');

    //Stock Scrab
    Route::get('transaksi/stockscrab', 'StockScrabController@index')->name('scrab.index');
    Route::get('transaksi/stockscrab/header/data', 'StockScrabController@getDataHeader')->name('scrab.header.data');
    Route::post('transaksi/stockscrab/detail', 'StockScrabController@getScrab')->name('scrab.detail');
    Route::post('transaksi/stockscrabdetail/data', 'StockScrabController@getDataDetail')->name('scrab.detail.data');
    Route::post('transaksi/stockscrabdetail', 'StockScrabController@getDetail')->name('scrab.detail.detail');
    Route::post('transaksi/stockscrab/kewenangan', 'StockScrabController@kewenanganScrab')->name('scrab.kewenangan');
    Route::get('transaksi/stockscrab/tambah', 'StockScrabController@tambah')->name('scrab.tambah');
    Route::post('transaksi/stockscrab/tambah', 'StockScrabController@simpan')->name('scrab.tambah');
    Route::post('transaksi/stockscrabdetail/tambah', 'StockScrabController@simpanDetail')->name('scrabdetail.tambah');
    Route::post('transaksi/stockscrab/ajuanadm', 'StockScrabController@ajuanAdm')->name('scrab.ajuanadm');
    Route::post('transaksi/stockscrab/ajuanstok', 'StockScrabController@ajuanStok')->name('scrab.ajuanstok');
    Route::post('transaksi/stockscrab/ajuanmutasi', 'StockScrabController@ajuanMutasi')->name('scrab.ajuanmutasi');

    // Mutasi
    Route::get('transaksi/mutasi', 'MutasiController@index')->name('mutasi.index');
    Route::get('transaksi/mutasi/data', 'MutasiController@getData')->name('mutasi.data');
    Route::post('transaksi/mutasi/data/detail', 'MutasiController@getDataDetail')->name('mutasi.data.detail');

    // Antar Gudang
    Route::get('transaksi/antargudang', 'AntarGudangController@index')->name('antargudang.index');
    Route::get('transaksi/antargudang/data', 'AntarGudangController@getData')->name('antargudang.data');
    Route::get('transaksi/antargudang/view', 'AntarGudangController@getAg')->name('antargudang.view');
    Route::get('transaksi/antargudang/detail/view', 'AntarGudangController@getAgD')->name('antargudang.view.detail');
    Route::get('transaksi/antargudang/pil', 'AntarGudangController@cetakPil')->name('antargudang.cetakpil');
    Route::get('transaksi/antargudang/nota', 'AntarGudangController@cetakNota')->name('antargudang.cetaknota');
    Route::post('transaksi/antargudang/data/detail', 'AntarGudangController@getDataDetail')->name('antargudang.data.detail');
    Route::post('transaksi/antargudang/create', 'AntarGudangController@createAntarGudang')->name('antargudang.create');
    Route::post('transaksi/antargudang/detail/create', 'AntarGudangController@createAntarGudangDetail')->name('antargudang.create.detail');
    Route::post('transaksi/antargudang/update', 'AntarGudangController@updateAntarGudang')->name('antargudang.update');
    Route::post('transaksi/antargudang/detail/update', 'AntarGudangController@updateAntarGudangDetail')->name('antargudang.update.detail');
    Route::post('transaksi/antargudang/sync', 'AntarGudangController@syncAntarGudang')->name('antargudang.sync');
    Route::post('transaksi/antargudang/delete', 'AntarGudangController@deleteAntarGudang')->name('antargudang.delete');
    Route::post('transaksi/antargudang/detail/delete', 'AntarGudangController@deleteAntarGudangDetail')->name('antargudang.delete.detail');

    //Antar Gudang Versi 2
    Route::get('transaksi/antargudangv2', 'AntarGudangV2Controller@index')->name('antargudangv2.index');
    Route::get('transaksi/antargudangv2/data', 'AntarGudangV2Controller@getData')->name('antargudangv2.data');
    Route::get('transaksi/antargudangv2/detail','AntarGudangV2Controller@getDataDetail')->name('antargudangv2.detail');
    Route::get('transaksi/antargudangv2/view','AntarGudangV2Controller@getHeader')->name('antargudangv2.view');
    Route::get('transaksi/antargudangv2/detail/view','AntarGudangV2Controller@getDetail')->name('antargudangv2.detail.view');
    Route::post('transaksi/antargudangv2/kewenangan', 'AntarGudangV2Controller@Kewenangan')->name('antargudangv2.kewenangan');
    Route::get('transaksi/antargudangv2/tambah','AntarGudangV2Controller@tambah')->name('antargudangv2.tambah');
    Route::post('transaksi/antargudangv2/tambah', 'AntarGudangV2Controller@insertData')->name('antargudangv2.tambah');
    Route::post('transaksi/antargudangv2/detail/tambah', 'AntarGudangV2Controller@insertDetail')->name('antargudangv2.detail.tambah');
    Route::get('transaksi/antargudangv2/detail/cekstok', 'AntarGudangV2Controller@checkStockExists')->name('antargudangv2.detail.cekstok');
    Route::post('transaksi/antargudangv2/pengiriman','AntarGudangV2Controller@updatePengiriman')->name('antargudangv2.pengiriman');
    Route::get('transaksi/antargudangv2/cetakpil', 'AntarGudangV2Controller@cetakPil')->name('antargudangv2.cetakpil');
    Route::get('transaksi/antargudangv2/cetaknota', 'AntarGudangV2Controller@cetakNota')->name('antargudangv2.cetaknota');
    Route::post('transaksi/antargudangv2/updatenota', 'AntarGudangV2Controller@updateNota')->name('antargudangv2.updatenota');
    // Tampilkan laporan
    Route::get('laporan/hapus', 'LaporanController@hapus')->name('laporan.hapus');

    // Laporan Salesman
    Route::get('laporan/salesman/sodonota', 'LaporanController@indexsodonota')->name('rptperbandingansodonotabo.index');
    Route::get('laporan/salesman/sodonota/report', 'LaporanController@sodonota')->name('rptperbandingansodonotabo.report');
    Route::get('laporan/salesman/sodonotadetail/report', 'LaporanController@sodonotadetail')->name('rptperbandingansodonotabodetail.report');
    Route::get('laporan/salesman/pernjualanhi', 'LaporanController@salesmanPenjualanhi')->name('rptpenjualanhi.index');
    Route::post('laporan/salesman/pernjualanhi/report', 'LaporanController@salesmanPenjualanhireport')->name('rptpenjualanhi.report');
    Route::get('laporan/salesman/rekapitulasipenjualan', 'LaporanController@salesmanRekapitulasipenjualan')->name('rekapitulasipenjualan.index');
    Route::post('laporan/salesman/rekapitulasipenjualan/report', 'LaporanController@salesmanRekapitulasipenjualanreport')->name('rekapitulasipenjualan.report');
    
    Route::get('laporan/salesman/notajual', 'LaporanController@salesmanNotaJual')->name('rptsalesmannotajual.index');
    Route::post('laporan/salesman/notajual/report', 'LaporanController@salesmanNotaJualreport')->name('rptsalesmannotajual.report');

    // Laporan ACC Harga Ditolak
    Route::get('laporan/acchargaditolak', 'LaporanAccHargaDitolakController@index')->name('laporan.acchargaditolak.index');
    Route::get('laporan/acchargaditolak/cetak', 'LaporanAccHargaDitolakController@cetak')->name('laporan.acchargaditolak.cetak');
    Route::get('laporan/acchargaditolak/excel', 'LaporanAccHargaDitolakController@excel')->name('laporan.acchargaditolak.excel');
        
    // Stock laporan master
    Route::get('laporan/stock/{dname}', 'StockLaporanMasterController@index')->name('laporan.stock.index');
    Route::get('laporan/stock/{dname}/process', 'StockLaporanMasterController@process')->name('laporan.stock.process');
    Route::get('laporan/stock/{dname}/preview', 'StockLaporanMasterController@preview')->name('laporan.stock.preview');
    Route::get('laporan/stock/{dname}/excel', 'StockLaporanMasterController@excel')->name('laporan.stock.excel');
    Route::get('laporan/stock/{dname}/pdf', 'StockLaporanMasterController@pdf')->name('laporan.stock.pdf');

    // Analisa laporan master
    Route::get('laporan/analisa/{dname}', 'AnalisaLaporanMasterController@index')->name('laporan.analisa.index');
    Route::get('laporan/analisa/{dname}/process', 'AnalisaLaporanMasterController@process')->name('laporan.analisa.process');
    Route::get('laporan/analisa/{dname}/preview', 'AnalisaLaporanMasterController@preview')->name('laporan.analisa.preview');
    Route::get('laporan/analisa/{dname}/excel', 'AnalisaLaporanMasterController@excel')->name('laporan.analisa.excel');
    Route::get('laporan/analisa/{dname}/pdf', 'AnalisaLaporanMasterController@pdf')->name('laporan.analisa.pdf');

    // Laporan Pengiriman Gudang
    Route::get('laporan/pengirimangudang', 'LaporanPengirimanGudangController@index')->name('laporan.pengirimangudang.index');
    Route::get('laporan/pengirimangudang/cetak', 'LaporanPengirimanGudangController@cetak')->name('laporan.pengirimangudang.cetak');
    Route::get('laporan/pengirimangudang/excel', 'LaporanPengirimanGudangController@excel')->name('laporan.pengirimangudang.excel');

    // Laporan Perfomance Toko
    Route::get('laporan/perfomancetoko', 'LaporanPerfomanceTokoController@index')->name('laporan.perfomancetoko.index');
    Route::get('laporan/perfomancetoko/cetak/toko', 'LaporanPerfomanceTokoController@cetak')->name('laporan.perfomancetoko.cetak');
    Route::get('laporan/perfomancetoko/cetak/bulan', 'LaporanPerfomanceTokoController@cetakBulan')->name('laporan.perfomancetoko.cetakBulan');
    Route::get('laporan/perfomancetoko/excel', 'LaporanPerfomanceTokoController@excel')->name('laporan.perfomancetoko.excel');

    // Laporan Penjualan
    Route::get('laporan/penjualan', 'LaporanPenjualanController@index')->name('laporan.penjualan.index');
    Route::get('laporan/penjualan/performanceoutlet', 'LaporanPenjualanController@performanceOutlet')->name('laporan.penjualan.performanceoutlet');
    Route::post('laporan/penjualan/performanceoutlet/data', 'LaporanPenjualanController@performanceOutletData')->name('laporan.penjualan.performanceoutletdata');
    Route::get('laporan/penjualan/performanceoutlet/excel', 'LaporanPenjualanController@performanceOutletExcel')->name('laporan.penjualan.performanceoutletexcel');
    Route::get('laporan/penjualan/performanceproduct', 'LaporanPenjualanController@performanceProduct')->name('laporan.penjualan.performanceproduct');
    Route::post('laporan/penjualan/performanceproduct/data', 'LaporanPenjualanController@performanceProductData')->name('laporan.penjualan.performanceproductdata');
    Route::get('laporan/penjualan/performanceproduct/excel', 'LaporanPenjualanController@performanceProductExcel')->name('laporan.penjualan.performanceproductexcel');
    Route::get('laporan/penjualan/performanceproductpersales', 'LaporanPenjualanController@performanceProductPersales')->name('laporan.penjualan.performanceproductpersales');
    Route::post('laporan/penjualan/performanceproductpersales/data', 'LaporanPenjualanController@performanceProductPersalesData')->name('laporan.penjualan.performanceproductpersalesdata');
    Route::get('laporan/penjualan/performanceproductpersales/excel', 'LaporanPenjualanController@performanceProductPersalesExcel')->name('laporan.penjualan.performanceproductpersalesexcel');
    Route::get('laporan/penjualan/performancesalesman', 'LaporanPenjualanController@performanceSalesman')->name('laporan.penjualan.performancesalesman');
    Route::post('laporan/penjualan/performancesalesman/data', 'LaporanPenjualanController@performanceSalesmanData')->name('laporan.penjualan.performancesalesmandata');
    Route::get('laporan/penjualan/performancesalesman/excel', 'LaporanPenjualanController@performanceSalesmanExcel')->name('laporan.penjualan.performancesalesmanexcel');

    // Laporan Penjualan Git
    Route::get('laporan/penjualan/git', 'LaporanPenjualanController@git')->name('laporan.penjualan.git');
    Route::get('laporan/penjualan/git/preview', 'LaporanPenjualanController@gitPreview')->name('laporan.penjualan.gitpreview');
    Route::post('laporan/penjualan/git/data', 'LaporanPenjualanController@gitData')->name('laporan.penjualan.gitdata');
    Route::get('laporan/penjualan/git/excel', 'LaporanPenjualanController@gitExcel')->name('laporan.penjualan.gitexcel');

    // Laporan HPPA
    Route::get('laporan/hppa/{dname}', 'LaporanHPPAController@index')->name('laporan.hppa.index');
    Route::get('laporan/hppa/{dname}/process', 'LaporanHPPAController@process')->name('laporan.hppa.process');
    Route::get('laporan/hppa/{dname}/{dsub}', 'LaporanHPPAController@page')->name('laporan.hppa.page');

    // Laporan Custom
    Route::get('laporan/custom', 'LaporanCustomController@index')->name('laporan.custom.index');
    Route::get('laporan/custom/parameter/{id}', 'LaporanCustomController@parameter')->name('laporan.custom.parameter');
    Route::get('laporan/custom/preview', 'LaporanCustomController@preview')->name('laporan.custom.preview');
    Route::post('laporan/custom/preview/data', 'LaporanCustomController@previewData')->name('laporan.custom.previewdata');
    Route::post('laporan/custom/preview/excel', 'LaporanCustomController@previewExcel')->name('laporan.custom.previewexcel');
    Route::get('laporan/custom/list', 'LaporanCustomController@list')->name('laporan.custom.list');
    Route::post('laporan/custom/data', 'LaporanCustomController@data')->name('laporan.custom.data');
    Route::get('laporan/custom/tambah', 'LaporanCustomController@form')->name('laporan.custom.tambah');
    Route::post('laporan/custom/tambah', 'LaporanCustomController@save')->name('laporan.custom.tambah');
    Route::get('laporan/custom/ubah/{id}', 'LaporanCustomController@form')->name('laporan.custom.ubah');
    Route::post('laporan/custom/ubah/{id}', 'LaporanCustomController@save')->name('laporan.custom.ubah');
    Route::delete('laporan/custom/hapus/{id}', 'LaporanCustomController@delete')->name('laporan.custom.hapus');
    Route::get('laporan/custom/group/index', 'LaporanCustomGroupController@index')->name('laporan.customgroup.index');
    Route::post('laporan/custom/group/data', 'LaporanCustomGroupController@data')->name('laporan.customgroup.data');
    Route::get('laporan/custom/group/tambah', 'LaporanCustomGroupController@form')->name('laporan.customgroup.tambah');
    Route::post('laporan/custom/group/tambah', 'LaporanCustomGroupController@save')->name('laporan.customgroup.tambah');
    Route::get('laporan/custom/group/ubah/{id}', 'LaporanCustomGroupController@form')->name('laporan.customgroup.ubah');
    Route::post('laporan/custom/group/ubah/{id}', 'LaporanCustomGroupController@save')->name('laporan.customgroup.ubah');
    Route::delete('laporan/custom/group/hapus/{id}', 'LaporanCustomGroupController@delete')->name('laporan.customgroup.hapus');

    // Laporan Salesman
    Route::get('laporan/salesman/pernjualanABE', 'LaporanController@salesmanPenjualanABE')->name('rptpenjualanabe.index');
    Route::get('laporan/salesman/pernjualanABE/report', 'LaporanController@salesmanPenjualanABEreport')->name('rptpenjualanabe.report');
    Route::get('laporan/salesman/pernjualanABE/reportNetto', 'LaporanController@salesmanPenjualanABEreport_Netto')->name('rptpenjualanabe.reportNetto');
    Route::get('laporan/hapus', 'LaporanController@hapus')->name('laporan.hapus');

    //Laporan -  Barang
    Route::get('laporan/barang/penjualanperitem', 'LaporanController@indexpenjualanperitem')->name('rptpenjualanperitem.index');
    Route::get('laporan/barang/penjualanperitem/report', 'LaporanController@penjualanperitem')->name('rptpenjualanperitem.report');

    //Lookup
    Route::get('hr/getsalesman/{recowner}/{query}', 'HrController@getSalesman')->name('hr.getsalesman');
    Route::get('mstr/gettoko/{query}', 'MstrController@getToko')->name('mstr.gettoko');
    Route::get('mstr/kategoripenjualan', 'MstrController@getKategoriPenjualan')->name('mstr.getkategoripenjualan');
    Route::get('mstr/subcabang', 'MstrController@getSubCabang')->name('mstr.getsubcabang');

     // fixed assets
    Route::get('master/aktiva','AktivaController@index')->name('aktiva.index');
    Route::get('master/aktiva/data','AktivaController@getData')->name('aktiva.data');
    Route::get('master/aktiva/detail','AktivaController@getDataDetail')->name('aktiva.detail');
    Route::get('master/aktiva/tambah','AktivaController@tambah')->name('aktiva.tambah');
    Route::get('master/aktiva/edit','AktivaController@getDataById')->name('aktiva.edit');
    Route::post('master/aktiva/simpan','AktivaController@RunQueryInsert')->name('aktiva.simpan');
    Route::get('master/aktiva/hapus','AktivaController@RunQueryDelete')->name('aktiva.hapus');

    Route::get('master/aktiva/depresiasi','AktivaController@getDataDepresiasi')->name('aktiva.depresiasi');
    Route::post('master/aktiva/depresiasi/simpan','AktivaController@RunQueryDepresiasi')->name('aktiva.depresiasi.simpan');
    Route::post('master/aktiva/jual','AktivaController@RunQuerySellingAssets')->name('aktiva.jual');
    Route::get('master/aktiva/usiapenyusutan','AktivaController@getUsiaPenyusutan')->name('aktiva.usiapenyusutan');
    Route::get('master/aktiva/closingdate', 'AktivaController@GetLastClosing')->name('aktiva.closingdate');

    //closing aktiva
    Route::get('master/aktiva/closingfa', 'AktivaController@closingFAIndex')->name('aktiva.closingfa');
    Route::post('master/aktiva/closingsubmit', 'AktivaController@closingFA')->name("aktiva.closingsubmit");

    // Laporan Aktiva
    Route::get('laporan/penyusutan/hartatetap', 'AktivaController@laporanAktivaIndex')->name('laporanaktiva.index');
    Route::get('laporan/penyusutan/data/hartatetap', 'AktivaController@getLaporanPenyusutanData')->name('laporanaktiva.data');
    Route::get('laporan/penyusutan/cetak/hartatetap', 'AktivaController@getLaporanPenyusutanData')->name('laporanaktiva.cetak');
    Route::get('laporan/penyusutan/download/hartatetap', 'AktivaController@download')->name('laporanaktiva.download');

    // TOS Form
    Route::get('master/tosform', 'TOSController@index')->name('tos.index');
    Route::post('master/tosform/fbauth', ['before' => 'csrf', 'uses' => 'TOSController@fbauth'])->name('tos.fbauth');
    Route::post('master/tosform/config/{name}', ['before' => 'csrf', 'uses' => 'TOSController@config'])->name('tos.config');

    // TOS Form
    Route::get('master/storage', 'StorageController@index')->name('storage.index');
    Route::post('master/storage/upload', 'StorageController@upload')->name('storage.upload');
    
    //plan jual
    Route::get('transaksi/planjual','PlanJualController@index')->name('planjual.index');
    Route::get('transaksi/planjual/salesman','PlanJualController@getSalesmanActive')->name('planjual.salesman');
    Route::get('transaksi/planjual/nettopenjualan','PlanJualController@getNettoPenjualan')->name('planjual.nettopenjualan');
    Route::get('transaksi/planjual/data', 'PlanJualController@getPlanSales')->name('planjual.data');
    Route::get('transaksi/planjual/history','PlanJualController@getHistorySales')->name('planjual.history');
    Route::get('transaksi/planjual/cetak','PlanJualController@cetak')->name('transaksi.planjual.cetak');
    Route::get('transaksi/planjual/excel', 'PlanJualController@excel')->name('transaksi.planjual.excel');
    Route::post('transaksi/planjual/update','PlanJualController@RunQueryUpdate')->name('planjual.update');
    Route::get('transaksi/planjual/upload','PlanJualController@UploadSQLServerStaging')->name('planjual.upload');
    Route::get('transaksi/planjual/test','PlanJualController@test')->name('planjual.test'); //coba dd
    Route::get('transaksi/planjual/download/{name}','PlanJualController@download')->name('planjual.download'); //coba dd

    //Synch To ISADb
    //OrderPenjualan
    Route::get('synch/orderpenjualan/header', 'SynchController@synchDOHeader')->name('synch.orderpenjualan.header');
    Route::get('synch/orderpenjualan/detail', 'SynchController@synchDODetail')->name('synch.orderpenjualan.detail');
    //NotaPenjualan
    Route::get('synch/notapenjualan/header', 'SynchController@synchPJHeader')->name('synch.notapenjualan.header');
    Route::get('synch/notapenjualan/detail', 'SynchController@synchPJDetail')->name('synch.notapenjualan.detail');
    Route::get('synch/notapenjualan/koreksi', 'SynchController@synchPJKoreksi')->name('synch.notapenjualan.koreksi');
    //ReturPenjualan
    Route::get('synch/returpenjualan/header', 'SynchController@synchRJHeader')->name('synch.returpenjualan.header');
    Route::get('synch/returpenjualan/detail', 'SynchController@synchRJDetail')->name('synch.returpenjualan.detail');
    Route::get('synch/returpenjualan/koreksi', 'SynchController@synchRJKoreksi')->name('synch.returpenjualan.koreksi');
    //Pengiriman
    Route::get('synch/pengiriman/header', 'SynchController@synchPEHeader')->name('synch.pengiriman.header');
    Route::get('synch/pengiriman/detail', 'SynchController@synchPEDetail')->name('synch.pengiriman.detail');
    //Order Pembelian
    Route::get('synch/orderpembelian/header', 'SynchController@synchOHHeader')->name('synch.orderpembelian.header');
    Route::get('synch/orderpembelian/detail', 'SynchController@synchOHDetail')->name('synch.orderpembelian.detail');
    //Nota Pembelian
    Route::get('synch/notapembelian/header', 'SynchController@synchPBHeader')->name('synch.notapembelian.header');
    Route::get('synch/notapembelian/detail', 'SynchController@synchPBDetail')->name('synch.notapembelian.detail');
    Route::get('synch/notapembelian/koreksi', 'SynchController@synchPBKoreksi')->name('synch.notapembelian.koreksi');
    //Retur Pembelian
    Route::get('synch/returpembelian/header', 'SynchController@synchRBHeader')->name('synch.returpembelian.header');
    Route::get('synch/returpembelian/detail', 'SynchController@synchRBDetail')->name('synch.returpembelian.detail');
    Route::get('synch/returpembelian/koreksi', 'SynchController@synchRBKoreksi')->name('synch.returpembelian.koreksi');

    Route::get('synch/token', 'SynchController@token')->name('synch.token');

    // standar stock
    Route::get('transaksi/standarstock','StandarStockController@index')->name('standarstock.index');
    Route::get('transaksi/standarstock/getdata','StandarStockController@getData')->name('standarstock.getdata');
    Route::post('transaksi/standarstock/chekdata','StandarStockController@checkData')->name('standarstock.chekdata');
    Route::post('transaksi/standarstock/insertdata','StandarStockController@insertData')->name('standarstock.insertdata');
    Route::get('transaksi/standarstock/laporan','StandarStockController@laporan')->name('standarstock.laporan');
    Route::match(['GET', 'POST'],'transaksi/standarstock/laporanstandarstock','StandarStockController@laporanStandarStock')->name('standarstock.laporanStandarStock');

});
