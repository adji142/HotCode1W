@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item"><a href="{{ route('returpembelian.index') }}">Retur Pembelian</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            @if(session('message'))
            <div class="alert alert-{{session('message')['status']}}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                {{ session('message')['desc'] }}
            </div>
            @endif
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Retur Pembelian</h2>
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
                                    <label for="tglmulai" style="margin-right: 10px;">Tgl Retur PB</label>
                                    <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">

                                    <label for="tglselesai">-</label>
                                    <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            @can('returpembelian.tambah')
                            <a href="{{route('returpembelian.tambah')}}" id="skeyIns" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Retur Pembelian - Ins</a>
                            @endcan
                            @can('returpembelian.sync11')
                            <button type="button" id="btnSync11" data-id="skeyF11" class="btn btn-default">Sync 11 - F11</button>
                            @endcan
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl. PRB</th>
                                <th>No. PRB</th>
                                <th>Supplier</th>
                                <th>Tgl. Kirim</th>
                                <th>Tgl. NRJ11</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Tgl. PRB</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> No. PRB</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Supplier</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Tgl. Kirim</a> |
                            <a style="padding:0 5px;" class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. NRJ11</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty. PRB</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Nama Barang</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Satuan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Qty. PRB</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('returpembelian.detail.tambah')
    {{-- BEGIN Modal Tambah Detail RPB --}}
    <div class="modal fade" id="modalDetailRPB" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Detail Retur Pembelian</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="historis">Historis</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="checkbox">
                                    <input type="checkbox" class="flat" id="historis" checked name="historis" value="1" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barang">Barang <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="barang" name="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                                <input type="hidden" id="barangid" name="barangid">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" id="satuan" name="satuan" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyprb">Qty PRB</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtyprb" name="qtyprb" class="form-control text-right" tabindex="-1" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dtnokoli">No. Koli</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="dtnokoli" name="dtnokoli" class="form-control" tabindex="-1" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategoriprbid">Kategori PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="kategoriprb" name="kategoriprb" class="form-control" placeholder="Kategori PRB" autocomplete="off" required="required">
                                <input type="hidden" id="kategoriprbid" name="kategoriprbid">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keteranganprb">Keterangan PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="keteranganprb" name="keteranganprb" class="form-control" tabindex="-1" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="returid" name="returid" value="" readonly tabindex="-1">
                        <input type="hidden" id="npbdid" name="npbdid" value="" readonly tabindex="-1">
                        <input type="hidden" id="notapembelianid" name="notapembelianid" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtynota" name="qtynota" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyterima" name="qtyterima" value="" readonly tabindex="-1">
                        <input type="hidden" id="hrgsatuanbrutto" name="hrgsatuanbrutto" value="" readonly tabindex="-1">
                        <input type="hidden" id="disc1" name="disc1" value="" readonly tabindex="-1">
                        <input type="hidden" id="disc2" name="disc2" value="" readonly tabindex="-1">
                        <input type="hidden" id="ppn" name="ppn" value="" readonly tabindex="-1">
                        <input type="hidden" id="hrgsatuannetto" name="hrgsatuannetto" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyretur" name="qtyretur" value="" readonly tabindex="-1">
                        <input type="hidden" id="maxqty" name="maxqty" value="" readonly tabindex="-1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Tambah Detail RPB --}}
    @endcan

    @can('returpembelian.updatetglkirim')
    {{-- BEGIN Modal Update Tanggal Kirim --}}
    <div class="modal fade" id="modalUpdateKirim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Update Tanggal Kirim</h4>
                </div>
                <form id="formUpdateKirim" class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recordownerid">Transaksi untuk</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="recordownerid" class="form-control" value="" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglprb">Tgl. PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="tglprb" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noprb">No. PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="noprb" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="supplier" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemeriksa00">Pemeriksa 00 <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="pemeriksa00" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkirim">Tgl. Kirim</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="tglkirim" name="tglkirim" class="form-control" tabindex="-1" data-inputmask="'mask': 'd-m-y'" value="{{Carbon\Carbon::now()->format('d-m-Y')}}">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtykoli">Qty. Koli</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="qtykoli" name="qtykoli" class="form-control" tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nokoli">No. Koli</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="nokoli" name="nokoli" class="form-control" tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="returidKirim" name="returidKirim" class="form-control" tabindex="-1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Update Tanggal Kirim --}}
    @endcan

    {{-- BEGIN Modal Kewenangan --}}
    <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
                </div>
                <form id="formKewenangan" action="{{route('returpembelian.kewenangan')}}" class="form-horizontal" method="post">
                    <input type="hidden" id="returidHapus" value="">
                    <input type="hidden" id="tipe" value="">
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
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Proses</button>
          </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Kewenangan --}}

    @can('returpembelian.detail.koreksi')
    {{-- BEGIN Modal Koreksi --}}
    <div class="modal fade" id="modalKoreksi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Koreksi</h4>
                </div>
                <form id="formKoreksi" class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksirecordowner">Transaksi untuk</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksirecordowner" class="form-control" value="" tabindex="-1" disabled>
                                        <input type="hidden" id="koreksirecordownerid" class="form-control" value="" tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksitglprb">Tgl. PRB</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksitglprb" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksinoprb">No. PRB</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksinoprb" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksisupplier">Supplier <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksisupplier" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksipemeriksa00">Pemeriksa 00 <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksipemeriksa00" class="form-control" disabled>
                                        <input type="hidden" id="koreksipemeriksa00id" class="form-control" tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksitglkirim">Tgl. Kirim</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksitglkirim" name="tglkirim" class="form-control" tabindex="-1" data-inputmask="'mask': 'd-m-y'" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksiqtykoli">Qty. Koli</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksiqtykoli" name="qtykoli" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="koreksinokoli">No. Koli</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <input type="text" id="koreksinokoli" name="nokoli" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksihistoris">Historis</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="checkbox">
                                            <input type="checkbox" class="flat" id="koreksihistoris" checked name="historis" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksibarang">Barang <span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" id="koreksibarang" class="form-control" placeholder="Barang" autocomplete="off" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksisatuan">Satuan</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksisatuan" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksiqtyprb">Qty PRB</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="number" id="koreksiqtyprb" class="form-control" disabled>
                                        <input type="hidden" id="koreksiqtyprb_tmp" class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksidtnokoli">No. Koli</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="number" id="koreksidtnokoli" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksikategoriprbid">Kategori PRB</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" id="koreksikategoriprb" class="form-control" placeholder="Kategori PRB" autocomplete="off" disabled>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="koreksiketeranganprb">Ket. PRB</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" id="koreksiketeranganprb" class="form-control" tabindex="-1" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr/>
                                <h4 id="textNotifikasiKoreksi">ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN QTY. PRB = 0. NILAI KOREKSI DICATAT PADA PENGAJUAN RETUR BELI YANG BARU. ANDA YAKIN?</h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="koreksiidrpbd" name="koreksiidrpbd" class="form-control" tabindex="-1">
                        <button type="button" id="btnCancelKoreksi" class="btn btn-default" data-dismiss="modal">Tidak</button>
                        <button type="button" id="btnSubmitNotifikasiKoreksi" class="btn btn-primary">Ya</button>
                        <button type="submit" id="btnSubmitKoreksi" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Koreksi --}}
    @endcan

    {{-- BEGIN Modal Data Retur Pembelian --}}
    <div class="modal fade" id="modalDataReturPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Retur Pembelian</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="returData" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyOrderDetailData">
                                    </tbody>
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
    {{-- END Modal Data Retur Pembelian --}}

    {{-- BEGIN Modal Data Retur Pembelian Detail --}}
    <div class="modal fade" id="modalDataDetailReturPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Retur Pembelian Detail</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="returDataDetail" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyOrderDetailData">
                                    </tbody>
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
    {{-- END Modal Data Retur Pembelian Detail --}}

