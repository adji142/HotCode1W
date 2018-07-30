@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('returpenjualan.index') }}">Retur Penjualan</a></li>
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
                    <h2>Daftar Retur Penjualan</h2>
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
                                    <label for="tglmulai" style="margin-right: 10px;">Tgl MPR / NRJ </label>
                                    <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">

                                    <label for="tglselesai">-</label>
                                    <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            @can('returpenjualan.tambah')
                            <a href="{{route('returpenjualan.tambah')}}" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah Retur Penjualan - Ins</a>
                            @endcan
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl. MPR</th>
                                <th>No. MPR</th>
                                <th>Tgl. NRJ</th>
                                <th>No. NRJ</th>
                                <th>Toko</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. MPR</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. MPR</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Tgl. NRJ</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> No. NRJ</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Toko</a>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty. MPR</th>
                                <th>Qty. NRJ</th>
                                <th>Hrg. NRJ</th>
                                <th>Ttl. Hrg. NRJ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nama Barang</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Satuan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. MPR</a> |
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Qty. NRJ</a> |
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Hrg. NRJ</a> |
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Ttl. Hrg. NRJ</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BEGIN Modal Data--}}
    <div class="modal fade" id="modalDataRetur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Retur Penjualan</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="dataRetur" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDataRetur">
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
    {{-- END Modal Data--}}

    {{-- BEGIN Modal Tambah Detail RPJ --}}
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
                                    <input type="checkbox" class="flat" id="historis" checked name="historis" value="1" readonly tabindex="-1">
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
                                    <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
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
                                <input type="number" id="qtysppb" name="qtysppb" class="form-control" readonly tabindex="-1">
                            </div>
                            <p class="help-block muted">Jumlah yg diterima Penjualan</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty NRJ</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtynrj" name="qtynrj" class="form-control" readonly tabindex="-1">
                            </div>
                            <p class="help-block muted">Jumlah yg diterima Stok</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuanbrutto">Harga Satuan Brutto <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgsatuanbrutto" name="hrgsatuanbrutto" class="form-control hitungnetto" placeholder="Harga Brutto" required="required" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc1">Disc 1</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="disc1" name="disc1" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 1" required="required" readonly tabindex="-1">
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgdisc1">Harga Setelah Disc 1</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgdisc1" class="form-control" readonly tabindex="-1">
                                </div>
                            </div>
                            <p class="help-block muted">= Harga Brutto - (Disc 1 x Harga Brutto)</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc2">Disc 2</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="disc2" name="disc2" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 2" required="required" readonly tabindex="-1">
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgdisc2">Harga Setelah Disc 2</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgdisc2" class="form-control" readonly tabindex="-1">
                                </div>
                            </div>
                            <p class="help-block muted">= Harga Setelah Disc 1 - (Disc 2 x Harga Setelah Disc 2)</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ppn">PPN</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <input type="number" id="ppn" name="ppn" class="form-control hitungnetto" step="0.01" value="{{$ppn}}" placeholder="PPN" required="required" readonly tabindex="-1">
                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuannetto">Harga Satuan Netto</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                                    <input type="number" id="hrgsatuannetto" name="hrgsatuannetto" class="form-control" readonly tabindex="-1">
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
                        <input type="hidden" id="returid" name="returid" value="" readonly tabindex="-1">
                        <input type="hidden" id="tokoid" name="tokoid" value="" readonly tabindex="-1">
                        <input type="hidden" id="npjdid" name="npjdid" value="" readonly tabindex="-1">
                        <input type="hidden" id="notapenjualanid" name="notapenjualanid" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtynota" name="qtynota" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyterima" name="qtyterima" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyretur" name="qtyretur" value="" readonly tabindex="-1">
                        <input type="hidden" id="maxqty" name="maxqty" value="" readonly tabindex="-1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Tambah Detail RPJ --}}

    <div class="modal fade" id="modalupdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Retur Penjualan Update SPPB</h4>
                </div>
                <form class="form-horizontal" method="post" id="formupdate">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tglmpr">Tgl. MPR <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="tglmpr" name="tglmpr" class="tgl form-control" value="{{date('d-m-Y')}}" data-inputmask="'mask': 'd-m-y'" required readonly tabindex="-1">
                            </div>
                            <p class="help-block muted col-md-2 col-sm-2 col-xs-12">Tanggal Toko Request Retur</p>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nompr">No. MPR <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="nompr" name="nompr" class="form-control" placeholder="No. MPR" required readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tglsppb">Tgl. SPPB</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                {{-- <input type="text" id="tglsppb" name="tglsppb" class="tgl form-control" value="{{date('d-m-Y')}}" readonly tabindex="-1"> --}}
                                <input type="text" id="tglsppb" name="tglsppb" class="tgl form-control" readonly tabindex="-1">
                            </div>
                            <p class="help-block muted col-md-2 col-sm-2 col-xs-12">Tanggal Proses Adm. Retur</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tglnjr">Tgl. NRJ</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="tglnjr" name="tglnjr" class="tgl form-control" readonly tabindex="-1">
                            </div>
                            <p class="help-block muted col-md-3 col-sm-3 col-xs-12">Tanggal Pengakuan Omset / Stok Masuk</p>
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="nonrj">No. NRJ</label>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input type="text" id="nonrj" name="nonrj" class="form-control" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="toko">Toko <span class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="toko" class="form-control" placeholder="Toko" autocomplete="off" required readonly tabindex="-1">
                                    <input type="hidden" id="tokoid" name="tokoid">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="alamat">Alamat</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" id="alamat" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="kota">Kota</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="kota" class="form-control" readonly tabindex="-1">
                            </div>
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="daerah">Kecamatan</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="kecamatan" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="wilid">WILID</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="wilid" class="form-control" readonly tabindex="-1">
                            </div>
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="idtoko">Toko ID</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="tokoid" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="expedisi" class="form-control" placeholder="Expedisi" autocomplete="off" required>
                                    <input type="hidden" id="expedisiid" name="expedisiid">
                                </div>
                            </div>
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="karyawanexpedisi">Staff Pengambil Barang <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="karyawanexpedisi" class="form-control" placeholder="Staff Pengambil Barang" autocomplete="off" required>
                                    <input type="hidden" id="karyawanexpedisiid" name="karyawanidexpedisi">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="karyawanstock">Staff Stok</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="karyawanstock" class="form-control" placeholder="Staff Stok" autocomplete="off" readonly tabindex="-1">
                                    <input type="hidden" id="karyawanstockid" name="karyawanidstock">
                                </div>
                            </div>
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="karyawanpenjualan">Staff Penjualan <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="karyawanpenjualan" class="form-control" placeholder="Staff Penjualan" autocomplete="off" required>
                                    <input type="hidden" id="karyawanpenjualanid" name="karyawanidpenjualan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="tableupdate" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="30%">Barang</th>
                                            <th>Sat.</th>
                                            <th>Qty. MPR</th>
                                            <th>Qty. SPPB</th>
                                            <th>Qty. NRJ</th>
                                            <th>Hrg. Sat. Netto</th>
                                            <th>Hrg. Total MPR</th>
                                            <th>Hrg. Total SPPB</th>
                                            <th>Hrg. Total NRJ</th>
                                            <th>Kategori RJ</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="updateid" name="updateid" class="form-control" tabindex="-1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btnsppb" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- BEGIN Modal Kewenangan --}}
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
                    <input type="hidden" id="returid" name="returid">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="uxserKewenangan" name="uxserKewenangan" class="form-control" placeholder="Username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="password" id="pxassKewenangan" name="pxassKewenangan" class="form-control" placeholder="Password">
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

    {{-- BEGIN Modal Koreksi --}}
    <div class="modal fade" id="modalKoreksi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Koreksi</h4>
                </div>
                <form id="formKoreksi" class="form-horizontal" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglmpr">Tgl. MPR <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksitglmpr" name="tglmpr" class="tgl form-control" data-inputmask="'mask': 'd-m-y'" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Tanggal Toko Request Retur</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nompr">No. MPR <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksinompr" name="nompr" class="form-control" placeholder="No. MPR" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglsppb">Tgl. SPPB</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksitglsppb" name="tglsppb" class="tgl form-control" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Tanggal Proses Adm. Retur</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglnjr">Tgl. NRJ</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksitglnjr" name="tglnjr" class="tgl form-control" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Tanggal Pengakuan Omset / Stok Masuk</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nonrj">No. NRJ</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="koreksinonrj" name="nonrj" class="form-control" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="toko">Toko <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksitoko" class="form-control" placeholder="Toko" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksitokoid" name="tokoid">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksiexpedisi" class="form-control" placeholder="Expedisi" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksiexpedisiid" name="expedisiid">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="karyawanexpedisi">Staff Pengambil Barang <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksikaryawanexpedisi" class="form-control" placeholder="Staff Pengambil Barang" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksikaryawanexpedisiid" name="karyawanidexpedisi">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="karyawanpenjualan">Staff Penjualan <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksikaryawanpenjualan" class="form-control" placeholder="Staff Penjualan" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksikaryawanpenjualanid" name="karyawanidpenjualan">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="karyawanstock">Staff Stok</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksikaryawanstock" class="form-control" placeholder="Staff Stok" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksikaryawanstockid" name="karyawanidstock">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="historis">Dari Riwayat NPJ</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="checkbox">
                                            <input type="checkbox" class="flat" id="koreksihistoris" checked name="historis" value="1" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksibarang" class="form-control" placeholder="Barang" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksibarangid" name="barangid">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
                                    <div class="form-group row">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <input type="text" id="koreksikodebarang" class="form-control" readonly tabindex="-1">
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <input type="text" id="koreksisatuan" class="form-control" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtympr">Qty MPR <span class="required">*</span></label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="number" id="koreksiqtympr" name="qtympr" class="form-control hitungnetto" placeholder="Qty MPR" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Jml. yg diajukan retur sesuai memo MPR</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty SPPB</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="number" id="koreksiqtysppb" name="qtysppb" class="form-control" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Jml. yg diterima Penjualan</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty NRJ</label>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <input type="number" id="koreksiqtynrj" name="qtynrj" class="form-control" readonly tabindex="-1">
                                        <input type="hidden" id="koreksiqtynrj_tmp" name="qtynrj_tmp" class="form-control" readonly tabindex="-1">
                                    </div>
                                    <p class="help-block muted">Jml. yg diterima Stok</p>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategoriidrpj">Kategori RJ</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input type="text" id="koreksikategorirpj" name="kategorirpj" class="form-control" placeholder="Kategori RJ" autocomplete="off" readonly tabindex="-1">
                                            <input type="hidden" id="koreksikategoriidrpj" name="kategoriidrpj">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatan">Catatan</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <textarea id="koreksicatatan" name="catatan" rows="3" class="form-control" placeholder="Catatan" readonly tabindex="-1"></textarea>
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
                        <input type="hidden" id="koreksiidrpjd" name="koreksiidrpjd" class="form-control" tabindex="-1">
                        <button type="button" id="btnCancelKoreksi" class="btn btn-default" data-dismiss="modal">Tidak</button>
                        <button type="button" id="btnSubmitNotifikasiKoreksi" class="btn btn-primary">Ya</button>
                        <button type="submit" id="btnSubmitKoreksi" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END Modal Koreksi --}}

    {{-- <div id="pdfDiv" class="hidden"></div> --}}
