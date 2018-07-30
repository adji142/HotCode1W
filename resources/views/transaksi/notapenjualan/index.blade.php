@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('notapenjualan.index') }}">Nota Penjualan</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Nota Penjualan</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float: right;">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label style="margin-right: 10px;">Tgl. Picking List</label>
                                    <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                                    <label>-</label>
                                    <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl. Picking List</th>
                                <th>No. Picking List</th>
                                <th>Toko</th>
                                <th>Salesman</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Picking List</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. Picking List</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Toko</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Salesman</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl. Proforma</th>
                                <th>Tgl. Nota/Terima</th>
                                <th>No. Nota</th>
                                <th>Hrg. Total Nota</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl. Proforma</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Tgl. Nota/Terima</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> No. Nota</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Hrg. Total Nota</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table3" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th width="40%">Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty. Nota</th>
                                <th>Hrg. Satuan Netto</th>
                                <th>Hrg. Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-3" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nama Barang</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-3" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Satuan</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-3" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Nota</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-3" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Hrg. Satuan Netto</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-3" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Hrg. Total</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table4" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>No. Koli</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-4" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> No. Koli</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-4" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Keterangan</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data double click -->
    <div class="modal fade" id="modalDoubleClickPenjualan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="doubleclickData" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end of data double click -->

    <!-- Data double click -->
    <div class="modal fade" id="modalTableKartuPiutang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">INFO PIUTANG</h4>
                    <h5>Toko ini memiliki koleksi BGC tolak yang nota piutangnya belum lunas :
                </div>
                <div class="modal-body">
                    {{--  <div class="row">  --}}
                        {{--  <div class="col-xs-12">  --}}
                                <table id="tabelkartupiutang" class="table table-bordered table-striped display" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tanggal Nota</th>
                                            <th class="text-center">Nomer Nota</th>
                                            <th class="text-center">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>    
                        {{--  </div>  --}}
                    {{--  </div>  --}}
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick='showModalNPJ()' data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end of data double click -->

    <!-- Modal checker dan koli insert update -->
    <div class="modal fade" id="modalCheckerKoli" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Penjualan - Isi Checker dan Koli</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaPenjualanId" class="form-clear" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. PiL</label>
                                    <input type="text" id="tglPicking" class="form-control form-clear" placeholder="Tgl. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. Proforma</label>
                                    <input type="text" id="tglProforma" class="form-control form-clear" placeholder="Tgl. Proforma" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. Nota/Terima</label>
                                    <input type="text" id="tglNota" class="form-control form-clear" placeholder="Tgl. Nota/Terima" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>No. PiL</label>
                                    <input type="text" id="noPicking" class="form-control form-clear" placeholder="No. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>No. Nota</label>
                                    <input type="text" id="noNota" class="form-control form-clear" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>Tipe Transaksi</label>
                                    <input type="text" id="tipeTransaksi" class="form-control form-clear" placeholder="Tipe Transaksi" readonly tabindex="-1">  
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="kettipetransaksi" id="tipeTransaksi_k" value="Kredit"> Kredit
                                        </label>
                                        <label>
                                            <input type="radio" name="kettipetransaksi" id="tipeTransaksi_t" value="Tunai"> Tunai
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Toko</label>
                                    <input type="text" id="toko" class="form-control form-clear" placeholder="Toko" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" id="alamat" class="form-control form-clear" placeholder="Alamat" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" id="daerah" class="form-control form-clear" placeholder="Daerah" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <input type="text" id="kota" class="form-control form-clear" placeholder="Kota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>WILID</label>
                                    <input type="text" id="wilid" class="form-control form-clear" placeholder="WILID" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>NIT</label>
                                    <input type="text" id="nit" class="form-control form-clear" placeholder="NIT" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Checker 1</label>
                                    <input type="text" id="checker1" class="form-control form-clear" placeholder="Checker 1">
                                    <input type="hidden" id="checker1id" name="checker1id" class="form-clear" value="">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Checker 2</label>
                                    <input type="text" id="checker2" class="form-control form-clear" placeholder="Checker 2">
                                    <input type="hidden" id="checker2id" name="checker2id" class="form-clear" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table id="tableCheckerKoli" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="40%">Barang</th>
                                            <th>Sat.</th>
                                            <th>Qty. Nota</th>
                                            <th>Nomor Koli</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitCheckerKoli" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of checker dan koli insert update -->

    <!-- Modal tgl terima -->
    <div class="modal fade" id="modalTglTerima" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Penjualan - Isi Tanggal Terima</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaPenjualanTerimaId" class="form-clear" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. PiL</label>
                                    <input type="text" id="tglPickingTerima" class="form-control form-clear" placeholder="Tgl. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. Proforma</label>
                                    <input type="text" id="tglProformaTerima" class="form-control form-clear" placeholder="Tgl. Proforma" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. Nota/Terima</label>
                                    <input type="text" id="tglNotaTerima" class="form-control form-clear" data-inputmask="'mask': 'd-m-y'" placeholder="Tgl. Nota/Terima">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>No. PiL</label>
                                    <input type="text" id="noPickingTerima" class="form-control form-clear" placeholder="No. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>No. Nota</label>
                                    <input type="text" id="noNotaTerima" class="form-control form-clear" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label>Tipe Transaksi</label>
                                    <input type="text" id="tipeTransaksiTerima" class="form-control form-clear" placeholder="Tipe Transaksi" readonly tabindex="-1">  
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <label></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="kettipetransaksi" id="tipeTransaksiTerima_k" value="Kredit"> Kredit
                                        </label>
                                        <label>
                                            <input type="radio" name="kettipetransaksi" id="tipeTransaksiTerima_t" value="Tunai"> Tunai
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Toko</label>
                                    <input type="text" id="tokoTerima" class="form-control form-clear" placeholder="Toko" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" id="alamatTerima" class="form-control form-clear" placeholder="Alamat" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" id="daerahTerima" class="form-control form-clear" placeholder="Daerah" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <input type="text" id="kotaTerima" class="form-control form-clear" placeholder="Kota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>WILID</label>
                                    <input type="text" id="wilidTerima" class="form-control form-clear" placeholder="WILID" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Toko ID</label>
                                    <input type="text" id="idTerima" class="form-control form-clear" placeholder="TOKO ID" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Tempo Nota</label>
                                    <input type="text" id="temponotaTerima" class="form-control form-clear" placeholder="Tempo Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Tempo Salesman</label>
                                    <input type="text" id="temposalesmanTerima" class="form-control form-clear" placeholder="Tempo Salesman" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitTglTerima" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of tgl terima -->

    <!-- Notifikasi Koreksi Penjualan -->
    <div class="modal fade" id="modalKoreksiNotaDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Penjualan - Notifikasi Koreksi Penjualan</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaId" value="">
                    <input type="hidden" id="notaIdDetail" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Informasi Nota</h2>
                            </div>
                            <div class="col-md-6">
                                <h2>Informasi Barang</h2>
                            </div>

                            <!-- Info nota -->
                            <div class="col-md-3" id="infoNotaKoreksiKiri">
                                <div class="form-group">
                                    <label>Tgl. Nota</label>
                                    <input type="text" id="tglNotaKoreksi" class="form-control" placeholder="Tgl. Nota" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>No. Nota</label>
                                    <input type="text" id="noNotaKoreksi" class="form-control" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3" id="infoNotaKoreksiKanan">
                                <div class="form-group">
                                    <label>Tgl. Proforma</label>
                                    <input type="text" id="tglProformaKoreksi" class="form-control" placeholder="Tgl. Proforma" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Salesman</label>
                                    <input type="text" id="SalesmanKoreksi" class="form-control" placeholder="Salesman" readonly tabindex="-1">
                                </div>
                            </div>

                            <!-- Info Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <input type="text" id="barangKoreksi" class="form-control" placeholder="Barang" readonly tabindex="-1">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg. Sat. Brutto</label>
                                    <input type="text" id="hrgBrutoKoreksi" class="form-control text-right" placeholder="Hrg. Sat. Brutto" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg. Sat. Netto</label>
                                    <input type="text" id="hrgNettoKoreksi" class="form-control text-right" placeholder="Hrg. Sat Netto" readonly tabindex="-1">
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Checker 1</label>
                                    <input type="text" id="checker1Koreksi" class="form-control" placeholder="Checker 1" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Checker 2</label>
                                    <input type="text" id="checker2Koreksi" class="form-control" placeholder="Checker 2" readonly tabindex="-1">
                                    <input type="hidden" id="checker2Koreksiid" value="">
                                </div>
                                <div class="form-group">                                    
                                    <label>Keterangan Koreksi</label>
                                    <textarea id="keteranganKoreksi" class="form-control textarea-noresize" placeholder="Keterangan" readonly tabindex="-1" rows="4"></textarea>
                                </div>
                            </div>

                            <!-- Data Harga -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Disc 1</label>
                                    <input type="text" id="disc1Koreksi" class="form-control text-right" placeholder="Disc. 1" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Disc 2</label>
                                    <input type="text" id="disc2Koreksi" class="form-control text-right" placeholder="Disc. 2" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg Setelah Disc 1</label>
                                    <input type="text" id="hrgDisc1Koreksi" class="form-control text-right" placeholder="Hrg. Setelah Disc. 1" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg Setelah Disc 2</label>
                                    <input type="text" id="hrgDisc2Koreksi" class="form-control text-right" placeholder="Hrg. Setelah Disc. 2" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PPN</label>
                                    <input type="text" id="ppnKoreksi" class="form-control text-right" placeholder="PPN" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty. Nota</label>
                                    <input type="text" id="qtyNotaKoreksi" class="form-control text-right" placeholder="Qty. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>
                        {{-- <hr> --}}
                        <div class="row">
                        <div class="col-md-12">
                            <div id="textNotifikasiKoreksi"><p>ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN HARGA SATUAN NETTO = 0
                        NILAI KOREKSI DICATAT PADA NOTA PENJUALAN YANG BARU.
                        ANDA YAKIN?</p></div>
                        </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitNotifikasiKoreksi" class="btn btn-primary">Ya</button>
                        <button type="button" id="btnSubmitKoreksi" class="btn btn-primary">Simpan</button>
                        <button type="button" id="btnCancelKoreksi" class="btn btn-default" data-dismiss="modal">Tidak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of koreksi -->

    <!-- Edit Qty Nota Penjualan -->
    <div class="modal fade" id="modalEditQty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Penjualan - Edit Qty Nota Penjualan</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaId" value="">
                    <input type="hidden" id="notaIdDetail" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Informasi Nota</h2>
                            </div>
                            <div class="col-md-6">
                                <h2>Informasi Barang</h2>
                            </div>

                            <!-- Info nota -->
                            <div class="col-md-3" id="infoNotaKoreksiKiri">
                                <div class="form-group">
                                    <label>Tgl. Nota</label>
                                    <input type="text" id="tglNotaEditQty" class="form-control" placeholder="Tgl. Nota" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>No. Nota</label>
                                    <input type="text" id="noNotaEditQty" class="form-control" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3" id="infoNotaEditQtyKanan">
                                <div class="form-group">
                                    <label>Tgl. Proforma</label>
                                    <input type="text" id="tglProformaEditQty" class="form-control" placeholder="Tgl. Proforma" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Salesman</label>
                                    <input type="text" id="SalesmanEditQty" class="form-control" placeholder="Salesman" readonly tabindex="-1">
                                </div>
                            </div>

                            <!-- Info Barang -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <input type="text" id="barangEditQty" class="form-control" placeholder="Barang" readonly tabindex="-1">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg. Sat. Brutto</label>
                                    <input type="text" id="hrgBrutoEditQty" class="form-control text-right" placeholder="Hrg. Sat. Brutto" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg. Sat. Netto</label>
                                    <input type="text" id="hrgNettoEditQty" class="form-control text-right" placeholder="Hrg. Sat Netto" readonly tabindex="-1">
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Checker 1</label>
                                    <input type="text" id="checker1EditQty" class="form-control" placeholder="Checker 1" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Checker 2</label>
                                    <input type="text" id="checker2EditQty" class="form-control" placeholder="Checker 2" readonly tabindex="-1">
                                    <input type="hidden" id="checker2EditQtyId" value="">
                                </div>
                                <div class="form-group">                                    
                                    <label>Keterangan EditQty</label>
                                    <textarea id="keteranganEditQty" class="form-control textarea-noresize" placeholder="Keterangan" readonly tabindex="-1" rows="4"></textarea>
                                </div>
                            </div>

                            <!-- Data Harga -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Disc 1</label>
                                    <input type="text" id="disc1EditQty" class="form-control text-right" placeholder="Disc. 1" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Disc 2</label>
                                    <input type="text" id="disc2EditQty" class="form-control text-right" placeholder="Disc. 2" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg Setelah Disc 1</label>
                                    <input type="text" id="hrgDisc1EditQty" class="form-control text-right" placeholder="Hrg. Setelah Disc. 1" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hrg Setelah Disc 2</label>
                                    <input type="text" id="hrgDisc2EditQty" class="form-control text-right" placeholder="Hrg. Setelah Disc. 2" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PPN</label>
                                    <input type="text" id="ppnEditQty" class="form-control text-right" placeholder="PPN" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty. Nota</label>
                                    <input type="hidden" id="qtyOrderEditQty">
                                    <input type="hidden" id="qtyNotaOldEditQty">
                                    <input type="text" id="qtyNotaEditQty" class="form-control text-right" placeholder="Qty. Nota">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitEditQty" class="btn btn-primary">Simpan</button>
                        <button type="button" id="btnCancelEditQty" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of koreksi -->

    <!-- Modal npj insert -->
    <div class="modal fade" id="modalNpjInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Penjualan - Nota Penjualan Insert</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="orderPenjualanNpjId" class="form-clear" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Tgl. PiL</label>
                                    <input type="text" id="tglPickingNpj" class="form-control form-clear" placeholder="Tgl. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>No. PiL</label>
                                    <input type="text" id="noPickingNpj" class="form-control form-clear" placeholder="No. PiL" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Toko</label>
                                    <input type="text" id="tokoNpj" class="form-control form-clear" placeholder="Toko" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" id="alamatNpj" class="form-control form-clear" placeholder="Alamat" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" id="daerahNpj" class="form-control form-clear" placeholder="Daerah" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <input type="text" id="kotaNpj" class="form-control form-clear" placeholder="Kota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>WILID</label>
                                    <input type="text" id="wilidNpj" class="form-control form-clear" placeholder="WILID" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Toko ID</label>
                                    <input type="text" id="tokoidNpj" class="form-control form-clear" placeholder="TOKO ID" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>RP ACC Piutang</label>
                                    <input type="text" id="rpaccpiutang" class="form-control form-clear" placeholder="RP ACC PIUTANG" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table id="tableNpj" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="50%">Barang</th>
                                            <th>Sat.</th>
                                            <th>Qty. SO ACC</th>
                                            <th>Qty. Gudang</th>
                                            <th>Qty. Nota</th>
                                            <th>Hrg. Sat Netto</th>
                                            <th>Hrg. Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">Total Hrg. Nota</td>
                                            <td id="totalharganota">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnIsiQtyNota" class="btn btn-warning">Qty Nota = Qty SO Acc</button>
                        <button type="button" id="btnSubmitNpjInsert" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of npj insert -->

    {{-- <div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pencarian Staff</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="query">Masukkan kata kunci pencarian</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="query" class="form-control" placeholder="Masukkan kata kunci pencarian">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table id="staff" class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NIK Lama</th>
                                            <th class="text-center">NIK HRD</th>
                                            <th class="text-center">Nama Pemeriksa 00</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyStaff">
                                        <tr class="kosong">
                                            <td colspan="3" class="text-center">Tidak ada detail</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihStaff" class="btn btn-primary">Pilih</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <!-- Form Kewenangan -->
    <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaId" value="">
                    <input type="hidden" id="permission" value="">
                    <input type="hidden" id="about" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="uxserKewenangan" class="form-control kecilin" placeholder="Username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="password" id="pxassKewenangan" class="form-control kecilin" placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitKewenangan" class="btn btn-primary">Proses</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    var custom_search = [
        { text : '', filter : '='},
        { text : '', filter : '='},
        { text : '', filter : '='},
        { text : '', filter : '='},
        { text : '', filter : '='},
    ];
    var filter_number = ['<=','<','=','>','>='];
    var filter_text = ['=','!='];
    var tipe = ['Find','Filter'];
    var column_index = 0;
    var last_index = '';
    var context_menu_number_state = 'hide';
    var context_menu_text_state = 'hide';
    var tipe_search = null;
    var data_piutang = {tglterima:0, nonota:0, saldo:46};
    var tableCheckerKoli, tableNpj, table, table2, table3, table4, table_index, table2_index, table3_index, table4_index, fokus, tabelkartupiutang;

    // lookup
    lookupstaff();

    $(document).ready(function(){
        $(".tgl").inputmask();
        $("#tglNotaTerima").inputmask();
        $("#tglNotaTerima").on('change',function() {
            if($(this).val()) {
                var dateStr = $(this).val().split("-");
                var dateOne = new Date(dateStr[2],dateStr[1]-1,dateStr[0]);
                var dateTwo = new Date({{date('Y,m-1,d')}});
                var dateTri = new Date({{date('Y,m-1,01')}});

                if(dateOne.getTime() > dateTwo.getTime()) {
                    swal("Ups!", "Tidak boleh isi tanggal terima dengan tanggal masa depan !", "error");
                    $(this).val('').focus();
                    return false;
                }else if(dateOne.getTime() < dateTri.getTime()) {
                    swal("Ups!", "Tidak boleh isi tanggal terima beda bulan/tahun !", "error");
                    $(this).val('').focus();
                    return false;
                }        
            }
        });

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax  : {
                url : '{{ route("notapenjualan.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    d.tglmulai         = $('#tglmulai').val();
                    d.tglselesai     = $('#tglselesai').val();
                },
            },
            order     : [[ 1, 'asc' ]],
            scrollY : 130,
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            stateLoadParams: function (settings, data) {
                for (var i = 0; i < data.columns.length; i++) {
                    data.columns[i].search.search = "";
                }
            },
            rowCallback: function(row, data, index) {
                console.log(data);
            },
            columns        : [
                {
                    "data" : "tambah",
                    "orderable" : false,
                    render : function(data, type, row) {
                        return "<div class='btn btn-xs btn-success no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah' onclick='tambah_npj(this,"+row.id+")' data-message='"+data+"' data-tipe='header'><i class='fa fa-plus'></i></div>";
                    }
                },
                {
                    "data" : "tglpickinglist",
                    "className" : "menufilter numberfilter"
                },
                {
                    "data" : "nopickinglist",
                    "className" : "menufilter textfilter"
                },
                {
                    "data" : "namatoko",
                    "className" : "menufilter textfilter"
                },
                {
                    "data" : "namakaryawan",
                    "className" : "menufilter textfilter"
                },
            ]
        });

        table2 = $('#table2').DataTable({
            dom       : 'lrtp',
            select: {style:'single'},
            keys: {keys: [38,40]},
            scrollY : 130,
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order      : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns: [
                {
                    "data" : "action",
                    "orderable" : false
                },
                {
                    "data" : "tglproforma",
                },
                {
                    "data" : "tglnota",
                },
                {
                    "data" : "nonota",
                    "className" : "text-right"
                },
                {
                    "data" : "hrgtotalnota",
                    "className" : "text-right"
                },
            ],
        });

        table3 = $('#table3').DataTable({
            dom     : 'lrtp',
            select: {style:'single'},
            keys: {keys: [38,40]},
            scrollY : 130,
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order         : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns     : [
                {
                    "data" : "action",
                    "orderable" : false
                },
                {
                    "data" : "barang"
                },
                {
                    "data" : "satuan"
                },
                {
                    "data" : "qtynota",
                    "className" : "text-right"
                },
                {
                    "data" : "hrgsatnetto",
                    "className" : "text-right"
                },
                {
                    "data" : "hrgtotal",
                    "className" : "text-right"
                },
            ],
        });

        table4 = $('#table4').DataTable({
            dom     : 'lrtp',
            select: {style:'single'},
            keys: {keys: [38,40]},
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order         : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns     : [
                {"data" : "action","orderable" : false },
                {"data" : "nokoli"},
                {"data" : "keterangan"},
            ],
        });

        tableCheckerKoli = $('#tableCheckerKoli').DataTable({
            dom       : 'lrtp',
            paging    : false,
            ordering  : false,
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(3)').prop('contenteditable', true);
                $( row ).find('td:eq(4)').prop('contenteditable', true);
            },
            columns     : [
                {"data" : "barang"},
                {"data" : "satuan"},
                {"data" : "qtynota","className" : "text-right"},
                {"data" : "nomorkoli","className" : "text-right updateNomorKoli"},
                {"data" : "keterangan","className" : "updateKeterangan"},
            ],
        });

        tabelkartupiutang = $('#tabelkartupiutang').DataTable({
            dom     : 't',
            data : data_piutang,
            select  : true,
            scrollY : 130,
            scrollX : false,
            autoWidth: false,
            //fixedHeader: true,
            //scroller: {
            //    loadingIndicator: true
            //},
            columns     : [
                {
                    "data" : "tglterima",
                    "className": "textfilter",
                    "width": "40px"
                },
                {
                    "data" : "nonota",
                    "className": "textfilter",
                    "width": "40px"
                },
                {
                    "data" : "saldo",
                    "className": "textfilter",
                    "width": "40px"
                },
            ]
            
        });

        tableNpj = $('#tableNpj').DataTable({
            dom       : 'lrtp',
            paging    : false,
            ordering  : false,
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(4)').prop('contenteditable', true);
            },
            footerCallback: function ( tfoot, data, start, end, display ) {
                // console.log(data);
                var total = 0;
                for (var i = 0; i < data.length; i++) {
                    total += parseInt(data[i].hrgtotal);
                }
                $(tfoot).find('td').eq(1).html(total);
            },
            columns     : [
                {
                    "data" : "barang"
                },
                {
                    "data" : "satuan"
                },
                {
                    "data" : "qtysoacc",
                    "className" : "h_qtysoacc text-right"
                },
                {
                    "data" : "qtygudang",
                    "className" : "text-right"
                },
                {
                    "data" : "qtynota",
                    "className" : "h_qtynota text-right"
                },
                {
                    "data" : "hrgsatuannetto",
                    "className" : "text-right",
                    render : function(data, type, row) {
                        return parseInt(data).toLocaleString("id-ID");
                    }
                },
                {
                    "data" : "hrgtotal",
                    "className" : "text-right"
                },
            ]
        });

        $('#tableNpj').on('focus','tbody td', function (e) {
            if($(this).html() == 0){
                $(this).html(null);
            }
        } );

        $('#tableNpj').on('blur','tbody td', function (e) {
            if($(this).html() == ''){
                $(this).html('0');
            }
        } );

        $.contextMenu({
            selector: '#table1 tbody td.menufilter.numberfilter',
            className: 'numberfilter',
            items: {
                name: {
                    name: "Text",
                    type: 'text',
                    value: "",
                    events: {
                        keyup: function(e) {
                            // add some fancy key handling here?
                            // window.console && console.log('key: '+ e.keyCode);
                        }
                    }
                },
                // tipe: {
                //     name: "Tipe",
                //     type: 'select',
                //     options: {1: 'Find', 2: 'Filter'},
                //     selected: 1
                // },
                filter: {
                    name: "Filter",
                    type: 'select',
                    options: {1: '<=', 2: '<', 3: '=', 4: '>', 5: '>='},
                    selected: 3
                },
                key: {
                    name: "Cancel",
                    callback: $.noop
                }
            },
            events: {
                show: function(opt) {
                    context_menu_number_state = 'show';
                    $(document).off('focusin.modal');
                    setTimeout(function(){
                        $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
                    }, 10);
                },
                hide: function(opt) {
                    // this is the trigger element
                    var $this = this;
                    // export states to data store
                    var contextData = $.contextMenu.getInputValues(opt, $this.data());
                    console.log('number');
                    console.log(contextData);
                    context_menu_number_state = 'hide';
                    column_index = table.column(this).index();
                    if(column_index != undefined) {
                        last_index = column_index;
                    }else {
                        column_index = last_index;
                    }
                    custom_search[column_index].filter = filter_number[contextData.filter-1];
                    custom_search[column_index].text = contextData.name;
                    // table.columns(table.column(this).index()).search( contextData.name ).draw();
                    // this basically dumps the input commands' values to an object
                    // like {name: "foo", yesno: true, radio: "3", &hellip;}
                },
            }
        });

        $.contextMenu({
            selector: '#table1 tbody td.menufilter.textfilter',
            className: 'textfilter',
            items: {
                name: {
                    name: "Text",
                    type: 'text',
                    value: "",
                    events: {
                        keyup: function(e) {
                            // add some fancy key handling here?
                            // window.console && console.log('key: '+ e.keyCode);
                        }
                    }
                },
                // tipe: {
                //     name: "Tipe",
                //     type: 'select',
                //     options: {1: 'Find', 2: 'Filter'},
                //     selected: 1
                // },
                filter: {
                    name: "Filter",
                    type: 'select',
                    options: {1: '=', 2: '!='},
                    selected: 1
                },
                key: {
                    name: "Cancel",
                    callback: $.noop
                }
            },
            events: {
                show: function(opt) {
                    context_menu_text_state = 'show';
                    $(document).off('focusin.modal');
                    setTimeout(function(){
                        $('.context-menu-list.textfilter input[name="context-menu-input-name"]').focus();
                    }, 10);
                },
                hide: function(opt) {
                    // this is the trigger element
                    var $this = this;
                    // export states to data store
                    var contextData = $.contextMenu.getInputValues(opt, $this.data());
                    console.log('text');
                    console.log(contextData);
                    context_menu_text_state = 'hide';
                    column_index = table.column(this).index();
                    if(column_index != undefined) {
                        last_index = column_index;
                    }else {
                        column_index = last_index;
                    }
                    custom_search[column_index].filter = filter_text[contextData.filter-1];
                    custom_search[column_index].text = contextData.name;
                    // table.columns(table.column(this).index()).search( contextData.name ).draw();
                    // this basically dumps the input commands' values to an object
                    // like {name: "foo", yesno: true, radio: "3", &hellip;}
                },
            }
        });

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('a.toggle-vis-2').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table2.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('a.toggle-vis-3').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table3.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('a.toggle-vis-4').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table4.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('.tgl').change(function(){
            table.draw();
        });

        $(document.body).on("keydown", function(e){
            ele = document.activeElement;
            if(e.keyCode == 8){
                if($('#modalCheckerKoli').is(':visible')){
                    if(ele.id == 'checker1'){
                        $('#checker1id').val('');
                    }else if(ele.id == 'checker2'){
                        $('#checker2id').val('');
                    }
                }
            }

            else if(e.keyCode == 13){
                if(context_menu_number_state == 'show'){
                    $(".context-menu-list.numberfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }else if(context_menu_text_state == 'show'){
                    $(".context-menu-list.textfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }

                // if($('#modalStaff').is(':visible')){
                //     if(ele.className == 'selected'){
                //         pilih_staff(tipe_search);
                //     }else if(ele.id == 'btnPilihStaff'){
                //         pilih_staff(tipe_search);
                //     }else if(ele.id == 'query'){
                //         search_staff($(ele).val());
                //         return false;
                //     }
                // }

                if($('#modalCheckerKoli').is(':visible')){
                    console.log(ele);
                    // if(ele.id == 'checker1'){
                    //     tipe_search = 'checker1';
                    //     if(!$('#checker1id').val()){
                    //         $('#modalStaff').modal('show');
                    //         $('#modalStaff #query').val($(ele).val());
                    //         $('#modalStaff').on('shown.bs.modal', function() {
                    //             $('#query').focus();
                    //         });
                    //         search_staff($(ele).val());
                    //     }else{
                    //         submit_checkerkoli();
                    //         return false;
                    //     }
                    // }else if(ele.id == 'checker2'){
                    //     tipe_search = 'checker2';
                    //     if(!$('#checker2id').val()){
                    //         $('#modalStaff').modal('show');
                    //         $('#modalStaff #query').val($(ele).val());
                    //         $('#modalStaff').on('shown.bs.modal', function() {
                    //             $('#query').focus();
                    //         });
                    //         search_staff($(ele).val());
                    //     }else{
                    //         submit_checkerkoli();
                    //         return false;
                    //     }
                    // }else if($(ele).hasClass('updateNomorKoli')){
                    if($(ele).hasClass('updateNomorKoli')){
                        submit_checkerkoli();
                        return false;
                    }else if($(ele).hasClass('updateKeterangan')){
                        submit_checkerkoli();
                        return false;
                    }
                }

                if($('#modalTglTerima').is(':visible')){
                    submit_tglterima();
                    return false;
                }

                if($('#modalNpjInsert').is(':visible')){
                    if($(ele).hasClass('text-right')){
                        submit_npj();
                        return false;
                    }
                }

                if($('#modalKewenangan').is(':visible')){
                    submit_kewenangan();
                    return false;
                }
                 if($('#modalKoreksiNotaDetail').is(':visible')){
                    if($('#btnSubmitKoreksi').is(":visible")){
                        if(ele.id == 'checker2Koreksi'){
                            if($('#checker2Koreksiid').val() && $('#hrgNettoKoreksi').val()){
                                console.log('submit koreksi');
                                submit_koreksi();
                            }else if($('#checker2Koreksiid').val() && !$('#hrgNettoKoreksi').val()){
                                swal("Ups!", "Hrg. Satuan Netto belum diisi.", "error");
                                return false;
                            }else{
                                $('#modalStaff').modal('show');
                                $('#modalStaff #query').val($(ele).val());
                                $('#modalStaff').on('shown.bs.modal', function() {
                                    $('#query').focus();
                                });
                                search_staff($(ele).val());
                            }
                        }else if(ele.id == 'hrgNettoKoreksi'){
                            if($('#hrgNettoKoreksi').val()){
                                console.log('submit koreksi');
                                submit_koreksi();
                            }else{
                                swal("Ups!", "Data belum terisi.", "error");
                                return false;
                            }
                        }
                    }else{
                        if(ele.id == 'modalKoreksiNotaDetail'){
                            submit_notifikasi_koreksi();
                        }
                    }
                }
            }
        });
        
        $(document.body).on("keyup", function(e){
            if($('#modalNpjInsert').is(':visible')){
                if($(ele).hasClass('text-right')){
                    var current = $(ele);
                    var qty     = parseInt($(ele).html());
                    if(isNaN(qty)){
                        current.html(0);
                        qty = parseInt(0);
                        // qty = parseInt($(ele).html());
                    }
                    var hrgsatnetto = $(ele).next().html().replace(/[.]/g,'');
                    var total       = qty*parseInt(hrgsatnetto);
                    var hrgtotal    = $(ele).next().next();
                    hrgtotal.html(total.toLocaleString("id-ID"));

                    var column_hrgtotal = tableNpj.column(6);
                    var footer_total    = 0;
                    $.each(column_hrgtotal.nodes(),function(index, value){
                        footer_total += parseInt($(value).html().replace(/[.]/g,''));
                    });

                    $(column_hrgtotal.footer()).html(footer_total.toLocaleString("id-ID"));
                }
            }
        });

        // $('#modalStaff').on('hidden.bs.modal', function() {
        //     $('#modalKoreksiNotaDetail #checker2Koreksi').focus();
        // });

        table.on('select', function ( e, dt, type, indexes ){
            var rowData = table.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });
            $('#table1 tr.selected > td:first > div').data('message','Menunggu proses verifikasi');
            $.ajax({
                type: 'POST',
                data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("notapenjualan.nota")}}',
                success: function(data){

                    if(data.error_message){
                        $('#table1 tr.selected > td:first > div').data('message',data.error_message);
                    }else if (data.datakartupiutang){
                        $('#table1 tr.selected > td:first > div').data('message','kartupiutang');
                        data_piutang = data;
                    }
                    
                    if(data.notapenjualan){
                        table2.clear();
                        for (var i = 0; i < data.notapenjualan.length; i++) {
                            var html = "<div class='btn btn-xs btn-warning no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Isi Tgl. Terima' onclick='tgl_terima(this,"+data.notapenjualan[i].id+")' data-message='"+data.notapenjualan[i].isitglterima+"' data-tipe='header'><i class='fa fa-calendar'></i></div>";
                                html += "<div class='btn btn-xs btn-success no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Isi Checker N Koli' onclick='checker(this,"+data.notapenjualan[i].id+")' data-message='"+data.notapenjualan[i].isichecker+"' data-tipe='header'><i class='fa fa-gift'></i></div>";
                                html += "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Delete' onclick='hapus(this,"+data.notapenjualan[i].id+")' data-message='"+data.notapenjualan[i].delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
                                html += "<div class='btn btn-xs btn-primary no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Cetak Proforma' onclick='cetakproforma(this,"+data.notapenjualan[i].id+")' data-message='"+data.notapenjualan[i].cetakproforma+"' data-tipe='header'><i class='fa fa-print'></i> PRI</div>";
                                html += "<div class='btn btn-xs btn-primary no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Cetak Nota' onclick='cetaknota(this,"+data.notapenjualan[i].id+")' data-message='"+data.notapenjualan[i].cetaknota+"' data-tipe='header'><i class='fa fa-print'></i> NPJ</div>";
                            table2.row.add({
                                action      : html,
                                tglproforma : data.notapenjualan[i].tglproforma,
                                tglnota     : data.notapenjualan[i].tglnota,
                                nonota      : data.notapenjualan[i].nonota,
                                hrgtotalnota: data.notapenjualan[i].hrgtotalnota,
                                id          : data.notapenjualan[i].id,
                            });
                        }
                        table2.draw();
                        table3.clear().draw();
                    }  
                },
                error: function(data){
                    console.log(data);
                    $('#table1 tr.selected > td:first > div').data('message',data);
                }
            });
        });

        table.on('deselect', function ( e, dt, type, indexes ) {
            table2.clear().draw();
            table3.clear().draw();
            table4.clear().draw();
        });

        table2.on('select', function ( e, dt, type, indexes ){
            var rowData = table2.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("notapenjualandetail.data")}}',
                success: function(data){
                    //console.log(data);
                    table3.clear();
                    for (var i = 0; i < data.notapenjualandetail.length; i++) {
                        var actione = '';
                        actione += "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Delete' onclick='hapus_detail(this,"+data.notapenjualandetail[i].id+")' data-message='"+data.notapenjualandetail[i].delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
                        actione += "<div class='btn btn-xs btn-info no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Koreksi' onclick='koreksi_detail(this,"+data.notapenjualandetail[i].id+")' data-message='"+data.notapenjualandetail[i].koreksi+"' data-tipe='header'><i class='fa fa-edit'></i></div>";
                        actione += "<div class='btn btn-xs btn-warning no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Edit Qty' onclick='editqty_detail(this,"+data.notapenjualandetail[i].id+")' data-message='"+data.notapenjualandetail[i].editqty+"' data-tipe='header'>Edit Qty</div>";

                        table3.row.add({
                            action     : actione,
                            barang     : data.notapenjualandetail[i].namabarang,
                            satuan     : data.notapenjualandetail[i].satuan,
                            qtynota    : data.notapenjualandetail[i].qtynota,
                            hrgsatnetto: data.notapenjualandetail[i].hrgsatuannetto,
                            hrgtotal   : data.notapenjualandetail[i].hrgtotal,
                            id         : data.notapenjualandetail[i].id,
                        });
                    }
                    table3.draw();
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        table2.on( 'deselect', function ( e, dt, type, indexes ) {
            table3.clear().draw();
            table4.clear().draw();
        });

        table3.on('select', function ( e, dt, type, indexes ){
            var rowData = table3.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("notapenjualandetailkoli.data")}}',
                success: function(data){
                    //console.log(data);
                    table4.clear();
                    for (var i = 0; i < data.koli.length; i++) {
                        table4.row.add({
                            action    : "",
                            nokoli    : data.koli[i].nokoli,
                            keterangan: data.koli[i].keterangan,
                            id        : data.koli[i].id,
                        });
                    }
                    table4.draw();
                },
                error: function(data){
                   //console.log(data);
                }
            });
        });

        table3.on( 'deselect', function ( e, dt, type, indexes ) {
            table4.clear().draw();
        });

        var tabledoubleclick = $('#doubleclickData').DataTable({
            dom         : 'lrtp',
            paging        : false,
            columns     : [
                {
                    "className" : "text-right"
                },
                null,
                null,
            ],
        });

        $('#table1 tbody').on('dblclick', 'tr', function(){
            var data = table.row(this).data();
            $.ajax({
                type: 'GET',
                url: '{{route("orderpenjualan.header")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                      tabledoubleclick.clear();
                      tabledoubleclick.rows.add([
                        {'0':'1.', '1':'C1', '2':data.c1},
                        {'0':'2.', '1':'C2', '2':data.c2},
                        {'0':'3.', '1':'No. SO', '2':data.noso},
                        {'0':'4.', '1':'Tgl. SO', '2':data.tglso},
                        {'0':'5.', '1':'No. Picking List', '2':data.nopickinglist},
                        {'0':'6.', '1':'Tgl. Picking List', '2':data.tglpickinglist},
                        {'0':'7.', '1':'Toko', '2':data.tokonama},
                        {'0':'8.', '1':'Alamat', '2':data.tokoalamat},
                        {'0':'9.', '1':'Kota', '2':data.tokokota},
                        {'0':'10.', '1':'Kecamatan', '2':data.kecamatan},
                        {'0':'11.', '1':'WILID', '2':data.wilid},
                        {'0':'12.', '1':'Toko ID', '2':data.idtoko},
                        {'0':'13.', '1':'Status Toko', '2':data.statustoko},
                        {'0':'14.', '1':'Salesman', '2':data.salesmannama},
                        {'0':'15.', '1':'Tipe Transaksi', '2':data.tipetransaksi},
                        {'0':'16.', '1':'Tempo Nota', '2':data.temponota},
                        {'0':'17.', '1':'Tempo Kirim', '2':data.tempokirim},
                        {'0':'18.', '1':'Tempo Salesman', '2':data.temposalesman},
                        {'0':'19.', '1':'No. ACC Piutang', '2':data.noaccpiutang},
                        {'0':'20.', '1':'Nama PKP', '2':data.namapkp},
                        {'0':'21.', '1':'Tgl. ACC Piutang', '2':data.tglaccpiutang},
                        {'0':'22.', '1':'Rp. ACC Piutang', '2':data.rpaccpiutang},
                        {'0':'23.', '1':'Rp. Saldo Piutang', '2':data.rpsaldopiutang},
                        {'0':'24.', '1':'Rp. Saldo Overdue', '2':data.rpsaldooverdue},
                        {'0':'25.', '1':'Rp. SO ACC Proses', '2':data.rpsoaccproses},
                        {'0':'26.', '1':'Rp. GIT', '2':data.rpgit},
                        {'0':'27.', '1':'Catatan Penjualan', '2':data.catatanpenjualan},
                        {'0':'28.', '1':'Catatan Pembayaran', '2':data.catatanpembayaran},
                        {'0':'29.', '1':'Catatan Pengiriman', '2':data.catatanpengiriman},
                        {'0':'30.', '1':'Print', '2':data.print},
                        {'0':'31.', '1':'Tgl. Print Picking List', '2':data.tglprintpickinglist},
                        {'0':'32.', '1':'Status Approval Overdue', '2':data.statusapprovaloverdue},
                        {'0':'33.', '1':'Tgl. Terima SO ke Piutang', '2':data.tglterimapilpiutang},
                        {'0':'34.', '1':'Status Ajuan Harga 11', '2':data.statusajuanhrg11},
                        {'0':'35.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'36.', '1':'Last Updated On', '2':data.lastupdatedon},
                      ]);
                      tabledoubleclick.draw();
                      $('#modalDoubleClickPenjualan #myModalLabel').html('Data Order Penjualan');
                      $('#modalDoubleClickPenjualan').modal('show');
                }
              });
        });

        $('#table2 tbody').on('dblclick', 'tr', function(){
            var data = table2.row(this).data();
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.header")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                      tabledoubleclick.clear();
                      tabledoubleclick.rows.add([
                        {'0':'1.', '1':'C1', '2':data.c1},
                        {'0':'2.', '1':'C2', '2':data.c2},
                        {'0':'3.', '1':'Tgl. Picking List', '2':data.tglpickinglist},
                        {'0':'4.', '1':'No. Picking List', '2':data.nopickinglist},
                        {'0':'5.', '1':'Tgl. Proforma', '2':data.tglproforma},
                        {'0':'6.', '1':'No. Nota', '2':data.nonota},
                        {'0':'7.', '1':'Tgl. Nota', '2':data.tglnota},
                        {'0':'8.', '1':'Toko', '2':data.toko},
                        {'0':'9.', '1':'Alamat', '2':data.alamat},
                        {'0':'10.', '1':'Kota', '2':data.kota},
                        {'0':'11.', '1':'Daerah', '2':data.kecamatan},
                        {'0':'12.', '1':'WILID', '2':data.wilid},
                        {'0':'13.', '1':'Toko ID', '2':data.idtoko},
                        {'0':'14.', '1':'Status Toko', '2':data.statustoko},
                        {'0':'15.', '1':'Salesman', '2':data.salesman},
                        {'0':'16.', '1':'Tipe Transaksi', '2':data.tipetransaksi},
                        {'0':'17.', '1':'Tempo Nota', '2':data.temponota},
                        {'0':'18.', '1':'Tempo Kirim', '2':data.tempokirim},
                        {'0':'19.', '1':'Tempo Salesman', '2':data.temposalesman},
                        {'0':'20.', '1':'Print Proforma', '2':data.printproforma},
                        {'0':'21.', '1':'Tgl. Print Proforma', '2':data.tglprintproforma},
                        {'0':'22.', '1':'Print Nota', '2':data.printnota},
                        {'0':'23.', '1':'Tgl. Print Nota', '2':data.tglprintnota},
                        {'0':'24.', '1':'Tgl. Check', '2':data.tglcheck},
                        {'0':'25.', '1':'Checker 1', '2':data.checker1},
                        {'0':'26.', '1':'Checker 2', '2':data.checker2},
                        {'0':'27.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'28.', '1':'Last Updated On', '2':data.lastupdatedon},
                      ]);
                      tabledoubleclick.draw();
                      $('#modalDoubleClickPenjualan #myModalLabel').html('Data Nota Penjualan');
                      $('#modalDoubleClickPenjualan').modal('show');
                }
              });
        });



        $('#table3 tbody').on('dblclick', 'tr', function(){
            var data = table3.row(this).data();
            //console.log(data);
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.detail")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                      tabledoubleclick.clear();
                      tabledoubleclick.rows.add([
                        {'0':'1.', '1':'Nama Barang', '2':data.barang},
                        {'0':'2.', '1':'Satuan', '2':data.sat},
                        {'0':'3.', '1':'Qty. Nota', '2':data.qtynota},
                        {'0':'4.', '1':'Hrg. Sat. Brutto', '2':data.hrgsatuanbrutto},
                        {'0':'5.', '1':'Disc. 1', '2':data.disc1},
                        {'0':'6.', '1':'Hrg. Setelah Disc. 1', '2':data.hrgsetelahdisc1},
                        {'0':'7.', '1':'Disc. 2', '2':data.disc2},
                        {'0':'8.', '1':'Hrg. Setelah Disc. 2', '2':data.hrgsetelahdisc2},
                        {'0':'9.', '1':'PPN', '2':data.ppn},
                        {'0':'10.', '1':'Hrg. Sat. Netto', '2':data.hrgsatuannetto},
                        {'0':'11.', '1':'Hrg. Total Netto', '2':data.hrgtotalnetto},
                        {'0':'12.', '1':'Qty. Stock Gudang', '2':data.qtystockgudang},
                        {'0':'13.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'14.', '1':'Last Updated On', '2':data.lastupdatedon},  
                      ]);
                      tabledoubleclick.draw();
                      $('#modalDoubleClickPenjualan #myModalLabel').html('Data Nota Penjualan Detail');
                      $('#modalDoubleClickPenjualan').modal('show');
                }
              });
        });

        $('#table4 tbody').on('dblclick', 'tr', function(){
            var data = table4.row(this).data();
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.detailkoli")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                    //console.log(data);
                      tabledoubleclick.clear();
                      tabledoubleclick.rows.add([
                        {'0':'1.', '1':'No. Koli', '2':data.nokoli},
                        {'0':'2.', '1':'Keterangan', '2':data.keterangan},
                        {'0':'3.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'4.', '1':'Last Updated On', '2':data.lastupdatedon},  
                      ]);
                      tabledoubleclick.draw();
                      $('#modalDoubleClickPenjualan #myModalLabel').html('Data Nota Penjualan Detail Koli');
                      $('#modalDoubleClickPenjualan').modal('show');
                }
              });
        });
        disable_radio('checker');
        disable_radio('tglterima');

        $('#btnSubmitNotifikasiKoreksi').click(function(){
            submit_notifikasi_koreksi();
            // console.log('dipencet');
        });

        $('#btnSubmitKoreksi').click(function(){
            if($('#hrgNettoKoreksi').val()){
                // console.log('submit koreksi');
                submit_koreksi();
            }else{
                swal("Ups!", "Data belum terisi.", "error");
                return false;
            }
        });

        $('#btnSubmitEditQty').click(function(){
            if($('#qtyNotaEditQty').val()){
                submit_editqty();
            }else{
                swal("Ups!", "Tidak bisa simpan record. Qty Nota tidak boleh kosong. Hubungi Manager anda.", "error");
            }
        });

        $('#btnIsiQtyNota').click(function() {
            var i = 1;
            tableNpj.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                var qty  = parseInt(data['qtysoacc']);
                if(isNaN(qty)) qty = parseInt(0);
                var hrgsatnetto = data['hrgsatuannetto'].replace(/[.]/g,'');
                var total       = qty*parseInt(hrgsatnetto);

                data['qtynota']  = qty;
                data['hrgtotal'] = total.toLocaleString("id-ID");
                this.data(data).draw();

                var column_hrgtotal = tableNpj.column(6);
                var footer_total    = 0;
                $.each(column_hrgtotal.nodes(),function(index, value){
                    footer_total += parseInt($(value).html().replace(/[.]/g,''));
                });
                $(column_hrgtotal.footer()).html(footer_total.toLocaleString("id-ID"));
            })
        });
    });

    function showModalNPJ(){
        $('#modalNpjInsert').modal('show');
        $('#modalNpjInsert').on('shown.bs.modal', function() {
            tableNpj.columns.adjust().draw();
        });
    }

    function hapus(e, data){
        @cannot('notapenjualan.delete')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var message = $(e).data('message');
        if(message == 'auth'){
            $('#modalKewenangan #notaId').val(data);
            $('#modalKewenangan #permission').val('notapenjualan.delete');
            $('#modalKewenangan #about').val('delete');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    };

    function hapus_detail(e, data){
        @cannot('notapenjualan.deletedetail')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var message = $(e).data('message');
        if(message == 'auth'){
            $('#modalKewenangan #notaId').val(data);
            $('#modalKewenangan #permission').val('notapenjualan.deletedetail');
            $('#modalKewenangan #about').val('deletedetail');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    };

    function koreksi_detail(e,data){
        // console.log(data);
        var message = $(e).data('message');
        // ganti acl
        @cannot('notapenjualan.detail.koreksi')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(message == 'koreksi') {
            tipe_edit = 'koreksi';
            $.ajax({
                type: 'POST',
                url: '{{route("notapenjualan.detail.detail")}}',
                data: {id: data},
                dataType: "json",
                success: function(data){
                    if (data.success) {
                        $('#modalKoreksiNotaDetail #notaId').val(data.notaid);
                        $('#modalKoreksiNotaDetail #notaIdDetail').val(data.notadetailid);
                        $('#modalKoreksiNotaDetail #salesmanKoreksi').val(data.karyawan);
                        $('#modalKoreksiNotaDetail #tglNotaKoreksi').val(data.tglnota);
                        $('#modalKoreksiNotaDetail #noNotaKoreksi').val(data.nonota);
                        $('#modalKoreksiNotaDetail #tglProformaKoreksi').val(data.tglproforma);
                        $('#modalKoreksiNotaDetail #checker1Koreksi').val(data.karyawanchecker1);
                        $('#modalKoreksiNotaDetail #checker1KoreksiId').val(data.karyawanchecker1id);
                        $('#modalKoreksiNotaDetail #checker2Koreksi').val(data.karyawanchecker2);
                        $('#modalKoreksiNotaDetail #checker2Koreksiid').val(data.karyawanchecker2id);
                        $('#modalKoreksiNotaDetail #barangKoreksi').val(data.namabarang);
                        $('#modalKoreksiNotaDetail #qtyNotaKoreksi').val(data.qtynota);
                        $('#modalKoreksiNotaDetail #qtyTerimaKoreksi').val(data.qtyterima);
                        $('#modalKoreksiNotaDetail #hrgBrutoKoreksi').val(data.hrgsatuanbrutto);
                        $('#modalKoreksiNotaDetail #disc1Koreksi').val(data.disc1);
                        $('#modalKoreksiNotaDetail #hrgDisc1Koreksi').val(data.hrgdisc1);
                        $('#modalKoreksiNotaDetail #disc2Koreksi').val(data.disc2);
                        $('#modalKoreksiNotaDetail #hrgDisc2Koreksi').val(data.hrgdisc2);
                        $('#modalKoreksiNotaDetail #ppnKoreksi').val(data.ppn);
                        $('#modalKoreksiNotaDetail #hrgNettoKoreksi').val(data.hrgsatuannetto).attr('readonly',true).attr('tabindex',-1);
                        
                        $('#modalKoreksiNotaDetail').modal('show');
                        $('#btnSubmitKoreksi').hide();
                        active_field(false);
                    }
                    
                },
            });
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function editqty_detail(e,data){
        // console.log(data);
        var message = $(e).data('message');
        // ganti acl
        @cannot('notapenjualan.detail.editqty')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(message == 'editqty') {
            tipe_edit = 'editqty';
            $.ajax({
                type: 'POST',
                url: '{{route("notapenjualan.detail.detail")}}',
                data: {id: data},
                dataType: "json",
                success: function(data){
                    if (data.success) {
                        $('#modalEditQty #notaId').val(data.notaid);
                        $('#modalEditQty #notaIdDetail').val(data.notadetailid);
                        $('#modalEditQty #salesmanEditQty').val(data.karyawan);
                        $('#modalEditQty #tglNotaEditQty').val(data.tglnota);
                        $('#modalEditQty #noNotaEditQty').val(data.nonota);
                        $('#modalEditQty #tglProformaEditQty').val(data.tglproforma);
                        $('#modalEditQty #checker1EditQty').val(data.karyawanchecker1);
                        $('#modalEditQty #checker1EditQtyId').val(data.karyawanchecker1id);
                        $('#modalEditQty #checker2EditQty').val(data.karyawanchecker2);
                        $('#modalEditQty #checker2EditQtyid').val(data.karyawanchecker2id);
                        $('#modalEditQty #barangEditQty').val(data.namabarang);
                        $('#modalEditQty #qtyOrderEditQty').val(data.qtyorder);
                        $('#modalEditQty #qtyNotaOldEditQty').val(data.qtynota);
                        $('#modalEditQty #qtyNotaEditQty').val(data.qtynota)
                        $('#modalEditQty #qtyTerimaEditQty').val(data.qtyterima);
                        $('#modalEditQty #hrgBrutoEditQty').val(data.hrgsatuanbrutto);
                        $('#modalEditQty #disc1EditQty').val(data.disc1);
                        $('#modalEditQty #hrgDisc1EditQty').val(data.hrgdisc1);
                        $('#modalEditQty #disc2EditQty').val(data.disc2);
                        $('#modalEditQty #hrgDisc2EditQty').val(data.hrgdisc2);
                        $('#modalEditQty #ppnEditQty').val(data.ppn);
                        $('#modalEditQty #hrgNettoEditQty').val(data.hrgsatuannetto);

                        $('#modalEditQty').modal('show').on('shown.bs.modal', function () {
                            $('#modalEditQty #qtyNotaEditQty').focus();
                        });

                    }
                    
                },
            });
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function active_field(e){
        if(e){
            $('#checker2Koreksi').removeAttr("readonly");
            $('#hrgNettoKoreksi').removeAttr("readonly");
            $('#hrgNettoKoreksi').attr("tabindex","2");
            $('#btnSubmitKoreksi').attr("tabindex","3");
            $('#btnSubmitKoreksi').attr("tabindex","4");
            $('#btnCancelKoreksi').html('Batal');
            $('#btnSubmitNotifikasiKoreksi').hide();
            $('#btnSubmitKoreksi').show();
            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI DIBUAT SEBAGAI KOREKSI. ATAS NOTA PENJUALAN SEBELUMNYA YANG SUDAH ANDA KOREKSI/BATALKAN MENJADI 0.');
            $("#infoNotaKoreksiKiri").hide();
            $("#infoNotaKoreksiKanan").removeClass('col-md-3');
            $("#infoNotaKoreksiKanan").addClass('col-md-6');
        }else{
            $('#hrgNettoKoreksi').attr("readonly","true");
            $('#checker1Koreksi').attr("tabindex","-1");
            $('#qtyTerimaKoreksi').attr("tabindex","-1");
            $('#btnSubmitKoreksi').attr("tabindex","-1");
            $('#btnCancelKoreksi').html('Tidak');
            $('#btnSubmitNotifikasiKoreksi').show();
            $('#btnSubmitKoreksi').hide();
            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN HARGA SATUAN NETTO = 0. NILAI KOREKSI DICATAT PADA NOTA PENJUALAN YANG BARU. ANDA YAKIN?');
            $("#infoNotaKoreksiKiri").show();
            $("#infoNotaKoreksiKanan").removeClass('col-md-6');
            $("#infoNotaKoreksiKanan").addClass('col-md-3');
        }
    }

    function submit_notifikasi_koreksi(){
        $('#modalKoreksiNotaDetail').modal('toggle');
        active_field(true);
        setTimeout(function(){
            var date = '{{Carbon\Carbon::now()->format("d-m-Y")}}';
            $('#modalKoreksiNotaDetail #checker2Koreksi').val('').attr('readonly',true).attr('tabindex',-1);
            $('#modalKoreksiNotaDetail #checker2Koreksiid').val('');
            $('#modalKoreksiNotaDetail #hrgNettoKoreksi').val('').attr('readonly',false).attr('tabindex',-1);
            $('#modalKoreksiNotaDetail #tglNotaKoreksi').val(date);
            $('#modalKoreksiNotaDetail #tglProformaKoreksi').val(date);
            // $('#modalKoreksiNotaDetail #qtyNotaKoreksi').val();

            $('#modalKoreksiNotaDetail').modal('toggle');
            $('#modalKoreksiNotaDetail').on('shown.bs.modal', function() {
                $('#modalKoreksiNotaDetail #hrgNettoKoreksi').focus();
            });
        }, 750);
    }

    //ganti acl 
    function submit_koreksi(){
        @cannot('notapenjualan.detail.koreksi')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if($('#hrgNettoKoreksi').val()){
            var data = {
                tglnota         : $('#tglNotaKoreksi').val(),
                nonota          : $('#noNotaKoreksi').val(),
                tglproforma     : $('#tglProformaKoreksi').val(),
                checker2        : $('#checker2Koreksiid').val(),
                qtynota         : $('#qtyNotaKoreksi').val(),
                hrgsatuannetto  : $('#hrgNettoKoreksi').val(),
                nota            : $('#modalKoreksiNotaDetail #notaId').val(),
                notadetail      : $('#modalKoreksiNotaDetail #notaIdDetail').val(),
            };

            $.ajax({
                type: 'POST',
                // ganti url bro
                url: '{{route("notapenjualan.detail.koreksi")}}',
                data: {data: data},
                dataType: "json",
                success: function(data){
                    $('#modalKoreksiNotaDetail').modal('hide');
                    $('#hrgNettoKoreksi').val('');
                    swal('Berhasil!',data.message,'success');
                    table.ajax.reload(null, true);
                    tipe_edit = null;
                    setTimeout(function(){
                        table.row(0).select();
                    },1000);
                },
            });
        }else{
            swal("Ups!", "Tidak bisa simpan record. Harga Netto Koreksi tidak boleh kosong. Hubungi Manager anda.", "error");
        }
        @endcannot
    }

    function submit_editqty(){
        @cannot('notapenjualan.detail.editqty')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if($('#qtyNotaEditQty').val()){
            var data = {
                _token    : "{{ csrf_token() }}",
                qtynota   : $('#qtyNotaEditQty').val(),
                notadetail: $('#modalEditQty #notaIdDetail').val(),
            };

            $.ajax({
                type    : 'POST',
                url     : '{{route("notapenjualan.detail.editqty")}}',
                data    : data,
                dataType: "json",
                success : function(data){
                    $('#modalEditQty').modal('hide');
                    $('#modalEditQty').find('input').val('');
                    $('#modalEditQty').find('textarea').val('');

                    swal('Berhasil!','Qty Nota Berhasil di edit!','success');
                    tipe_edit = null;

                    table2.row(table2_index).deselect();
                    table2.row(table2_index).select();
                },
            });
        }else{
            swal("Ups!", "Tidak bisa simpan record. Qty Nota tidak boleh kosong. Hubungi Manager anda.", "error");
        }
        @endcannot
    }

    function cetakproforma(e, data){
        @cannot('notapenjualan.cetakproforma')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var message = $(e).data('message');
        if(message == 'cetak'){
            window.open('{{route("notapenjualan.cetakproforma")}}'+'?id='+data);
        }else if(message == 'auth'){
            $('#modalKewenangan #notaId').val(data);
            $('#modalKewenangan #permission').val('notapenjualan.cetakproforma');
            $('#modalKewenangan #about').val('proforma');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function cetaknota(e, data){
        @cannot('notapenjualan.cetaknota')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var message = $(e).data('message');
        if(message == 'cetak'){
            window.open('{{route("notapenjualan.cetaknota")}}'+'?id='+data);
        }else if(message == 'auth'){
            $('#modalKewenangan #notaId').val(data);
            $('#modalKewenangan #permission').val('notapenjualan.cetaknota');
            $('#modalKewenangan #about').val('nota');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function checker(e, data){
        // console.log(e);
        var message = $(e).data('message');
        if(message == 'isi'){
            @cannot('notapenjualan.submitchecker')
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.isichecker")}}',
                data: {id: data},
                dataType: "json",
                success: function(data){
                    clear_column('modalCheckerKoli');
                    $("#modalCheckerKoli #notaPenjualanId").val(data.nota.id);
                    $('#modalCheckerKoli #tglPicking').val(data.nota.tglpickinglist);
                    $('#modalCheckerKoli #tglProforma').val(data.nota.tglproforma);
                    $('#modalCheckerKoli #tglNota').val(data.nota.tglnota);
                    $('#modalCheckerKoli #noPicking').val(data.nota.nopickinglist);
                    $('#modalCheckerKoli #noNota').val(data.nota.nonota);
                    $('#modalCheckerKoli #tipeTransaksi').val(data.nota.tipetransaksi);
                    $('#modalCheckerKoli #toko').val(data.nota.toko);
                    $('#modalCheckerKoli #alamat').val(data.nota.alamat);
                    $('#modalCheckerKoli #daerah').val(data.nota.kecamatan);
                    $('#modalCheckerKoli #kota').val(data.nota.kota);
                    $('#modalCheckerKoli #wilid').val(data.nota.wilid);
                    $('#modalCheckerKoli #idtoko').val(data.nota.idtoko);

                    if(data.nota.tipetransaksi.substring(0,1) == 'K'){
                        $('#modalCheckerKoli #tipeTransaksi_k').prop('checked', true);
                    }else if(data.nota.tipetransaksi.substring(0,1) == 'T'){
                        $('#modalCheckerKoli #tipeTransaksi_t').prop('checked', true);
                    }

                    tableCheckerKoli.clear();
                    for (var i = 0; i < data.barang.length; i++) {
                          tableCheckerKoli.row.add({
                            barang     : data.barang[i].namabarang,
                            satuan     : data.barang[i].satuan,
                            qtynota    : data.barang[i].qtynota,
                            nomorkoli  : null,
                            keterangan : null,
                            DT_RowId   : data.barang[i].id,
                          });
                    }
                      tableCheckerKoli.draw();
                      $('#modalCheckerKoli').modal('show');
                      $('#modalCheckerKoli').on('shown.bs.modal', function() {
                          tableCheckerKoli.columns.adjust().draw();
                        $('#modalCheckerKoli #checker1').focus();
                    });
                }
              });
              @endcannot
        }else{
            swal('Ups!', message,'error');
        }
    }

    function disable_radio(tipe){
        if(tipe == 'checker'){
            $('#modalCheckerKoli #tipeTransaksi_k').prop('disabled', true);
            $('#modalCheckerKoli #tipeTransaksi_t').prop('disabled', true);
        }else if(tipe == 'tglterima'){
            $('#modalTglTerima #tipeTransaksiTerima_t').prop('disabled', true);
        }
    }

    function tgl_terima(e, data){
        var message = $(e).data('message');
        if(message == 'isi'){
            @cannot('notapenjualan.submittglterima')
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.isitglterima")}}',
                data: {id: data},
                dataType: "json",
                success: function(data){
                    clear_column('modalTglTerima');
                    $("#modalTglTerima #notaPenjualanTerimaId").val(data.nota.id);
                    $('#modalTglTerima #tglPickingTerima').val(data.nota.tglpickinglist);
                    $('#modalTglTerima #tglProformaTerima').val(data.nota.tglproforma);
                    $('#modalTglTerima #noPickingTerima').val(data.nota.nopickinglist);
                    $('#modalTglTerima #noNotaTerima').val(data.nota.nonota);
                    $('#modalTglTerima #tipeTransaksiTerima').val(data.nota.tipetransaksi);
                    $('#modalTglTerima #tokoTerima').val(data.nota.toko);
                    $('#modalTglTerima #alamatTerima').val(data.nota.alamat);
                    $('#modalTglTerima #daerahTerima').val(data.nota.kecamatan);
                    $('#modalTglTerima #kotaTerima').val(data.nota.kota);
                    $('#modalTglTerima #wilidTerima').val(data.nota.wilid);
                    $('#modalTglTerima #idTerima').val(data.nota.idtoko);
                    $('#modalTglTerima #temponotaTerima').val(data.nota.temponota);
                    $('#modalTglTerima #temposalesmanTerima').val(data.nota.temposalesman);

                    if(data.nota.tipetransaksi.substring(0,1) == 'K'){
                        $('#modalTglTerima #tipeTransaksiTerima_k').prop('checked', true);
                    }else if(data.nota.tipetransaksi.substring(0,1) == 'T'){
                        $('#modalTglTerima #tipeTransaksiTerima_t').prop('checked', true);
                    }

                    $('#modalTglTerima').modal('show');
                    $('#modalTglTerima').on('shown.bs.modal', function() {
                        $('#modalTglTerima #tglNotaTerima').focus();
                    });
                }
              });
              @endcannot
        }else if(message == 'hapus'){
            $('#modalKewenangan #notaId').val(data);
            $('#modalKewenangan #permission').val('notapenjualan.hapustglterima');
            $('#modalKewenangan #about').val('hapustglterima');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
    }

    function tambah_npj(e, data){
        var message = $(e).data('message');
        @cannot('notapenjualan.add')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(message == 'tambah'){
            $.ajax({
                type: 'GET',
                url: '{{route("notapenjualan.tambah")}}',
                data: {id: data},
                dataType: "json",
                success: function(data){
                    clear_column('modalNpjInsert');
                    $("#modalNpjInsert #orderPenjualanNpjId").val(data.order.id);
                    $('#modalNpjInsert #tglPickingNpj').val(data.order.tglpickinglist);
                    $('#modalNpjInsert #noPickingNpj').val(data.order.nopickinglist);
                    $('#modalNpjInsert #tokoNpj').val(data.order.toko);
                    $('#modalNpjInsert #alamatNpj').val(data.order.alamat);
                    $('#modalNpjInsert #daerahNpj').val(data.order.kecamatan);
                    $('#modalNpjInsert #kotaNpj').val(data.order.kota);
                    $('#modalNpjInsert #wilidNpj').val(data.order.wilid);
                    $('#modalNpjInsert #tokoidNpj').val(data.order.idtoko);
                    $('#modalNpjInsert #rpaccpiutang').val(data.order.rpaccpiutang);

                    tableNpj.clear();
                    for (var i = 0; i < data.barang.length; i++) {
                          tableNpj.row.add({
                            barang         : data.barang[i].namabarang,
                            satuan         : data.barang[i].satuan,
                            qtysoacc       : data.barang[i].qtysoacc,
                            hrgsatuannetto : data.barang[i].hrgsatuannetto,
                            qtygudang      : data.barang[i].qtygudang,
                            qtynota        : 0,
                            hrgtotal       : 0,
                            id             : data.barang[i].id,
                          });
                    }
                      tableNpj.draw();

                    $('#modalNpjInsert').modal('show');
                    $('#modalNpjInsert').on('shown.bs.modal', function() {
                      tableNpj.columns.adjust().draw();
                    });
                }
              });
        }else if(message == 'kartupiutang'){
            var ajax2 = new Promise( /* executor */ function(resolve, reject) {
                resolve($.ajax({
                    type: 'GET',
                    url: '{{route("notapenjualan.tambah")}}',
                    data: {id: data},
                    dataType: "json",
                    success: function(data){
                        clear_column('modalNpjInsert');
                        $("#modalNpjInsert #orderPenjualanNpjId").val(data.order.id);
                        $('#modalNpjInsert #tglPickingNpj').val(data.order.tglpickinglist);
                        $('#modalNpjInsert #noPickingNpj').val(data.order.nopickinglist);
                        $('#modalNpjInsert #tokoNpj').val(data.order.toko);
                        $('#modalNpjInsert #alamatNpj').val(data.order.alamat);
                        $('#modalNpjInsert #daerahNpj').val(data.order.kecamatan);
                        $('#modalNpjInsert #kotaNpj').val(data.order.kota);
                        $('#modalNpjInsert #wilidNpj').val(data.order.wilid);
                        $('#modalNpjInsert #tokoidNpj').val(data.order.idtoko);
                        $('#modalNpjInsert #rpaccpiutang').val(data.order.rpaccpiutang);
    
                        tableNpj.clear();
                        for (var i = 0; i < data.barang.length; i++) {
                              tableNpj.row.add({
                                barang         : data.barang[i].namabarang,
                                satuan         : data.barang[i].satuan,
                                qtysoacc       : data.barang[i].qtysoacc,
                                hrgsatuannetto : data.barang[i].hrgsatuannetto,
                                qtygudang      : data.barang[i].qtygudang,
                                qtynota        : 0,
                                hrgtotal       : 0,
                                id             : data.barang[i].id,
                              });
                        }
                          tableNpj.draw();
    
                        //$('#modalNpjInsert').modal('show');
                        //$('#modalNpjInsert').on('shown.bs.modal', function() {
                        //    tableNpj.columns.adjust().draw();
                        //});
                    }
                  }));
            });
            var ajax1 = new Promise( /* executor */ function(resolve, reject) {
                $.ajaxSetup({
                    headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
                });
                resolve($.ajax({
                    type: 'POST',
                    data: {id: data},
                    dataType: "json",
                    url: '{{route("notapenjualan.nota")}}',
                    success: function(data){
                        //$('#modalTableKartuPiutang').modal('show');
                        data_piutang = data;
                        tabelkartupiutang.clear();
                        
                        for (var i = 0; i < data_piutang.datakartupiutang.length; i++) {
                            tabelkartupiutang.row.add({
                                tglterima : data_piutang.datakartupiutang[i].tglterima,
                                nonota    : data_piutang.datakartupiutang[i].nonota,
                                saldo     : data_piutang.datakartupiutang[i].saldo,
                            });
                        }
    
                        tabelkartupiutang.columns.adjust().draw();
                        $('#modalTableKartuPiutang').modal('show');
                        $('#modalTableKartuPiutang').on('shown.bs.modal', function() {
                            tabelkartupiutang.columns.adjust().draw();
                        });
                        
                    },
                    error: function(data){
                        reject(data)
                    }
                }));   
            });

            ajax2.then(result=>{
                console.log(result)
                return ajax1;
            }).then(result2=>{
                console.log(result2)
            }).catch(error=>{
                
            })
        }else{
            swal('Ups!', message,'error');
        }
          @endcannot
    }

    // function search_staff(query){
    //     $.ajax({
    //         type: 'POST',
    //         url: '{{route("lookup.getstaff")}}',
    //         data : {katakunci : query},
    //         dataType : 'json',
    //         success: function(data){
    //             staff = data;
    //             $('#tbodyStaff tr').remove();
    //             var x = '';
    //             if (staff.length > 0) {
    //                 for (var i = 0; i < staff.length; i++) {
    //                     x += '<tr tabindex=0>';
    //                     x += '<td>'+ staff[i].niksystemlama +'<input type="hidden" class="id_staff" value="'+ staff[i].id +'"></td>';
    //                     x += '<td>'+ staff[i].nikhrd +'</td>';
    //                     x += '<td>'+ staff[i].namakaryawan +'</td>';
    //                     x += '</tr>';
    //                 }
    //             }else {
    //                 x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
    //             }
    //             $('#tbodyStaff').append(x);
    //         },
    //         error: function(data){
    //             console.log(data);
    //         }
    //     });
    // }

    // $('#tbodyStaff').on('click', 'tr', function(){
    //     $('.selected').removeClass('selected');
    //     $(this).addClass("selected");
    // });

    // $('#btnPilihStaff').on('click', function(){
    //     pilih_staff(tipe_search);
    // });

    // $('#modalStaff table.tablepilih tbody').on('dblclick', 'tr', function(){
    //        pilih_staff(tipe_search);
    // });

    // function pilih_staff(tipe){
    //     if ($('#tbodyStaff').find('tr.selected td').eq(1).text() == '') {
    //         swal("Ups!", "Staff belum dipilih.", "error");
    //         return false;
    //     }else {
    //         if(tipe == 'checker1'){
    //             $('#checker1').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#checker1id').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#checker1").focus();
    //         }else if(tipe == 'checker2'){
    //             $('#checker2').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#checker2id').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#checker2").focus();
    //         }
    //          if(tipe == 'update'){
    //             $('#checker2').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#checker2id').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#checker2").focus();
    //         }else{
    //             $('#checker2Koreksi').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#checker2Koreksiid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#checker2Koreksi").focus();
    //         }
    //     }
    //     tipe_search = null;
    // }

    $('#btnSubmitKewenangan').click(function(){
        submit_kewenangan();
    });

    $('#btnSubmitCheckerKoli').click(function(){
        submit_checkerkoli();
    });

    $('#btnSubmitTglTerima').click(function(){
        submit_tglterima();
    });

    $('#btnSubmitNpjInsert').click(function(){
        submit_npj();
    });

    function submit_kewenangan(){
        $.ajax({
            type: 'POST',
            url: '{{route("notapenjualan.kewenangan")}}',
            data: {
                username   : $('#modalKewenangan #uxserKewenangan').val(),
                password   : $('#modalKewenangan #pxassKewenangan').val(),
                notaid     : $('#modalKewenangan #notaId').val(),
                permission : $('#modalKewenangan #permission').val(),
                about      : $('#modalKewenangan #about').val(),
            },
            dataType: "json",
            success: function(data){
                $('#modalKewenangan #uxserKewenangan').val('').change();
                $('#modalKewenangan #pxassKewenangan').val('').change();

                if(data.success){
                    $('#modalKewenangan').modal('hide');
                    table.rows(table_index).deselect();
                    table.rows(table_index).select();
                    if(data.tipe == 'nota' || data.tipe == 'proforma'){
                        window.open(data.url);
                    }else if(data.tipe == 'delete'){
                        // table.rows(table_index).deselect();
                        // table.rows(table_index).select();
                    }else if(data.tipe == 'deletedetail'){
                        // table.rows(table_index).deselect();
                        // table.rows(table_index).select();
                        setTimeout(function(){
                            table2.rows(table2_index).select();
                        }, 400);
                    }else if(data.tipe == 'hapustglterima'){
                        setTimeout(function(){
                            table2.rows(table2_index).select();
                        }, 400);
                    }
                }else{
                    swal('Ups!', 'Data yang Anda inputkan tidak tepat','error');
                }
            },
            error: function(data){
                //console.log(data);
            }
        });
    }

    function submit_checkerkoli(){
        @cannot('notapenjualan.submitchecker')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        rowKoli = $('#modalCheckerKoli td.updateNomorKoli').length;
        rowKet  = $('#modalCheckerKoli td.updateKeterangan').length;
        isiKoli = 0;
        isiKet  = 0;
        koli    = [];
        for (var i = 0; i < rowKoli; i++) {
            if($('#modalCheckerKoli td.updateNomorKoli')[i].textContent){
                isiKoli++;
            }
            if($('#modalCheckerKoli td.updateKeterangan')[i].textContent){
                isiKet++;
            }
            koli[i] = {
                nomorkoli  : $('#modalCheckerKoli td.updateNomorKoli')[i].textContent,
                keterangan : $('#modalCheckerKoli td.updateKeterangan')[i].textContent,
                id         : $('#modalCheckerKoli tr[id]')[i].id
            };
        }

        if($('#modalCheckerKoli #checker1id').val() && $('#modalCheckerKoli #checker2id').val() && (rowKoli == isiKoli) && (rowKet == isiKet)){
            var data = {
                notaid   : $('#modalCheckerKoli #notaPenjualanId').val(),
                checker1 : $('#modalCheckerKoli #checker1id').val(),
                checker2 : $('#modalCheckerKoli #checker2id').val(),
                koli     : koli,
            };

            $.ajax({
                type: 'POST',
                url: '{{route("notapenjualan.submitchecker")}}',
                data: data,
                dataType: "json",
                success: function(data){
                    $('#modalCheckerKoli').modal('hide'),
                    table2.rows(table2_index).deselect();
                    table2.rows(table2_index).select();
                },
            });
        }else{
            swal("Ups!", "Tidak bisa simpan nama Checker dan nomor koli. Ada nama checker/ nomor koli dan keterangannya yang belum diisi. Silahkan diisi atau batalkan update.", "error");
        }
        @endcannot
    }

    function submit_tglterima(){
        @cannot('notapenjualan.submittglterima')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        tglterima      = $('#tglNotaTerima').val();
        transaksitunai = $('#tipeTransaksiTerima_t').is(':checked');
        tglhariini     = Date.parse("{{ Carbon\Carbon::now()->format('Y-m-d') }}");
        tglberjalan    = Date.parse("{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}");
        tglterimaparse = Date.parse(tglterima);

        if(tglterima && (tglterima.indexOf("_") < 0)){    
            if(transaksitunai){
                swal("Ups!", "Tidak bisa update tanggal terima. Penjualan tunai hanya bisa diproses melalui menu Penjualan Tunai di aplikasi Kasir. Hubungi manager anda.", "error");
                return false;
            }else{
                if(tglterimaparse < tglberjalan){
                    swal("Ups!", "Tidak bisa isi tanggal terima kurang dari periode bulan Server karena menyebabkan informasi GIT menjadi tidak valid. Silahkan isi dengan tanggal 1 periode bulan dan tahun server.", "error");
                    return false;
                }else if(tglterimaparse > tglhariini){
                    swal("Ups!", "Tidak bisa isi tanggal terima lebih dari hari ini karena menyebabkan informasi GIT menjadi tidak valid. Silahkan isi dengan tanggal 1 periode bulan dan tahun server.", "error");
                    return false;
                }else{
                    var data = {
                        notaid          : $('#notaPenjualanTerimaId').val(),
                        tglterima       : tglterima,
                        transaksikredit : $('#tipeTransaksiTerima_k').is(':checked'),
                    };

                    $.ajax({
                        type    : 'POST',
                        url     : '{{route("notapenjualan.submittglterima")}}',
                        data    : data,
                        dataType: "json",
                        success : function(data){
                            $('#modalTglTerima').modal('hide');

                            table.rows(table_index).deselect();
                            table.rows(table_index).select();
                            setTimeout(function(){
                                table2.rows(table2_index).select();
                            }, 400);
                        },
                    });
                }
            }
        }else{
            swal("Ups!", "Mohon isikan Tanggal Nota/Terima terlebih dahulu.", "error");
        }
        @endcannot
    }

    function submit_npj(){
        @cannot('notapenjualan.add')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var column_harga      = tableNpj.column(6);
        var column_barang     = tableNpj.column(0);
        var column_qty        = tableNpj.column(4);
        var column_qty_gudang = tableNpj.column(3);
        var node              = column_qty.nodes();
        var node_barang       = column_barang.nodes();
        var node_qty_gudang   = column_qty_gudang.nodes();
        var footer            = column_harga.footer();
        var row_data          = tableNpj.rows().data();
        var nol_qty           = 0;
        var error_count       = 0;
        var notadata          = [];

        $.each(node,function(index, value){
            notadata[index] = {
                'qty' : $(value).html(),
                'orderdetailid' : row_data[index].id
            };

            if($(value).html() == 0){
                nol_qty++;
            }else{
                var qtygudang = parseInt($(node_qty_gudang[index]).html());
                var barang = $(node_barang[index]).html();
                if(parseInt($(value).html()) > qtygudang){
                    error_count++;
                    swal("Ups!", "Tidak bisa buat nota dengan Qty. Nota lebih besar dari Qty. Gudang. barang "+barang+", Qty. Nota "+$(value).html()+", Qty gudang "+qtygudang+". Hubungi Manager anda", "error");
                    return false;
                }
            }
        });
        
        var rpaccpiutang   = parseInt($('#rpaccpiutang').val().replace(/[.]/g,''));
        var totalharganota = parseInt($('#totalharganota').html().replace(/[.]/g,''));

        if(nol_qty == node.length){    
            swal("Ups!", "Tidak bisa simpan nota. Kolom Qty. Nota semuanya terisi 0.", "error");
            return false;
        }else if(totalharganota > rpaccpiutang){
            swal("Ups!", "Tidak bisa simpan nota. RP Total Nota tidak boleh melebihi RP Total ACC.", "error");
            return false;
        }else if(error_count == 0){
            $.ajax({
                type: 'POST',
                url: '{{route("notapenjualan.add")}}',
                data: {
                    notadata : notadata,
                    id       : $(orderPenjualanNpjId).val(),
                },
                dataType: "json",
                success: function(data){
                    if(data.success){
                        $('#modalNpjInsert').modal('hide');
                        table.rows(table_index).deselect();
                        table.rows(table_index).select();
                    }else{
                        swal("Ups!", "Tidak bisa simpan nota. Nilai nota lebih besar dari nilai ACC Piutang. Kurangi Nota anda.", "error");
                    }
                },
            });
        }
        @endcannot
    }

    $('input[name="kettipetransaksi"]').on('click', function(e) {
        if($('#modalTglTerima #tipeTransaksiTerima').val().substring(0,1) == 'T'){
            $.ajax({
                type: 'POST',
                data: {
                    tipe : $('#modalTglTerima input[name=kettipetransaksi]:checked').val(),
                    id   : $('#notaPenjualanTerimaId').val(),
                },
                dataType: "json",
                url: '{{route("notapenjualan.changetipetransaksi")}}',
                success: function(data){
                    enable_column(true);
                    $('#temponotaTerima').val(0);
                    $('#temposalesmanTerima').val(data.temposalesman);
                    $('#temponotaTerima').focus();
                },
                error: function(data){
                    //console.log(data);
                }
            });
        }else{
            console.log('not change');
        }
    });    

    function clear_column(id){
        for (var i = 0; i < $('#'+id+' .form-clear').length; i++) {
            element = $('#'+id+' .form-clear')[i];
            $(element).val('');
        }
    }

    function enable_column(e){
        if(e){
            $('#temponotaTerima').removeAttr("readonly");
            $('#temponotaTerima').attr("tabindex","0");
            // $('#temposalesmanTerima').removeAttr("readonly");
        }else{
            $('#temponotaTerima').attr("readonly","true");
        }
    }
</script>
@endpush