@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item"><a href="{{ route('opname.index') }}">Opname</a></li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Stock Opname</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <form class="form-inline">
                            <div class="form-group">
                                <label style="margin-right: 10px;">Tgl. Rencana</label>
                                <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                                <label>-</label>
                                <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('opname.rencana')
                        <a onclick="open_rsbbi()" class="btn btn-default" id="skeyF6">R. STOP Banyak Brg. - F6</a>
                        @endcan
                        @can('opname.cetakcfs')
                        <a onclick="open_cfs()" class="btn btn-default" id="skeyF7">Cetak F. STOP - F7</a>
                        @endcan
                        @can('opname.analisaclosing')
                        <a onclick="open_as()" class="btn btn-default" id="skeyF8">Analisa STOP - F8</a>
                        @endcan
                        @can('opname.closeallbarang')
                        <a onclick="open_cab()" class="btn btn-default" id="skeyF9">Closing ALL - F9</a>
                        @endcan
                    </div>
                </div>
                <div class="row-fluid">
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                {{-- <th>rowid</th> --}}
                                <th>Barang</th>
                                <th>Kd. Barang</th>
                                <th>Sat.</th>
                                <th>Aktif</th>
                                <th>Area 1</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> rowid</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Barang</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Kd. Barang</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Sat.</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Aktif</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Area 1</a>
                        </p>
                    </div>
                </div>
                {{-- <hr> --}}
                <div class="row-fluid">
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl. Rencana</th>
                                <th>Tgl. STOP</th>
                                <th>Qty. Awal</th>
                                <th>Qty. Baik</th>
                                <th>Qty. Rusak</th>
                                <th>Qty. GS</th>
                                <th>Qty. AG GIT</th>
                                <th>Qty. STOP</th>
                                <th>Qty. Selisih</th>
                                <th>Tgl. Closing</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl. Rencana</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Tgl. STOP</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Awal</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Qty. Baik</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Qty. Rusak</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Qty. GS</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="7"><i id="eye-detail7" class="fa fa-eye"></i> Qty. AG GIT</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="8"><i id="eye-detail8" class="fa fa-eye"></i> Qty. STOP</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="9"><i id="eye-detail9" class="fa fa-eye"></i> Qty. Selisih</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="10"><i id="eye-detail10" class="fa fa-eye"></i> Tgl. Closing</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data double click -->
<div class="modal fade" id="modalDoubleClickPengiriman" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
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