@endsection

@push('scripts')
<script type="text/javascript">
    var table, table2, tabledataorder, tabledatadetailorder, table_focus, tipe_edit,table_index,table2_index,fokus;
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var last_index    = '';
    var custom_search = [
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
    ];
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var tipe          = ['Find','Filter'];
    var column_index  = 0;
    var barang_text_state    = 'hide';
    var barang_custom_search = [
        { text : '', filter : '=' },
        { text : '', filter : '=' },
    ];

    {{-- @include('lookupbarang') --}}
    // Run Lookup
    lookupstaff();
    lookupbarang();
    lookupnpbd();
    lookupkategoriretur();

    $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_flat-green',
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
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

        $(document).ajaxComplete(function() {
            $('input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_flat-green',
            }).on('ifClicked', function(flat) {
                $(this).trigger("onclick");
            });
        });

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("returpembelian.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    // d.length        = 50;
                    d.tglmulai      = $('#tglmulai').val();
                    d.tglselesai    = $('#tglselesai').val();
                    d.tipe_edit     = tipe_edit;
                    // d.order      = [{ column : "0", dir : "desc" }];
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
            columns: [
                { "data": "action"       , "orderable": false},
                { "data": "tglprb"       , "className": "menufilter numberfilter"},
                { "data": "noprb"        , "className": "menufilter textfilter"},
                { "data": "namasupplier" , "className": "menufilter textfilter"},
                { "data": "tglkirim"     , "className": "menufilter numberfilter"},
                { "data": "tglnrj11"     , "className": "menufilter numberfilter"},
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
            order  : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                if(data.koreksirpbparentid){
                    //birumuda
                    $(row).addClass('blue');
                }else if(data.newchildid){
                    //abu2muda
                    $(row).addClass('grey');
                }
            },
            columns: [
                {"data": "action"     , "orderable": false},
                {"data": "namabarang" , "className": "menufilter textfilter"},
                {"data": "satuan"     , "className": "menufilter textfilter"},
                {"data": "qtyprb"     , "className": "menufilter numberfilter text-right"},
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            // table_index = indexes;
            // fokus       = 'header';
            var rowData = table.rows( indexes ).data().toArray();
            // console.log(rowData[0].id);
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("returpembelian.detail.data")}}',
                success: function(data){
                    table2.clear();
                    table2.rows.add(data.node);
                    table2.draw();
                },
                error: function(data){
                    console.log(data);
                }
            });
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

        $(document.body).on("keydown", function(e){
            if(e.keyCode == 13){
                if(context_menu_number_state == 'show'){
                    $(".context-menu-list.numberfilter").contextMenu("hide");
                    table.ajax.reload(null, false);
                }else if(context_menu_text_state == 'show'){
                    $(".context-menu-list.textfilter").contextMenu("hide");
                    table.ajax.reload(null, false);
                }
            }
        });

        $(".tgl").inputmask();
        $('.tgl').change(function(){
            table.ajax.reload(null, false);
        });

        tabledataorder       = $('#returData').DataTable({ dom : 'lrtp', paging : false,});
        tabledatadetailorder = $('#returDataDetail').DataTable({ dom : 'lrtp', paging: false,});

        $('#table1 tbody').on('dblclick', 'tr', function(){
            var data = table.row(this).data();
            tabledataorder.clear();
            tabledataorder.rows.add([
                {'0':'1.', '1':'Tgl. PRB', '2':data.tglprb},
                {'0':'2.', '1':'No. PRB', '2':data.noprb},
                {'0':'3.', '1':'Supplier', '2':data.namasupplier},
                {'0':'4.', '1':'Tgl. Kirim', '2':data.tglkirim},
                {'0':'5.', '1':'Staff Pemeriksa00', '2':data.namastaff},
                {'0':'6.', '1':'Ekspedisi', '2':'?'},
                {'0':'7.', '1':'Qty. Koli', '2':data.qtykoli},
                {'0':'8.', '1':'No. Koli', '2':data.nokoli},
                {'0':'9.', '1':'Keterangan', '2':data.keterangan},
                {'0':'10.', '1':'Tgl. NRJ11', '2':data.tglnrj11},
                {'0':'11.', '1':'No. NRJ11', '2':data.nonrj11},
                {'0':'12.', '1':'Pemeriksa 11', '2':data.staffpemeriksa11},
                {'0':'13.', '1':'OH PS Depo', '2':data.ohpsdepo},
                {'0':'14.', '1':'Last Updated By', '2':data.lastupdatedby},
                {'0':'15.', '1':'Last Updated On', '2':data.lastupdatedon}
            ]);
            tabledataorder.draw();
            $('#modalDataReturPembelian').modal('show');
        });

        $('#table2 tbody').on('dblclick', 'tr', function(){
            var data = table2.row(this).data();
            $.ajax({
                type: 'POST',
                url: '{{route("returpembelian.detail.detail")}}',
                data: {id: data[4]},
                dataType: "json",
                success: function(data){
                    tabledatadetailorder.clear();
                    tabledatadetailorder.rows.add([
                        {'0':'1.', '1':'Historis Pembelian', '2':(data.historispembelian)?'Ya':'Tidak'},
                        {'0':'2.', '1':'Barang', '2':data.barang},
                        {'0':'3.', '1':'Qty PRB', '2':data.qtyprb},
                        {'0':'4.', '1':'Qty NRJ', '2':data.qtynrj},
                        {'0':'5.', '1':'Qty RQ 11', '2':data.qtyrq11},
                        {'0':'6.', '1':'Hrg Brutto NRJ', '2':data.hargabruttonrj},
                        {'0':'7.', '1':'Disc 1', '2':data.disc1},
                        {'0':'8.', '1':'Hrg Setelah Disc 1', '2':data.hrgdisc1},
                        {'0':'9.', '1':'Disc 2', '2':data.disc2},
                        {'0':'10.', '1':'Hrg Setelah Disc 2', '2':data.hrgdisc2},
                        {'0':'11.', '1':'PPN', '2':data.ppn},
                        {'0':'12.', '1':'Kategori PRB', '2':data.kategoriprb},
                        {'0':'13.', '1':'Keterangan', '2':data.keteranganprb},
                        {'0':'14.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'15.', '1':'Last Updated On', '2':data.lastupdatedon},
                    ]);
                    tabledatadetailorder.draw();
                    $('#modalDataDetailReturPembelian').modal('show');
                },
            });
        });

        $('#modalDetail').on('shown.bs.modal', function () {
            $('#barang').focus();
        });
        $('#modalUpdateKirim').on('shown.bs.modal', function () {
            $('#tglkirim').focus();
        });
        $('#modalKewenangan').on('shown.bs.modal', function () {
            $('#uxserKewenangan').focus();
        });

        /* BEGIN - TAMBAH DETAIL */
        $('#formDetail').on('submit', function(e){
            e.preventDefault();

            @cannot('returpembelian.detail.tambah')
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            if(parseInt($('#qtyprb').val()) > parseInt($('#maxqty').val())) {
                swal("Ups!", "Tidak bisa simpan record. Nilai Qty. PRB lebih besar dari sisa Qty. terima di pembelian. Hubungi Manager anda atau isi nilai Qty. PRB kekurangannya di nota record baru.", "error");
                return false;
            }

            tipe_edit = true;
            $.ajax({
                type    : 'POST',
                url     : '{{route('returpembelian.detail.tambah')}}',
                data    : $('#formDetail').serialize(),
                dataType: "json",
                success : function(data){
                    console.log(data.node);
                    if(data.success == true) {
                        swal({
                            title: "Sukses!",
                            text : "Tambah Retur Detail Berhasil.",
                            type : "success",
                            html : true
                        },function(){
                            table.ajax.reload(null, true);
                            tipe_edit = null;
                            setTimeout(function(){
                              table.row('#'+table_index).select();
                              table.row('#'+table_index).scrollTo(false);
                            },1000);
                        });
                    }else{
                        swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });

            $('#returid').val('');
            $('#barang').val('');
            $('#barangid').val('');
            $('#satuan').val('');
            $('#qtyprb').val('');
            $('#dtnokoli').val('');
            $('#kategoriprb').val('');
            $('#kategoriprbid').val('');
            $('#keteranganprb').val('');

            $('#npbdid').val('');
            $('#notapembelianid').val('');
            $('#stockid').val('');
            $('#qtynota').val('');
            $('#qtyterima').val('');
            $('#hrgsatuanbrutto').val('');
            $('#disc1').val('');
            $('#disc2').val('');
            $('#ppn').val('');
            $('#hrgsatuannetto').val('');
            $('#qtyretur').val('');
            $('#maxqty').val('');

            $('#modalDetailRPB').modal('hide');
            return false;
            @endcannot
        });
        /* END - TAMBAH DETAIL */

        /* BEGIN - UPDATE TANGGAL KIRIM */
        $('#formUpdateKirim').on('submit', function(e){
            e.preventDefault();
            @cannot('returpembelian.updatetglkirim')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            tipe_edit = true;
            $.ajax({
                type: 'POST',
                data: {
                    returid : $('#returidKirim').val(),
                    tglkirim: $('#tglkirim').val(),
                    qtykoli : $('#qtykoli').val(),
                    nokoli  : $('#nokoli').val(),
                },
                dataType: "json",
                url     : '{{route('returpembelian.updatetglkirim')}}',
                success : function(data){
                    if(data.success == true) {
                        swal({
                            title: "Sukses!",
                            text : "Update Tanggal Kirim Berhasil.",
                            type : "success",
                            html : true
                        },function(){
                            table.ajax.reload(null, true);
                            tipe_edit = null;
                            setTimeout(function(){
                              table.row('#'+table_index).select();
                              table.row('#'+table_index).scrollTo(false);
                            },1000);
                        });
                    }else{
                        swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });

            $('#recordownerid').val('');
            $('#tglprb').val('');
            $('#noprb').val('');
            $('#supplier').val('');
            $('#pemeriksa00').val(''); 
            $('#tglkirim').val('{{Carbon\Carbon::now()->format('d-m-Y')}}');
            $('#qtykoli').val('');
            $('#nokoli').val('');
            $('#returidKirim').val('');

            $('#modalUpdateKirim').modal('hide');
            return false;
            @endcannot
        });
        /* END - UPDATE TANGGAL KIRIM */

        /* BEGIN - HAPUS RETUR */
        $('#formKewenangan').on('submit', function(e){
            e.preventDefault();

            $.ajax({
                type: 'POST',
                    url: '{{route("returpembelian.kewenangan")}}',
                    data: {
                        username  : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
                        password  : $('#modalKewenangan #pxassKewenangan').val(),
                        returid   : $('#modalKewenangan #returidHapus').val(),
                        tipe      : $('#modalKewenangan #tipe').val(),
                        permission: 'returpembelian.hapus',
                    },
                    dataType: "json",
                success: function(data){
                    $('#modalKewenangan #uxserKewenangan').val('').change();
                    $('#modalKewenangan #pxassKewenangan').val('').change();

                    if(data.success){
                        swal('Sukses!', 'Data berhasil dihapus','success');
                        window.location.reload();
                    }else{
                        swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });

            $('#modalKewenangan').modal('hide');
            return false;
        });
        /* END - HAPUS RETUR */

        /* BEGIN - KOREKSI */
        $('#btnSubmitNotifikasiKoreksi').click(function(){
            $('#modalKoreksi').modal('toggle');
            active_field(true);
            setTimeout(function(){
                var date = '{{Carbon\Carbon::now()->format("d-m-Y")}}';
                $('#koreksitglprb').val(date);
                $('#koreksitglkirim').val(date);
                $('#koreksinoprb').val('KOREKSI00');
                $('#koreksiketeranganprb').val('KOREKSI00');
                $('#koreksiqtyprb').val(0);
                $('#koreksipemeriksa00').val('').focus();

                $('#modalKoreksi').modal('toggle');
            }, 750);

        });

        $('#formKoreksi').on('submit', function(e){
            e.preventDefault();
            @cannot('returpembelian.detail.koreksi')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else

            if(!$('#koreksipemeriksa00').val()){
                swal("Ups!", "Tidak bisa simpan record. Nama pemeriksa 00 tidak boleh kosong. Hubungi Manager anda.", "error");
            }else if($('#koreksiqtyprb').val() <= 0){
                swal("Ups!", "Tidak bisa simpan record. Qty. Koreksi PRB tidak boleh kosong. Hubungi Manager anda.", "error");
            }else if($('#koreksiqtyprb').val() >= $('#koreksiqtyprb_tmp').val()){
                swal("Ups!", "Tidak bisa simpan record. Qty. Koreksi PRB tidak boleh melebihi Qty. PRB sebelumnya. Hubungi Manager anda.", "error");
            }else{

                tipe_edit = 'koreksi';

                $.ajax({
                    type: 'POST',
                    url: '{{route("returpembelian.detail.koreksi")}}',
                    data: {
                        koreksiidrpbd       : $('#koreksiidrpbd').val(),
                        koreksitglprb       : $('#koreksitglprb').val(),
                        koreksitglkirim     : $('#koreksitglkirim').val(),
                        koreksinoprb        : $('#koreksinoprb').val(),
                        koreksipemeriksa00id: $('#koreksipemeriksa00id').val(),
                        koreksiqtyprb       : $('#koreksiqtyprb').val(),
                        koreksiketeranganprb: $('#koreksiketeranganprb').val(),
                    },
                    dataType: "json",
                    success: function(data){
                        console.log(data);
                        $('#modalKoreksi').modal('hide');
                        $('#modalKoreksi').find('input').val('');

                        table.ajax.reload(null,true);
                        tipe_edit = null;
                        setTimeout(function(){
                          table.row('#'+table_index).select();
                          table.row('#'+table_index).scrollTo(false);
                        },1000);
                        swal("Sukses!", "Koreksi Retur Pembelian Detail berhasil disimpan.", "success");
                        // window.location.reload();
                    },
                });
            }
            return false;
            @endcannot
        });
        /* END - KOREKSI */

        /* BEGIN - SYNC11 */
        $('#btnSync11').on('click', function(){
            @cannot('returpembelian.sync11')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else

            sync = true;
            var count = 0;
            var data = [];

            $('#table1').find('input[type="checkbox"]:checked:not(:disabled)').each(function () {
                count += 1;
                data.push($(this).val());
            });

            if (count > 0) {
                swal({
                    title: "Konfirmasi",
                    text: "Yakin akan melakukan sync?",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function () {
                    $.ajaxSetup({
                        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
                    });

                    $.ajax({
                        type: 'POST',
                        url: '{{route("returpembelian.sync11")}}',
                        data: {data:data},
                        success: function(data){
                            console.log(data);
                            table.ajax.reload(null, false);
                            sync = '';
                            swal('Berhasil!', 'Data berhasil di-sync','success');
                        }
                    });
                });
            }else {
                swal('Ups!', 'Tidak ada data yang dipilih','error');
            }
            @endcannot
        });
        /* END - SYNC11 */
    });

    function tambahrpbd(e) {
        @cannot('returpembelian.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var data = table.row($(e).parents('tr')).data();
        if(data.tambahrpbd == 'tambah') {
            $('#modalDetailRPB #returid').val(data.id);
            $('#modalDetailRPB').modal('show');
        }else{
            swal('Ups!', data.tambahrpbd,'error');
        }
        @endcannot
    }

    function updatekirim(e) {
        @cannot('returpembelian.updatetglkirim')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        var data = table.row($(e).parents('tr')).data();
        if(data.updatekirim == 'update') {
            $('#modalUpdateKirim #returidKirim').val(data.id);
            $('#modalUpdateKirim #recordownerid').val(data.namasubcabang);
            $('#modalUpdateKirim #tglprb').val(data.tglprb);
            $('#modalUpdateKirim #noprb').val(data.noprb);
            $('#modalUpdateKirim #supplier').val(data.namasupplier);
            $('#modalUpdateKirim #pemeriksa00').val(data.namastaff);

            $('#modalUpdateKirim').modal('show');
        }else{
            swal('Ups!', data.updatekirim,'error');
        }
        @endcannot
    }

    function hapusretur(e) {
        if($(e).parents('table').attr('id') == 'table2') {
            var data = table2.row($(e).parents('tr')).data();
        }else{
            var data = table.row($(e).parents('tr')).data();
        }

        if(data.hapusretur == 'auth'){
            $('#modalKewenangan #returidHapus').val(data.id);
            $('#modalKewenangan #tipe').val($(e).data('tipe'));
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.hapusretur,'error');
        }
    }

    function koreksi(e) {
        @cannot('returpembelian.detail.koreksi')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else

        if($(e).parents('table').attr('id') == 'table2') {
            var data = table2.row($(e).parents('tr')).data();
        }else{
            var data = table.row($(e).parents('tr')).data();
        }

        if(data.koreksi == 'koreksi'){
            var iddetail = data.id;

            $.ajax({
                type: 'POST',
                url: '{{route("returpembelian.detail.detail")}}',
                data: {id: iddetail},
                success: function(data){
                    $('#koreksiidrpbd').val(iddetail);

                    $('#koreksirecordowner').val(data.recordowner);
                    $('#koreksirecordownerid').val(data.recordownerid);
                    $('#koreksitglprb').val(data.tglprb);
                    $('#koreksinoprb').val(data.noprb);
                    $('#koreksisupplier').val(data.supplier);
                    $('#koreksipemeriksa00').val(data.pemeriksa00);
                    $('#koreksitglkirim').val(data.tglkirim);
                    $('#koreksiqtykoli').val(data.qtykoli);
                    $('#koreksinokoli').val(data.nokoli);
                    $('#koreksihistoris').attr('checked',data.koreksihistoris).change();
                    $('#koreksibarang').val(data.barang);
                    $('#koreksisatuan').val(data.satuan);
                    $('#koreksiqtyprb').val(data.qtyprb);
                    $('#koreksiqtyprb_tmp').val(data.qtyprb);
                    $('#koreksidtnokoli').val(data.dtnokoli);
                    $('#koreksikategoriprb').val(data.kategoriprb);
                    $('#koreksiketeranganprb').val(data.keteranganprb);

                    active_field(false);
                    $('#modalKoreksi').modal('show');
                },
                error: function(data){
                    console.log(data);
                }
            });

        }else{
            swal('Ups!', data.koreksi,'error');
        }
        @endcannot
    }

    function active_field(e){
        if(e){
            $('#koreksipemeriksa00').removeAttr("disabled").attr("tabindex",1).focus();
            $('#koreksiqtyprb').removeAttr("disabled").attr("tabindex",2);
            $('#btnSubmitNotifikasiKoreksi').hide();
            $('#btnSubmitKoreksi').show();

            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI DIBUAT SEBAGAI KOREKSI ATAS PENGAJUAN RETUR BELI SEBELUMNYA YANG SUDAH ANDA KOREKSI/BATALKAN MENJADI 0.');
        }else{
            $('#koreksipemeriksa00').attr("disabled","disabled").attr("tabindex",-1);
            $('#koreksiqtyprb').attr("disabled","disabled").attr("tabindex",-1);
            $('#btnSubmitNotifikasiKoreksi').show();
            $('#btnSubmitKoreksi').hide();

            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN QTY. PRB = 0. NILAI KOREKSI DICATAT PADA PENGAJUAN RETUR BELI YANG BARU. ANDA YAKIN?');
        }
    }

</script>
@endpush