@endsection

@push('scripts')
<script type="text/javascript">
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
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
    var last_index, tipestaff, tipekategorirj, dataupdate, tipedataupdate, tipe_edit;
    var table, table2, tabledataretur, tableupdate, table_index, table2_index,fokus;

    lookupsubcabang();
    lookuptoko();
    lookupexpedisi();
    lookupbarang();
    lookupstaff();
    lookupnpjd();
    lookupkategoriretur();

    // Initialize
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

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("returpenjualan.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    d.tglmulai      = $('#tglmulai').val();
                    d.tglselesai    = $('#tglselesai').val();
                    d.tipe_edit     = tipe_edit;
                },
            },
            // order   : [[ 1, 'asc' ]],
            order   : [[ 5, 'desc' ]],
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
                {
                    "orderable": false,
                    "data"     : "action",
                    render     : function(data, type, row) {
                        var json_row = JSON.stringify(row);
                        var tombol = "";
                        // tombol += "<div class='checkbox'><label><input type='checkbox' class='flat' name='kirim[]'></label></div>";
                        if(row.tambahrpjd != 'disabled') {
                        tombol += "<div class='btn btn-xs btn-success skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah RPJD - F1' onclick='tambahrpjd(this,"+json_row+")'><i class='fa fa-plus'></i></div>";
                        }else{
                        tombol += "<button disabled class='btn btn-xs btn-success skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah RPJD - F1'><i class='fa fa-plus'></i></button>";
                        }
                        if(row.hapusretur != 'disabled') {
                        tombol += "<div class='btn btn-xs btn-danger skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus RPJ - Del' onclick='hapusretur(this,"+json_row+")' data-tipe='header'><i class='fa fa-trash'></i></div>";
                        }else{
                        tombol += "<button disabled class='btn btn-xs btn-danger skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus RPJ - Del'><i class='fa fa-trash'></i></button>";
                        }
                        tombol += "<div class='btn-group'>";
                        tombol += "<button data-toggle='dropdown' class='btn btn-xs btn-warning dropdown-toggle' type='button' aria-expanded='false' style='margin-bottom: 5px;margin-right: 5px;'> Action <span class='caret'></span> </button>";
                        tombol += "<ul class='dropdown-menu'>";
                        if(row.updatesppb != 'disabled') {
                        tombol += "<li><a href='#' class='skeyF2' onclick='updatesppb(this,"+json_row+")'>Update SPPB - F2</a></li>";
                        }
                        if(row.updatenrj != 'disabled') {
                        tombol += "<li><a href='#' class='skeyF3' onclick='updatenrj(this,"+json_row+")'>Update NRJ - F3</a></li>";
                        }
                        if(row.cetaksppb != 'disabled') {
                        tombol += "<li><a href='#' class='skeyF7' onclick='cetaksppb(this,"+json_row+")'>Cetak SPPB - F7</a></li>";
                        }
                        if(row.cetaknrj != 'disabled') {
                        tombol += "<li><a href='#' class='skeyF8' onclick='cetaknrj(this,"+json_row+")'>Cetak NRJ - F8</a></li>";
                        }
                        tombol += "</ul>";
                        tombol += "</div>";
                        return tombol;
                    }
                },
                {
                    "data"     : "tglmpr",
                    "className": "menufilter numberfilter"
                },
                {
                    "data"     : "nompr",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "tglnotaretur",
                    "className": "menufilter numberfilter"
                },
                {
                    "data"     : "nonotaretur",
                    "className": "menufilter textfilter",
                },
                {
                    "data"     : "namatoko",
                    "className": "menufilter textfilter"
                },
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
                // console.log(data);
                if(data.newchildid){
                    //birumuda
                    $(row).addClass('blue');
                }else if(data.sync11){
                    //abu2muda
                    $(row).addClass('grey');
                }
            },
            columns: [
                { "data": "action", "orderable": false },
                { "data": "namabarang" },
                { "data": "satuan" },
                { "data": "qtympr", "className": "text-right" },
                { "data": "qtynrj", "className": "text-right" },
                { "data": "hrgsatnetto", "className": "text-right" },
                { "data": "hrgsatnettonrj", "className": "text-right" },
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            var rowData = table.rows( indexes ).data().toArray();
            $.ajax({
                headers : { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
                type    : 'POST',
                data    : {id: rowData[0].id},
                dataType: "json",
                url     : '{{route("returpenjualandetail.data")}}',
                success : function(data){
                    table2.clear();
                    $.each(data.node, function (i,node) {
                        console.log(node);
                        var json_row = JSON.stringify({'id':node.id,'koreksi':node.koreksi,'hapusretur':node.hapusretur});
                        var tombol   = "";
                        if(node.koreksi != 'disabled'){
                            tombol += "<div class='btn btn-xs btn-info skeyF6' data-toggle='tooltip' data-placement='bottom' title='Koreksi - F6' onclick='koreksi(this,"+json_row+")'><i class='fa fa-edit'></i></div>";
                        }
                        if(node.hapusretur != 'disabled'){
                            tombol += "<div class='btn btn-xs btn-danger skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus RPJ - Del' onclick='hapusretur(this,"+json_row+")' data-tipe='detail'><i class='fa fa-trash'></i></div>"; }
                        node.action = tombol;
                        table2.row.add(node);
                    });

                    table2.draw();
                },
                error: function(data){
                    // console.log(data);
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
                            window.console && console.log('key: '+ e.keyCode);
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
                    // console.log('number');
                    // console.log(contextData);
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
                            window.console && console.log('key: '+ e.keyCode);
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
                    // console.log('text');
                    // console.log(contextData);
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

        tabledataretur = $('#dataRetur').DataTable({ dom : 'lrtp', paging : false,});

        $('#table1 tbody').on('dblclick', 'tr', function(){
            var data = table.row(this).data();
            $.ajax({
                type: 'POST',
                url: '{{route("returpenjualan.header")}}',
                data: {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success: function(data){
                    tabledataretur.clear();
                    tabledataretur.rows.add([
                        {'0':'1.', '1':'C1', '2':data.omsetsubcabangkode},
                        {'0':'2.', '1':'C2', '2':data.pengirimsubcabangkode},
                        {'0':'3.', '1':'Tgl. MPR', '2':data.tglmpr},
                        {'0':'4.', '1':'No. MPR', '2':data.nompr},
                        {'0':'5.', '1':'Tgl. SPPB', '2':data.tglsppb},
                        {'0':'6.', '1':'No. NRJ', '2':data.nonotaretur},
                        {'0':'7.', '1':'Tgl. NRJ', '2':data.tglnotaretur},
                        {'0':'8.', '1':'Toko', '2':data.namatoko},
                        {'0':'9.', '1':'Alamat', '2':data.alamattoko},
                        {'0':'10.', '1':'Kota', '2':data.kotatoko},
                        {'0':'11.', '1':'Daerah', '2':data.kecamatantoko},
                        {'0':'12.', '1':'WILID', '2':data.wilidtoko},
                        {'0':'13.', '1':'Toko ID', '2':data.idtoko},
                        {'0':'14.', '1':'Keterangan', '2':data.keterangan},
                        {'0':'15.', '1':'Tgl. Print SPPB', '2':data.tglprintsppb},
                        {'0':'16.', '1':'Print SPPB', '2':data.printsppb},
                        {'0':'17.', '1':'Tgl. Print NRJ', '2':data.tglprintnrj},
                        {'0':'18.', '1':'Print NRJ', '2':data.printnrj},
                        {'0':'19.', '1':'Expedisi', '2':data.namaexpedisi},
                        {'0':'20.', '1':'Staff Pengambil Barang', '2':data.namastaffexpedisi},
                        {'0':'21.', '1':'Staff Penjualan', '2':data.namastaffpenjualan},
                        {'0':'22.', '1':'Staff Stok', '2':data.namastaffstok},
                        {'0':'23.', '1':'Link ke Piutang', '2':((data.kartupiutangdetailid)?'Ya':'Tidak')},
                        {'0':'24.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'25.', '1':'Last Updated On', '2':data.lastupdatedon}
                    ]);
                    tabledataretur.draw();
                    $('#modalDataRetur myModalLabel').html('Data Retur Penjualan');
                    $('#modalDataRetur').modal('show');
                },
            });
        });

        $('#table2 tbody').on('dblclick', 'tr', function(){
            var data = table2.row(this).data();
            $.ajax({
                type: 'POST',
                url: '{{route("returpenjualan.detail")}}',
                data: {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success: function(data){
                    tabledataretur.clear();
                    tabledataretur.rows.add([
                        {'0':'1.', '1':'Dari Riwayat NPJ', '2':(data.notapenjualandetailid)?'Ya':'Tidak'},
                        {'0':'2.', '1':'Barang', '2':data.barang},
                        {'0':'3.', '1':'Satuan', '2':data.satuan},
                        {'0':'4.', '1':'Qty MPR', '2':data.qtympr},
                        {'0':'5.', '1':'Qty SPPB', '2':data.qtysppb},
                        {'0':'6.', '1':'Qty NRJ', '2':data.qtynrj},
                        {'0':'7.', '1':'Hrg. Sat. Bruto', '2':data.hrgsatuanbrutto},
                        {'0':'8.', '1':'Disc 1', '2':data.disc1},
                        {'0':'9.', '1':'Hrg. Setelah Disc 1', '2':data.hrgdisc1},
                        {'0':'10.', '1':'Disc 2', '2':data.disc2},
                        {'0':'11.', '1':'Hrg. Setelah Disc 2', '2':data.hrgdisc2},
                        {'0':'12.', '1':'PPN', '2':data.ppn},
                        {'0':'13.', '1':'Hrg. Sat. Netto', '2':data.hrgsatnetto},
                        {'0':'14.', '1':'Hrg. Total Netto MPR', '2':data.hrgsatnettompr},
                        {'0':'15.', '1':'Hrg. Total Netto SPPB', '2':data.hrgsatnettosppb},
                        {'0':'16.', '1':'Hrg. Total Netto NRJ', '2':data.hrgsatnettonrj},
                        {'0':'17.', '1':'Kategori RJ', '2':data.kategorirj},
                        {'0':'18.', '1':'Last Updated By', '2':data.lastupdatedby},
                        {'0':'19.', '1':'Last Updated On', '2':data.lastupdatedon},
                    ]);
                    tabledataretur.draw();
                    $('#modalDataRetur myModalLabel').html('Data Detail Retur Penjualan');
                    $('#modalDataRetur').modal('show');
                },
            });
        });

        tableupdate = $('#tableupdate').DataTable({
            dom       : 'lrtp',
            paging    : false,
            ordering  : false,
            createdRow: function( row, data, dataIndex ) {
                if(tipedataupdate == 'nrj') {
                    // $( row ).find('td:eq(4)').attr('onKeypress', "if(event.keyCode < 48 || event.keyCode > 57){return false;}");
                    $( row ).find('td:eq(4)').prop('contenteditable', true);
                }else{
                    // $( row ).find('td:eq(3)').attr('onKeypress', "if(event.keyCode < 48 || event.keyCode > 57){return false;}");
                    $( row ).find('td:eq(3)').prop('contenteditable', true);
                }
                $( row ).attr('id',tipedataupdate+data.id);
            },
            footerCallback: function ( tfoot, data, start, end, display ) {
                console.log(data);
                var total = 0;
                for (var i = 0; i < data.length; i++) {
                    total += parseInt(data[i].hrgtotal);
                }
                $(tfoot).find('td').eq(1).html(total);
            },
            columns: [
                {
                    "data" : "namabarang",
                    render : function(data, type, row) {
                        var render = "<input type='hidden' name='updateiddetail[]' value='"+row.id+"'>";
                            render += "<input type='hidden' name='updateqtympr[]' value='"+row.qtympr+"'>"; 
                            render += "<input type='hidden' name='updateqtysppb[]' value='"+row.qtysppb+"'>"; 
                            render += "<input type='hidden' name='updateqtynrj[]' value='"+row.qtynrj+"'>"; 
                            render += "<input type='hidden' name='updatehrgsatnetto[]' value='"+row.hrgsatnetto+"'>"; 
                            render += "<input type='hidden' name='updatekategoriidrpj[]' value='"+row.kategoriidrpj+"'>"; 
                            render += row.namabarang; 
                        return render;
                    }
                },
                {
                    "data" : "satuan"
                },
                {
                    "data"     : "qtympr",
                    "className": "text-right"
                },
                {
                    "data"     : "qtysppb",
                    "className": "text-right editqtysppb"
                },
                {
                    "data"     : "qtynrj",
                    "className": "text-right editqtynrj"
                },
                {
                    "data"     : "hrgsatnetto",
                    "className": "text-right",
                    render     : function(data, type, row) {
                        return parseInt(data).toLocaleString("id-ID");
                    }
                },
                {
                    "data"     : "hrgsatnettompr",
                    "className": "text-right",
                    render     : function(data, type, row) {
                        return parseInt(data).toLocaleString("id-ID");
                    }
                },
                {
                    "data"     : "hrgsatnettosppb",
                    "className": "text-right",
                    render     : function(data, type, row) {
                        return parseInt(data).toLocaleString("id-ID");
                    }
                },
                {
                    "data"     : "hrgsatnettonrj",
                    "className": "text-right",
                    render     : function(data, type, row) {
                        return parseInt(data).toLocaleString("id-ID");
                    }
                },
                {
                    "data" : "kategorirj",
                    "className": "editkategorirj",
                },
            ]
        });

        $('#modalDetail').on('shown.bs.modal', function () {
            $('#barang').focus();
        });

        // $('.tbodySelect').on('click', 'tr', function(){
        //     $('.selected').removeClass('selected');
        //     $(this).addClass("selected");
        // });

        $('#historis').on('ifUnchecked', function(event){
            $('#modalKewenangan').find('#permission').val('returpenjualan.historis');
            $('#modalKewenangan').modal('show');
        });

        $('#historis').on('ifChecked', function(event){
            $('#hrgsatuanbrutto').attr('readonly').attr('tabindex',-1);
            $('#disc1').attr('readonly').attr('tabindex',-1);
            $('#disc2').attr('readonly').attr('tabindex',-1);
            $('#ppn').attr('readonly').attr('tabindex',-1);
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

        $('#formDetail').on('submit', function(e){
            e.preventDefault();
            @cannot('returpenjualan.tambah.detail')
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            var qtnot = $('#qtynota').val();
            var qtret = $('#qtyretur').val();
            var sisa = parseFloat(qtnot) - parseFloat(qtret);
            if(parseFloat($('#qtympr').val()) > sisa){
                swal("Ups!", "Quantity mpr melebihi sisa.", "error");
                return false;
            }


            tipe_edit = true;

            if($('#returid').val() == '') {
                swal("Ups!", "Tidak bisa simpan record. Retur Penjualan belum disimpan.", "error");
                $('#modalDetail').modal('hide');
                return false;
            // }else if(($('#historis').is(':checked')) && parseInt($('#qtympr').val()) > parseInt($('#maxqty').val())) {
            //     swal("Ups!", "Tidak bisa simpan record. Nilai Qty. PRB lebih besar dari sisa Qty. terima di Penjualan. Hubungi Manager anda atau isi nilai Qty. PRB kekurangannya di nota record baru.", "error");
            //     return false;
            }else{
                console.log($('#formDetail').serialize())
                $.ajax({
                    type    : 'POST',
                    url     : '{{route('returpenjualan.tambah.detail')}}',
                    data    : $('#formDetail').serialize(),
                    dataType: "json",
                    success : function(data){
                        if (typeof data.success == 'boolean' && data.success) {
                            swal({
                                title: "Sukses!",
                                text : "Tambah Retur Detail Berhasil.",
                                type : "success",
                                html : true
                            },function(){
                                $('#modalDetail').modal('hide');
                                table.ajax.reload(null, true);
                                tipe_edit = null;
                                setTimeout(function(){
                                    table.row(0).deselect();
                                    table.row(0).select();
                                },1000);
                            });

                        } else if (typeof data.msg != 'undefined') swal("Ups!", data.msg.toString(), "warning");
                        else swal("Ups!", "Request di tolak oleh server", "error");

                        return false;
                    },
                    error: function(data){ 
                        // console.log(data);
                    }
                });
            }

            return false;
            @endcannot
        });

        $('#formKewenangan').on('submit', function(e){
            e.preventDefault();

            $('#modalKewenangan #uxserKewenangan').val($('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase());

            $.ajax({
                type: 'POST',
                url : '{{route("returpenjualan.kewenangan")}}',
                data: $('#formKewenangan').serialize(),
                dataType: "json",
                success: function(data){
                    $('#modalKewenangan #uxserKewenangan').val('').change();
                    $('#modalKewenangan #pxassKewenangan').val('').change();

                    if($('#permission').val() == 'returpenjualan.historis') {
                        if(data.success){
                            $('#historis').iCheck('uncheck'); 
                            $('#hrgsatuanbrutto').removeAttr('readonly').removeAttr('tabindex');
                            $('#disc1').removeAttr('readonly').removeAttr('tabindex');
                            $('#disc2').removeAttr('readonly').removeAttr('tabindex');
                            $('#ppn').removeAttr('readonly').removeAttr('tabindex');
                        }else{
                            swal('Ups!', 'Tidak Memiliki Hak Akses.','error');
                            $('#historis').iCheck('check'); 
                            $('#hrgsatuanbrutto').attr('readonly').attr('tabindex',-1);
                            $('#disc1').attr('readonly').attr('tabindex',-1);
                            $('#disc2').attr('readonly').attr('tabindex',-1);
                            $('#ppn').attr('readonly').attr('tabindex',-1);
                        }
                    }
                    else if($('#permission').val() == 'returpenjualan.hapusretur' || $('#permission').val() == 'returpenjualan.hapusdetail'){
                        if(data.success){
                            swal('Sukses!', 'Data berhasil dihapus','success');
                            table.row('#'+table_index).select();
                        }else{
                            swal('Ups!', 'Tidak Memiliki Hak Akses.','error');
                        }
                    }
                    else if($('#permission').val() == 'returpenjualan.updatesppb'){
                        updatesppb(null,dataupdate);
                    }
                    else if($('#permission').val() == 'returpenjualan.updatenrj'){
                        updatenrj(null,dataupdate);
                    }
                },
                error: function(data){
                    // console.log(data);
                }
            });

            $('#modalKewenangan').modal('hide');
            return false;
        });

        /* BEGIN - KOREKSI */
        $('#btnSubmitNotifikasiKoreksi').click(function(){
            $('#modalKoreksi').modal('toggle');
            active_field(true);
            setTimeout(function(){
                var date = '{{Carbon\Carbon::now()->format("d-m-Y")}}';
                $('#koreksitglnjr').val(date);
                $('#koreksitglkirim').val(date);
                $('#koreksinonrj').val('KOREKSI00');
                $('#koreksicatatan').val('KOREKSI00');
                $('#koreksiqtynrj').val(0);
                $('#koreksikaryawanstock').val('').focus();

                $('#modalKoreksi').modal('toggle');
            }, 750);

        });

        $('#formKoreksi').on('submit', function(e){
            e.preventDefault();
            if(!$('#koreksikaryawanstockid').val()){
                swal("Ups!", "Tidak bisa simpan record. Nama Karyawan Stok tidak boleh kosong. Hubungi Manager anda.", "error");
            }else if(parseInt($('#koreksiqtynrj').val()) <= 0){
                swal("Ups!", "Tidak bisa simpan record. Qty. Koreksi NRJ tidak boleh kosong. Hubungi Manager anda.", "error");
            // }else if(parseInt($('#koreksiqtynrj').val()) >= parseInt($('#koreksiqtynrj_tmp').val())){
            //     swal("Ups!", "Tidak bisa simpan record. Qty. Koreksi NRJ tidak boleh melebihi Qty. NRJ sebelumnya. Hubungi Manager anda.", "error");
            }else{
                tipe_edit = true;
                $.ajax({
                    type    : 'POST',
                    url     : '{{route("returpenjualandetail.koreksi")}}',
                    data    : $('#formKoreksi').serialize(),
                    dataType: "json",
                    success : function(data){
                        // console.log(data);
                        $('#modalKoreksi').modal('hide');
                        $('#modalKoreksi').find('input').val('');

                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.row('#'+table_index).select();
                        },1000);
                        swal("Sukses!", "Koreksi Retur Penjualan Detail berhasil disimpan.", "success");
                    },
                });
            }
            return false;
        });


        $('#modalupdate').on('hidden.bs.modal', function () {
            $('#formupdate').find("input[name!='_token'][name!='historis'][name!='returid']").val('');
            tableupdate.clear();
        });

        $("#tableupdate").on('keyup', '.editqtysppb', function(){
            var value = parseInt($(this).html());
            var updateqtysppb     = $(this).parent().find("input[name^=updateqtysppb]");
            var updateqtympr      = $(this).parent().find("input[name^=updateqtympr]").val();
            var updatehrgsatnetto = $(this).parent().find("input[name^=updatehrgsatnetto]");
            var hrgsatnettosppb   = $(this).parent().find("td:nth-child(8)");

            if(value > updateqtympr) {
                value = updateqtympr;
                $(this).html(value);
            }

            updateqtysppb.val(value);
            hrgsatnettosppb.html(parseInt(value*updatehrgsatnetto.val()).toLocaleString("id-ID"));
        });

        $("#tableupdate").on('keyup', '.editqtynrj', function(){
            var value = parseInt($(this).html());
            var updateqtynrj      = $(this).parent().find("input[name^=updateqtynrj]");
            var updateqtympr      = $(this).parent().find("input[name^=updateqtympr]").val();
            var updatehrgsatnetto = $(this).parent().find("input[name^=updatehrgsatnetto]");
            var hrgsatnettonrj    = $(this).parent().find("td:nth-child(9)");

            if(value > updateqtympr) {
                value = updateqtympr;
                $(this).html(value);
            }

            updateqtynrj.val(value);
            hrgsatnettonrj.html(parseInt(value*updatehrgsatnetto.val()).toLocaleString("id-ID"));
        });

        // $("#tableupdate").on('click', '.editkategorirj', function(){
        //     tipekategorirj = $(this).parent().attr('id');
        //     $('#modalKategoriRetur').modal('show');
        //     $('#txtQueryKategoriRetur').val($(this).html());
        //     search_kategoriretur($(this).html());

        //     $('#txtQueryKategoriRetur').focus();
        //     return false;
        // });

        $('#formupdate').on('submit', function(e){
            e.preventDefault();

            if(tipedataupdate == 'sppb') {
                @cannot('returpenjualan.updatesppb')
                    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else
                if(!($('#expedisiid').val())) {
                    swal({ title: "Ups!", text: "Field Expedisi belum diisi.", type: "error"},
                        function(){ setTimeout(function(){ $('#expedisi').focus(); }, 10);}
                    );
                    return false;
                }
                if(!($('#karyawanexpedisiid').val())) {
                    swal({ title: "Ups!", text: "Field Staff Pengambil Barang belum diisi.", type: "error"},
                        function(){ setTimeout(function(){ $('#karyawanexpedisi').focus(); }, 10);}
                    );
                    return false;
                }
                if(!($('#karyawanpenjualanid').val())) {
                    swal({ title: "Ups!", text: "Field Staff Penjualan belum diisi.", type: "error"},
                        function(){ setTimeout(function(){ $('#karyawanpenjualan').focus(); }, 10);}
                    );
                    return false;
                }
                @endcannot
            }else if(!($('#karyawanstockid').val())) {
                @cannot('returpenjualan.updatenrj')
                    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else
                swal({ title: "Ups!", text: "Field Staff Stock belum diisi.", type: "error"},
                    function(){ setTimeout(function(){ $('#karyawanstock').focus(); }, 10);}
                );
                return false;
                @endcannot
            }

            $.ajax({
                type    : 'POST',
                url     : (tipedataupdate == 'sppb') ? '{{route("returpenjualan.updatesppb")}}' : '{{route("returpenjualan.updatenrj")}}',
                data    : $('#formupdate').serialize(),
                dataType: "json",
                success : function(data){
                    // console.log(data);
                    $('#modalupdate').modal('hide');
                    $('#modalupdate').find('input').val('');

                    if (typeof data.success == 'boolean' && data.success) {
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.row(0).deselect();
                            table.row(0).select();
                        },1000);

                        if(tipedataupdate == 'sppb') {
                            swal("Sukses!", "Update SPPB Retur Penjualan berhasil disimpan.", "success");
                        } else swal("Sukses!", "Update NRJ Retur Penjualan berhasil disimpan.", "success");

                    } else if (typeof data.msg != 'undefined') swal("Ups!", data.msg.toString(), "warning");
                    else swal("Ups!", "Request di tolak oleh server", "error");

                    // window.location.reload();
                },
            });

            return false;
        });
    });

    function tambahrpjd(e,data) {
        @cannot('returpenjualan.tambah.detail')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.tambahrpjd == 'tambah') {
            $('#modalDetail #returid').val(data.id);
            $('#modalDetail #tokoid').val(data.tokoid);
            $('#modalDetail').modal('show');
        }else{
            swal('Ups!', data.tambahrpjd,'error');
        }
        @endcannot
    }

    function hapusretur(e,data) {
        if(data.hapusretur == 'auth'){
            $('#modalKewenangan #returid').val(data.id);
            if($(e).data('tipe') == 'header') {
                $('#modalKewenangan #permission').val('returpenjualan.hapusretur');
            }else{
                $('#modalKewenangan #permission').val('returpenjualan.hapusdetail');
            }
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.hapusretur,'error');
        }
    }

    function updatesppb(e,data) {
        tipedataupdate = 'sppb';
        @cannot('returpenjualan.updatesppb')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.updatesppb == 'update'){
            tipe_edit = true;
            $.ajax({
                type    : 'POST',
                url     : '{{route("returpenjualan.header")}}',
                data    : {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    $('#modalupdate #updateid').val(data.id);
                    $('#modalupdate #tglmpr').val(data.tglmpr);
                    $('#modalupdate #nompr').val(data.nompr);
                    $('#modalupdate #tglsppb').val(data.tglsppb);
                    $('#modalupdate #tglsppb').val(data.tglsppb);
                    $('#modalupdate #tglnjr').val(data.tglnjr);
                    $('#modalupdate #nonrj').val(data.nonrj);
                    $('#modalupdate #toko').val(data.namatoko);
                    $('#modalupdate #tokoid').val(data.tokoid);
                    $('#modalupdate #alamat').val(data.alamattoko);
                    $('#modalupdate #kota').val(data.kotatoko);
                    $('#modalupdate #kecamatan').val(data.kecamatantoko);
                    $('#modalupdate #wilid').val(data.wilidtoko);
                    $('#modalupdate #idtoko').val(data.tokoid);
                    $('#modalupdate #expedisi').val(data.namaexpedisi);
                    $('#modalupdate #expedisiid').val(data.Expedisiid);
                    $('#modalupdate #karyawanexpedisi').val(data.namastaffexpedisi);
                    $('#modalupdate #karyawanexpedisiid').val(data.karyawanidexpedisi);
                    $('#modalupdate #karyawanstock').val(data.namastaffstock);
                    $('#modalupdate #karyawanstockid').val(data.karyawanidstock);
                    $('#modalupdate #karyawanpenjualan').val(data.namastaffpenjualan);
                    $('#modalupdate #karyawanpenjualanid').val(data.karyawanidpenjualan);
                },
            });

            $.ajax({
                type    : 'POST',
                url     : '{{route("returpenjualandetail.data")}}',
                data    : {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    tableupdate.clear();
                    $.each(data.node, function (i,node) {
                        tableupdate.row.add(node);
                    });
                    tableupdate.draw();
                }
            });

            $('#modalupdate #myModalLabel').html("Retur Penjualan Update SPPB");
            $('#modalupdate #expedisi').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #expedisiid').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanexpedisi').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanexpedisiid').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanpenjualan').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanpenjualanid').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanstock').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanstockid').attr('readonly',true).attr('tabindex',-1);

            $('#modalupdate').modal('show');
            $('#modalupdate').on('shown.bs.modal', function() {
                tableupdate.columns.adjust().draw();
            });
        }else if(data.updatesppb == 'auth'){
            data.updatesppb = 'update';
            dataupdate  = data;
            $('#modalKewenangan #returid').val(data.id);
            $('#modalKewenangan #permission').val('returpenjualan.updatesppb');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.updatesppb,'error');
        }
        @endcannot
    }

    function updatenrj(e,data) {
        tipedataupdate = 'nrj';
        @cannot('returpenjualan.updatenrj')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.updatenrj == 'update'){
            $.ajax({
                type    : 'POST',
                url     : '{{route("returpenjualan.header")}}',
                data    : {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    $('#modalupdate #updateid').val(data.id);
                    $('#modalupdate #tglmpr').val(data.tglmpr);
                    $('#modalupdate #nompr').val(data.nompr);
                    $('#modalupdate #tglsppb').val(data.tglsppb);
                    $('#modalupdate #tglsppb').val(data.tglsppb);
                    $('#modalupdate #tglnjr').val(data.tglnjr);
                    $('#modalupdate #nonrj').val(data.nonrj);
                    $('#modalupdate #toko').val(data.namatoko);
                    $('#modalupdate #tokoid').val(data.tokoid);
                    $('#modalupdate #alamat').val(data.alamattoko);
                    $('#modalupdate #kota').val(data.kotatoko);
                    $('#modalupdate #kecamatan').val(data.kecamatantoko);
                    $('#modalupdate #wilid').val(data.wilidtoko);
                    $('#modalupdate #idtoko').val(data.tokoid);
                    $('#modalupdate #expedisi').val(data.namaexpedisi);
                    $('#modalupdate #expedisiid').val(data.Expedisiid);
                    $('#modalupdate #karyawanexpedisi').val(data.namastaffexpedisi);
                    $('#modalupdate #karyawanexpedisiid').val(data.karyawanidexpedisi);
                    $('#modalupdate #karyawanstock').val(data.namastaffstock);
                    $('#modalupdate #karyawanstockid').val(data.karyawanidstock);
                    $('#modalupdate #karyawanpenjualan').val(data.namastaffpenjualan);
                    $('#modalupdate #karyawanpenjualanid').val(data.karyawanidpenjualan);
                },
            });

            $.ajax({
                type    : 'POST',
                url     : '{{route("returpenjualandetail.data")}}',
                data    : {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    // console.log(tableupdate.settings().createdRow);

                    tableupdate.clear();
                    $.each(data.node, function (i,node) {
                        tableupdate.row.add(node);
                    });
                    tableupdate.draw();
                }
            });

            $('#modalupdate #myModalLabel').html("Retur Penjualan Update NRJ");
            $('#modalupdate #expedisi').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #expedisiid').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanexpedisi').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanexpedisiid').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanpenjualan').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanpenjualanid').attr('readonly',true).attr('tabindex',-1);
            $('#modalupdate #karyawanstock').removeAttr('readonly').removeAttr('tabindex').attr('required',true);
            $('#modalupdate #karyawanstockid').removeAttr('readonly').removeAttr('tabindex').attr('required',true);

            $('#modalupdate').modal('show');
            $('#modalupdate').on('shown.bs.modal', function() {
                tableupdate.columns.adjust().draw();
            });
        }else if(data.updatenrj == 'auth'){
            data.updatenrj = 'update';
            dataupdate  = data;
            $('#modalKewenangan #returid').val(data.id);
            $('#modalKewenangan #permission').val('returpenjualan.updatenrj');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.updatenrj,'error');
        }
        @endcannot
    }

    function cetaksppb(e,data) {
        @cannot('returpenjualan.cetaksppb')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.cetaksppb == 'cetak'){
            window.open('{{ route('returpenjualan.cetaksppb',null)}}/'+data.id, '_blank');
        }else if(data.cetaksppb == 'auth'){
            $('#modalKewenangan #returid').val(data.id);
            $('#modalKewenangan #permission').val('returpenjualan.cetaksppb');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.cetaksppb,'error');
        }
        @endcannot
    }

    function cetaknrj(e,data) {
        @cannot('returpenjualan.cetaknrj')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.cetaknrj == 'cetak'){
            window.open('{{ route('returpenjualan.cetaknrj',null)}}/'+data.id, '_blank');

            // var div = $('#pdfDiv');
            // div.html('<iframe width="0" height="0" id="pdfFrame"></iframe>'); 
            // var frame = div.find('#pdfFrame');

            // frame.attr('src', '{{ route('returpenjualan.cetaknrj',null)}}/'+data.id);
            // frame.load(function() {
            //     var frame = document.getElementById('pdfFrame');
            //     if (!frame) return;

            //     frame = frame.contentWindow;
            //     frame.focus();
            //     frame.print();
            // });
        }else if(data.cetaknrj == 'auth'){
            $('#modalKewenangan #returid').val(data.id);
            $('#modalKewenangan #permission').val('returpenjualan.nrj');
            $('#modalKewenangan').modal('show');
        }else{
            swal('Ups!', data.cetaknrj,'error');
        }
        @endcannot
    }


    function koreksi(e,data) {
        @cannot('returpenjualan.koreksi')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(data.koreksi == 'koreksi'){
            var iddetail = data.id;

            // Detail
            $.ajax({
                type: 'POST',
                url: '{{route("returpenjualan.detail")}}',
                data: {id: iddetail},
                success: function(data){
                    $('#koreksiidrpjd').val(iddetail);
                    $('#koreksihistoris').attr('checked',data.historis).change();
                    $('#koreksibarang').val(data.barang);
                    $('#koreksikodebarang').val(data.kodebarang);
                    $('#koreksisatuan').val(data.satuan);
                    $('#koreksiqtympr').val(data.qtympr);
                    $('#koreksiqtysppb').val(data.qtysppb);
                    $('#koreksiqtynrj').val(data.qtynrj);
                    $('#koreksiqtynrj_tmp').val(data.qtynrj);
                    $('#koreksikategorirpj').val(data.kategorirj);
                    $('#koreksicatatan').val(data.catatan);

                    // Header
                    $.ajax({
                        type: 'POST',
                        url: '{{route("returpenjualan.header")}}',
                        data: {id: data.returpenjualanid},
                        success: function(data){
                            $('#koreksitglmpr').val(data.tglmpr);
                            $('#koreksinompr').val(data.nompr);
                            $('#koreksitglsppb').val(data.tglsppb);
                            $('#koreksitglnjr').val(data.tglnotaretur);
                            $('#koreksinonrj').val(data.nonotaretur);
                            $('#koreksitoko').val(data.namatoko);
                            $('#koreksiexpedisi').val(data.namaexpedisi);
                            $('#koreksiexpedisiid').val(data.expedisiid);
                            $('#koreksikaryawanexpedisi').val(data.namastaffexpedisi);
                            $('#koreksikaryawanexpedisiid').val(data.karyawanidexpedisi);
                            $('#koreksikaryawanpenjualan').val(data.namastaffpenjualan);
                            $('#koreksikaryawanpenjualanid').val(data.karyawanidpenjualan);
                            $('#koreksikaryawanstock').val(data.namastaffstock);
                            $('#koreksikaryawanstockid').val(data.karyawanidstock);

                            active_field(false);
                            $('#modalKoreksi').modal('show');
                        }
                    });
                }
            });

        }else{
            swal('Ups!', data.koreksi,'error');
        }
        @endcannot
    }

    function active_field(e){
        if(e){
            $('#koreksikaryawanpenjualan').removeAttr("readonly").attr("tabindex",1).focus();
            $('#koreksikaryawanstock').removeAttr("readonly").attr("tabindex",1);
            $('#koreksiqtynrj').removeAttr("readonly").attr("tabindex",2);
            $('#btnSubmitNotifikasiKoreksi').hide();
            $('#btnSubmitKoreksi').show();

            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI DIBUAT SEBAGAI KOREKSI ATAS NOTA RETUR JUAL SEBELUMNYA YANG SUDAH ANDA KOREKSI/BATALKAN MENJADI 0.');
        }else{
            $('#koreksikaryawanstock').attr("readonly","readonly").attr("tabindex",-1);
            $('#koreksikaryawanpenjualan').attr("readonly","readonly").attr("tabindex",-1);
            $('#koreksiqtynrj').attr("readonly","readonly").attr("tabindex",-1);
            $('#btnSubmitNotifikasiKoreksi').show();
            $('#btnSubmitKoreksi').hide();

            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN QTY. NRJ = 0. NILAI KOREKSI DICATAT PADA NOTA RETUR JUAL YANG BARU. ANDA YAKIN?');
        }
    }



</script>
@endpush