<!-- MODAL RENCANA -->
<div class="modal fade" id="modalRencana" tabindex="-1" role="dialog" aria-labelledby="modalRencanaTitle" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalRencanaTitle"></h4>
            </div>
            <form class="form-horizontal" method="post">
                <input type="hidden" id="barangId" class="form-clear" value="">
                <input type="hidden" id="modalRencanaTipe" class="form-clear" value="">
                <div class="modal-body">
                    <hr class="hr-text" data-content="Rencana STOP">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="tipestop" id="tipestop_harian" value="Harian"> Harian
                                    </label>
                                    <label>
                                        <input type="radio" name="tipestop" id="tipestop_tahunan" value="Tahunan"> Tahunan
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <strong>Tanggal</strong>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <input type="text" id="tglrsbbi" class="tglForm form-control form-clear" placeholder="Tanggal" readonly tabindex="-1" data-inputmask="'mask': 'd-m-y'">
                            </div>
                        </div>
                    </div>
                    <hr class="hr-text" data-content="Filter Rencana STOP (kosong berarti semua)">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" id="namabarang" class="form-control form-clear" placeholder="Nama Barang" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" id="kodebarang" class="form-control form-clear" placeholder="Kode Barang" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status Aktif</label>
                                <select class="form-control" id="statusaktif" disabled tabindex="-1">
                                    <option value="1" id="statusaktif_aktif">Aktif</option>
                                    <option value="0" id="statusaktif_pasif">Pasif</option>
                                    <option value="2" id="statusaktif_semua">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 1</label>
                                <input type="text" id="rak1" class="form-control form-clear" placeholder="Area 1" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 2</label>
                                <input type="text" id="rak2" class="form-control form-clear" placeholder="Area 2" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 3</label>
                                <input type="text" id="rak3" class="form-control form-clear" placeholder="Area 3" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Penanggung Jawab Area</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="penanggungjawabrak" class="form-control form-clear" placeholder="Penanggung Jawab Area" readonly tabindex="-1">
                                    <input type="hidden" id="penanggungjawabrakId" name="penanggungjawabrakId" class="form-clear" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Keterangan Rencana</label>
                                <input type="text" id="keteranganrencana" class="form-control form-clear" placeholder="Keterangan Rencana" readonly tabindex="-1">
                                {{-- <input type="text" id="keteranganrencana" class="form-control form-clear" placeholder="Keterangan Rencana"> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitRencana" class="btn btn-primary">Proses</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CFS -->
<div class="modal fade" id="modalCFS_AS_CAB" tabindex="-1" role="dialog" aria-labelledby="modalCFS_AS_CAB_Title" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalCFS_AS_CAB_Title"></h4>
            </div>
            <form class="form-horizontal" method="post">
                <input type="hidden" id="barangId" class="form-clear" value="">
                <input type="hidden" id="modalCFS_AS_CAB_Tipe" class="form-clear" value="">
                <div class="modal-body">
                    <hr class="hr-text" data-content="Rencana STOP">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6" id="AS_only">
                            <div class="form-group">
                                <label></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="tipestop" id="tipestop_rencana" value="rencana"> Rencana
                                    </label>
                                    <label>
                                        <input type="radio" name="tipestop" id="tipestop_closing" value="closing"> Closing
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" id="tglrsbbi" class="tglForm form-control form-clear" placeholder="Tanggal" data-inputmask="'mask': 'd-m-y'">
                            </div>
                        </div>
                    </div>
                    <hr class="hr-text" data-content="Filter Rencana STOP (kosong berarti semua)">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" id="namabarang" class="form-control form-clear" placeholder="Nama Barang">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" id="kodebarang" class="form-control form-clear" placeholder="Kode Barang">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status Aktif</label>
                                <select class="form-control" id="statusaktif">
                                    <option value="1" id="statusaktif_aktif">Aktif</option>
                                    <option value="0" id="statusaktif_pasif">Pasif</option>
                                    <option value="2" id="statusaktif_semua">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 1</label>
                                <input type="text" id="rak1" class="form-control form-clear" placeholder="Area 1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 2</label>
                                <input type="text" id="rak2" class="form-control form-clear" placeholder="Area 2">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Area 3</label>
                                <input type="text" id="rak3" class="form-control form-clear" placeholder="Area 3">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Penanggung Jawab Area</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="penanggungjawabarea" class="form-control form-clear" placeholder="Penanggung Jawab Area">
                                    <input type="hidden" id="penanggungjawabareaId" name="penanggungjawabareaId" class="form-clear" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitCFS_AS_CAB" class="btn btn-primary">Proses</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL UPDATE -->
<div class="modal fade" id="modalUpdateStop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update STOP</h4>
            </div>
            <form class="form-horizontal" method="post">
                <input type="hidden" id="sohId" class="form-clear" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Tgl. STOP</label>
                                <input type="text" id="tglstop" class="form-control form-clear" placeholder="Tgl. STOP" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>StockId</label>
                                <input type="text" id="stockid" class="form-control form-clear" placeholder="StockId" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Barang</label>
                                <input type="text" id="barang" class="form-control form-clear" placeholder="Barang" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" id="kodebarang" class="form-control form-clear" placeholder="Kode Barang" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Sat.</label>
                                <input type="text" id="satuan" class="form-control form-clear" placeholder="Sat." readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Status Aktif</label>
                                <input type="text" id="statusaktif" class="form-control form-clear" placeholder="Status Aktif" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Qty. Awal</label>
                                <input type="text" id="qtyawal" class="form-control form-clear" placeholder="Qty. Awal" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Qty. Baik</label>
                                <input type="text" id="qtybaik" class="form-control form-clear" placeholder="Qty. Baik" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Qty. Rusak</label>
                                <input type="text" id="qtyrusak" class="form-control form-clear" placeholder="Qty. Rusak" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Qty. GS</label>
                                <input type="text" id="qtygs" class="form-control form-clear" placeholder="Qty. GS" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Qty. GIT AG</label>
                                <input type="text" id="qtyaggit" class="form-control form-clear" placeholder="Qty. GIT AG" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Qty. STOP</label>
                                <input type="text" id="qtystop" class="form-control form-clear" placeholder="Qty. STOP" readonly tabindex="-1">
                                <p class="form-control-static">Qty.(Baik + Rusak + GS + GIT AG)</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Qty. Selisih</label>
                                <input type="text" id="qtyselisih" class="form-control form-clear" placeholder="Qty. Selisih" readonly tabindex="-1">
                                <p class="form-control-static">Qty.(STOP - Awal)</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Penghitung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="penghitung" class="form-control form-clear" placeholder="Penghitung" readonly tabindex="-1">
                                    <input type="hidden" id="penghitungid" name="penghitungId" class="form-clear" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Pemeriksa</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="pemeriksa" class="form-control form-clear" placeholder="Pemeriksa" readonly tabindex="-1">
                                    <input type="hidden" id="pemeriksaid" name="pemeriksaId" class="form-clear" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Keterangan Rencana</label>
                                <input type="text" id="keteranganrencana" class="form-control form-clear" placeholder="Keterangan Rencana" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Keterangan Hasil Hitung</label>
                                <input type="text" id="keteranganhasilhitung" class="form-control form-clear" placeholder="Keterangan Hasil Hitung" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitUpdateStop" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="modalPJ" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
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
                                <tbody id="tbodyPJ">
                                    <tr class="kosong">
                                        <td colspan="3" class="text-center">Tidak ada detail</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnPilihPJ" class="btn btn-primary">Pilih</button>
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
                <input type="hidden" id="id" value="">
                <input type="hidden" id="permission" value="">
                <input type="hidden" id="about" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" id="uxserKewenangan" class="form-control" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="password" id="pxassKewenangan" class="form-control" placeholder="Password">
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
    var custom_search_barang = [];
    var custom_search_soh    = [];
    for (var i = 0; i < 7; i++) {
        custom_search_barang[i] = {
            text   : '',
            filter : '='
        };
    }
    for (var i = 0; i < 11; i++) {
        custom_search_soh[i] = {
            text   : '',
            filter : '='
        };
    }
    var filter_number = ['<=','<','=','>','>='];
    var filter_text = ['=','!='];
    var tipe = ['Find','Filter'];
    var column_index = 0;
    var last_index = '';
    var context_menu_number_state = 'hide';
    var context_menu_text_state = 'hide';
    var tipe_edit_header = null,
        tipe_edit_detail = null;
    var tipe_search = null,
        tipe_pj = null;
    var table,
        table2,
        tabledoubleclick,
        table_index,
        table2_index,fokus;

    // Lookup
    lookupstaff();
    lookuppenanggungjawab();
    lookupbarang();

    $(document).ready(function(){
        $(".tgl").inputmask();
        $(".tglForm").inputmask();
        $('.modal').on('hidden.bs.modal', function () {
            if($('.modal.in').length == 0 && table_index) {
                table.cell('#gv1_'+table_index,":eq(1)").focus();
            }
        });

        $("#modalRencana #tglrsbbi").on('change',function() {
            if($(this).val()) {
                var dateStr = $(this).val().split("-");
                var dateOne = new Date(dateStr[2],dateStr[1]-1,dateStr[0]);
                var dateTwo = new Date({{date('Y,m-1,d')}});

                if(dateOne.getTime() < dateTwo.getTime()) {
                    swal('Ups!', 'Tidak boleh masukkan tanggal rencana lebih kecil dari tanggal server.','error');
                    $('#modalRencana #btnSubmitRencana').attr('disabled',true);
                }else{
                    $('#modalRencana #btnSubmitRencana').removeAttr('disabled');
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
            ajax       : {
                url : '{{ route("opname.header.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search_barang;
                    d.tglmulai      = $('#tglmulai').val();
                    d.tglselesai    = $('#tglselesai').val();
                    d.tipe_edit     = tipe_edit_header;
                },
            },
            order   : [[ 1, 'asc' ]],
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
                // console.log(data);
            },
            columns     : [
                {
                    "data" : "action",
                    "orderable" : false,
                    render : function(data, type, row) {
                        var html  = "<div class='btn btn-xs btn-success no-margin-action skeyF1' onclick='open_rspb("+row.id+")' data-tipe='header'>Rencana - F1</div>";
                        return html;
                    }
                },
                // {"data" : "id", "className" : "menufilter numberfilter text-right"},
                {"data" : "namabarang", "className" : "menufilter textfilter"},
                {"data" : "kodebarang", "className" : "menufilter textfilter"},
                {"data" : "satuan", "className" : "menufilter textfilter"},
                {"data" : "statusaktif", "className" : "menufilter textfilter uppercase"},
                {"data" : "area1", "className" : "menufilter textfilter"},
            ]
        });

        table2 = $('#table2').DataTable({
            dom     : 'lrtp',
            select: {style:'single'},
            keys: {keys: [38,40]},
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order       : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns     : [
                {
                    "data" : "action",
                    "orderable" : false,
                },
                {"data" : "tglrencana", "className" : "menufilter numberfilter"},
                {"data" : "tglstop", "className" : "menufilter numberfilter"},
                {"data" : "qtyawal", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtybaik", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtyrusak", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtygs", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtyaggit", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtystop", "className" : "menufilter numberfilter text-right"},
                {"data" : "qtyselisih", "className" : "menufilter numberfilter text-right"},
                {"data" : "tglclosing", "className" : "menufilter numberfilter"},
            ],
        });

        tabledoubleclick = $('#doubleclickData').DataTable({
            dom    : 'lrtp',
            paging : false,
            columns: [{"className" : "text-right"},null,null,],
        });

        $.contextMenu({
            selector: '#table1 tbody td.dataTables_empty',
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
                    custom_search_barang[column_index].filter = filter_number[contextData.filter-1];
                    custom_search_barang[column_index].text = contextData.name;
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
                    custom_search_barang[column_index].filter = filter_text[contextData.filter-1];
                    custom_search_barang[column_index].text = contextData.name;
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

        $(document.body).on("keydown", function(e){
            ele = document.activeElement;
            if(e.keyCode == 13){
                if(context_menu_number_state == 'show'){
                    $(".context-menu-list.numberfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }else if(context_menu_text_state == 'show'){
                    $(".context-menu-list.textfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }

                // if($('#modalStaff').is(':visible')){
                //  if(ele.className == 'selected'){
                //      pilih_staff(tipe_search);
                //  }else if(ele.id == 'btnPilihStaff'){
                //      pilih_staff(tipe_search);
                //  }else if(ele.id == 'query'){
                //      search_staff($(ele).val());
                //      return false;
                //  }
                // }

                // if($('#modalPJ').is(':visible')){
                //     if(ele.className == 'selected'){
                //         pilih_pj(tipe_pj);
                //     }else if(ele.id == 'btnPilihPJ'){
                //         pilih_pj(tipe_pj);
                //     }else if(ele.id == 'query'){
                //         search_pj($(ele).val());
                //         return false;
                //     }
                // }

                if($('#modalRencana').is(':visible') && ele.id != 'penanggungjawabrak'){
                    // if(ele.id == 'penanggungjawabrak'){
                    //     tipe_pj = 'rencana';
                    //     $('#modalPJ').modal('show');
                    //     $('#modalPJ #query').val($(ele).val());
                    //     $('#modalPJ').on('shown.bs.modal', function() {
                    //         $('#modalPJ #query').focus();
                    //     });
                    //     search_pj($(ele).val());
                    // }else{
                    //     if(!$('#modalPJ').is(':visible')){
                    //         if($('#modalRencana #modalRencanaTipe').val() == 'banyak'){
                    //             submit_rsbbi();
                    //         }else{
                    //             submit_rspb();
                    //         }
                    //     }
                    // }
                    if($('#modalRencana #modalRencanaTipe').val() == 'banyak'){
                        submit_rsbbi();
                    }else{
                        submit_rspb();
                    }
                }

                if($('#modalCFS_AS_CAB').is(':visible') && ele.id != 'penanggungjawabarea'){
                    // if(ele.id == 'penanggungjawabarea'){
                    //     tipe_pj = 'cfs_as_cab';
                    //     $('#modalPJ').modal('show');
                    //     $('#modalPJ #query').val($(ele).val());
                    //     $('#modalPJ').on('shown.bs.modal', function() {
                    //         $('#modalPJ #query').focus();
                    //     });
                    //     search_pj($(ele).val());
                    // }else{
                    //     if(!$('#modalPJ').is(':visible')){
                    //         tipe_cfs_as_cab = $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val();
                    //         if(tipe_cfs_as_cab == 'cab'){
                    //             $('#modalKewenangan #permission').val('opname.closeallbarang');
                    //             $('#modalKewenangan #about').val('stopcab');
                    //             $('#modalKewenangan').modal('show');
                    //         }else{
                    //             submit_cfs_as_cab(tipe_cfs_as_cab);
                    //         }
                    //     }
                    // }
                    tipe_cfs_as_cab = $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val();
                    if(tipe_cfs_as_cab == 'cab'){
                        $('#modalKewenangan #permission').val('opname.closeallbarang');
                        $('#modalKewenangan #about').val('stopcab');
                        $('#modalKewenangan').modal('show');
                    }else{
                        submit_cfs_as_cab(tipe_cfs_as_cab);
                    }
                }

                if($('#modalUpdateStop').is(':visible')){
                    // if(ele.id == 'penghitung'){
                    //  tipe_search = 'penghitung';
                    //  $('#modalStaff').modal('show');
                    //  $('#modalStaff #query').val($(ele).val());
                    //  $('#modalStaff').on('shown.bs.modal', function() {
                    //      $('#query').focus();
                    //  });
                    //  search_staff($(ele).val());
                    // }else if(ele.id == 'pemeriksa'){
                    //  tipe_search = 'pemeriksa';
                    //  $('#modalStaff').modal('show');
                    //  $('#modalStaff #query').val($(ele).val());
                    //  $('#modalStaff').on('shown.bs.modal', function() {
                    //      $('#query').focus();
                    //  });
                    //  search_staff($(ele).val());
                    // }else if(ele.id == 'tglstop' || ele.id == 'qtybaik' || ele.id == 'qtyrusak' || ele.id == 'keteranganhasilhitung' || ele.id == 'btnSubmitUpdateStop'){
                    //  submit_update();
                    //  return false;
                    // }
                    if(ele.id == 'tglstop' || ele.id == 'qtybaik' || ele.id == 'qtyrusak' || ele.id == 'keteranganhasilhitung' || ele.id == 'btnSubmitUpdateStop'){
                        submit_update();
                        return false;
                    }
                }

                if($('#modalKewenangan').is(':visible')){
                    submit_kewenangan();
                    return false;
                }
            }else if(e.keyCode == 9){
                if($('#modalRencana').is(':visible')){
                    if(ele.id == 'tglrsbbi'){
                        date = new Date($(ele).val().split("-").reverse().join("-"));
                        today = new Date('{{Carbon\Carbon::now()->toDateString()}}');
                        if(date < today){
                            swal('Ups!', 'Tidak boleh masukkan tanggal rencana lebih kecil dari tanggal server.','error');
                        }
                    }
                }
            }
        });

        $(document.body).on("keyup", function(e){
            ele = document.activeElement;
            if($('#modalUpdateStop').is(':visible')){
                if(ele.id == 'qtybaik'){
                    qtyawal    = parseInt($('#modalUpdateStop #qtyawal').val());
                    qtybaik    = parseInt($('#modalUpdateStop #qtybaik').val());
                    qtyrusak   = parseInt($('#modalUpdateStop #qtyrusak').val());
                    qtygs      = parseInt($('#modalUpdateStop #qtygs').val());
                    qtyaggit   = parseInt($('#modalUpdateStop #qtyaggit').val());
                    qtystop    = qtybaik+qtyrusak+qtygs+qtyaggit;
                    qtyselisih = qtystop-qtyawal;
                    $('#modalUpdateStop #qtystop').val(qtystop);
                    $('#modalUpdateStop #qtyselisih').val(qtyselisih);
                }else if(ele.id == 'qtyrusak'){
                    qtyawal    = parseInt($('#modalUpdateStop #qtyawal').val());
                    qtybaik    = parseInt($('#modalUpdateStop #qtybaik').val());
                    qtyrusak   = parseInt($('#modalUpdateStop #qtyrusak').val());
                    qtygs      = parseInt($('#modalUpdateStop #qtygs').val());
                    qtyaggit   = parseInt($('#modalUpdateStop #qtyaggit').val());
                    qtystop    = qtybaik+qtyrusak+qtygs+qtyaggit;
                    qtyselisih = qtystop-qtyawal;
                    $('#modalUpdateStop #qtystop').val(qtystop);
                    $('#modalUpdateStop #qtyselisih').val(qtyselisih);
                }
                if(qtyselisih < 0){
                    $('#modalUpdateStop #qtyselisih').css("color","red");
                    $('#modalUpdateStop #qtyselisih').css("font-weight","bold");
                }else if(qtyselisih > 0){
                    $('#modalUpdateStop #qtyselisih').css("color","blue");
                    $('#modalUpdateStop #qtyselisih').css("font-weight","bold");
                }
            }
        });

        $('#btnSubmitRencana').click(function(){
            if($('#modalRencana #modalRencanaTipe').val() == 'banyak'){
                submit_rsbbi();
            }else{
                submit_rspb();
            }
        });

        $('#btnSubmitCFS_AS_CAB').click(function(){
            tipe_cfs_as_cab = $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val();
            if(tipe_cfs_as_cab == 'cab'){
                $('#modalKewenangan #permission').val('opname.closeallbarang');
                $('#modalKewenangan #about').val('stopcab');
                $('#modalKewenangan').modal('show');
            }else{
                submit_cfs_as_cab(tipe_cfs_as_cab);
            }
        });

        $('#btnSubmitUpdateStop').click(function(){
            submit_update();
        });

        // $('#tbodyStaff').on('click', 'tr', function(){
        //  $('.selected').removeClass('selected');
        //  $(this).addClass("selected");
        // });

        // $('#btnPilihStaff').on('click', function(){
        //  pilih_staff(tipe_search);
        // });
        // $('#modalStaff table.tablepilih tbody').on('dblclick', 'tr', function(){
        //       pilih_staff(tipe_search);
        // });
        // $('#tbodyPJ').on('click', 'tr', function(){
        //  $('.selected').removeClass('selected');
        //  $(this).addClass("selected");
        // });

        // $('#btnPilihPJ').on('click', function(){
        //     pilih_pj(tipe_pj);
        // });
        // $('#modalPJ table.tablepilih tbody').on('dblclick', 'tr', function(){
        //      pilih_pj(tipe_pj);
        // });
        $('#btnSubmitKewenangan').click(function(){
            submit_kewenangan();
        });

        $('#table2 tbody').on('dblclick', 'tr', function(){
            var data = table2.row(this).data();
            $.ajax({
                type: 'GET',
                url: '{{route("opname.view")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                    tabledoubleclick.clear();
                    tabledoubleclick.rows.add([
                        {'0':'1.', '1':'Stock ID', '2':data.stockid},
                        {'0':'2.', '1':'Tgl. Rencana', '2':data.tglrencana},
                        {'0':'3.', '1':'Tgl. STOP', '2':data.tglstop},
                        {'0':'4.', '1':'Qty. Awal', '2':data.qtyawal},
                        {'0':'5.', '1':'Qty. Baik', '2':data.qtybaik},
                        {'0':'6.', '1':'Qty. Rusak', '2':data.qtyrusak},
                        {'0':'7.', '1':'Qty. GS', '2':data.qtygs},
                        {'0':'8.', '1':'Qty. AG GIT', '2':data.qtyaggit},
                        {'0':'9.', '1':'Qty. STOP', '2':data.qtystop},
                        {'0':'10.', '1':'Penghitung', '2':data.penghitung},
                        {'0':'11.', '1':'Pemeriksa', '2':data.pemeriksa},
                        {'0':'12.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'13.', '1':'Last Updated On', '2':data.lastupdatedon},
                    ]);
                    tabledoubleclick.draw();
                    $('#modalDoubleClickPengiriman #myModalLabel').html('Data Pengiriman Detail');
                    $('#modalDoubleClickPengiriman').modal('show');
                }
            });
        });

        table.on('select', function ( e, dt, type, indexes ){
            // table_index = indexes;
            // fokus       = 'header';
            var rowData = table.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                data: {
                    id : rowData[0].id,
                    tglmulai : $('#tglmulai').val(),
                    tglselesai : $('#tglselesai').val(),
                },
                // data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("opname.detail.data")}}',
                success: function(data){
                    table2.clear();
                    for (var i = 0; i < data.soh.length; i++) {
                        var html_buttonaction  = "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Update - F2' onclick='update(this,"+data.soh[i].id+")' data-message='"+data.soh[i].update+"' data-tipe='header'><i class='fa fa-pencil'></i></div>";
                            html_buttonaction += "<div class='btn btn-xs btn-danger no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='hapus(this,"+data.soh[i].id+")' data-message='"+data.soh[i].hapus+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
                            html_buttonaction += "<div class='btn btn-xs btn-primary no-margin-action skeyF4' data-toggle='tooltip' data-placement='bottom' title='Closing - F4' onclick='closing(this,"+data.soh[i].id+")' data-message='"+data.soh[i].closing+"' data-tipe='header' data-barang='"+data.soh[i].barang+"'>Closing - F4</div>";
                            html_buttonaction += "<div class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Cetak DFS - F3' onclick='cetak(this,"+data.soh[i].id+")' data-tipe='header'><i class='fa fa-print'></i></div>";
                        table2.row.add({
                            action     : html_buttonaction,
                            tglrencana : data.soh[i].tglrencana,
                            tglstop    : data.soh[i].tglstop,
                            qtyawal    : data.soh[i].qtyawal,
                            qtybaik    : data.soh[i].qtybaik,
                            qtyrusak   : data.soh[i].qtyrusak,
                            qtygs      : data.soh[i].qtygs,
                            qtyaggit   : data.soh[i].qtyaggit,
                            qtystop    : data.soh[i].qtystop,
                            qtyselisih : data.soh[i].qtyselisih,
                            tglclosing : data.soh[i].tglclosing,
                            id         : data.soh[i].id,
                            DT_RowId   : data.soh[i].DT_RowId,
                        });
                    }
                    table2.draw();
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        table.on('deselect', function ( e, dt, type, indexes ) {
            table2.clear().draw();
        });

        // table2.on('select', function ( e, dt, type, indexes ){
        //     fokus       = 'detail';
        //     table2_index = indexes;
        // });

        $('.tgl').change(function(){
            table.draw();
            table2.draw();
        });
    });

    function open_rspb(data){
        @cannot('opname.rencana')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        var barangid = data;
        $.ajax({
            type: 'GET',
            data: {id : data},
            dataType: "json",
            url: '{{route("opname.stockview")}}',
            success: function(data){
                clear_column('modalRencana');
                toogle_column('modalRencana',['tglrsbbi','namabarang','kodebarang','statusaktif','rak1','rak2','rak3','penanggungjawabrak']);
                toogle_column('modalRencana',['tglrsbbi'],true);
                disable_radio('modalRencana',['tipestop_harian','tipestop_tahunan'],true);
                $('#modalRencana #modalRencanaTitle').html('Rencana STOP Per Barang Insert');
                $('#modalRencana #modalRencanaTipe').val('satuan');
                $('#modalRencana #tipestop_harian').prop('checked', true);
                $('#modalRencana #barangId').val(barangid);
                $('#modalRencana #tglrsbbi').val('{{Carbon\Carbon::now()->format("d-m-Y")}}').change();
                $('#modalRencana #namabarang').val(data.namabarang);
                $('#modalRencana #kodebarang').val(data.kodebarang);
                $('#modalRencana #statusaktif').val(data.statusaktif);
                $('#modalRencana #rak1').val(data.rak1);
                $('#modalRencana #rak2').val(data.rak2);
                $('#modalRencana #rak3').val(data.rak3);
                $('#modalRencana #penanggungjawabrak').val(data.pemegangrak);
                $('#modalRencana #keteranganrencana').val('RENCANA OPNAME HARIAN {{strtoupper(auth()->user()->username)}}');
                $('#modalRencana').modal('show');
                $('#modalRencana').on('shown.bs.modal', function() {
                    $('#modalRencana #tglrsbbi').focus();
                });
            },
            error: function(data){
                console.log(data);
            }
        });
        @endcannot
    }

    function open_rsbbi(){
        @cannot('opname.rencana')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        init_form();
        $('#modalRencana #modalRencanaTitle').html('Rencana STOP Banyak Barang Insert');
        $('#modalRencana #modalRencanaTipe').val('banyak');
        $('#modalRencana').modal('show');
        $('#modalRencana').on('shown.bs.modal', function() {
            $('#modalRencana #tglrsbbi').focus();
        });
        @endcannot
    }

    function open_cfs(){
        @cannot('opname.cetakcfs')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        init_CFS_AS_CAB('cfs');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Title').html('Cetak Form STOP');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val('cfs');
        $('#modalCFS_AS_CAB').modal('show');
        $('#modalCFS_AS_CAB').on('shown.bs.modal', function() {
            $('#modalCFS_AS_CAB #tglrsbbi').focus();
        });
        @endcannot
    }

    function open_as(){
        @cannot('opname.analisaclosing')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        init_CFS_AS_CAB('as');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Title').html('Analisa STOP');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val('as');
        $('#modalCFS_AS_CAB').modal('show');
        $('#modalCFS_AS_CAB').on('shown.bs.modal', function() {
            $('#modalCFS_AS_CAB #tipestop_rencana').focus();
        });
        @endcannot
    }

    function open_cab(){
        @cannot('opname.closeallbarang')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        init_CFS_AS_CAB('cab');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Title').html('Closing ALL Barang');
        $('#modalCFS_AS_CAB #modalCFS_AS_CAB_Tipe').val('cab');
        $('#modalCFS_AS_CAB').modal('show');
        $('#modalCFS_AS_CAB').on('shown.bs.modal', function() {
            $('#modalCFS_AS_CAB #namabarang').focus();
        });
        @endcannot
    }

    function update(e,data){
        @cannot('opname.update')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        var message = $(e).data('message');
        if(message == 'update'){
            $.ajax({
                type: 'GET',
                data: {id : data},
                dataType: "json",
                url: '{{route("opname.view")}}',
                success: function(data){
                    clear_column('modalUpdateStop');
                    toogle_column('modalUpdateStop',['qtybaik','qtyrusak','penghitung','pemeriksa','keteranganhasilhitung'],true);
                    $('#modalUpdateStop #sohId').val(data.sohid);
                    $('#modalUpdateStop #tglstop').val('{{Carbon\Carbon::now()->format("d-m-Y")}}');
                    $('#modalUpdateStop #stockid').val(data.stockid);
                    $('#modalUpdateStop #barang').val(data.namabarang);
                    $('#modalUpdateStop #kodebarang').val(data.kodebarang);
                    $('#modalUpdateStop #satuan').val(data.satuan);
                    $('#modalUpdateStop #statusaktif').val(data.statusaktif);
                    $('#modalUpdateStop #qtyawal').val(data.qtyawal);
                    $('#modalUpdateStop #qtybaik').val(data.qtybaik);
                    $('#modalUpdateStop #qtyrusak').val(data.qtyrusak);
                    $('#modalUpdateStop #qtygs').val(data.qtygs);
                    $('#modalUpdateStop #qtyaggit').val(data.qtyaggit);
                    $('#modalUpdateStop #qtystop').val(data.qtystop);
                    $('#modalUpdateStop #qtyselisih').val(data.qtyselisih);
                    $('#modalUpdateStop #penghitung').val(data.penghitung);
                    $('#modalUpdateStop #pemeriksa').val(data.pemeriksa);
                    $('#modalUpdateStop #penghitungid').val(data.karyawanidpenghitung);
                    $('#modalUpdateStop #pemeriksaid').val(data.karyawanidpemeriksa);
                    $('#modalUpdateStop #keteranganrencana').val(data.keteranganrencana);
                    $('#modalUpdateStop #keteranganhasilhitung').val(data.keteranganhasilhitung);
                    $('#modalUpdateStop').modal('show');
                    $('#modalUpdateStop').on('shown.bs.modal', function() {
                        // $('#modalUpdateStop #tglstop').focus();
                        $('#modalUpdateStop #qtybaik').focus();
                    });
                },
                error: function(data){
                    console.log(data);
                }
            });
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function hapus(e,data){
        @cannot('opname.delete')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var message = $(e).data('message');
        if(message == 'hapus'){
            $('#modalKewenangan #id').val(data);
            $('#modalKewenangan #permission').val('opname.delete');
            $('#modalKewenangan #about').val('deletesoh');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function closing(e,data){
        @cannot('opname.closing')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        var message = $(e).data('message');
        if(message == 'closing'){
            var barang = $(e).data('barang');
            swal({
                title: "Apakah anda yakin ingin closing stock opname atas barang "+barang+" ?",
                text: "BARANG TIDAK BISA DIOPNAME KEMBALI PADA HARI YANG SAMA",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            },
            function(){
                tipe_edit_detail = true;
                $.ajaxSetup({
                    headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
                });

                $.ajax({
                    type: 'POST',
                    data: {id : data},
                    dataType: "json",
                    url: '{{route("opname.closing")}}',
                    success: function(data){
                        swal("Sukses", "Berhasil diclosing", "success");
                        table.ajax.reload(null, true);
                        tipe_edit_header = null;
                        setTimeout(function(){
                            // table.row(0).select();
                            table.cell("#gv1_"+table_index,":eq(1)").focus();
                        },1000);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    function submit_rsbbi(){
        @cannot('opname.rencana')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        tipestop = $('#modalRencana input[name=tipestop]:checked').val();
        tipe_edit_header = true;
        if(tipestop == 'Tahunan'){
            // url = '{{route("opname.rencana","tahunan")}}'+'?tgl='+$('#modalRencana #tglrsbbi').val()+'&&ket='+$('#modalRencana #keteranganrencana').val();
            $.ajax({
                type: 'GET',
                data: {
                    tgl: $('#modalRencana #tglrsbbi').val(),
                    ket: $('#modalRencana #keteranganrencana').val(),
                },
                dataType: "json",
                url     : '{{route("opname.rencana","tahunan")}}',
                success : function(data){
                    if(data.success){
                        table.ajax.reload(null, true);
                        tipe_edit_header = null;
                        setTimeout(function(){
                            // table.row(0).select();
                            table.cell("#gv1_"+table_index,":eq(1)").focus();
                        },1000);
                        window.open(data.url);
                    }else{
                        swal('Ups!', data.error, 'error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
            // data = {
            //  tgl : $('#modalRencana #tglrsbbi').val(),
            //  ket : $('#modalRencana #keteranganrencana').val(),
            // }
        }else{
            $.ajax({
                type: 'GET',
                data: {
                    tgl        : $('#modalRencana #tglrsbbi').val(),
                    ket        : $('#modalRencana #keteranganrencana').val(),
                    namabarang : $('#modalRencana #namabarang').val(),
                    kodebarang : $('#modalRencana #kodebarang').val(),
                    statusaktif: $('#modalRencana #statusaktif').val(),
                    rak1       : $('#modalRencana #rak1').val(),
                    rak2       : $('#modalRencana #rak2').val(),
                    rak3       : $('#modalRencana #rak3').val(),
                    penanggungjawabrak: $('#modalRencana #penanggungjawabarea').val(),
                },
                dataType: "json",
                url     : '{{route("opname.rencana","harian")}}',
                success : function(data){
                    if(data.success){
                        table.ajax.reload(null, true);
                        tipe_edit_header = null;
                        setTimeout(function(){
                            // table.row(0).select();
                            table.cell("#gv1_"+table_index,":eq(1)").focus();
                        },1000);
                        window.open(data.url);
                    }else{
                        swal('Ups!', data.error, 'error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
            // url = '{{route("opname.rencana","harian")}}'+'?tgl='+$('#modalRencana #tglrsbbi').val()+'&&ket='+$('#modalRencana #keteranganrencana').val()+'&&namabarang='+$('#modalRencana #namabarang').val()+'&&kodebarang='+$('#modalRencana #kodebarang').val()+'&&statusaktif='+$('#modalRencana #statusaktif').val()+'&&rak1='+$('#modalRencana #rak1').val()+'&&rak2='+$('#modalRencana #rak2').val()+'&&rak3='+$('#modalRencana #rak3').val()+'&&penanggungjawabrak='+$('#modalRencana #penanggungjawabrakId').val();
            // data = {
            //  tgl                : $('#modalRencana #tglrsbbi').val(),
            //  ket                : $('#modalRencana #keteranganrencana').val(),
            //  namabarang         : $('#modalRencana #namabarang').val(),
            //  kodebarang         : $('#modalRencana #kodebarang').val(),
            //  statusaktif        : $('#modalRencana #statusaktif').val(),
            //  rak1               : $('#modalRencana #rak1').val(),
            //  rak2               : $('#modalRencana #rak2').val(),
            //  rak3               : $('#modalRencana #rak3').val(),
            //  penanggungjawabrak : $('#modalRencana #penanggungjawabrak').val(),
            // }
        }
        $('#modalRencana').modal('hide');
        @endcannot
    }

    function submit_rspb(){
        @cannot('opname.rencanaperbarang')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        $.ajax({
            type: 'GET',
            data: {
                id         : $('#modalRencana #barangId').val(),
                tgl        : $('#modalRencana #tglrsbbi').val(),
                namabarang : $('#modalRencana #namabarang').val(),
                kodebarang : $('#modalRencana #kodebarang').val(),
                statusaktif: $('#modalRencana #statusaktif').val(),
                rak1       : $('#modalRencana #rak1').val(),
                rak2       : $('#modalRencana #rak2').val(),
                rak3       : $('#modalRencana #rak3').val(),
                penanggungjawabrak : $('#modalRencana #penanggungjawabarea').val(),
                keteranganrencana  : $('#modalRencana #keteranganrencana').val(),
            },
            dataType: "json",
            url: '{{route("opname.rencanaperbarang")}}',
            success: function(data){
                if(data.success){
                    $('#modalRencana').modal('hide');
                    swal({
                        title: "Mau cetak Form STOP?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        closeOnConfirm: true,
                    },
                    function(){
                        table.ajax.reload(null, true);
                        tipe_edit_header = null;
                        setTimeout(function(){
                            table.cell("#gv1_"+data.id,":eq(1)").focus();
                        },1000);
                        cetak(null,data.id);
                    });
                }else{
                    swal('Ups!', data.error, 'error');
                }
            },
            error: function(data){
                console.log(data);
            }
        });
        @endcan
    }

    function submit_cfs_as_cab(tipe){
        tipestop = $('#modalCFS_AS_CAB input[name=tipestop]:checked').val();
        if(tipe == 'cfs'){
            @cannot('opname.cetakcfs')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            url = '{{route("opname.cetakcfs")}}'+'?tipe='+tipe+'&&tipestop='+tipestop+'&&tgl='+$('#modalCFS_AS_CAB #tglrsbbi').val()+'&&namabarang='+$('#modalCFS_AS_CAB #namabarang').val()+'&&kodebarang='+$('#modalCFS_AS_CAB #kodebarang').val()+'&&statusaktif='+$('#modalCFS_AS_CAB #statusaktif').val()+'&&rak1='+$('#modalCFS_AS_CAB #rak1').val()+'&&rak2='+$('#modalCFS_AS_CAB #rak2').val()+'&&rak3='+$('#modalCFS_AS_CAB #rak3').val()+'&&penanggungjawabrak='+$('#modalCFS_AS_CAB #penanggungjawabareaId').val();
            window.open(url);
            @endcan
        }else if(tipe == 'cab'){
            @cannot('opname.closeallbarang')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                url: '{{route("opname.closeallbarang")}}',
                data: {
                    tgl                : $('#modalCFS_AS_CAB #tglrsbbi').val(),
                    namabarang         : $('#modalCFS_AS_CAB #namabarang').val(),
                    kodebarang         : $('#modalCFS_AS_CAB #kodebarang').val(),
                    statusaktif        : $('#modalCFS_AS_CAB #statusaktif').val(),
                    rak1               : $('#modalCFS_AS_CAB #rak1').val(),
                    rak2               : $('#modalCFS_AS_CAB #rak2').val(),
                    rak3               : $('#modalCFS_AS_CAB #rak3').val(),
                    penanggungjawabrak : $('#modalCFS_AS_CAB #penanggungjawabareaId').val(),
                },
                dataType: "json",
                success: function(data){
                    swal("Sukses", "Berhasil diclosing", "success");
                    table.ajax.reload(null, true);
                    tipe_edit_header = null;
                    setTimeout(function(){
                        // table.row(0).select();
                        table.cell("#gv1_"+table_index,":eq(1)").focus();
                    },1000);
                },
                error: function(data){
                    console.log(data);
                }
            });
            @endcannot
        }else{
            @cannot('opname.analisaclosing')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            $.ajax({
                type: 'GET',
                data: {
                    tipe               : tipe,
                    tipestop           : tipestop,
                    tgl                : $('#modalCFS_AS_CAB #tglrsbbi').val(),
                    namabarang         : $('#modalCFS_AS_CAB #namabarang').val(),
                    kodebarang         : $('#modalCFS_AS_CAB #kodebarang').val(),
                    statusaktif        : $('#modalCFS_AS_CAB #statusaktif').val(),
                    rak1               : $('#modalCFS_AS_CAB #rak1').val(),
                    rak2               : $('#modalCFS_AS_CAB #rak2').val(),
                    rak3               : $('#modalCFS_AS_CAB #rak3').val(),
                    penanggungjawabrak : $('#modalCFS_AS_CAB #penanggungjawabareaId').val(),
                },
                dataType: "json",
                url: '{{route("opname.analisaclosing")}}',
                success: function(data){
                    if(data.success){
                        window.open(data.url);
                    }else{
                        swal('Ups!', data.error, 'error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
            // url = '{{route("opname.analisaclosing")}}'+'?tipe='+tipe+'&&tipestop='+tipestop+'&&tgl='+$('#modalCFS_AS_CAB #tglrsbbi').val()+'&&namabarang='+$('#modalCFS_AS_CAB #namabarang').val()+'&&kodebarang='+$('#modalCFS_AS_CAB #kodebarang').val()+'&&statusaktif='+$('#modalCFS_AS_CAB #statusaktif').val()+'&&rak1='+$('#modalCFS_AS_CAB #rak1').val()+'&&rak2='+$('#modalCFS_AS_CAB #rak2').val()+'&&rak3='+$('#modalCFS_AS_CAB #rak3').val()+'&&penanggungjawabrak='+$('#modalCFS_AS_CAB #penanggungjawabareaId').val();
            // window.open(url);
            @endcannot
        }
        $('#modalCFS_AS_CAB').modal('hide');
    }

    function submit_update(){
        @cannot('opname.update')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        pemeriksa   = $('#modalUpdateStop #pemeriksaid').val();
        penghitung  = $('#modalUpdateStop #penghitungid').val();
        tglstop     = $('#modalUpdateStop #tglstop').val();
        kethasilhtg = $('#modalUpdateStop #keteranganhasilhitung').val();

        if(penghitung == null){
            swal("Ups!", "Tidak bisa update Rencana. Penghitung harus diisi. Hubungi Manager Anda.", "error");
        }else if(pemeriksa == null){
            swal("Ups!", "Tidak bisa update Rencana. Pemeriksa harus diisi. Hubungi Manager Anda.", "error");
        }else if(tglstop == null){
            swal("Ups!", "Tidak bisa update Rencana. Tgl.Stop harus diisi. Hubungi Manager Anda.", "error");
        }else if(pemeriksa == penghitung) {
            swal("Ups!", "Tidak bisa update Rencana. Nama penghitung dan Pemeriksa tidak boleh sama. Hubungi Manager Anda.", "error");
        }else if(kethasilhtg == null){
            swal("Ups!", "Tidak bisa update Rencana. Keterangan Hasil Hitung harus diisi. Hubungi Manager Anda.", "error");
        }else if(pemeriksa && penghitung && tglstop){
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                url: '{{route("opname.update")}}',
                data: {
                    id      : $('#modalUpdateStop #sohId').val(),
                    tglstop : $('#modalUpdateStop #tglstop').val(),
                    qtybaik : $('#modalUpdateStop #qtybaik').val(),
                    qtyrusak: $('#modalUpdateStop #qtyrusak').val(),
                    penghitungid: $('#modalUpdateStop #penghitungid').val(),
                    pemeriksaid : $('#modalUpdateStop #pemeriksaid').val(),
                    keteranganhasilhitung: $('#modalUpdateStop #keteranganhasilhitung').val(),
                },
                dataType: "json",
                success: function(data){
                    $('#modalUpdateStop').modal('hide');
                    table.ajax.reload(null, true);
                    tipe_edit_header = null;
                    setTimeout(function(){
                        // table.row(0).select();
                        table.cell("#gv1_"+table_index,":eq(1)").focus();
                    },1000);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        @endcannot
    }

    function cetak(e,data){
        @cannot('opname.cetakdfs')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        url = '{{route("opname.cetakdfs")}}'+'?id='+data;
        window.open(url);
        @endcannot
    }

    function init_form(){
        clear_column('modalRencana');
        toogle_column('modalRencana',['tglrsbbi','namabarang','kodebarang','statusaktif','rak1','rak2','rak3','penanggungjawabrak','keteranganrencana']);
        toogle_column('modalRencana',['tglrsbbi','namabarang','kodebarang','statusaktif','rak1','rak2','rak3','penanggungjawabrak','keteranganrencana'],true);
        disable_radio('modalRencana',['tipestop_harian','tipestop_tahunan'],false);
        $('#modalRencana #tipestop_harian').prop('checked', true);
        $('#modalRencana #statusaktif_aktif').prop('selected', true);
        $('#modalRencana #keteranganrencana').val('RENCANA OPNAME HARIAN {{strtoupper(auth()->user()->username)}}');
        $('#modalRencana #tglrsbbi').val('{{Carbon\Carbon::now()->format("d-m-Y")}}').change();
    }

    function init_CFS_AS_CAB(tipe){
        clear_column('modalCFS_AS_CAB');
        $('#modalCFS_AS_CAB #tglrsbbi').val('{{Carbon\Carbon::now()->format("d-m-Y")}}');
        $('#modalCFS_AS_CAB #tglrsbbi').prop("readonly",false);
        $('#modalCFS_AS_CAB #tipestop_rencana').prop('checked', true);
        if(tipe == 'cfs'){
            $('#modalCFS_AS_CAB #AS_only').hide();
            $('#modalCFS_AS_CAB #statusaktif_aktif').prop('selected', true);
        }else if(tipe == 'cab'){
            $('#modalCFS_AS_CAB #AS_only').hide();
            $('#modalCFS_AS_CAB #statusaktif_semua').prop('selected', true);
            $('#modalCFS_AS_CAB #tglrsbbi').val('');
            $('#modalCFS_AS_CAB #tglrsbbi').prop("readonly",true);
        }else{
            $('#modalCFS_AS_CAB #AS_only').show();
            $('#modalCFS_AS_CAB #statusaktif_semua').prop('selected', true);
        }
    }

    $('input[name="tipestop"]').on('click', function(e) {
        tipestop = $('#modalRencana input[name=tipestop]:checked').val();
        if(tipestop == 'Tahunan'){
            toogle_column('modalRencana',['namabarang','kodebarang','statusaktif','rak1','rak2','rak3','penanggungjawabrak'],false,true);
            $('#modalRencana #statusaktif_semua').prop('selected', true);
            $('#modalRencana #statusaktif').prop('disabled', true);
            $('#modalRencana #keteranganrencana').val('RENCANA OPNAME TAHUNAN {{strtoupper(auth()->user()->username)}}');
        }else{
            toogle_column('modalRencana',['namabarang','kodebarang','statusaktif','rak1','rak2','rak3','penanggungjawabrak'],true);
            $('#modalRencana #statusaktif_aktif').prop('selected', true);
            $('#modalRencana #statusaktif').prop('disabled', false);
            $('#modalRencana #keteranganrencana').val('RENCANA OPNAME HARIAN {{strtoupper(auth()->user()->username)}}');
        }
    }); 

    // function pilih_staff(tipe){
    //  if ($('#tbodyStaff').find('tr.selected td').eq(1).text() == '') {
    //      swal("Ups!", "Staff belum dipilih.", "error");
    //      return false;
    //  }else {
    //      if(tipe == 'penghitung'){
    //          $('#penghitung').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //          $('#penghitungid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //          $('#modalStaff').modal('hide');
    //          $("#penghitung").focus();
    //      }else if(tipe == 'pemeriksa'){
    //          $('#pemeriksa').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //          $('#pemeriksaid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //          $('#modalStaff').modal('hide');
    //          $("#pemeriksa").focus();
    //      }
    //  }
    //  tipe_search = null;
    // }

    // function pilih_pj(tipe){
    //     if ($('#tbodyPJ').find('tr.selected td').eq(1).text() == '') {
    //         swal("Ups!", "Staff belum dipilih.", "error");
    //         return false;
    //     }else {
    //         if(tipe == 'rencana'){
    //             $('#penanggungjawabrak').val($('#tbodyPJ').find('tr.selected td').eq(2).text());
    //             $('#penanggungjawabrakId').val($('#tbodyPJ').find('tr.selected td .id_staff').val());
    //             $('#modalPJ').modal('hide');
    //             $("#penanggungjawabrak").focus();
    //         }else if(tipe == 'cfs_as_cab'){
    //             $('#penanggungjawabarea').val($('#tbodyPJ').find('tr.selected td').eq(2).text());
    //             $('#penanggungjawabareaId').val($('#tbodyPJ').find('tr.selected td .id_staff').val());
    //             $('#modalPJ').modal('hide');
    //             $("#penanggungjawabarea").focus();
    //         }
    //     }
    //     tipe_search = null;
    // }

    function clear_column(modal){
        for (var i = 0; i < $('#'+modal+' .form-clear').length; i++) {
            element = $('#'+modal+' .form-clear')[i];
            $(element).val('');
        }
    }

    function toogle_column(modal, column, enable, empty){
        if(enable){
            for (var i = 0; i < column.length; i++) {
                if($('#'+modal+' #'+column[i]).prop('type') == 'select-one'){
                    $('#'+modal+' #'+column[i]).removeAttr("disabled");
                }else{
                    $('#'+modal+' #'+column[i]).removeAttr("readonly");
                }
                $('#'+modal+' #'+column[i]).removeAttr("tabindex");
                // $('#'+modal+' #'+column[i]).attr("tabindex",i+1);
                if(empty){
                    $('#'+modal+' #'+column[i]).val('');
                }
            }
        }else{
            for (var i = 0; i < column.length; i++) {
                if($('#'+modal+' #'+column[i]).prop('type') == 'select-one'){
                    $('#'+modal+' #'+column[i]).attr("disabled","true");
                }else{
                    $('#'+modal+' #'+column[i]).attr("readonly","true");
                }
                $('#'+modal+' #'+column[i]).attr("tabindex",'-1');
                if(empty){
                    $('#'+modal+' #'+column[i]).val('');
                }
            }
        }
    }

    function disable_radio(modal, column, disable){
        for (var i = 0; i < column.length; i++) {
            $('#'+modal+' #'+column[i]).prop('disabled', disable);
        }
    }

    // function search_staff(query){
    //  $.ajaxSetup({
    //      headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
    //  });

    //  $.ajax({
    //      type: 'POST',
    //      url: '{{route("lookup.getstaff")}}',
    //      data : {katakunci : query},
    //      dataType : 'json',
    //      success: function(data){
    //          staff = data;
    //          $('#tbodyStaff tr').remove();
    //          var x = '';
    //          if (staff.length > 0) {
    //              for (var i = 0; i < staff.length; i++) {
    //                  x += '<tr tabindex=0>';
    //                  x += '<td>'+ staff[i].niksystemlama +'<input type="hidden" class="id_staff" value="'+ staff[i].id +'"></td>';
    //                  x += '<td>'+ staff[i].nikhrd +'</td>';
    //                  x += '<td>'+ staff[i].namakaryawan +'</td>';
    //                  x += '</tr>';
    //              }
    //          }else {
    //              x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
    //          }
    //          $('#tbodyStaff').append(x);
    //      },
    //      error: function(data){
    //          console.log(data);
    //      }
    //  });
    // }

    // function search_pj(query){
    //     $.ajaxSetup({
    //         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
    //     });

    //     $.ajax({
    //         type: 'POST',
    //         url: '{{route("lookup.karyawangetpj")}}',
    //         data : {katakunci : query},
    //         dataType : 'json',
    //         success: function(data){
    //             staff = data;
    //             $('#tbodyPJ tr').remove();
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
    //             $('#tbodyPJ').append(x);
    //         },
    //         error: function(data){
    //             console.log(data);
    //         }
    //     });
    // }

    function submit_kewenangan(){
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
        });

        $.ajax({
            type: 'POST',
            url: '{{route("opname.kewenangan")}}',
            data: {
                id         : $('#modalKewenangan #id').val(),
                username   : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
                password   : $('#modalKewenangan #pxassKewenangan').val(),
                permission : $('#modalKewenangan #permission').val(),
                about      : $('#modalKewenangan #about').val(),
            },
            dataType: "json",
            success: function(data){
                $('#modalKewenangan #uxserKewenangan').val('').change();
                $('#modalKewenangan #pxassKewenangan').val('').change();

                if(data.success){
                    $('#modalKewenangan').modal('hide');
                    if(data.tipe == 'stopcab'){
                        swal({
                            title: "Apakah anda yakin ingin closing stock opname semua barang ?",
                            text: "BARANG TIDAK BISA DIOPNAME KEMBALI PADA HARI YANG SAMA",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        },
                        function(){
                            submit_cfs_as_cab('cab');
                        });
                    }else if(data.tipe == 'deletesoh'){
                        table.ajax.reload(null, true);
                        tipe_edit_header = null;
                        setTimeout(function(){
                            // table.row(0).select();
                            table.cell("#gv1_"+table_index,":eq(1)").focus();
                        },1000);
                    }
                }else{
                    swal('Ups!', data.error,'error');
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    }
</script>
@endpush