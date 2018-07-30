@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('returpenjualan.index') }}">Retur Penjualan</a></li>
    <li class="breadcrumb-item active">Tambah Transaksi</li>
@endsection

@section('main_container')
    <div class="">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Tambah Retur Penjualan - {{$subcabanguser}}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @can('returpenjualan.tambah')
                    <form class="form-horizontal form-label-left" id="formRetur" method="POST" action="{{route('returpenjualan.tambah')}}">
                    @else
                    <form class="form-horizontal form-label-left" id="formRetur" method="POST" action="#">
                    @endcan
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c1">C1</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="text" id="c1" name="c1" class="form-control" value="{{$subcabanguser}}" tabindex="-1" readonly>
                                    </div>
                                    <p class="help-block muted">Kode Cabang Omset</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c2">C2 <span class="required">*</span></label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="c2" class="form-control" placeholder="C2" autocomplete="off" autofocus required value="{{$subcabanguser}}">
                                            <input type="hidden" id="c2id" name="c2id" value="{{$subcabang}}">
                                        </div>
                                    </div>
                                    <p class="help-block muted">Kode Cabang Pengirim</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglmpr">Tgl. MPR <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="tglmpr" name="tglmpr" class="tgl form-control" value="{{date('d-m-Y')}}" data-inputmask="'mask': 'd-m-y'" required>
                                    </div>
                                    <p class="help-block muted">Tanggal Toko Request Retur</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nompr">No. MPR <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="nompr" name="nompr" class="form-control" placeholder="No. MPR" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglsppb">Tgl. SPPB</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        {{-- <input type="text" id="tglsppb" name="tglsppb" class="tgl form-control" value="{{date('d-m-Y')}}" readonly> --}}
                                        <input type="text" id="tglsppb" name="tglsppb" class="tgl form-control" readonly>
                                    </div>
                                    <p class="help-block muted">Tanggal Proses Adm. Retur</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglnjr">Tgl. NRJ</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="tglnjr" name="tglnjr" class="tgl form-control" readonly>
                                    </div>
                                    <p class="help-block muted">Tanggal Pengakuan Omset / Stok Masuk</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nonrj">No. NRJ</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="nonrj" name="nonrj" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="toko">Toko <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="toko" class="form-control" placeholder="Toko" autocomplete="off" required>
                                            <input type="hidden" id="tokoid" name="tokoid">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="alamat">Alamat</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="alamat" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kota">Kota</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="kota" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="daerah">Kecamatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="kecamatan" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="wilid">WILID</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="text" id="wilid" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="noinduktoko">No. Induk Toko</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="text" id="noinduktoko" class="form-control" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="expedisi" class="form-control" placeholder="Expedisi" value="{{isset($expedisi)? $expedisi->namaexpedisi:''}}" autocomplete="off" required>
                                            <input type="hidden" id="expedisiid" name="expedisiid" value="{{isset($expedisi)? $expedisi->id:''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="karyawanexpedisi">Staff Expedisi <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="karyawanexpedisi" class="form-control" placeholder="Staff Expedisi" value="{{isset($karyawanexpedisi)? $karyawanexpedisi->namakaryawanexpedisi:''}}" autocomplete="off" required>
                                            <input type="hidden" id="karyawanidexpedisi" name="karyawanidexpedisi" value="{{isset($karyawanexpedisi)? $karyawanexpedisi->id:''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="karyawanpenjualan">Staff Penjualan <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="karyawanpenjualan" class="form-control" placeholder="Staff Penjualan" value="{{isset($karyawanpenjualan)? $karyawanpenjualan->namakaryawanpenjualan:''}}" autocomplete="off" required>
                                            <input type="hidden" id="karyawanidpenjualan" name="karyawanidpenjualan" value="{{isset($karyawanpenjualan)? $karyawanpenjualan->id:''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="karyawanstock">Staff Stok</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="karyawanstock" class="form-control" placeholder="Staff Stok" value="{{isset($karyawanstock)? $karyawanstock->namakaryawanstock:''}}" autocomplete="off" readonly>
                                            <input type="hidden" id="karyawanidstock" name="karyawanidstock" value="{{isset($karyawanstock)? $karyawanstock->id:''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="keterangan">Catatan / Keterangan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Catatan / Keterangan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @can('returpenjualan.tambah')
                                <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                                <button type="button" id="btnTambah" class="btn btn-default"><i class="fa fa-plus"></i> Tambah Detail</button>
                                @endcan
                                <table id="tableDetail" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Barang</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-center">Qty. MPR</th>
                                            {{-- <th class="text-center">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDetail">
                                    </tbody>
                                    {{-- <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th colspan="2" class="text-right" id="txt_total">Rp. 0</th>
                                        </tr>
                                    </tfoot> --}}
                                </table>
                                <div class="ln_solid"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{route('returpenjualan.index')}}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal pilih subcabang -->
    <div class="modal fade" id="modalSubCabang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Sub Cabang</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQuerySubCabang" class="form-control" placeholder="Kode/Nama Sub Cabang">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Sub Cabang</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodySubCabang" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="2" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihSubCabang" class="btn btn-primary">Pilih</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of modal pilih subcabang -->

    <!-- modal pilih toko -->
    <div class="modal fade" id="modalToko" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Toko</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQueryToko" class="form-control" placeholder="Nama Toko">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Toko</th>
                                            <th class="text-center">Alamat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyToko" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="2" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihToko" class="btn btn-primary">Pilih</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of modal pilih toko -->

    <!-- modal pilih expedisi -->
    <div class="modal fade" id="modalExpedisi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Expedisi</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQueryExpedisi" class="form-control" placeholder="Nama Expedisi">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Expedisi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyExpedisi" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="2" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihExpedisi" class="btn btn-primary">Pilih</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of modal pilih expedisi -->

    <!-- modal pilih barang -->
    <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Barang</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQueryBarang" class="form-control" placeholder="Kode/Nama Barang">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Stok Gudang</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyBarang" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="3" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihBarang" class="btn btn-primary">Pilih</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of modal pilih barang -->

    <!-- modal pilih staff -->
    <div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Staff</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryStaff">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQueryStaff" class="form-control" placeholder="Kode/Nama Staff">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NIK Lama</th>
                                            <th class="text-center">NIK HRD</th>
                                            <th class="text-center">Nama Staff</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyStaff" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="3" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" id="btnPilihStaff" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of modal pilih staff -->

    <div class="modal fade" id="modalKategoriRetur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Kategori NRJ</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryKategoriRetur">Masukkan kata kunci pencarian</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input type="text" id="txtQueryKategoriRetur" class="form-control" placeholder="Kode/Nama Kategori NRJ">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Kategori NRJ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyKategoriRetur" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="2" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" id="btnPilihKategoriRetur" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNPJD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Daftar Nota Penjualan Detail</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No. Nota</th>
                                            <th class="text-center">Tgl. Nota</th>
                                            <th class="text-center">Qty. Nota</th>
                                            {{-- <th class="text-center">Qty. Terima</th> --}}
                                            <th class="text-center">Qty. Retur</th>
                                            <th class="text-center">Harga Satuan Bruto</th>
                                            <th class="text-center">Disc1</th>
                                            <th class="text-center">Disc2</th>
                                            <th class="text-center">Harga Satuan Netto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyNPJD" class="tbodySelect">
                                        <tr class="kosong">
                                            <td colspan="9" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" id="btnPilihNPJD" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
                </div>
                <form id="formKewenangan" action="{{route('returpenjualan.kewenangan')}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" id="permission" name="permission">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="userKewenangan">Username</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="userKewenangan" name="userKewenangan" class="form-control" placeholder="Username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="passKewenangan">Password</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="password" id="passKewenangan" name="passKewenangan" class="form-control" placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Detail Retur Penjualan</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="historis">Dari Riwayat NPJ</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="checkbox">
                                    <input type="checkbox" class="flat" id="historis" checked name="historis" value="1" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                                    <input type="hidden" id="barangid" name="barangid">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
                            <div class="form-group row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="kodebarang" class="form-control" tabindex="-1" readonly>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="satuan" class="form-control" tabindex="-1" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtympr">Qty MPR <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtympr" name="qtympr" class="form-control hitungnetto" placeholder="Qty MPR" required="required">
                            </div>
                            <p class="help-block muted">Jumlah yg diajukan retur sesuai memo permohonan retur</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty SPPB</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtysppb" name="qtysppb" class="form-control" tabindex="-1" readonly>
                            </div>
                            <p class="help-block muted">Jumlah yg diterima Penjualan</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty NRJ</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtynrj" name="qtynrj" class="form-control" tabindex="-1" readonly>
                            </div>
                            <p class="help-block muted">Jumlah yg diterima Stok</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuanbrutto">Harga Satuan Brutto <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgsatuanbrutto" name="hrgsatuanbrutto" class="form-control hitungnetto" placeholder="Harga Brutto" required="required" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc1">Disc 1</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="disc1" name="disc1" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 1" required="required" readonly>
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgdisc1">Harga Setelah Disc 1</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgdisc1" class="form-control" tabindex="-1" readonly>
                                </div>
                            </div>
                            <p class="help-block muted">= Harga Brutto - (Disc 1 x Harga Brutto)</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc2">Disc 2</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="disc2" name="disc2" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 2" required="required" readonly>
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgdisc2">Harga Setelah Disc 2</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgdisc2" class="form-control" tabindex="-1" readonly>
                                </div>
                            </div>
                            <p class="help-block muted">= Harga Setelah Disc 1 - (Disc 2 x Harga Setelah Disc 2)</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ppn">PPN</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="ppn" name="ppn" class="form-control hitungnetto" step="0.01" value="0" placeholder="PPN" required="required" readonly>
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuannetto">Harga Satuan Netto</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgsatuannetto" name="hrgsatuannetto" class="form-control" tabindex="-1" readonly>
                                </div>
                            </div>
                            <p class="help-block muted">= Harga Setelah Disc 2 + PPN</p>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategoriidrpj">Kategori RJ</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="kategorirpj" name="kategorirpj" class="form-control" placeholder="Kategori RJ" autocomplete="off" required="required">
                                    <input type="hidden" id="kategoriidrpj" name="kategoriidrpj">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatan">Catatan</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="catatan" name="catatan" rows="3" class="form-control" placeholder="Catatan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="returid" name="returid" value="" readonly>
                        <input type="hidden" id="npjdid" name="npjdid" value="" readonly>
                        <input type="hidden" id="notapenjualanid" name="notapenjualanid" value="" readonly>
                        <input type="hidden" id="qtynota" name="qtynota" value="" readonly>
                        <input type="hidden" id="qtyterima" name="qtyterima" value="" readonly>
                        {{-- <input type="hidden" id="hrgsatuanbrutto" name="hrgsatuanbrutto" value="" readonly> --}}
                        {{-- <input type="hidden" id="disc1" name="disc1" value="" readonly> --}}
                        {{-- <input type="hidden" id="disc2" name="disc2" value="" readonly> --}}
                        {{-- <input type="hidden" id="ppn" name="ppn" value="" readonly> --}}
                        {{-- <input type="hidden" id="hrgsatuannetto" name="hrgsatuannetto" value="" readonly> --}}
                        <input type="hidden" id="qtyretur" name="qtyretur" value="" readonly>
                        <input type="hidden" id="maxqty" name="maxqty" value="" readonly>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Form Validation -->
    <script src="{{asset('assets/js/validator.js')}}"></script>

    <script type="text/javascript">
        var table1;
        var tipestaff;

        $(document).ready(function(){
            $(".tgl").inputmask();
            reloadtabledetail();
            $('.modal').on('show.bs.modal', function(event) {
                var idx = $('.modal:visible').length;
                $(this).css('z-index', 1040 + (10 * idx));
            });
            $('.modal').on('shown.bs.modal', function(event) {
                var idx = ($('.modal:visible').length) -1; // raise backdrop after animation.
                $('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
                $('.modal-backdrop').not('.stacked').addClass('stacked');
            });
            $(document).on('hidden.bs.modal', '.modal', function () { //fix modal's scroll
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });

            $('#modalDetail').on('shown.bs.modal', function () {
                $('#barang').focus();
            });
            $('#modalSubCabang').on('shown.bs.modal', function () {
                $('#txtQuerySubCabang').focus();
            });
            $('#modalToko').on('shown.bs.modal', function () {
                $('#txtQueryToko').focus();
            });
            $('#modalExpedisi').on('shown.bs.modal', function () {
                $('#txtQueryExpedisi').focus();
            });
            $('#modalBarang').on('shown.bs.modal', function () {
                $('#txtQueryBarang').focus();
            });
            $('#modalStaff').on('shown.bs.modal', function () {
                $('#txtQueryStaff').focus();
            });

            $('#c2').on('keypress', function(e){
                if (e.keyCode == '13') {
                    $('#modalSubCabang').modal('show');
                    $('#txtQuerySubCabang').val($(this).val());
                    search_subcabang($('#txtQuerySubCabang').val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#c2id').val('');
            });
            $('#txtQuerySubCabang').on('keypress', function(e){
                if (e.keyCode == '13') {
                    search_subcabang($(this).val());
                    return false;
                }
            });
            $('#btnPilihSubCabang').on('click', function(){
                pilih_subcabang();
            });
            $('#modalSubCabang').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_subcabang();
                }
            });

            $('#toko').on('keypress', function(e){
                if (e.keyCode == '13') {
                    $('#modalToko').modal('show');
                    $('#txtQueryToko').val($(this).val());
                    search_toko($('#txtQueryToko').val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#tokoid').val('');
            });
            $('#txtQueryToko').on('keypress', function(e){
                if (e.keyCode == '13') {
                    search_toko($(this).val());
                    return false;
                }
            });
            $('#btnPilihToko').on('click', function(){
                pilih_toko();
            });
            $('#modalToko').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_toko();
                }
            });

            $('#expedisi').on('keypress', function(e){
                if (e.keyCode == '13') {
                    var recowner = "{{session('subcabang')}}";
                    $('#modalExpedisi').modal('show');
                    $('#txtQueryExpedisi').val($(this).val());
                    search_expedisi(recowner, $(this).val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#expedisiid').val('');
            });
            $('#txtQueryExpedisi').on('keypress', function(e){
                if (e.keyCode == '13') {
                    var recowner = "{{session('subcabang')}}";
                    search_expedisi(recowner, $(this).val());
                    return false;
                }
            });
            $('#btnPilihExpedisi').on('click', function(){
                pilih_expedisi();
            });
            $('#modalExpedisi').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_expedisi();
                }
            });

            $('#barang').on('keypress', function(e){
                if (e.keyCode == '13') {
                    var tipe = $('#tipetransaksi').val();
                    $('#modalBarang').modal('show');
                    $('#txtQueryBarang').val($(this).val());
                    search_barang($(this).val(), tipe);
                    return false;
                }
            }).on('keydown', function(e){
                $('#barangid').val('');
            });
            $('#txtQueryBarang').on('keypress', function(e){
                if (e.keyCode == '13') {
                    var tipe = $('#tipetransaksi').val();
                    search_barang($(this).val(), tipe);
                    return false;
                }
            });
            $('#btnPilihBarang').on('click', function(){
                pilih_barang();
            });
            $('#modalBarang').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_barang();
                }
            });

            $('#karyawanexpedisi').on('keypress', function(e){
                if (e.keyCode == '13') {
                    tipestaff = 'expedisi';

                    $('#modalStaff').modal('show');
                    $('#txtQueryStaff').val($(this).val());
                    search_staff($(this).val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#karyawanidexpedisi').val('');
            });
            $('#karyawanpenjualan').on('keypress', function(e){
                if (e.keyCode == '13') {
                    tipestaff = 'penjualan';

                    $('#modalStaff').modal('show');
                    $('#txtQueryStaff').val($(this).val());
                    search_staff($(this).val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#karyawanidpenjualan').val('');
            });
            $('#karyawanstock').on('keypress', function(e){
                if (e.keyCode == '13') {
                    tipestaff = 'stock';

                    $('#modalStaff').modal('show');
                    $('#txtQueryStaff').val($(this).val());
                    search_staff($(this).val());
                    return false;
                }
            }).on('keydown', function(e){
                $('#karyawanidstock').val('');
            });
            $('#txtQueryStaff').on('keypress', function(e){
                if (e.keyCode == '13') {
                    search_staff($(this).val());
                    return false;
                }
            });
            $('#btnPilihStaff').on('click', function(){
                pilih_staff();
            });
            $('#modalStaff').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_staff();
                }
            });

            $('#txtQueryNPJD').on('keypress', function(e){
                if (e.keyCode == '13') {
                    search_npjd($(this).val());
                    return false;
                }
            });

            $('#tbodyNPJD').on('click', 'tr', function(){
                $('.selected').removeClass('selected');
                $(this).addClass("selected");
            });

            $('#btnPilihNPJD').on('click', function(){
                pilih_npjd();
            });

            $('#modalNPJD').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_npjd();
                }
            });

            $('#kategorirpj').on('keypress', function(e){
                if (e.keyCode == '13') {
                    $('#modalKategoriRetur').modal('show');
                    $('#txtQueryKategoriRetur').val($(this).val());
                    search_kategoriretur($(this).val());

                    $('#txtQueryKategoriRetur').focus();
                    return false;
                }
            });

            $('#txtQueryKategoriRetur').on('keypress', function(e){
                if (e.keyCode == '13') {
                    search_kategoriretur($(this).val());
                    return false;
                }
            });

            $('#tbodyKategoriRetur').on('click', 'tr', function(){
                $('.selected').removeClass('selected');
                $(this).addClass("selected");
            });

            $('#btnPilihKategoriRetur').on('click', function(){
                pilih_kategoriretur();
            });

            $('#modalKategoriRetur').on('keypress', function(e){
                if (e.keyCode == '13') {
                    pilih_kategoriretur();
                }
            });

            $('.tbodySelect').on('click', 'tr', function(){
                $('.selected').removeClass('selected');
                $(this).addClass("selected");
            });

            $('#formRetur').submit(function(e){
                e.preventDefault();
                if ($('#c2id').val()=='') {
                    $('#c2').focus();
                    swal("Ups!", "C2 belum dipilih.", "error");
                    return false;
                }
                if ($('#tokoid').val()=='') {
                    $('#toko').focus();
                    swal("Ups!", "Toko belum dipilih.", "error");
                    return false;
                }
                if ($('#expedisiid').val()=='') {
                    $('#expedisi').focus();
                    swal("Ups!", "Expedisi belum dipilih.", "error");
                    return false;
                }
                if ($('#karyawanidexpedisi').val()=='') {
                    $('#karyawanexpedisi').focus();
                    swal("Ups!", "Staff Expedisi belum dipilih.", "error");
                    return false;
                }
                if ($('#karyawanidpenjualan').val()=='') {
                    $('#karyawanpenjualan').focus();
                    swal("Ups!", "Staff belum dipilih.", "error");
                    return false;
                }
                // if ($('#karyawanidstok').val()=='') {
                //     $('#karyawanstok').focus();
                //     swal("Ups!", "Staff Stok belum dipilih.", "error");
                //     return false;
                // }

                $.ajax({
                    type    : 'POST',
                    url     : $(this).attr('action'),
                    data    : $(this).serialize(),
                    dataType: 'json',
                    success : function(data){
                        console.log(data);
                        $('#btnSimpan').hide();
                        $('#c2').attr('disabled',true);
                        $('#nompr').attr('disabled',true);
                        $('#tglmpr').attr('disabled',true);
                        $('#toko').attr('disabled',true);
                        $('#expedisi').attr('disabled',true);
                        $('#karyawanexpedisi').attr('disabled',true);
                        $('#karyawanpenjualan').attr('disabled',true);
                        $('#keterangan').attr('disabled',true);
                        $('#modalDetail #returid').val(data.returid);
                        $('#modalDetail').modal('show');
                    },
                    error: function(data){
                        console.log(data);
                    }
                })
            });

            $('#historis').on('ifUnchecked', function(event){
                $('#modalKewenangan').find('#permission').val('returpenjualan.historis');
                $('#modalKewenangan').modal('show');
            });

            $('#historis').on('ifChecked', function(event){
                $('#hrgsatuanbrutto').attr('readonly');
                $('#disc1').attr('readonly');
                $('#disc2').attr('readonly');
                $('#ppn').attr('readonly');
            });

            $('.hitungnetto').on('keyup', function(){
                var qtympr   = Number($('#qtympr').val());
                var bruto    = Number($('#hrgsatuanbrutto').val());
                var disc1    = Number($('#disc1').val())/100;
                var hrgdisc1 = Math.round((1 - disc1) * bruto, 2);
                var disc2    = Number($('#disc2').val())/100;
                var hrgdisc2 = Math.round((1 - disc2) * hrgdisc1, 2);
                var ppn      = Number($('#ppn').val())/100;
                var hrgsatuannetto = Math.round((1 + ppn) * hrgdisc2, 2);
                $('#hrgdisc1').val(hrgdisc1);
                $('#hrgdisc2').val(hrgdisc2);
                $('#hrgsatuannetto').val(hrgsatuannetto);
            });

            $('#btnTambah').on('click', function(e){
                e.preventDefault();
                
                $('#modalDetail').modal('show');
                // if($('#returid').val() == '') {
                //     swal("Ups!", "Tidak bisa tambah record. Retur Penjualan belum disimpan.", "error");
                //     return false;
                // }else{
                //     $('#modalDetail').modal('show');
                // }
            });

            $('#formDetail').on('submit', function(e){
                e.preventDefault();

                if($('#returid').val() == '') {
                    swal("Ups!", "Tidak bisa simpan record. Retur Penjualan belum disimpan.", "error");
                    $('#modalDetail').modal('hide');
                    return false;
                // }else if(($('#historis').is(':checked')) && parseInt($('#qtympr').val()) > parseInt($('#maxqty').val())) {
                //     swal("Ups!", "Tidak bisa simpan record. Nilai Qty. PRB lebih besar dari sisa Qty. terima di penjualan. Hubungi Manager anda atau isi nilai Qty. PRB kekurangannya di nota record baru.", "error");
                //     return false;
                }else{
                    $.ajax({
                        type    : 'POST',
                        url     : '{{route('returpenjualan.tambah.detail')}}',
                        data    : $('#formDetail').serialize(),
                        dataType: "json",
                        success : function(data){
                            table1.destroy();

                            var x = '<tr>';
                            x += '<td>'+ $('#barang').val() +'</td>';
                            x += '<td>'+ $('#satuan').val() +'</td>';
                            x += '<td class"text-right">'+ $('#qtympr').val() +'</td>';
                            // x += '<td class="text-center">';
                            // x += '    <a class="btn btn-xs text-success details-control"><i class="fa fa-search-plus"></i></a>';
                            // // x += '    <a class="btn btn-sm text-danger btnRemove"><i class="fa fa-times"></i></a>';
                            // x += '</td>';
                            x += '</tr>';

                            $('#tbodyDetail').append(x);

                            swal({
                                title: "Sukses!",
                                text : "Tambah Retur Detail Berhasil.",
                                type : "success",
                                html : true
                            },function(){
                                setTimeout(function(){
                                    $('#formDetail').find("input[name!='_token'][name!='historis'][name!='returid']").val('');
                                    $('#formDetail').find("textarea").val('');

                                    reloadtabledetail();
                                    $('#barang').focus();
                                },0)
                            });

                            return false;
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                }

                return false;
            });

            $('#formKewenangan').on('submit', function(e){
                e.preventDefault();

                $("#formKewenangan #userKewenangan").val($("#formKewenangan #userKewenangan").val().trim().toUpperCase()); 

                $.ajax({
                    type: 'POST',
                    url : '{{route("returpenjualan.kewenangan")}}',
                    data: $('#formKewenangan').serialize(),
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            $('#historis').iCheck('uncheck'); 
                            $('#hrgsatuanbrutto').removeAttr('readonly');
                            $('#disc1').removeAttr('readonly');
                            $('#disc2').removeAttr('readonly');
                            $('#ppn').removeAttr('readonly');
                        }else{
                            swal('Ups!', 'Tidak Memiliki Hak Akses.','error');
                            $('#historis').iCheck('check'); 
                            $('#hrgsatuanbrutto').attr('readonly');
                            $('#disc1').attr('readonly');
                            $('#disc2').attr('readonly');
                            $('#ppn').attr('readonly');
                        }
                    },
                    error: function(data){
                        console.log(data);
                    }
                });

                $('#modalKewenangan').modal('hide');
                return false;
            });

            $('#tableDetail tbody').on('click', 'td a.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table1.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            });
        });

        function search_subcabang(query){
            $.ajax({
                type: 'GET',
                url: '{{route("orderpenjualan.getsubcabang",null)}}/' + query,
                success: function(data){
                    var subcabang = JSON.parse(data);
                    $('#tbodySubCabang tr').remove();
                    var x = '';
                    if (subcabang.length > 0) {
                        for (var i = 0; i < subcabang.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ subcabang[i].kodesubcabang +'<input type="hidden" class="id_sub" value="'+ subcabang[i].id +'"></td>';
                            x += '<td>'+ subcabang[i].namasubcabang +'</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="2" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodySubCabang').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        function pilih_subcabang(){
            if ($('#tbodySubCabang').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "Sub Cabang belum dipilih.", "error");
                return false;
            }else {
                $('#c2').val($('#tbodySubCabang').find('tr.selected td').eq(0).text());
                $('#c2id').val($('#tbodySubCabang').find('tr.selected td .id_sub').val());
                $('#modalSubCabang').modal('hide');
                $('#nompr').focus();
            }
        }

        function search_toko(query){
            $.ajax({
                type: 'GET',
                url: '{{route("orderpenjualan.gettoko",null)}}/' + query,
                success: function(data){
                    var toko = JSON.parse(data);
                    $('#tbodyToko tr').remove();
                    var x = '';
                    if (toko.length > 0) {
                        for (var i = 0; i < toko.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ toko[i].namatoko;
                            x +=        '<input type="hidden" class="idtoko" value="'+ toko[i].id +'">';
                            x +=        '<input type="hidden" class="kotatoko" value="'+ toko[i].kota +'">';
                            x +=        '<input type="hidden" class="kecamatantoko" value="'+ toko[i].kecamatan +'">';
                            x +=        '<input type="hidden" class="customwilayahtoko" value="'+ toko[i].customwilayah +'">';
                            x +=        '<input type="hidden" class="noinduktoko" value="'+ toko[i].noinduktoko +'">';
                            x +=        '<input type="hidden" class="jwkirim" value="'+ toko[i].jwkirim +'">';
                            x +=        '<input type="hidden" class="jwsales" value="'+ toko[i].jwsales +'">';
                            x +=        '<input type="hidden" class="karyawanidsalesman" value="'+ toko[i].karyawanidsalesman +'">';
                            x +=        '<input type="hidden" class="namakaryawan" value="'+ toko[i].namakaryawan +'">';
                            x += '</td>';
                            x += '<td>'+ toko[i].alamat +'</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="2" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyToko').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function pilih_toko(){
            if ($('#tbodyToko').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "Toko belum dipilih.", "error");
                return false;
            }else {
                $('#toko').val($('#tbodyToko').find('tr.selected td').eq(0).text());
                $('#tokoid').val($('#tbodyToko').find('tr.selected td .idtoko').val());
                $('#alamat').val($('#tbodyToko').find('tr.selected td').eq(1).text());
                var id = $('#tbodyToko').find('tr.selected td .idtoko').val();

                $.ajax({
                    type: 'GET',
                    url: '{{route("orderpenjualan.gettokodetail",null)}}/' + id,
                    dataType: 'json',
                    success: function(data){
                        $('#kota').val(data.kota);
                        $('#kecamatan').val(data.kecamatan);
                        $('#wilid').val(data.customwilayah);
                        $('#noinduktoko').val(data.noinduktoko);
                        $('#statustoko').val(data.statustoko);
                    },
                    error: function(data){
                    console.log(data);
                    }
                });

                $('#modalToko').modal('hide');
                $('#expedisi').focus();
            }
        }

        function search_expedisi(recowner, query){
            $.ajax({
                type: 'GET',
                url: '{{route("orderpenjualan.getexpedisi",[null,null])}}/' + recowner + '/' + query,
                success: function(data){
                    var expedisi = JSON.parse(data);
                    $('#tbodyExpedisi tr').remove();
                    var x = '';
                    if (expedisi.length > 0) {
                        for (var i = 0; i < expedisi.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ expedisi[i].kodeexpedisi +'<input type="hidden" class="id_expedisi" value="'+ expedisi[i].id +'"></td>';
                            x += '<td>'+ expedisi[i].namaexpedisi +'</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="2" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyExpedisi').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        function pilih_expedisi(){
            if ($('#tbodyExpedisi').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "Expedisi belum dipilih.", "error");
                return false;
            }else {
                $('#expedisi').val($('#tbodyExpedisi').find('tr.selected td').eq(1).text());
                $('#expedisiid').val($('#tbodyExpedisi').find('tr.selected td .id_expedisi').val());
                $('#modalExpedisi').modal('hide');
                $('#tipetransaksi').focus();
            }
        }

        function search_barang(query, tipe){
            $.ajax({
                type: 'GET',
                url: '{{route("orderpembelian.getbarang",[null,null])}}/' + query + '/' + tipe,
                success: function(data){
                    var barang = JSON.parse(data);
                    $('#tbodyBarang tr').remove();
                    var x = '';
                    if (barang.length > 0) {
                        for (var i = 0; i < barang.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ barang[i].kodebarang +'<input type="hidden" class="id_brg" value="'+ barang[i].id +'"><input type="hidden" class="satuan" value="'+barang[i].satuan+'"></td>';
                            x += '<td>'+ barang[i].namabarang +'</td>';
                            x += '<td>?</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyBarang').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        function pilih_barang(){
            if ($('#tbodyBarang').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "Barang belum dipilih.", "error");
                return false;
            }else {
                $('#kodebarang').val($('#tbodyBarang').find('tr.selected td').eq(0).text());
                $('#barang').val($('#tbodyBarang').find('tr.selected td').eq(1).text());
                $('#barangid').val($('#tbodyBarang').find('tr.selected td .id_brg').val());
                $('#satuan').val($('#tbodyBarang').find('tr.selected td .satuan').val());
                $('#modalBarang').modal('hide');

                if($('#historis').is(':checked')) {
                    search_npjd($('#barangid').val());
                }else{
                    $('#qtympr').focus();
                }
            }
        }

        function search_staff(query){
            $.ajax({
                type    : 'POST',
                url     : '{{route("transaksi.getstaff")}}',
                data    : { katakunci:query, _token:"{{ csrf_token() }}"},
                dataType: 'json',
                success : function(data){
                    $('#tbodyStaff tr').remove();
                    var x = '';
                    console.log(data.length);
                    if (data.length > 0) {
                        $.each(data, function (i,node) {
                            x += '<tr>';
                            x += '<td>'+ node.niksystemlama +'<input type="hidden" class="id_sup" value="'+ node.id +'"></td>';
                            x += '<td>'+ node.nikhrd +'</td>';
                            x += '<td>'+ node.namakaryawan +'</td>';
                            x += '</tr>';
                        });
                    }else {
                        x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyStaff').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function pilih_staff(){
            if ($('#tbodyStaff').find('tr.selected td').eq(2).text() == '') {
                swal("Ups!", "staff belum dipilih.", "error");
                return false;
            }else {
                $('#karyawan'+tipestaff).val($('#tbodyStaff').find('tr.selected td').eq(2).text());
                $('#karyawanid'+tipestaff).val($('#tbodyStaff').find('tr.selected td .id_sup').val());
                $('#modalStaff').modal('hide');
                $('#karyawan'+tipestaff).focus();
                tipestaff = null;
            }
        }

        function search_npjd(query){
            $.ajax({
                type: 'POST',
                url : '{{route("penjualan.getnpjd")}}',
                data: {_token:"{{ csrf_token() }}", barangid : query, tokoid : $('#tokoid').val()},
                dataType: "json",
                success : function(data){
                    // var data_json = JSON.parse(data);
                    var data_json = data;
                    $('#tbodyNPJD tr').remove();
                    var x = '';
                    if (data_json.length > 0) {
                        for (var i = 0; i < data_json.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ data_json[i].nonota +'</td>';
                            x += '<td>'+ data_json[i].tglnota +'</td>';
                            x += '<td class="text-right">'+ data_json[i].qtynota +'</td>';
                            // x += '<td class="text-right">'+ data_json[i].qtyterima +'</td>';
                            x += '<td class="text-right">'+ data_json[i].qtyretur +'</td>';
                            x += '<td class="text-right">'+ data_json[i].hrgsatuanbrutto +'</td>';
                            x += '<td class="text-right">'+ data_json[i].disc1 +'</td>';
                            x += '<td class="text-right">'+ data_json[i].disc2 +'</td>';
                            // x += '<td class="text-right">'+ data_json[i].hrgsatuannetto +'</td>';
                            x += '<td class="text-right">'+ data_json[i].hrgsatuannetto;
                            // x += '<td>'+ data_json[i].lastupdatedon;
                            x += '        <input type="hidden" class="npjdid" value="'+ data_json[i].id +'">';
                            x += '        <input type="hidden" class="notapenjualanid" value="'+ data_json[i].notapenjualanid +'">';
                            x += '        <input type="hidden" class="stockid" value="'+ data_json[i].stockid +'">';
                            x += '        <input type="hidden" class="qtynota" value="'+ data_json[i].qtynota +'">';
                            x += '        <input type="hidden" class="qtyterima" value="'+ data_json[i].qtyterima +'">';
                            x += '        <input type="hidden" class="hrgsatuanbrutto" value="'+ data_json[i].hrgsatuanbrutto +'">';
                            x += '        <input type="hidden" class="disc1" value="'+ data_json[i].disc1 +'">';
                            x += '        <input type="hidden" class="disc2" value="'+ data_json[i].disc2 +'">';
                            x += '        <input type="hidden" class="hrgdisc1" value="'+ data_json[i].hrgdisc1 +'">';
                            x += '        <input type="hidden" class="hrgdisc2" value="'+ data_json[i].hrgdisc2 +'">';
                            x += '        <input type="hidden" class="ppn" value="'+ data_json[i].ppn +'">';
                            x += '        <input type="hidden" class="hrgsatuannetto" value="'+ data_json[i].hrgsatuannetto +'">';
                            x += '        <input type="hidden" class="qtyretur" value="'+ data_json[i].qtyretur +'">';
                            x += '</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="15" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyNPJD').append(x);
                    $('#modalNPJD').modal('show');
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function pilih_npjd(){
            if ($('#tbodyNPJD').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "NPJD belum dipilih.", "error");
                return false;
            }else {
                $('#npjdid').val($('#tbodyNPJD').find('tr.selected td .npjdid').val());
                $('#notapenjualanid').val($('#tbodyNPJD').find('tr.selected td .notapenjualanid').val());
                $('#stockid').val($('#tbodyNPJD').find('tr.selected td .stockid').val());
                $('#qtynota').val($('#tbodyNPJD').find('tr.selected td .qtynota').val());
                $('#qtyterima').val($('#tbodyNPJD').find('tr.selected td .qtyterima').val());
                $('#hrgsatuanbrutto').val($('#tbodyNPJD').find('tr.selected td .hrgsatuanbrutto').val());
                $('#disc1').val($('#tbodyNPJD').find('tr.selected td .disc1').val());
                $('#disc2').val($('#tbodyNPJD').find('tr.selected td .disc2').val());
                $('#hrgdisc1').val($('#tbodyNPJD').find('tr.selected td .hrgdisc1').val());
                $('#hrgdisc2').val($('#tbodyNPJD').find('tr.selected td .hrgdisc2').val());
                $('#ppn').val($('#tbodyNPJD').find('tr.selected td .ppn').val());
                $('#hrgsatuannetto').val($('#tbodyNPJD').find('tr.selected td .hrgsatuannetto').val());
                $('#qtyretur').val($('#tbodyNPJD').find('tr.selected td .qtyretur').val());
                $('#maxqty').val($('#tbodyNPJD').find('tr.selected td .qtyterima').val()-$('#tbodyNPJD').find('tr.selected td .qtyretur').val());
                $('#modalNPJD').modal('hide');

                $('#barang').focus();
            }
        }

        function search_kategoriretur(query){
            $.ajax({
                type: 'GET',
                url: '{{route("pembelian.getkategoriretur",[null])}}/' + query,
                success: function(data){
                    var data_json = JSON.parse(data);
                    $('#tbodyKategoriRetur tr').remove();
                    var x = '';
                    if (data_json.length > 0) {
                        for (var i = 0; i < data_json.length; i++) {
                            x += '<tr>';
                            x += '<td>'+ data_json[i].kode +'<input type="hidden" class="id_kategori" value="'+ data_json[i].id +'"></td>';
                            x += '<td>'+ data_json[i].nama +'</td>';
                            x += '</tr>';
                        }
                    }else {
                        x += '<tr><td colspan="15" class="text-center">Tidak ada detail</td></tr>';
                    }
                    $('#tbodyKategoriRetur').append(x);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function pilih_kategoriretur(){
            if ($('#tbodyKategoriRetur').find('tr.selected td').eq(1).text() == '') {
                swal("Ups!", "Kategori NRJ belum dipilih.", "error");
                return false;
            }else {
                $('#kategorirpj').val($('#tbodyKategoriRetur').find('tr.selected td').eq(0).text()+' '+$('#tbodyKategoriRetur').find('tr.selected td').eq(1).text());
                $('#kategoriidrpj').val($('#tbodyKategoriRetur').find('tr.selected td .id_kategori').val());
                $('#modalKategoriRetur').modal('hide');
                $('#kategorirpj').focus();
            }
        }

        function reloadtabledetail(){
            table1 = $('#tableDetail').DataTable({
                "searching": false,
                "ordering": false,
                "info": false,
                "paging": false,
                responsive: true,
                language: {
                    "emptyTable": "Tidak ada detail",
                },
                "columns": [
                    { "data": "barang" },
                    { "data": "satuan" },
                    { "data": "qtympr" },
                    // { "data": "action" },
                ],
            });
        }
    </script>
@endpush