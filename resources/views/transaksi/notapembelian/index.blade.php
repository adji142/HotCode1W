@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item"><a href="{{ route('notapembelian.index') }}">Nota Pembelian</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Nota Pembelian</h2>
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
                                    <label style="margin-right: 10px;">Tgl. Nota</label>
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
                                <th>Tgl. Nota</th>
                                <th>No. Nota</th>
                                <th>Supplier</th>
                                <th>Tgl. Terima</th>
                                <th>Hrg. Total Nota</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Nota</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. Nota</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Supplier</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Terima</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Hrg. Total Nota</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th width="40%">Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty. Nota</th>
                                <th>Qty. Terima</th>
                                <th>Hrg. Satuan Netto</th>
                                <th>Hrg. Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nama Barang</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Satuan</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Nota</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Qty. Terima</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Hrg. Satuan Netto</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Hrg. Total</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateNota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pembelian - Update Nota Pembelian</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaId" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="supplier" class="form-control" placeholder="Supplier" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglNota">Tgl. Nota</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="tglNota" class="form-control" placeholder="Tgl. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noNota">No. Nota</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="noNota" class="form-control" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglTerima">Tgl. Terima</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="tglTerima" class="form-control" placeholder="Tgl. Terima" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemeriksa00">Pemeriksa 00</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="pemeriksa00" class="form-control" placeholder="Pemeriksa 00" required>
                                    <input type="hidden" id="pemeriksa00id" name="pemeriksa00id" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="keterangan" class="form-control" placeholder="Keterangan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemeriksa11">Pemeriksa 11</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" id="pemeriksa11" class="form-control" placeholder="Pemeriksa 11" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitUpdate" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateNotaDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pembelian - Update Nota Pembelian Detail</h4>
                </div>
                <form class="form-horizontal" method="post">
                    <input type="hidden" id="notaIdDetail" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglNotaDetail">Tgl. Nota</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="tglNotaDetail" class="form-control" placeholder="Tgl. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="noNotaDetail">No. Nota</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="noNotaDetail" class="form-control" placeholder="No. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglTerimaDetail">Tgl. Terima</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="tglTerimaDetail" class="form-control" placeholder="Tgl. Terima" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="supplierDetail">Supplier</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="supplierDetail" class="form-control" placeholder="Supplier" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pemeriksa00Detail">Pemeriksa 00</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input type="text" id="pemeriksa00Detail" class="form-control" placeholder="Pemeriksa 00" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table id="notaDetailData" class="table table-bordered table-striped tablepilih">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Barang</th>
                                            <th class="text-center">Sat.</th>
                                            <th class="text-center">Qty. Nota</th>
                                            <th class="text-center">Qty. Terima</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyNotaDetailData">
                                        <!-- <tr class="kosong">
                                            <td colspan="4" class="text-center">Tidak ada detail</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSubmitUpdateDetail" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifikasi Koreksi Pembelian -->
    <div class="modal fade" id="modalKoreksiNotaDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pembelian - Notifikasi Koreksi Pembelian</h4>
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
                                    <label>Tgl. Terima</label>
                                    <input type="text" id="tglTerimaKoreksi" class="form-control" placeholder="Tgl. Terima" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <input type="text" id="supplierKoreksi" class="form-control" placeholder="Supplier" readonly tabindex="-1">
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
                                    <label>Pemeriksa 11</label>
                                    <input type="text" id="pemeriksa11Koreksi" class="form-control" placeholder="Pemeriksa 11" readonly tabindex="-1">
                                </div>
                                <div class="form-group">
                                    <label>Pemeriksa 00</label>
                                    <input type="text" id="pemeriksa00Koreksi" class="form-control" placeholder="Pemeriksa 00" readonly tabindex="-1">
                                    <input type="hidden" id="pemeriksa00Koreksiid" value="">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan Koreksi</label>
                                    <textarea id="keteranganKoreksi" class="form-control textarea-noresize" placeholder="Keterangan" readonly tabindex="-1" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Catatan / No Acc</label>
                                    <textarea id="catatanKoreksi" class="form-control textarea-noresize" placeholder="Catatan / No Acc" readonly tabindex="-1" rows="2"></textarea>
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
                                    <label>Keterangan Barang Koreksi</label>
                                    <input type="text" id="keteranganBarangKoreksi" class="form-control" placeholder="Keterangan" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty. Nota</label>
                                    <input type="text" id="qtyNotaKoreksi" class="form-control text-right" placeholder="Qty. Nota" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty. Terima</label>
                                    <input type="text" id="qtyTerimaKoreksi" class="form-control text-right" placeholder="Qty. Terima" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>
                        <hr>
                        <div class="row">
                        <div class="col-md-12">
                            <div id="textNotifikasiKoreksi"><p>ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN QTY. TERIMA = 0.
                        NILAI KOREKSI DICATAT PADA NOTA PEMBELIAN YANG BARU.
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

    <!-- Data nota pembelian -->
    <div class="modal fade" id="modalDataNotaPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Nota Pembelian</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="notaData" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyNotaDetailData">
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
    <!-- end of data nota pembelian -->

    <!-- Data nota pembelian detail -->
    <div class="modal fade" id="modalDataDetailNotaPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Nota Pembelian Detail</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="notaDataDetail" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyNotaDetailData">
                                        <!-- <tr class="kosong">
                                            <td colspan="4" class="text-center">Tidak ada detail</td>
                                        </tr> -->
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
    <!-- end of data nota pembelian detail -->

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
    var table_focus, tipe_edit, table, table2,table_index,table2_index,fokus;
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var column_index  = 0;
    var last_index    = '';
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var tipe          = ['Find','Filter'];
    var custom_search = [
        {
            text   : '',
            filter : '='
        },
        {
            text   : '',
            filter : '='
        },
        {
            text   : '',
            filter : '='
        },
        {
            text   : '',
            filter : '='
        },
        {
            text   : '',
            filter : '='
        },
        {
            text   : '',
            filter : '='
        },
    ];

    // lookup
    lookupstaff();

    $(document).ready(function() {
        $(".tgl").inputmask();

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("notapembelian.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    d.tipe          = '{{$tipe}}';
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
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns: [
                {
                    "data"     : "action",
                    "orderable": false,
                },
                {
                    "data"     : "tglnota",
                    "className": "menufilter numberfilter"
                },
                {
                    "data"     : "nonota",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "suppliernama",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "tglterima",
                    "className": "menufilter numberfilter"
                },
                {
                    "data"     : "hargatotal",
                    "className": "text-right menufilter numberfilter"
                },
            ]
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
            ele = document.activeElement;
            if(e.keyCode == 8){
                if($('#modalUpdateNota').is(':visible')){
                    if(ele.id == 'pemeriksa00'){
                        $('#pemeriksa00id').val('');
                    }
                }
            }
            // else if(e.keyCode == 9){
            //     if($('#modalStaff').is(':visible')){
            //         console.log(ele);
            //         // jangan lupa ini ntar diganti yg terfocus
            //         // if(ele.tagName == 'TR'){
            //         //     $('#tbodyStaff tr.selected').removeClass('selected');
            //         //     $(ele).addClass("selected");
            //         // }
            //     }
            // }
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
                //         pilih_staff(tipe_edit);
                //     }else if(ele.id == 'btnPilihStaff'){
                //         pilih_staff(tipe_edit);
                //     }else if(ele.id == 'query'){
                //         search_staff($(ele).val());
                //         return false;
                //     }
                // }

                if($('#modalUpdateNota').is(':visible')){
                    // if(ele.id == 'pemeriksa00'){
                    //     if($('#pemeriksa00id').val()){
                    //         submit_update();
                    //     }else{
                    //         $('#modalStaff').modal('show');
                    //         $('#modalStaff #query').val($(ele).val());
                    //         $('#modalStaff').on('shown.bs.modal', function() {
                    //             $('#query').focus();
                    //         });
                    //         search_staff($(ele).val());
                    //     }
                    // }else if(ele.id == 'keterangan'){
                    if(ele.id == 'keterangan'){
                        if($('#pemeriksa00id').val()){
                            submit_update();
                        }else{
                            swal("Ups!", "Staff belum dipilih.", "error");
                            return false;
                        }
                    }
                }

                if($('#modalUpdateNotaDetail').is(':visible')){
                    if(ele.className == 'updateQtyTerima'){
                        return false;
                    }
                }

                if($('#modalKoreksiNotaDetail').is(':visible')){
                    if($('#btnSubmitKoreksi').is(":visible")){
                        // if(ele.id == 'pemeriksa00Koreksi'){
                        //     if($('#pemeriksa00Koreksiid').val() && $('#qtyTerimaKoreksi').val()){
                        //         console.log('submit koreksi');
                        //         submit_koreksi();
                        //     }else if($('#pemeriksa00Koreksiid').val() && !$('#qtyTerimaKoreksi').val()){
                        //         swal("Ups!", "Qty. Terima belum diisi.", "error");
                        //         return false;
                        //     }else{
                        //         $('#modalStaff').modal('show');
                        //         $('#modalStaff #query').val($(ele).val());
                        //         $('#modalStaff').on('shown.bs.modal', function() {
                        //             $('#query').focus();
                        //         });
                        //         search_staff($(ele).val());
                        //     }
                        // }else if(ele.id == 'qtyTerimaKoreksi'){
                        if(ele.id == 'qtyTerimaKoreksi'){
                            if($('#pemeriksa00Koreksiid').val() && $('#qtyTerimaKoreksi').val()){
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

        // $('#modalStaff').on('hidden.bs.modal', function() {
        //     $('#modalUpdateNota #pemeriksa00').focus();
        //     $('#modalKoreksiNotaDetail #pemeriksa00Koreksi').focus();
        // });

        $('.tgl').change(function(){
            table.draw();
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
            order         : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // if(data[8]){
                if(data.koreksipbparentid){
                    //birumuda
                    $(row).addClass('blue');
                // }else if(data[9]){
                }else if(data.newchildid){
                    //abu2muda
                    $(row).addClass('grey');
                }
            },
            columns     : [
                {
                    "data" : "action",
                    "orderable" : false,
                },
                {
                    "data" : "barang",
                },
                {
                    "data" : "satuan",
                },
                {
                    "data" : "qtynota",
                    "className" : "text-right",
                },
                {
                    "data" : "qtyterima",
                    "className" : "text-right",
                },
                {
                    "data" : "hrgsatuannetto",
                    "className" : "text-right",
                },
                {
                    "data" : "hrgtotalnetto",
                    "className" : "text-right",
                },

                // {
                //     "orderable" : false
                // },
                // null,
                // null,
                // {
                //     "className" : "text-right"
                // },
                // {
                //     "className" : "text-right"
                // },
                // {
                //     "className" : "text-right"
                // },
                // {
                //     "className" : "text-right"
                // },
            ],
        });

        var tabledatanota = $('#notaData').DataTable({
            dom         : 'lrtp',
            paging        : false,
        });

        var tabledatadetailnota = $('#notaDataDetail').DataTable({
            dom         : 'lrtp',
            paging        : false,
        });

        table.on('select', function ( e, dt, type, indexes ){
            table_focus = 'header';
            var rowData = table.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type: 'POST',
                data: {id: rowData[0].id},
                dataType: "json",
                url: '{{route("notapembelian.detail.data")}}',
                success: function(data){
                    table2.clear();
                    table2.rows.add(data.node);
                    table2.draw();

                    {{-- table2.clear();
                    for (var i = 0; i < data.node.length; i++) {
                        if(data.node[i][6]['edit'] == 'unedit' && data.node[i][6]['koreksi'] == 'unkoreksi'){
                            node_action = null;
                        }else if(data.node[i][6]['edit'] == 'unedit' && data.node[i][3] < 0){
                            node_action = null;
                        }else if(data.node[i][6]['edit'] == 'unedit'){
                            node_action = '<div class="btn btn-info btn-xs no-margin-action" onclick="koreksi(this,'+data.node[i][7]+')" data-message="'+data.node[i][6]['koreksi']+'" data-toggle="tooltip" data-placement="bottom" title="Koreksi"><i class="fa fa-edit"></i></div>';
                        }else if(data.node[i][3] < 0){
                            node_action = '<div class="btn btn-warning btn-xs no-margin-action" onclick="update(this,'+data.node[i][7]+')" data-message="'+data.node[i][6]['edit']+'" data-tipe="detail" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></div>';
                        }else{
                            node_action = '<div class="btn btn-warning btn-xs no-margin-action" onclick="update(this,'+data.node[i][7]+')" data-message="'+data.node[i][6]['edit']+'" data-tipe="detail" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></div>';
                            node_action += '<div class="btn btn-info btn-xs no-margin-action" onclick="koreksi(this,'+data.node[i][7]+')" data-message="'+data.node[i][6]['koreksi']+'" data-toggle="tooltip" data-placement="bottom" title="Koreksi"><i class="fa fa-edit"></i></div>';
                        }
                        table2.row.add([
                            node_action,
                            data.node[i][0],
                            data.node[i][1],
                            data.node[i][2],
                            data.node[i][3],
                            data.node[i][4],
                            data.node[i][5],
                            data.node[i][7],
                            data.node[i][8],
                            data.node[i][9],
                        ]);
                    }
                    table2.draw(); --}}
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        table2.on('select', function ( e, dt, type, indexes ){
            table_focus = 'detail';
        });

        $('#table1 tbody').on('dblclick', 'tr', function(){
            var data = table.row(this).data();
            console.log(data);
            tabledatanota.clear();
            tabledatanota.rows.add([
                {'0':'1.','1':'Tgl. Nota','2':data.tglnota},
                {'0':'2.','1':'No. Nota','2':data.nonota},
                {'0':'3.','1':'Supplier','2':data.suppliernama},
                {'0':'4.','1':'Tgl. Terima','2':data.tglterima},
                {'0':'5.','1':'Expedisi','2':data.expedisiid},
                {'0':'6.','1':'Staff pemeriksa 00','2':data.karyawannama},
                {'0':'7.','1':'Keterangan','2':data.keterangan},
                {'0':'8.','1':'Staff pemeriksa 11','2':data.staffidpemeriksa11},
                {'0':'9.','1':'Last Updated By','2':data.lastupdatedby},
                {'0':'10.','1':'Last Updated On','2':data.lastupdatedon},
            ]);
            tabledatanota.draw();
            $('#modalDataNotaPembelian').modal('show');
        });

        $('#table2 tbody').on('dblclick', 'tr', function(){
            var data = table2.row(this).data();
            $.ajax({
                type: 'POST',
                url: '{{route("notapembelian.detail.detail")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                    tabledatadetailnota.clear();
                    tabledatadetailnota.rows.add([
                        {'0':'1.','1':'Barang','2':data.namabarang},
                        {'0':'2.','1':'Qty. Nota','2':data.qtynota},
                        {'0':'3.','1':'Qty. Terima','2':data.qtyterima},
                        {'0':'4.','1':'Tgl. Terima','2':data.tglterima},
                        {'0':'5.','1':'Hrg. Sat. Brutto','2':data.hrgsatuanbrutto},
                        {'0':'6.','1':'Disc. 1','2':data.disc1},
                        {'0':'7.','1':'Hrg. Setelah Disc. 1','2':data.hrgdisc1},
                        {'0':'8.','1':'Disc. 2','2':data.disc2},
                        {'0':'9.','1':'Hrg. Setelah Disc. 2','2':data.hrgdisc2},
                        {'0':'10.','1':'PPN','2':data.ppn},
                        {'0':'11.','1':'Hrg. Sat. Netto','2':data.hrgsatuannetto},
                        {'0':'12.','1':'Keterangan','2':data.keteranganbarang},
                        {'0':'13.','1':'Last Updated By','2':data.lastupdatedby},
                        {'0':'14.','1':'Last Updated On','2':data.lastupdatedon},
                    ]);
                    tabledatadetailnota.draw();
                    $('#modalDataDetailNotaPembelian').modal('show');
                },
            });
        });

        $('#btnSubmitUpdateDetail').click(function(){
            @cannot('notapembelian.detail.ubah')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            var element = $('.updateQtyTerima');
            var data    = [];
            for (var i = 0; i < element.length; i++) {
                data[i] = element[i].textContent;
            }

            $.ajax({
                type: 'POST',
                url: '{{route("notapembelian.detail.ubah")}}',
                data: {
                    data     : data,
                    notaid     : $('#modalUpdateNotaDetail #notaIdDetail').val(),
                    tipe     : table_focus,
                },
                dataType: "json",
                success: function(data){
                    console.log(data);
                    $('#modalUpdateNotaDetail').modal('hide');
                    table.ajax.reload(null,true);
                    tipe_edit = null;
                    setTimeout(function(){
                        table.row(0).select();
                    },1000);
                },
            });
            @endcannot
        });

        $('#btnSubmitKoreksi').click(function(){
            if($('#pemeriksa00Koreksi').val() && $('#qtyTerimaKoreksi').val()){
                submit_koreksi();
            //     var data = {
            //         tglnota             : $('#tglNotaKoreksi').val(),
            //         nonota                 : $('#noNotaKoreksi').val(),
            //         tglterima             : $('#tglTerimaKoreksi').val(),
            //         pemeriksa00         : $('#pemeriksa00Koreksiid').val(),
            //         keterangan             : $('#keteranganKoreksi').val(),
            //         qtynota             : $('#qtyNotaKoreksi').val(),
            //         qtyterima             : $('#qtyTerimaKoreksi').val(),
            //         keteranganbarang     : $('#keteranganBarangKoreksi').val(),
            //         nota                 : $('#modalKoreksiNotaDetail #notaId').val(),
            //         notadetail             : $('#modalKoreksiNotaDetail #notaIdDetail').val(),
            //     };

            //     $.ajax({
            //         type: 'POST',
            //         url: '{{route("notapembelian.detail.koreksi")}}',
            //         data: {data: data},
            //         dataType: "json",
            //         success: function(data){
            //             console.log(data);
            //             $('#modalKoreksiNotaDetail').modal('hide');
            //             $('#pemeriksa00Koreksi').val('');
            //             $('#pemeriksa00Koreksiid').val('');
            //             //$('#keteranganKoreksi').val('');
            //             $('#qtyTerimaKoreksi').val('');
            //         },
            //     });
            }else{
                swal("Ups!", "Tidak bisa simpan record. Nama pemeriksa 00 dan Qty. Terima tidak boleh kosong. Hubungi Manager anda.", "error");
            }
        });
    });

    var tabledetail = $('#notaDetailData').DataTable({
        dom     : 'lrtp',
        paging  : false,
        tabIndex: -1,
    });

    function submit_koreksi(){
        @cannot('notapembelian.detail.koreksi')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if($('#pemeriksa00Koreksi').val()){
            var data = {
                tglnota         : $('#tglNotaKoreksi').val(),
                nonota          : $('#noNotaKoreksi').val(),
                tglterima       : $('#tglTerimaKoreksi').val(),
                pemeriksa00     : $('#pemeriksa00Koreksiid').val(),
                keterangan      : $('#keteranganKoreksi').val(),
                catatan         : $('#catatanKoreksi').val(),
                qtynota         : $('#qtyNotaKoreksi').val(),
                qtyterima       : $('#qtyTerimaKoreksi').val(),
                keteranganbarang: $('#keteranganBarangKoreksi').val(),
                nota            : $('#modalKoreksiNotaDetail #notaId').val(),
                notadetail      : $('#modalKoreksiNotaDetail #notaIdDetail').val(),
            };

            $.ajax({
                type: 'POST',
                url: '{{route("notapembelian.detail.koreksi")}}',
                data: {data: data},
                dataType: "json",
                success: function(data){
                    $('#modalKoreksiNotaDetail').modal('hide');
                    $('#modalKoreksiNotaDetail').find('input').val('');
                    $('#modalKoreksiNotaDetail').find('textarea').val('');

                    $('#pemeriksa00Koreksi').val('');
                    $('#pemeriksa00Koreksiid').val('');
                    //$('#keteranganKoreksi').val('');
                    $('#qtyTerimaKoreksi').val('');

                    table.ajax.reload(null, true);
                    tipe_edit = null;
                    setTimeout(function(){
                        table.row(0).select();
                    },100);
                },
            });
        }else{
            swal("Ups!", "Tidak bisa simpan record. Nama pemeriksa 00 tidak boleh kosong. Hubungi Manager anda.", "error");
        }
        @endcannot
    }

    function update(e) {
        var message = $(e).data('message');
        var tipe = $(e).data('tipe');
        if($(e).parents('table').attr('id') == 'table2') {
          var data = table2.row($(e).parents('tr')).data();
        }else{
          var data = table.row($(e).parents('tr')).data();
        }


        if(message == 'edit') {
            if(tipe == 'header') {
                @cannot('notapembelian.ubah') 
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else
                tipe_edit = 'update';
                $("#modalUpdateNota #pemeriksa00").val('');
                $("#modalUpdateNota #pemeriksa00id").val('');
                $("#modalUpdateNota #keterangan").val('');
                $('#modalUpdateNota #notaId').val(data.id);
                $('#modalUpdateNota #supplier').val(data.suppliernama);
                $('#modalUpdateNota #tglNota').val(data.tglnota);
                $('#modalUpdateNota #noNota').val(data.nonota);
                if(data.tglterima == null){
                    $('#modalUpdateNota #tglTerima').val('{{Carbon\Carbon::now()->format("d-m-Y")}}');
                }else{
                    $('#modalUpdateNota #tglTerima').val(data.tglterima);
                }
                $('#modalUpdateNota #pemeriksa11').val(data.staffidpemeriksa11);
                $('#modalUpdateNota').modal('show');
                $('#modalUpdateNota').on('shown.bs.modal', function() {
                    $('#pemeriksa00').focus();
                })
                @endcannot
            }else if(message == 'edit') {
                @cannot('notapembelian.detail.ubah')
                swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else
                tipe_edit = 'update';
                $.ajax({
                    type: 'POST',
                    url: '{{route("notapembelian.detail.detail")}}',
                    data: {id: data.id},
                    dataType: "json",
                    success: function(data){
                        $('#modalUpdateNotaDetail #tglNotaDetail').val(data.tglnota);
                        $('#modalUpdateNotaDetail #noNotaDetail').val(data.nonota);
                        $('#modalUpdateNotaDetail #tglTerimaDetail').val(data.tglterima);
                        $('#modalUpdateNotaDetail #supplierDetail').val(data.supplier);
                        $('#modalUpdateNotaDetail #pemeriksa00Detail').val(data.pemeriksa00);
                        $('#modalUpdateNotaDetail #notaIdDetail').val(data.notadetailid);

                        tabledetail.clear();
                        tabledetail.row.add([
                            data.namabarang,
                            data.satuan,
                            data.qtynota,
                            '<div class="updateQtyTerima" contenteditable>'+data.qtynota+'</div>',
                        ]);
                        tabledetail.draw();

                        $('#modalUpdateNotaDetail').modal('show');
                        $('#modalUpdateNotaDetail').on('shown.bs.modal', function() {
                            $('.updateQtyTerima').focus();
                        });
                    },
                });
                @endcannot
            }
        }else if(message == 'auth'){
            $('#modalKewenangan #notaId').val(data.id);
            if(tipe == 'header') {
                $('#modalKewenangan #permission').val('notapembelian.ubah');
                $('#modalKewenangan').modal('show');
            }else if(message == 'edit') {
                $('#modalKewenangan #permission').val('notapembelian.detail.ubah');
                $('#modalKewenangan').modal('show');
            }
        }else{
            swal('Ups!', message,'error');
        }
    }

    function koreksi(e,data) {
        var message = $(e).data('message');
        if($(e).parents('table').attr('id') == 'table2') {
          var data = table2.row($(e).parents('tr')).data();
        }else{
          var data = table.row($(e).parents('tr')).data();
        }

        @cannot('notapembelian.detail.koreksi')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if(message == 'koreksi') {
            tipe_edit = 'koreksi';
            $.ajax({
                type: 'POST',
                url: '{{route("notapembelian.detail.detail")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                    $('#modalKoreksiNotaDetail #notaId').val(data.notaid);
                    $('#modalKoreksiNotaDetail #notaIdDetail').val(data.notadetailid);
                    $('#modalKoreksiNotaDetail #supplierKoreksi').val(data.supplier);
                    $('#modalKoreksiNotaDetail #tglNotaKoreksi').val(data.tglnota);
                    $('#modalKoreksiNotaDetail #noNotaKoreksi').val(data.nonota);
                    $('#modalKoreksiNotaDetail #tglTerimaKoreksi').val(data.tglterima);
                    $('#modalKoreksiNotaDetail #pemeriksa00Koreksi').val(data.pemeriksa00);
                    $('#modalKoreksiNotaDetail #pemeriksa00Koreksiid').val(data.pemeriksa00id);
                    $('#modalKoreksiNotaDetail #keteranganKoreksi').val(data.keterangan);
                    $('#modalKoreksiNotaDetail #pemeriksa11Koreksi').val(data.pemeriksa11);

                    $('#modalKoreksiNotaDetail #barangKoreksi').val(data.namabarang);
                    $('#modalKoreksiNotaDetail #qtyNotaKoreksi').val(data.qtynota);
                    $('#modalKoreksiNotaDetail #qtyTerimaKoreksi').val(data.qtyterima);
                    $('#modalKoreksiNotaDetail #hrgBrutoKoreksi').val(data.hrgsatuanbrutto);
                    $('#modalKoreksiNotaDetail #disc1Koreksi').val(data.disc1);
                    $('#modalKoreksiNotaDetail #hrgDisc1Koreksi').val(data.hrgdisc1);
                    $('#modalKoreksiNotaDetail #disc2Koreksi').val(data.disc2);
                    $('#modalKoreksiNotaDetail #hrgDisc2Koreksi').val(data.hrgdisc2);
                    $('#modalKoreksiNotaDetail #ppnKoreksi').val(data.ppn);
                    $('#modalKoreksiNotaDetail #hrgNettoKoreksi').val(data.hrgsatuannetto);
                    $('#modalKoreksiNotaDetail #keteranganBarangKoreksi').val(data.keteranganbarang);


                    $('#btnSubmitKoreksi').hide();
                    active_field(false);
                    $('#modalKoreksiNotaDetail').modal('show');
                    $('#btnSubmitNotifikasiKoreksi').focus();
                },
            });
        }else{
            swal('Ups!', message,'error');
        }
        @endcannot
    }

    // $('#pemeriksa00').on('keypress', function(e){
    //     if (e.keyCode == '13') {
    //         $('#modalStaff').modal('show');
    //         $('#modalStaff #query').val($(this).val());
    //         $('#modalStaff').on('shown.bs.modal', function() {
    //             $('#query').focus();
    //         });
    //         search_staff($(this).val());
    //         return false;
    //     }
    // });

    // $('#modalStaff #query').on('keypress', function(e){
    //     if (e.keyCode == '13') {
    //         search_staff($(this).val());
    //         return false;
    //     }
    // });

    // $('#modalKoreksiNotaDetail #pemeriksa00Koreksi').on('keypress', function(e){
    //     if (e.keyCode == '13') {
    //         if(!$(this).attr('readonly')) {
    //             $('#modalStaff').modal('show');
    //             $('#modalStaff #query').val($(this).val());
    //             search_staff($(this).val());
    //             return false;
    //         }
    //     }
    // });

    // $('#modalStaff').on('keypress', function(e){
    //     if (e.keyCode == '13' && document.activeElement.id == 'btnPilihStaff') {
    //         pilih_staff(tipe_edit);
    //     }
    // });

    $('#btnSubmitNotifikasiKoreksi').click(function(){
        submit_notifikasi_koreksi();
        console.log('dipencet');
    });

    function submit_notifikasi_koreksi(){
        $('#modalKoreksiNotaDetail').modal('toggle');
        active_field(true);
        setTimeout(function(){
            var date = '{{Carbon\Carbon::now()->format("d-m-Y")}}';
            $('#modalKoreksiNotaDetail #pemeriksa00Koreksi').val('');
            $('#modalKoreksiNotaDetail #pemeriksa00Koreksiid').val('');
            $('#modalKoreksiNotaDetail #qtyTerimaKoreksi').val('');
            $('#modalKoreksiNotaDetail #tglNotaKoreksi').val(date);
            $('#modalKoreksiNotaDetail #noNotaKoreksi').val('KOREKSI00');
            $('#modalKoreksiNotaDetail #tglTerimaKoreksi').val(date);
            $('#modalKoreksiNotaDetail #qtyNotaKoreksi').val(0);
            $('#modalKoreksiNotaDetail #keteranganKoreksi').val('KOREKSI00');
            $('#modalKoreksiNotaDetail #keteranganBarangKoreksi').val('KOREKSI00');

            $('#modalKoreksiNotaDetail').modal('toggle');
            $('#modalKoreksiNotaDetail').on('shown.bs.modal', function() {
                $('#modalKoreksiNotaDetail #pemeriksa00Koreksi').focus();
            });
        }, 750);
    }

    function submit_update(){
        @cannot('notapembelian.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if($('#pemeriksa00').val()){
            var data = {
                notaid       : $('#modalUpdateNota #notaId').val(),
                pemeriksa00id: $('#pemeriksa00id').val(),
                keterangan   : $('#keterangan').val(),
                tglterima    : $('#tglTerima').val(),
            };

            $.ajax({
                type: 'POST',
                url: '{{route("notapembelian.ubah")}}',
                data: {data: data},
                dataType: "json",
                success: function(data){
                    console.log(data.node);
                    $('#modalUpdateNota').modal('hide');

                    $('#modalUpdateNotaDetail #tglNotaDetail').val(data.tglnota);
                    $('#modalUpdateNotaDetail #noNotaDetail').val(data.nonota);
                    $('#modalUpdateNotaDetail #tglTerimaDetail').val(data.tglterima);
                    $('#modalUpdateNotaDetail #supplierDetail').val(data.supplier);
                    $('#modalUpdateNotaDetail #pemeriksa00Detail').val(data.pemeriksa00);
                    $('#modalUpdateNotaDetail #notaIdDetail').val(data.id);

                    tabledetail.clear();
                    for (var i = 0; i < data.node.length; i++) {
                        tabledetail.row.add([
                            data.node[i][0],
                            data.node[i][1],
                            data.node[i][2],
                            '<div class="updateQtyTerima" contenteditable>'+data.node[i][2]+'</div>',
                        ]);
                    }
                    tabledetail.draw();

                    $('#modalUpdateNotaDetail').modal('show');
                    $('#modalUpdateNotaDetail').on('shown.bs.modal', function() {
                        $('.updateQtyTerima').focus();
                    });
                },
            });
        }else{
            swal("Ups!", "Tidak bisa simpan record. Nama pemeriksa 00 tidak boleh kosong. Hubungi Manager anda.", "error");
        }
        @endcannot
    }

    $('#btnSubmitUpdate').click(function(){
        submit_update();
    });

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
    //     pilih_staff(tipe_edit);
    // });
    // $('#modalStaff table.tablepilih tbody').on('dblclick', 'tr', function(){
    //    pilih_staff(tipe_edit);
    // });

    // function pilih_staff(tipe){
    //     if ($('#tbodyStaff').find('tr.selected td').eq(1).text() == '') {
    //         swal("Ups!", "Staff belum dipilih.", "error");
    //         return false;
    //     }else {
    //         if(tipe == 'update'){
    //             $('#pemeriksa00').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#pemeriksa00id').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#pemeriksa00").focus();
    //         }else{
    //             $('#pemeriksa00Koreksi').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
    //             $('#pemeriksa00Koreksiid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
    //             $('#modalStaff').modal('hide');
    //             $("#pemeriksa00Koreksi").focus();
    //         }
    //     }
    // }

    function active_field(e){
        if(e){
            $('#pemeriksa00Koreksi').removeAttr("readonly");
            // $('#keteranganKoreksi').removeAttr("readonly");
            $('#qtyTerimaKoreksi').removeAttr("readonly");
            $('#catatanKoreksi').removeAttr("readonly").attr("tabindex","3");
            $('#pemeriksa00Koreksi').attr("tabindex","1");
            // $('#keteranganKoreksi').attr("tabindex","2");
            $('#qtyTerimaKoreksi').attr("tabindex","2");
            $('#btnSubmitKoreksi').attr("tabindex","4");
            $('#btnSubmitKoreksi').attr("tabindex","5");
            $('#btnCancelKoreksi').html('Batal');
            $('#btnSubmitNotifikasiKoreksi').hide();
            $('#btnSubmitKoreksi').show();
            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI DIBUAT SEBAGAI KOREKSI. ATAS NOTA PEMBELIAN SEBELUMNYA YANG SUDAH ANDA KOREKSI/BATALKAN MENJADI 0.');
            $("#infoNotaKoreksiKiri").hide();
            $("#infoNotaKoreksiKanan").removeClass('col-md-3');
            $("#infoNotaKoreksiKanan").addClass('col-md-6');
        }else{
            $('#pemeriksa00Koreksi').attr("readonly","true").attr("tabindex","-1");
            // $('#keteranganKoreksi').attr("readonly","true").attr("tabindex","-1");
            $('#qtyTerimaKoreksi').attr("readonly","true").attr("tabindex","-1");
            $('#catatanKoreksi').attr("readonly","true").attr("tabindex","-1");
            $('#btnSubmitKoreksi').attr("tabindex","-1");
            $('#btnCancelKoreksi').html('Tidak');
            $('#btnSubmitNotifikasiKoreksi').show();
            $('#btnSubmitKoreksi').hide();
            $('#textNotifikasiKoreksi').html('ITEM BARANG DALAM NOTA INI AKAN DIBATALKAN DENGAN QTY. TERIMA = 0. NILAI KOREKSI DICATAT PADA NOTA PEMBELIAN YANG BARU. ANDA YAKIN?');
            $("#infoNotaKoreksiKiri").show();
            $("#infoNotaKoreksiKanan").removeClass('col-md-6');
            $("#infoNotaKoreksiKanan").addClass('col-md-3');
        }
    }

    $('#btnSubmitKewenangan').click(function(){
        $.ajax({
            type: 'POST',
            url : '{{route("notapembelian.kewenangan")}}',
            data: {
                username  : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
                password  : $('#modalKewenangan #pxassKewenangan').val(),
                notaid    : $('#modalKewenangan #notaId').val(),
                permission: $('#modalKewenangan #permission').val(),
                _token    : "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(data){
                $('#modalKewenangan #uxserKewenangan').val('').change();
                $('#modalKewenangan #pxassKewenangan').val('').change();

                if(data.success){
                    $('#modalKewenangan').modal('hide');

                    $('#modalUpdateNota #notaId').val(data.id);
                    $('#modalUpdateNota #supplier').val(data.suppliernama);
                    $('#modalUpdateNota #tglNota').val(data.tglnota);
                    $('#modalUpdateNota #noNota').val(data.nonota);
                    if(data.tglterima == null){
                        $('#modalUpdateNota #tglTerima').val('{{Carbon\Carbon::now()->format("d-m-Y")}}');
                    }else{
                        $('#modalUpdateNota #tglTerima').val(data.tglterima);
                    }
                    $('#modalUpdateNota #pemeriksa11').val(data.staffidpemeriksa11);
                    $('#modalUpdateNota').modal('show');
                }else{
                    swal('Ups!', 'Data yang Anda inputkan tidak tepat','error');
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    });
</script>
@endpush
