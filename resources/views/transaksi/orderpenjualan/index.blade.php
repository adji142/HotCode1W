@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('orderpenjualan.index') }}">Order Penjualan</a></li>
@endsection

@section('main_container')
<input type="hidden" id="headerid" value="0">
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
      <h2>Daftar Order Penjualan</h2>
      <ul class="nav navbar-right panel_toolbox">
        <li style="float: right;">
          <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
        <div class="x_content">
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <form class="form-inline">
                <div class="form-group">
                  <label style="margin-right: 10px;">Tgl. Picking List</label>
                  <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                  <label>-</label>
                  <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': '99-99-9999'" value="{{session('tglselesai')}}">
                </div>
              </form>
            </div>
            <div class="col-md-6 text-right">
              @can('orderpenjualan.tambah')
              <a href="{{route('orderpenjualan.tambah')}}" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah Order Penjualan - Ins</a>
              @endcan
              @can('orderpenjualan.ajuanupdate')
              <button type="button" id="btnSync11" data-id="skeyF11" class="btn btn-default">Sync 11 - F11</button>
              @endcan
            </div>
          </div>

          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th>Tgl. PiL</th>
                <th>No. PiL</th>
                <th>Tgl. SO</th>
                <th>No. SO</th>
                <th>No. ACC Piutang</th>
                <th>Toko</th>
                <th>Salesman</th>
                <th>Total Hrg SO ACC</th>
                <th>Ajuan Harga ke 11</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis" data-column="1">
                <i id="eye1" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl. SO
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="2">
                &nbsp;&nbsp;<i id="eye2" class="fa fa-eye"></i>&nbsp;&nbsp;No. SO
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="3">
                <i id="eye3" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl. PiL
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="4">
                &nbsp;&nbsp;<i id="eye4" class="fa fa-eye"></i>&nbsp;&nbsp;No. PiL
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="5">
                &nbsp;&nbsp;<i id="eye5" class="fa fa-eye"></i>&nbsp;&nbsp;No. ACC Piutang
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="6">
                &nbsp;&nbsp;<i id="eye6" class="fa fa-eye"></i>&nbsp;&nbsp;Toko
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="7">
                &nbsp;&nbsp;<i id="eye7" class="fa fa-eye"></i>&nbsp;&nbsp;Salesman
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="8">
                &nbsp;&nbsp;<i id="eye8" class="fa fa-eye"></i>&nbsp;&nbsp;Total Hrg SO ACC
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="9">
                &nbsp;&nbsp;<i id="eye9" class="fa fa-eye"></i>&nbsp;&nbsp;Ajuan Harga ke 11
              </a>
            </p>
          </div>
          {{-- <hr> --}}
          <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th width="40%">Barang</th>
                <th>Sat</th>
                <th>Qty SO ACC</th>
                <th>Hrg. Sat. Netto</th>
                <th>Hrg. Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis-2" data-column="1">
                <i id="eye-detail1" class="fa fa-eye"></i>&nbsp;&nbsp;Barang
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="2">
                &nbsp;&nbsp;<i id="eye-detail2" class="fa fa-eye"></i>&nbsp;&nbsp;Satuan
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="3">
                &nbsp;&nbsp;<i id="eye-detail3" class="fa fa-eye"></i>&nbsp;&nbsp;Qty SO ACC
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="4">
                &nbsp;&nbsp;<i id="eye-detail4" class="fa fa-eye"></i>&nbsp;&nbsp;Hrg Satuan Netto
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="5">
                &nbsp;&nbsp;<i id="eye-detail5" class="fa fa-eye"></i>&nbsp;&nbsp;Hrg Total
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Kewenangan -->
  <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
        </div>
        <form id="formKewenangan" action="{{route("orderpenjualan.kewenangan")}}" class="form-horizontal" method="post">
          <input type="hidden" id="orderIdHapus" value="">
          <input type="hidden" id="tipe" value="">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="text" id="uxserKewenangan" class="form-control" placeholder="Username" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="password" id="pxassKewenangan" class="form-control" placeholder="Password" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Proses</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of Form Kewenangan -->

  <!-- modal detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="labelModalDetail">Penjualan</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="orderpenjualanid" value="">
          <input type="hidden" id="orderIdDetail" value="">
          <input type="hidden" id="tipetransaksidetail" value="">
          <input type="hidden" id="statustokodetail" value="">
          <input type="hidden" id="tokoiddetail" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stockid">Barang <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                <input type="hidden" id="barangid">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
              <div class="form-group row">
                <div class="col-md-4 col-sm-4 col-xs-6">
                  <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6">
                  <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
                </div>
                <input type="hidden" id="kategoriPenjualan">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyso">Qty SO <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtyso" class="form-control hitungnetto" placeholder="Qty SO" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty SO ACC</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtysoacc" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuanbrutto">Harga Satuan Brutto <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgsatuanbrutto" class="form-control hitungnetto" placeholder="Harga Brutto" required="required">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgbmk">Harga AGR</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgbmk" class="form-control" readonly tabindex="-1">
                  <input type="hidden" id="arrhrgbmk">
                  <input type="hidden" id="hrgpricelist">
                  <input type="hidden" id="riwayatorder">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystockgudang">Qty Stock Gudang</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtystockgudang" class="form-control" value="0" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatandetail">Catatan</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="catatandetail" rows="3" class="form-control" placeholder="Catatan"></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-9 col-sm-9 col-xs-12 col-md-push-3 col-sm-push-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc1">Disc 1</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <input type="number" id="disc1" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 1" required="required" readonly>
                  {{-- <input type="number" id="disc1" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 1" required="required" {{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? "readonly" : ''}}> --}}
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
                  <input type="number" id="disc2" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 2" required="required" readonly>
                  {{-- <input type="number" id="disc2" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 2" required="required" {{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? "readonly" : ''}}> --}}
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
                  <input type="number" id="ppn" class="form-control" value="{{$ppn}}" readonly tabindex="-1">
                  <span class="input-group-addon" id="basic-addon1">%</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuannetto">Harga Satuan Netto</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgsatuannetto" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <p class="help-block muted">= Harga Setelah Disc 2 + PPN</p>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgtotalnetto">Harga Total Netto</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgtotalnetto" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer hidden">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal detail -->

  <!-- modal update -->
  <div class="modal fade" id="modalNoSo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Penjualan - Update No. SO Order Penjualan</h4>
        </div>
        <form class="form-horizontal" id="formNoSo" method="post">
          <input type="hidden" id="orderpenjualanid" name="id" value="">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noso_lama">No. SO Lama</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <input type="text" id="noso_lama" name="noso_lama" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noso">No. SO Baru</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <input type="text" id="noso" name="noso" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal update -->

  <!-- modal update -->
  <div class="modal fade" id="modalUpdateOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Pembelian - Update Order Pembelian</h4>
        </div>
        <form class="form-horizontal" id="formUpdateOrder" method="post">
          <input type="hidden" id="orderId" value="">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="c1">C1</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="c1" name="c1" class="form-control" readonly tabindex="-1">
                </div>
                <p class="help-block muted">Kode Cabang Omset</p>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="c2">C2 <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="c2" class="c2 form-control" placeholder="C2" autocomplete="off" autofocus required>
                    <input type="hidden" id="c2id" value="">
                  </div>
                </div>
                <p class="help-block muted">Kode Cabang Pengirim</p>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noso">No. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="noso" class="form-control" autocomplete="off" placeholder="No. SO" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglso">Tgl. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="tglso" class="tgl form-control" placeholder="Tgl. SO" data-inputmask="'mask': 'd-m-y'" required>
                </div>
                <p class="help-block muted">Tanggal Order diterima dari Toko</p>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nopickinglist">No. Picking List</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="nopickinglist" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglpickinglist">Tgl. Picking List</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="tglpickinglist" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="toko">Toko</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="toko" class="form-control" readonly tabindex="-1">
                  <input type="hidden" id="tokoid" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="alamat" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="kota" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="daerah">Kecamatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="kecamatan" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="wilid">WILID</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="wilid" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="idtoko">Toko ID</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="idtoko" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="statustoko">Status Toko</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="statustoko" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salesman">Salesman</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="salesman" class="form-control" autocomplete="off" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="expedisi" class="expedisi form-control" placeholder="Expedisi" autocomplete="off" required>
                    <input type="hidden" id="expedisiid" value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipetransaksi">Tipe Transaksi</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="tipetransaksi" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempo</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Nota</span>
                    <input type="number" id="temponota" class="form-control" readonly tabindex="-1">
                  </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Kirim</span>
                    <input type="number" id="tempokirim" class="form-control" readonly tabindex="-1">
                  </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Salesman</span>
                    <input type="number" id="temposalesman" class="form-control" readonly tabindex="-1">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatanpenjualan">Catatan Penjualan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpenjualan" class="form-control" placeholder="Catatan Penjualan">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatanpembayaran">Catatan Pembayaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpembayaran" class="form-control" placeholder="Catatan Pembayaran">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatanpengiriman">Catatan Pengiriman</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpengiriman" class="form-control" placeholder="Catatan Pengiriman">
                </div>
              </div>
            </div>
            <div class="row">

            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal update -->

  <!-- Data order penjualan -->
  <div class="modal fade" id="modalDataOrderPenjualan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Order Penjualan</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="orderData" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
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
  <!-- end of data order penjualan -->

  <!-- Data order penjualan detail -->
  <div class="modal fade" id="modalDataDetailOrderPenjualan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Order Penjualan Detail</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="orderDataDetail" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
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
  <!-- end of data order penjualan detail -->

  <!-- Modal DO KE OH -->
  <div class="modal fade" id="modalDoohInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Penjualan - Copy DO ke OH</h4>
        </div>
         <form id="formDoohInsert" class="form-horizontal" method="post">
          <input type="hidden" id="orderPenjualanDodoId" class="form-clear" value="" name="id">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class="form-group">
                <label>Tgl. Order</label>
                <input type="text" id="tglorder" name="tglorder" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
              </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                <label>Supplier <span class="required">*</span></label>
                
                  <input type="text" id="supplier" class="form-control col-md-7 col-xs-12" autofocus placeholder="Supplier" autocomplete="off" required="required" value="">
                  <input type="hidden" id="supplierid" name="supplierid" value="">
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>No. Order</label>
                  <input type="text" id="noorder" name="noorder" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>Tempo</label>
                  <div class="input-group">
                    <input type="number" id="tempo" name="tempo" class="form-control" value="0" min="0" required>
                    <span class="input-group-addon" id="basic-addon1">hari</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6">
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6">
                <label>Keterangan</label>
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="tableNpj" class="tableNpj table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th width="50%">Barang</th>
                      <th>Sat.</th>
                      <th>Qtyorder</th>
                      <th>Hrg. Sat Netto</th>
                      <th>Hrg. Total</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnSubmitDoInsert" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end Modal DO KE OH -->
  <!-- Modal DO KE DO -->
  <div class="modal fade" id="modalDodoInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Penjualan - Copy DO ke DO</h4>
        </div>
         <form id="formDodoInsert" class="form-horizontal" method="post">
          <input type="hidden" id="orderPenjualanDodoId" class="form-clear" value="" name="id">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c1">C1</label>
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <input type="text" id="c1" name="c1" class="form-control" value="" readonly tabindex="-1">
                  </div>
                  <p class="help-block muted">Kode Cabang Omset</p>
                </div>
                <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c2">C2 <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="c2" class="c2 form-control" value="" placeholder="C2" autocomplete="off" autofocus required readonly tabindex="-1"="">
                    <input type="hidden" id="c2id" name="c2" value="">
                  </div>
                </div>
                <p class="help-block muted">Kode Cabang Pengirim</p>
              </div>
              </div>
               <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="noso">No. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="noso" name="noso" class="form-control" autocomplete="off" placeholder="No. SO" required readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglso">Tgl. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="tglso" name="tglso" class="tgl form-control" placeholder="Tgl. SO" value="{{date('d-m-Y')}}" data-inputmask="'mask': 'd-m-y'" required readonly tabindex="-1">
                </div>
                <p class="help-block muted">Tanggal Order diterima dari Toko</p>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nopickinglist">No. Picking List</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="nopickinglist" name="nopickinglist" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglpickinglist">Tgl. Picking List</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="tglpickinglist" name="tglpickinglist" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="toko">Toko <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="toko" class="form-control" placeholder="Toko" autocomplete="off" required readonly tabindex="-1">
                    <input type="hidden" id="tokoid" name="tokoid">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="alamat">Alamat</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="alamat" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kota">Kota</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="kota" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="daerah">Kecamatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="kecamatan" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="wilid">WILID</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="wilid" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="idtoko">Toko ID</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="idtoko" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="statustoko">Status Toko</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="statustoko" class="form-control" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="salesman">Salesman <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="salesman" class="form-control" autocomplete="off" readonly tabindex="-1">
                    <input type="hidden" id="salesmanid">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="expedisi" class="expedisi form-control" placeholder="Expedisi" value="" autocomplete="off" required readonly tabindex="-1">
                    <input type="hidden" id="expedisiid" name="expedisiid" value="">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tipetransaksi">Tipe Transaksi <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control" name="tipetransaksi" id="tipetransaksi" required readonly tabindex="-1">
                    <option value="K2">K2</option>
                    <option value="K4" selected>K4</option>
                    <option value="KC">KC</option>
                    <option value="T2">T2</option>
                    <option value="T4">T4</option>
                    <option value="TC">TC</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tempo <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Nota</span>
                    <input type="number" id="temponota" name="temponota" class="form-control" value="0" readonly tabindex="-1">
                  </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Kirim</span>
                    <input type="number" id="tempokirim" name="tempokirim" class="form-control" value="0" readonly tabindex="-1">
                  </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon">Salesman</span>
                    <input type="number" id="temposalesman" name="temposalesman" class="form-control" value="0" readonly tabindex="-1">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatanpenjualan">Catatan Penjualan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpenjualan" name="catatanpenjualan" class="form-control" placeholder="Catatan Penjualan" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatanpembayaran">Catatan Pembayaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpembayaran" name="catatanpembayaran" class="form-control" placeholder="Catatan Pembayaran" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatanpengiriman">Catatan Pengiriman</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpengiriman" name="catatanpengiriman" class="form-control" placeholder="Catatan Pengiriman" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="tableNpjdo" class="tableNpjdo table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th width="50%">Barang</th>
                      <th>Sat.</th>
                      <th>Qty SO</th>
                      <th>Hrg. Sat Netto</th>
                      <th>Hrg. Total</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end Modal DO KE DO -->
@endsection

@push('scripts')
<script type="text/javascript">
  var sync      = false;
  var tipe_edit = null;
  var context_menu_nodata_state         = 'hide';
  var context_menu_number_state         = 'hide';
  var context_menu_text_state           = 'hide';
  var context_menu_nodata_state_detail  = 'hide';
  var context_menu_number_state_detail  = 'hide';
  var context_menu_text_state_detail    = 'hide';
  var last_index                        = '';
  var last_index_detail                 = '';
  var custom_search = [
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
  ];
  var custom_search_detail = [
    { text : '', filter : '=' },
    { text : '', filter : '=' },
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
  var column_index_detail  = 0;
  var table,table2,tabledataorder,tabledatadetailorder,table_index,table2_index,fokus;

  {{-- @include('lookupbarang') --}}

  // Run Lookup
  lookupsupplier();
  lookupsubcabang();
  lookupbarang('bmk','riwayatorder');
  lookupsalesman();
  lookupexpedisi();

  $(document).ready(function(){
    
    $(".tgl").inputmask();
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
    }).on('hidden.bs.modal', function () {
      var orderpenjualanid = $('#modalDetail #orderpenjualanid').val();
      $('#modalDetail').find('input').val('');
      $('#modalDetail').find('textarea').val('');
      // $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
      $('#modalDetail').find('#ppn').val({{$ppn}});
      $('#modalDetail').find('#disc1').val(0);
      $('#modalDetail').find('#disc2').val(0);
      $('#modalDetail').find('#qtystockgudang').val(0);
    });

    $('#modalKewenangan').on('shown.bs.modal', function () {
      $('#uxserKewenangan').focus();
    });

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
        url : '{{ route("orderpenjualan.data") }}',
        data: function ( d ) {
          d.custom_search = custom_search;
          d.tglmulai   = $('#tglmulai').val();
          d.tglselesai = $('#tglselesai').val();
          d.tipe_edit  = tipe_edit;
          d.issync     = sync;

          var now = new Date();
          now = now.getDate() + "-" + now.getMonth() + "-" + now.getFullYear();
          if (d.tglselesai.trim() == "") d.tglselesai = now;
          if (d.tglmulai.trim() == "") d.tglmulai = now;
        }
      },
      scrollY : 130,
      scrollX : true,
      scroller: {
        loadingIndicator: true
      },
      // stateLoadParams: function (settings, data) {
      //   for (var i = 0; i < data.columns.length; i++) {
      //     data.columns[i].search.search = "";
      //   }
      // },
      columns: [
        {
          render : function(data, type, row) {
            // console.log(row);
            var action = "";
            if (row.statusajuanhrg11 != null) {
              action += "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='"+row.id+"' onclick='ajuanharga(this,"+JSON.stringify(row)+")' data-message='"+row.ajuan+"' checked disabled></div>";
              // action += "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='"+JSON.stringify(row)+"' onclick='ajuanharga(this,"+JSON.stringify(row)+")' data-message='"+row.ajuan+"' checked disabled></div>";
              // action += "<div class='checkbox checkboxaction'><input type='checkbox' class='ajuanharga flat' value='"+JSON.stringify(row)+"' data-message='"+row.ajuan+"' checked disabled></div>";
            }else {
              action += "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='"+row.id+"' onclick='ajuanharga(this,"+JSON.stringify(row)+")' data-message='"+row.ajuan+"'></div>";
              // action += "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='"+JSON.stringify(row)+"' onclick='ajuanharga(this,"+JSON.stringify(row)+")' data-message='"+row.ajuan+"'></div>";
              // action += "<div class='checkbox checkboxaction'><input type='checkbox' class='ajuanharga flat' value='"+JSON.stringify(row)+"' data-message='"+row.ajuan+"'></div>";
            }

            action += "<div class='btn btn-success btn-xs no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah Detail - F1' onclick='tambahDetail(this,"+JSON.stringify(row)+")' data-message='"+row.add+"'><i class='fa fa-plus'></i></div>";
            action += "<div class='btn btn-warning btn-xs no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Edit - F2' onclick='update(this,"+JSON.stringify(row)+")' data-message='"+row.edit+"' data-tipe='header'><i class='fa fa-pencil'></i></div>";
            action += "<div class='btn btn-danger btn-xs no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='deleteOrder(this,"+JSON.stringify(row)+")' data-message='"+row.delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
            action += "<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>A <span class='caret'></span></button>";
            action += "<ul class='dropdown-menu'>";
            action += "<li><a href='#' onclick='updateNoSO(this,"+JSON.stringify(row)+")' data-message='"+row.updatenoso+"' class='skeyF6'>Update No. SO - F6</a></li>";
            action += "<li><a href='#' onclick='copyDOOH(this,"+JSON.stringify(row)+")' data-message='"+row.copydooh+"' class='skeyF7'>Copy OH - F7</a></li>";
            action += "<li><a href='#' onclick='copyDODO(this,"+JSON.stringify(row)+")' data-message='"+row.copydodo+"' class='skeyF8'>Copy PiL - F8</a></li>";
            action += "<li><a href='#' onclick='riwayatJual(this,"+JSON.stringify(row)+")' class='skeyF9'>Riwayat Jual - F9</a></li>";
            action += "<li><a href='#' onclick='batalPiL(this,"+JSON.stringify(row)+")' data-message='"+row.batalpil+"' data-tipe='header' class='skeyF4'>Batal PiL - F4</a></li>";
            action += "<li><a href='#' onclick='cetakPiL(this,"+JSON.stringify(row)+")' data-message='"+row.cetakpil+"' data-tipe='header' class='skeyF3'>Cetak PiL - F3</a></li>";
            action += "</ul></div>";
            return action;
          },
          "orderable" : false,
        },
        { "data" : "tglpickinglist", "className": "menufilter numberfilter" },
        { "data" : "nopickinglist", "className": "menufilter textfilter" },
        { "data" : "tglso", "className": "menufilter numberfilter" },
        { "data" : "noso", "className": "menufilter textfilter" },
        { "data" : "noaccpiutang", "className": "menufilter numberfilter" },
        { "data" : "tokonama", "className": "menufilter textfilter" },
        { "data" : "salesmannama", "className": "menufilter textfilter" },
        { "data" : "rpaccpiutang", "className": "text-right menufilter numberfilter" },
        { "data" : "statusajuanhrg11", "className": "menufilter textfilter" },
      ]
    });

    $('a.toggle-vis').on( 'click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
      $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
    });

    $('a.toggle-vis-2').on( 'click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table2.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
      $('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
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
        //   name: "Tipe",
        //   type: 'select',
        //   options: {1: 'Find', 2: 'Filter'},
        //   selected: 1
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
          context_menu_nodata_state = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          if(context_menu_nodata_state == "show")
          {
            // this is the trigger element
            var $this = this;
            // export states to data store
            var contextData = $.contextMenu.getInputValues(opt, $this.data());
            console.log('number');
            console.log(contextData);
            context_menu_nodata_state = 'hide';
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
          }
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
        //  name: "Tipe",
        //  type: 'select',
        //  options: {1: 'Find', 2: 'Filter'},
        //  selected: 1
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
          if(context_menu_number_state == "show")
          {
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
          }
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
        //  name: "Tipe",
        //  type: 'select',
        //  options: {1: 'Find', 2: 'Filter'},
        //  selected: 1
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
          if(context_menu_text_state == "show")
          {
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
          }
        },
      }
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
      order     : [[ 1, 'asc' ]],
      rowCallback: function(row, data, index) {
        if(data.batalpil){
          //birumuda
          $(row).addClass('abang');
        }
      },
      columns   : [
        {"data" : "action", "orderable" : false,},
        {"data" : "namabarang", "className" : "menufilter textfilter"},
        {"data" : "satuan", "className" : "menufilter textfilter"},
        {"data" : "qtysoacc", "className" : "text-right menufilter numberfilter",},
        {"data" : "hrgnetto", "className" : "text-right menufilter numberfilter",},
        {"data" : "hrgtotal", "className" : "text-right menufilter numberfilter",},
      ],
    });

    reloadDetail();

    $.contextMenu({
      selector: '#table2 tbody td.dataTables_empty',
      className: 'numberfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
            }
          }
        },
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
          context_menu_nodata_state_detail = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          if(context_menu_nodata_state_detail == "show")
          {
            var $this = this;
            var contextData = $.contextMenu.getInputValues(opt, $this.data());

            context_menu_nodata_state_detail = 'hide';
            column_index_detail = table2.column(this).index();
            if(column_index_detail != undefined) {
              last_index_detail = column_index_detail;
            }else {
              column_index_detail = last_index_detail;
            }
            custom_search_detail[column_index_detail].filter = filter_number[contextData.filter-1];
            custom_search_detail[column_index_detail].text = contextData.name;
          }
        },
      }
    });

    $.contextMenu({
      selector: '#table2 tbody td.menufilter.numberfilter',
      className: 'numberfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
            }
          }
        },
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
          context_menu_number_state_detail = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          if(context_menu_number_state_detail == "show")
          {
            var $this = this;
            var contextData = $.contextMenu.getInputValues(opt, $this.data());

            context_menu_number_state_detail = 'hide';
            column_index_detail = table2.column(this).index();
            if(column_index_detail != undefined) {
              last_index_detail = column_index_detail;
            }else {
              column_index_detail = last_index_detail;
            }
            custom_search_detail[column_index_detail].filter = filter_number[contextData.filter-1];
            custom_search_detail[column_index_detail].text = contextData.name;
          }
        },
      }
    });

    $.contextMenu({
      selector: '#table2 tbody td.menufilter.textfilter',
      className: 'textfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
            }
          }
        },
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
          context_menu_text_state_detail = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.textfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          if(context_menu_text_state_detail == "show")
          {
            var $this = this;
            var contextData = $.contextMenu.getInputValues(opt, $this.data());

            context_menu_text_state_detail = 'hide';
            column_index_detail = table2.column(this).index();

            if(column_index_detail != undefined) {
              last_index_detail = column_index_detail;
            }else {
              column_index_detail = last_index_detail;
            }

            var filter_index = parseInt(contextData.filter) - 1;
            custom_search_detail[column_index_detail].filter = filter_text[filter_index];
            custom_search_detail[column_index_detail].text = contextData.name.toUpperCase();
          }
        },
      }
    });    

    $(document.body).on("keydown", function(e){
      // console.log("key:", e.keyCode);
      if(e.keyCode == 13){

        $(".sweet-alert.showSweetAlert button.confirm").trigger("click");

        if(context_menu_nodata_state == 'show'){
          if($(".context-menu-list.numberfilter").length > 1)
          {
            $($(".context-menu-list.numberfilter")[0]).contextMenu("hide");
            table.ajax.reload();
          }
        }else if(context_menu_number_state == 'show'){
          if($(".context-menu-list.numberfilter").length > 1)
          {
            $($(".context-menu-list.numberfilter")[1]).contextMenu("hide");
            table.ajax.reload(null, false);
          }
        }else if(context_menu_text_state == 'show'){
          $(".context-menu-list.textfilter").contextMenu("hide");
          table.ajax.reload(null, false);
        }else if(context_menu_nodata_state_detail == 'show'){
          if($(".context-menu-list.numberfilter").length > 1)
          {
            $($(".context-menu-list.numberfilter")[2]).contextMenu("hide");
            reloadDetail();
          }
        }else if(context_menu_number_state_detail == 'show'){
          if($(".context-menu-list.numberfilter").length > 1)
          {
            $($(".context-menu-list.numberfilter")[3]).contextMenu("hide");
            reloadDetail();
          }
        }else if(context_menu_text_state_detail == 'show'){
          if($(".context-menu-list.textfilter").length > 1)
          {
            $($(".context-menu-list.textfilter")[1]).contextMenu("hide");
            reloadDetail();
          }
        }
      // }else if(e.keyCode == 45) {
      //   // Insert
      //   if($('.modal.in').length == 0) {
      //     e.preventDefault();
      //     window.location = $('#btnTambahHeader').attr('href');
      //     return false;
      //   }
      // }else if(e.keyCode == 32) {
      //   // Space
      //   if($('.modal.in').length == 0) {
      //     e.preventDefault();
      //     alert();
      //     return false;
      //   }
      // }else if(e.keyCode == 114) {
      //   // F3
      //   if($('.modal.in').length == 0) {
      //     e.preventDefault();
      //     alert();
      //     return false;
      //   }
      }
    });

    $('.tgl').change(function(){
      table.ajax.reload(null, false);
    });

    tableNpj = $('#tableNpj.tableNpj').DataTable({
      dom       : 'lrtp',
      paging    : false,
      ordering  : false,
      createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:eq(4)').prop('contenteditable', true);
      },
      footerCallback : function ( tfoot, data, start, end, display ) {
        // console.log(data);
        var total = 0;
        for (var i = 0; i < data.length; i++) {
          total += parseInt(data[i].hrgtotal);
        }
        $(tfoot).find('td').eq(1).html(total);
      },
      columns   : [
        {
          "data" : "barang"
        },
        {
          "data" : "satuan"
        },
        {
          "data" : "qtysoacc",
          "className" : "text-right"
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
          "className" : "text-right",
          render : function(data, type, row) {
            return parseInt(data).toLocaleString("id-ID");
          }
        },
      ]
    });
    tableNpjdo = $('#tableNpjdo').DataTable({
      dom     : 'lrtp',
      paging    : false,
      ordering  : false,
      createdRow  : function( row, data, dataIndex ) {
        $( row ).find('td:eq(4)').prop('contenteditable', true);
      },
      footerCallback : function ( tfoot, data, start, end, display ) {
        // console.log(data);
        var total = 0;
        for (var i = 0; i < data.length; i++) {
          total += parseInt(data[i].hrgtotal);
        }
        $(tfoot).find('td').eq(1).html(total);
      },
      columns   : [
        {
          "data" : "barang"
        },
        {
          "data" : "satuan"
        },
        {
          "data" : "qtysoacc",
          "className" : "text-right"
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
          "className" : "text-right",
          render : function(data, type, row) {
            return parseInt(data).toLocaleString("id-ID");
          }
        },
      ]
    });

    table.on('select', function ( e, dt, type, indexes ){
      var rowData = table.rows( indexes ).data().toArray();
      console.log(rowData);
      $("#headerid").val(rowData[0].id);
      reloadDetail();
    });
    
    // table2.on('select', function ( e, dt, type, indexes ){
    //   fokus       = 'detail';
    //   table2_index = indexes;
    // });

    tabledataorder = $('#orderData').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    tabledatadetailorder = $('#orderDataDetail').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    $('#table1 tbody').on('dblclick', 'tr', function(){
      var data = table.row(this).data();

      $.ajax({
        type: 'GET',
        url: '{{route("orderpenjualan.header")}}',
        data: {id: data.id},
        dataType: "json",
        success: function(data){
          tabledataorder.clear();
          tabledataorder.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'C1', '2':data.c1},
            {'0':'<div class="text-right">2.</div>', '1':'C2', '2':data.c2},
            {'0':'<div class="text-right">3.</div>', '1':'No. SO', '2':data.noso},
            {'0':'<div class="text-right">4.</div>', '1':'Tgl. SO', '2':data.tglso},
            {'0':'<div class="text-right">5.</div>', '1':'No. Picking List', '2':data.nopickinglist},
            {'0':'<div class="text-right">6.</div>', '1':'Tgl. Picking List', '2':data.tglpickinglist},
            {'0':'<div class="text-right">7.</div>', '1':'Toko', '2':data.tokonama},
            {'0':'<div class="text-right">8.</div>', '1':'Alamat', '2':data.tokoalamat},
            {'0':'<div class="text-right">9.</div>', '1':'Kota', '2':data.tokokota},
            {'0':'<div class="text-right">10.</div>', '1':'Kecamatan', '2':data.kecamatan},
            {'0':'<div class="text-right">11.</div>', '1':'WILID', '2':data.wilid},
            {'0':'<div class="text-right">12.</div>', '1':'Toko ID', '2':data.tokoid},
            {'0':'<div class="text-right">13.</div>', '1':'Status Toko', '2':data.statustoko},
            {'0':'<div class="text-right">14.</div>', '1':'Toko ID Warisan', '2':data.tokoidwarisan},
            {'0':'<div class="text-right">15.</div>', '1':'Salesman', '2':data.salesmannama},
            {'0':'<div class="text-right">16.</div>', '1':'Expedisi', '2':data.expedisinama},
            {'0':'<div class="text-right">17.</div>', '1':'Tipe Transaksi', '2':data.tipetransaksi},
            {'0':'<div class="text-right">18.</div>', '1':'Tempo Nota', '2':data.temponota},
            {'0':'<div class="text-right">19.</div>', '1':'Tempo Kirim', '2':data.tempokirim},
            {'0':'<div class="text-right">20.</div>', '1':'Tempo Salesman', '2':data.temposalesman},
            {'0':'<div class="text-right">21.</div>', '1':'No. ACC Piutang', '2':data.noaccpiutang},
            {'0':'<div class="text-right">22.</div>', '1':'Nama PKP', '2':data.namapkp},
            {'0':'<div class="text-right">23.</div>', '1':'Tgl. ACC Piutang', '2':data.tglaccpiutang},
            {'0':'<div class="text-right">24.</div>', '1':'Rp. ACC Piutang', '2':data.rpaccpiutang},
            {'0':'<div class="text-right">25.</div>', '1':'Rp. Saldo Piutang', '2':data.rpsaldopiutang},
            {'0':'<div class="text-right">26.</div>', '1':'Rp. Saldo Overdue', '2':data.rpsaldooverdue},
            {'0':'<div class="text-right">27.</div>', '1':'Rp. SO ACC Proses', '2':data.rpsoaccproses},
            {'0':'<div class="text-right">28.</div>', '1':'Rp. GIT', '2':data.rpgit},
            {'0':'<div class="text-right">29.</div>', '1':'Catatan Penjualan', '2':data.catatanpenjualan},
            {'0':'<div class="text-right">30.</div>', '1':'Catatan Pembayaran', '2':data.catatanpembayaran},
            {'0':'<div class="text-right">31.</div>', '1':'Catatan Pengiriman', '2':data.catatanpengiriman},
            {'0':'<div class="text-right">32.</div>', '1':'Print', '2':data.print},
            {'0':'<div class="text-right">33.</div>', '1':'Tgl. Print Picking List', '2':data.tglprintpickinglist},
            {'0':'<div class="text-right">34.</div>', '1':'Status Approval Overdue', '2':data.statusapprovaloverdue},
            {'0':'<div class="text-right">35.</div>', '1':'Tgl. Terima SO ke Piutang', '2':data.tglterimapilpiutang},
            {'0':'<div class="text-right">36.</div>', '1':'Status Ajuan Harga 11', '2':data.statusajuanhrg11},
            {'0':'<div class="text-right">37.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">38.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledataorder.draw();
          $('#modalDataOrderPenjualan').modal('show');
        }
      });
    });

    $('#table2 tbody').on('dblclick', 'tr', function(){
      var data = table2.row(this).data();
      console.log(data[6]);//untuk pop up grid

      $.ajax({
        type: 'POST',
        url: '{{route("orderpenjualan.detail.detail")}}',
        data: {id: data[6]},
        dataType: "json",
        success: function(data){
          tabledatadetailorder.clear();
          tabledatadetailorder.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'Barang', '2':data.namabarang},
            {'0':'<div class="text-right">2.</div>', '1':'Satuan', '2':data.satuan},
            {'0':'<div class="text-right">3.</div>', '1':'Qty SO', '2':data.qtyso},
            {'0':'<div class="text-right">4.</div>', '1':'Qty SO ACC', '2':data.qtysoacc},
            {'0':'<div class="text-right">5.</div>', '1':'Hrg Sat Brutto', '2':data.hrgsatuanbrutto},
            {'0':'<div class="text-right">6.</div>', '1':'Disc 1', '2':data.disc1},
            {'0':'<div class="text-right">7.</div>', '1':'Hrg Setelah Disc 1', '2':data.hrgdisc1},
            {'0':'<div class="text-right">8.</div>', '1':'Disc 2', '2':data.disc2},
            {'0':'<div class="text-right">9.</div>', '1':'Hrg Setelah Disc 2', '2':data.hrgdisc2},
            {'0':'<div class="text-right">10.</div>', '1':'PPN', '2':data.ppn},
            {'0':'<div class="text-right">11.</div>', '1':'Hrg Sat Netto', '2':data.hrgsatuannetto},
            {'0':'<div class="text-right">12.</div>', '1':'Hrg Total Netto', '2':data.hrgtotalnetto},
            {'0':'<div class="text-right">13.</div>', '1':'Hrg AGR', '2':data.hrgbmk},
            {'0':'<div class="text-right">14.</div>', '1':'No ACC 11', '2':data.noacc11},
            {'0':'<div class="text-right">15.</div>', '1':'Catatan', '2':data.catatan},
            {'0':'<div class="text-right">16.</div>', '1':'Qty Stock Gudang', '2':data.qtystockgudang},
            {'0':'<div class="text-right">17.</div>', '1':'Komisi Khusus 11', '2':data.komisikhusus11},
            {'0':'<div class="text-right">18.</div>', '1':'Link Pembelian', '2':data.linkpembelian},
            {'0':'<div class="text-right">19.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">20.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatadetailorder.draw();
          $('#modalDataDetailOrderPenjualan').modal('show');
        },
      });
    });

    $('.tbodySelect').on('click', 'tr', function(){
      $('.selected').removeClass('selected');
      $(this).addClass("selected");
    });

    $('.hitungnetto').on('keyup', function(){
      $('#qtysoacc').val($('#qtyso').val());
      hitung_netto();
    });

    $('#formUpdateOrder').submit(function(e){
      e.preventDefault();
      @cannot('orderpenjualan.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      tipe_edit = 'ubah';
      if ($('#c2id').val()=='') {
        $('#c2').focus();
        swal("Ups!", "C2 belum dipilih.", "error");
        return false;
      }
      if ($('#expedisiid').val()=='') {
        $('#expedisi').focus();
        swal("Ups!", "Expedisi belum dipilih.", "error");
        return false;
      }
      var data = {
        orderid           : $('#modalUpdateOrder #orderId').val(),
        c2                : $('#c2id').val(),
        noso              : $('#noso').val(),
        tglso             : $('#tglso').val(),
        expedisiid        : $('#expedisiid').val(),
        tempokirim        : $('#tempokirim').val(),
        catatanpenjualan  : $('#catatanpenjualan').val(),
        catatanpembayaran : $('#catatanpembayaran').val(),
        catatanpengiriman : $('#catatanpengiriman').val(),
      };
      $.ajax({
        type    : 'POST',
        url     : '{{route("orderpenjualan.ubah")}}',
        data    : {data: data},
        dataType: "json",
        success : function(data){
          $('#modalUpdateOrder').modal('hide');
          //window.location.reload();
          table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
              table.row('#'+table_index).select();
            },1000);
        },
      });
      @endcannot
    });

    // Cek Barang Order di hari yang sama dan toko sama
    $('#barangid').on('change input', function(){
      if($('#barangid').val() != '') {
        $.ajax({
          type: 'GET',
          url : '{{route("orderpenjualan.cekbarangopj")}}',
          data: {
            id     : $('#orderpenjualanid').val(),
            stockid: $('#barangid').val(),
          },
          success: function(respon){
            if(respon) {
              swal({
                  title: "Perhatian!",
                  text : "Sudah ada order <br> [ "+respon.namabarang+" ] <br> di "+respon.tglpickinglist+" : "+respon.nopickinglist+" <br> untuk toko [ "+respon.namatoko+" ]!<br><br>Anda yakin mau order dengan tanggal yg sama?",
                  type : "warning",
                  html : true,
                  showCancelButton  : true,
                  confirmButtonColor: '#DD6B55',
                  confirmButtonText : "Ya",
                  cancelButtonText  : "Tidak",
                  closeOnConfirm    : true,
                  closeOnCancel     : true
              },
              function(isConfirm) {
                  if (!isConfirm) {
                    $('#modalDetail').find("input[type!='hidden']").val('');
                    $('#modalDetail').find('#ppn').val({{$ppn}});
                    $('#modalDetail').find('#disc1').val(0);
                    $('#modalDetail').find('#disc2').val(0);
                    $('#modalDetail').find('#qtystockgudang').val(0);
                  }
              });
            }
          }
        });
      }
    });


    $('#qtyso').on('focusout', function(){
      var teks = "Harga A Rp. 000<br>Harga G Rp. 000<br>Harga R Rp. 000";
      
      if ($('#arrhrgbmk').val() !='') {
        var data = JSON.parse($('#arrhrgbmk').val());
        teks = "Harga A Rp. "+ currencyFormat(data.hrgb) +"<br>Harga G Rp. "+ currencyFormat(data.hrgm) +"<br>Harga R Rp. "+ currencyFormat(data.hrgk);
      }

      teks += "<br> ------";
      if ($('#riwayatorder').val() !='') {
        var data = JSON.parse($('#riwayatorder').val());
        teks += "<br> No Nota "+ data.nonota +"<br>Tgl. Nota "+ data.tglnota +"<br>Harga Netto Rp. "+ currencyFormat(data.hrgsatuannetto);
      }else{
        teks += "<br> No Nota -- <br>Tgl. Nota -- <br>Harga Netto Rp. 000";
      }

      swal({
        title: "Info",
        text: teks,
        type: "info",
        html: true
      },function(){
        setTimeout(function(){
          $('#hrgsatuanbrutto').focus();
        },0);
      });
    });
 
    $('#formDetail').submit(function(e){
      e.preventDefault();

      @cannot('orderpenjualan.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
        

        //=============== DIREMARKS HALIM. (REVISI / CHANGE REQUESTED) - DIGANTI DIBAWAHNYA ======////
        // tipe_edit = 'tambah';

        // var kdbarang = $("#modalDetail #barang").val();
        // var kdbarangSub = $("#modalDetail #kodebarang").val().substring(0,2);
        // var hrgnetto = parseInt($("#modalDetail #hrgsatuannetto").val());
        // var sttstoko = $('#modalDetail #statustokodetail').val();
        // var tipetran = $("#modalDetail #tipetransaksidetail").val().substring(0,1);
        // var pricelist = parseInt((isNaN($('#modalDetail #hrgpricelist').val()) ? 0 : $('#modalDetail #hrgpricelist').val()));
        // var kategori = parseInt((isNaN($('#modalDetail #kategoriPenjualan').val()) ? 0 : $('#modalDetail #kategoriPenjualan').val()));
        // var hrgb = 0, hrgm = 0, hrgk = 0;
        // if ($('#arrhrgbmk').val() !='') {
        //   var data = JSON.parse($('#arrhrgbmk').val());
        //   hrgb = data.hrgb;
        //   hrgm = data.hrgm;
        //   hrgk = data.hrgk;
        // }
        
        // if (sttstoko == 'K' && hrgnetto < hrgk && kdbarangSub == 'FE' && (kdbarang.includes('HCA') || kdbarang.includes('THM')))
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'K' && hrgnetto < hrgk && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'M' && hrgnetto < hrgm && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgm.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'B' && hrgnetto < hrgb && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgb.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'K' && tipetran == 'K' && kdbarangSub != 'FE' && hrgnetto < pricelist )
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + pricelist.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else{
        //   if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
        //     swal({
        //         title: "Perhatian!",
        //         text : "Harga satuan Netto lebih rendah dari harga AGR. Anda yakin?",
        //         type : "warning",
        //         showCancelButton  : true,
        //         confirmButtonColor: '#DD6B55',
        //         confirmButtonText : "Ya",
        //         cancelButtonText  : "Tidak",
        //         closeOnConfirm    : true,
        //         closeOnCancel     : true
        //     },
        //     function(isConfirm) {
        //         if (isConfirm) {
        //           checkSaveDetail();
        //         }else{
        //           // ajax_formdetail(false);
        //           $("#hrgsatuanbrutto").val(0);
        //           $("#hrgsatuanbrutto").focus();
        //           $("#hrgsatuanbrutto").select();
        //         }
        //     });
        //   }else{
        //     checkSaveDetail();
        //   }
        // }


        
        //=============GANTINYA DIMARI GAN..============
        tipe_edit = 'tambah';
        var hrgnetto = parseInt($("#modalDetail #hrgsatuannetto").val());
        var hrgbrutto = parseInt($("#modalDetail #hrgsatuanbrutto").val());
        var sttstoko = $('#modalDetail #statustokodetail').val();
        var tipetran = $("#modalDetail #tipetransaksidetail").val().substring(0,1);
        var pricelist = parseInt((isNaN($('#modalDetail #hrgpricelist').val()) ? 0 : $('#modalDetail #hrgpricelist').val()));
        var kategori = parseInt((isNaN($('#modalDetail #kategoriPenjualan').val()) ? 0 : $('#modalDetail #kategoriPenjualan').val()));
        var hrgb = 0, hrgm = 0, hrgk = 0;
        if ($('#arrhrgbmk').val() !='') {
          var data = JSON.parse($('#arrhrgbmk').val());
          hrgb = data.hrgb;
          hrgm = data.hrgm;
          hrgk = data.hrgk;
        }
        
        if ((sttstoko == "K" && tipetran == "K" && hrgnetto < hrgk) || (tipetran == "T" && hrgnetto < hrgb)){
          swal("Ups!", "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ".", "error");
          $("#qtyso").val(0);
          $("#qtysoacc").val(0);
          $("#catatandetail").val("TOLAK OTOMATIS");
          checkSaveDetail();
        }else if (sttstoko == "K" && tipetran == "T" && hrgnetto > hrgm && hrgnetto < hrgk){
          swal("Ups!", "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ".","error");
          $("#qtyso").val(0);
          $("#qtysoacc").val(0);
          $("#catatandetail").val("ACC OTOMATIS");
          checkSaveDetail();
        }else{
          if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
            swal({
                title: "Perhatian!",
                text : "Harga satuan Netto lebih rendah dari harga AGR. Anda yakin?",
                type : "warning",
                showCancelButton  : true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText : "Ya",
                cancelButtonText  : "Tidak",
                closeOnConfirm    : true,
                closeOnCancel     : true
            },
            function(isConfirm) {
                if (isConfirm) {
                  checkSaveDetail();
                }else{
                  // ajax_formdetail(false);
                  $("#hrgsatuanbrutto").val(0);
                  $("#hrgsatuanbrutto").focus();
                }
            });
          }else{
            checkSaveDetail();
          }
        }
      
      @endcannot
    });

    function checkSaveDetail(){
      $.ajax({
        type   : 'GET',
        url    : '{{route("orderpenjualan.cekbarang")}}',
        data   : {
          id     : $('#modalDetail #orderpenjualanid').val(),
          stockid: $('#modalDetail #barangid').val(),
        },
        success: function(respon){
          if(respon) {
            swal({
                title: "Perhatian!",
                text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
                type : "warning",
                showCancelButton  : true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText : "Ya",
                cancelButtonText  : "Tidak",
                closeOnConfirm    : true,
                closeOnCancel     : true
            },
            function(isConfirm) {
                if (isConfirm) {
                  $('#ppn').val(0);
                  $('#hrgsatuanbrutto').val(1).change();
                  hitung_netto();
                  ajax_formdetail(true);
                } else {
                  ajax_formdetail(false);
                }
            });
          }else{
            ajax_formdetail(true);
          }
        }
      });
    }
    //di remarks halim
    //
    // $('#formDetail').submit(function(e){
    //   e.preventDefault();
      
    //   @cannot('orderpenjualan.detail.tambah')
    //     swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    //   @else
    //   tipe_edit = 'tambah';
          // Cek Barang
      // if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
      //     swal({
      //         title: "Perhatian!",
      //         text : "Harga satuan Netto lebih rendah dari harga AGR. Anda yakin?",
      //         type : "warning",
      //         showCancelButton  : true,
      //         confirmButtonColor: '#DD6B55',
      //         confirmButtonText : "Ya",
      //         cancelButtonText  : "Tidak",
      //         closeOnConfirm    : true,
      //         closeOnCancel     : true
      //     },
      //     function(isConfirm) {
      //         if (isConfirm) {
      //           $.ajax({
      //             type   : 'GET',
      //             url    : '{{route("orderpenjualan.cekbarang")}}',
      //             data   : {
      //               id     : $('#modalDetail #orderpenjualanid').val(),
      //               stockid: $('#modalDetail #barangid').val(),
      //             },
      //             success: function(respon){
      //               if(respon) {
      //                 swal({
      //                     title: "Perhatian!",
      //                     text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
      //                     type : "warning",
      //                     showCancelButton  : true,
      //                     confirmButtonColor: '#DD6B55',
      //                     confirmButtonText : "Ya",
      //                     cancelButtonText  : "Tidak",
      //                     closeOnConfirm    : true,
      //                     closeOnCancel     : true
      //                 },
      //                 function(isConfirm) {
      //                     if (isConfirm) {
      //                       $('#ppn').val(0);
      //                       $('#hrgsatuanbrutto').val(1).change();
      //                       hitung_netto();
      //                       ajax_formdetail(true);
      //                     } else {
      //                       ajax_formdetail(false);
      //                     }
      //                 });
      //               }else{
      //                 ajax_formdetail(true);
      //               }
      //             }
      //           });
      //         }else{
      //           // ajax_formdetail(false);
      //           $("#hrgsatuanbrutto").val(0);
      //           $("#hrgsatuanbrutto").focus();
      //           $("#hrgsatuanbrutto").select();
      //         }
      //     });
      //   }else{
      //     $.ajax({
      //       type   : 'GET',
      //       url    : '{{route("orderpenjualan.cekbarang")}}',
      //       data   : {
      //         id     : $('#modalDetail #orderpenjualanid').val(),
      //         stockid: $('#modalDetail #barangid').val(),
      //       },
      //       success: function(respon){
      //         if(respon) {
      //           swal({
      //               title: "Perhatian!",
      //               text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
      //               type : "warning",
      //               showCancelButton  : true,
      //               confirmButtonColor: '#DD6B55',
      //               confirmButtonText : "Ya",
      //               cancelButtonText  : "Tidak",
      //               closeOnConfirm    : true,
      //               closeOnCancel     : true
      //           },
      //           function(isConfirm) {
      //               if (isConfirm) {
      //                 $('#ppn').val(0);
      //                 $('#hrgsatuanbrutto').val(1).change();
      //                 hitung_netto();
      //                 ajax_formdetail(true);
      //               } else {
      //                 ajax_formdetail(false);
      //               }
      //           });
      //         }else{
      //           ajax_formdetail(true);
      //         }
      //       }
      //     });
      //   }
        
    //  @endcannot
    // });

    function ajax_formdetail(lanjut){
      if(lanjut == true) {
        console.log(lanjut);
        $.ajax({
          type: 'POST',
          url: '{{route("orderpenjualan.detail.tambah")}}',
          dataType: 'json',
          data: {
            orderpenjualanid : $('#modalDetail #orderpenjualanid').val(),
            stockid          : $('#modalDetail #barangid').val(),
            qtyso            : $('#modalDetail #qtyso').val(),
            qtysoacc         : $('#modalDetail #qtysoacc').val(),
            hrgbmk           : $('#modalDetail #hrgbmk').val(),
            hrgsatuanbrutto  : $('#modalDetail #hrgsatuanbrutto').val(),
            disc1            : $('#modalDetail #disc1').val(),
            disc2            : $('#modalDetail #disc2').val(),
            ppn              : $('#modalDetail #ppn').val(),
            hrgsatuannetto   : $('#modalDetail #hrgsatuannetto').val(),
            qtystockgudang   : $('#modalDetail #qtystockgudang').val(),
            catatandetail    : $('#modalDetail #catatandetail').val(),
          },
          success: function(data){
            if(data.cek_opjd) {
              swal('Perhatian!', data.cek_opjd, 'warning');
            }


            // $('#modalDetail').modal('hide');
            var orderpenjualanid = $('#modalDetail #orderpenjualanid').val();

            $('#modalDetail').find('input').val('');
            $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
            $('#modalDetail').find('#ppn').val({{$ppn}});
            $('#modalDetail').find('#disc1').val(0);
            $('#modalDetail').find('#disc2').val(0);
            $('#modalDetail').find('#qtystockgudang').val(0);
            $('#modalDetail').find('#barang').focus();
            $('#modalDetail').find('#catatandetail').val('');

            table.ajax.reload(null, true);
            // tipe_edit = null;
            // setTimeout(function(){
            //   table.row('#'+table_index).select();
            // },1000);
          }
        });
      }else{
        $('#modalDetail').modal('hide');
        $('#modalDetail').find('input').val(''); 
        $('#modalDetail').find('#ppn').val({{$ppn}});
        $('#modalDetail').find('#disc1').val(0);
        $('#modalDetail').find('#disc2').val(0);
        $('#modalDetail').find('#qtystockgudang').val(0);
        $('#modalDetail').find('#catatandetail').val('');
      }
    }
   
    $('#formKewenangan').submit(function(e){
      e.preventDefault();
      tipe_edit = 'hapus';
      $.ajax({
        type: 'POST',
        url: '{{route("orderpenjualan.kewenangan")}}',
        data: {
          username    : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
          password    : $('#modalKewenangan #pxassKewenangan').val(),
          orderid     : $('#modalKewenangan #orderIdHapus').val(),
          tipe        : $('#modalKewenangan #tipe').val(),
          permission  : 'orderpenjualan.hapus',
        },
        dataType: "json",
        success: function(data){
          $('#modalKewenangan #uxserKewenangan').val('').change();
          $('#modalKewenangan #pxassKewenangan').val('').change();

          if(data.success){
            $('#modalKewenangan').modal('hide');
            swal('Sukses!', 'Data berhasil dihapus','success');
            //window.location.reload();
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
              table.row('#'+table_index).select();
            },1000);
          }else{
            swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
          }
        },
        error: function(data){
          console.log(data);
        }
      });
    });


    $('#btnSync11').on('click', function(){
      sync      = true;
      var count = 0;
      var data  = [];
      $('#table1').find('input[type="checkbox"]:checked:not(:disabled)').each(function () {
        count += 1;
        data.push($(this).val());
      });
      if (count > 0) {
        swal({
          title: "Konfirmasi",
          text: "Yakin akan melakukan ajuan ke 11?",
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
            url : '{{route("orderpenjualan.ajuanupdate")}}',
            data: { id : data},
            dataType: "json",
            success : function(data){
              table.ajax.reload(null, false);
              sync = false;
              swal('Berhasil!', 'Data berhasil di-sync','success');
            }
          });
        });
      }else {
        swal('Ups!', 'Tidak ada data yang dipilih','error');
      }
    });

  });

  function ajuanharga(e, data){
    var message = data.ajuan;
    var nopickinglist    = data.nopickinglist;
    tipe_edit   = 'ajuan';

    if (message == 'ajuan') {
      // Cek Markup
      $.ajax({
        type: 'GET',
        url: '{{route("orderpenjualan.cekmarkup")}}',
        data: {id: data.id},
        dataType: "json",
        success: function(data){
          if(!data){
            swal('Perhatian !', 'Picking List no. '+nopickinglist+' tidak perlu di sync ke 11.','success');
            setTimeout(function(){ $(e).iCheck('uncheck');}, 1);
            return false;
          }else{
            return true;
          }
        },
        error: function(data){
          console.log(data);
        }
     });
    }else{
      swal('Ups!', message,'error');
      setTimeout(function(){ $(e).iCheck('uncheck');}, 1);
      return false;
    }
  }

  // function ajuanharga(e, data){
  //   var message = $(e).data('message');
  //   tipe_edit   = 'ajuan';

  //   if (message == 'ajuan') {
  //       var id   = data.id;
  //       var noso = data.noso;
  //       $.ajaxSetup({
  //         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
  //       });
  //       $.ajax({
  //         type: 'POST',
  //         url: '{{route("orderpenjualan.ajuanupdate")}}',
  //         data: {id: id},
  //         dataType: "json",
  //         success: function(data){
  //           if(data.success){
  //              swal('Ok!', 'SO no. '+noso+' berhasil diajukan ke 11. Tunggu jawaban dari 11.','success');
  //              window.setTimeout(function(){ 
  //                  location.reload();
  //              } ,3000);
  //           }else{
  //           swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
  //           }
  //         },
  //         error: function(data){
  //           console.log(data);
  //         }
  //      });
  //   }else {
  //     swal('Ups!', message,'error');
  //     window.setTimeout(function(){ 
  //       location.reload();
  //     } ,3000);
  //   }
  // }

  function updateNoSO(e, data){
    var message = data.updatenoso;
    if (message == 'updatenoso') {
      console.log(data);

      @cannot('orderpenjualan.updatenoso')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      $('#modalNoSo #orderpenjualanid').val(data.id);
      $('#modalNoSo #noso_lama').val(data.noso);
      $('#modalNoSo').modal('show').on('shown.bs.modal', function () {
        $('#modalNoSo #noso').focus();
      });
      @endcannot
    }else {
      swal('Ups!', message,'error');
    }
  }

  $('#formNoSo').submit(function(e){
    e.preventDefault();
    @cannot('orderpenjualan.updatenoso')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    $("#formNoSo #noso").val($("#formNoSo #noso").val().toUpperCase());
    $.ajax({
      headers : { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
      type    : 'POST',
      url     : '{{route("orderpenjualan.updatenoso")}}',
      data    : $('#formNoSo').serialize(),
      dataType: "json",
      success : function(data){
        if(data.success){
          $('#modalNoSo').find('input:text').val("");
          $('#modalNoSo').modal('hide');
          swal({
              title: "Sukses!",
              text : "Update No. SO Berhasil",
              type : "success",
              html : true
          },function(){
              table.ajax.reload(null, true);
              tipe_edit = null;
              setTimeout(function(){
                  // table.row('#'+table_index).deselect();
                  table.row('#'+table_index).select();
                  table.row('#'+table_index).scrollTo(false);
              },1000);
          });
        }else{
          swal("Ups!", "Gagal update No SO", "error");
        }
      },
      error:function(data)
      {
        swal("Ups!", "Gagal update No SO", "error");
      }
    });
    @endcannot
  });

  function tambahDetail(e, data){
    var message = data.add;
    if (message == 'add') {
      console.log(data);

      @cannot('orderpenjualan.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      $('#modalDetail #orderpenjualanid').val(data.id);
      $('#modalDetail #orderIdDetail').val('');
      $('#modalDetail #tipetransaksidetail').val(data.tipetransaksi);
      $('#modalDetail #statustokodetail').val(data.statustoko);
      $('#modalDetail #tokoiddetail').val(data.tokoid);
      $('#modalDetail #kodebarang').val('');
      $('#modalDetail #barang').val('');
      $('#modalDetail #barangid').val('');
      $('#modalDetail #satuan').val('');
      $('#modalDetail #qtyso').val('');
      $('#modalDetail #qtysoacc').val('');
      $('#modalDetail #hrgsatuanbrutto').val('');
      $('#modalDetail #disc1').val(0);
      $('#modalDetail #hrgdisc1').val('');
      $('#modalDetail #disc2').val(0);
      $('#modalDetail #hrgdisc2').val('');
      $('#modalDetail #ppn').val('{{$ppn}}');
      $('#modalDetail #hrgsatuannetto').val('');
      $('#modalDetail #hrgtotalnetto').val('');
      $('#modalDetail #hrgbmk').val('');
      $('#modalDetail #arrhrgbmk').val('');
      $('#modalDetail #qtystockgudang').val(0);
      $('#modalDetail #catatandetail').val('');
      $('#modalDetail #kategoriPenjualan').val('');
      $('#modalDetail #hrgpricelist').val('');

      $('#labelModalDetail').text('Penjualan - Tambah Order Penjualan Detail');
      $('#modalDetail').modal('show');
      @endcannot
    }else {
      swal('Ups!', message,'error');
    }
  }

  function update(e, data){
    var message = data.edit;
    if(message == 'edit') {
      @cannot('orderpenjualan.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      var id = data.id;
      $.ajax({
        type: 'GET',
        url: '{{route("orderpenjualan.header")}}',
        data: {id: id},
        dataType: "json",
        success: function(data){
          $('#modalUpdateOrder #orderId').val(id);
          $('#modalUpdateOrder #c1').val(data.c1);
          $('#modalUpdateOrder #c2').val(data.c2);
          $('#modalUpdateOrder #c2id').val(data.c2id);
          $('#modalUpdateOrder #noso').val(data.noso);
          $('#modalUpdateOrder #tglso').val(data.tglso);
          $('#modalUpdateOrder #nopickinglist').val(data.nopickinglist);
          $('#modalUpdateOrder #tglpickinglist').val(data.tglpickinglist);
          $('#modalUpdateOrder #toko').val(data.tokonama);
          $('#modalUpdateOrder #tokoid').val(data.tokoid);
          $('#modalUpdateOrder #alamat').val(data.tokoalamat);
          $('#modalUpdateOrder #kota').val(data.tokokota);
          $('#modalUpdateOrder #kecamatan').val(data.kecamatan);
          $('#modalUpdateOrder #wilid').val(data.wilid);
          $('#modalUpdateOrder #idtoko').val(data.tokoid);
          $('#modalUpdateOrder #statustoko').val(data.statustoko);
          $('#modalUpdateOrder #salesman').val(data.salesmannama);
          $('#modalUpdateOrder #expedisi').val(data.expedisinama);
          $('#modalUpdateOrder #expedisiid').val(data.expedisiid);
          $('#modalUpdateOrder #tipetransaksi').val(data.tipetransaksi);
          $('#modalUpdateOrder #temponota').val(data.temponota);
          $('#modalUpdateOrder #tempokirim').val(data.tempokirim);
          $('#modalUpdateOrder #temposalesman').val(data.temposalesman);
          $('#modalUpdateOrder #catatanpenjualan').val(data.catatanpenjualan);
          $('#modalUpdateOrder #catatanpembayaran').val(data.catatanpembayaran);
          $('#modalUpdateOrder #catatanpengiriman').val(data.catatanpengiriman);
        },
        error: function(data){
          console.log(data);
        }
      });

      $('#modalUpdateOrder').modal('show');
      @endcannot
    }else {
      swal('Ups!', message,'error');
    }
  }

  function deleteOrder(e, data){
    var message = $(e).data('message');
    var tipe = $(e).data('tipe');

    console.log(message);
    if (message == 'auth') {
      if (tipe == 'header') {
        $('#modalKewenangan #orderIdHapus').val(data.id);
      }else {
        $('#modalKewenangan #orderIdHapus').val(data);
      }
      $('#modalKewenangan #tipe').val(tipe);
      $('#modalKewenangan').modal('show');
    }else {
      swal('Ups!',message,'error');
    }
  }

  function copyDOOH(e, data){
    @cannot('orderpenjualan.insertdooh')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if(message != "copydooh")
    {
      swal('Ups!', message,'error'); return false;
    }
    else
    {
      swal({
        title: "Konfirmasi",
        text: "Mau copy and paste PiL nomor "+ data.nopickinglist +" tanggal "+ data.tglpickinglist +" ke OH?",
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      },
      function () {
        $.ajax({
          type: 'GET',
          url: '{{route("orderpenjualan.copydooh")}}',
          data: {id: data.id},
          dataType: "json",
          success: function(data){
            swal.close();
            
            clear_column('modalDoohInsert');
            $("#modalDoohInsert #tglorder").val();
            $('#modalDoohInsert #noorder').val();
            $('#modalDoohInsert #supplier').val(data.supplierdefault.nama);
            $('#modalDoohInsert #supplierid').val(data.supplierdefault.id);
            $('#modalDoohInsert #tempo').val();
            $('#modalDoohInsert #keterangan').val("OH dari PiL "+ data.order.nopickinglist +" tanggal "+ data.order.tglpickinglist);
            $('#orderPenjualanDodoId').val(data.order.id);
           
            tableNpj.clear();
            for (var i = 0; i < data.barang.length; i++) {
              tableNpj.row.add({
                barang         : data.barang[i].namabarang,
                satuan         : data.barang[i].satuan,
                qtysoacc       : data.barang[i].qtysoacc,
                hrgsatuannetto : data.barang[i].hrgsatuannetto,
                hrgtotal       : data.barang[i].hrgtotal,
                id             : data.barang[i].id,
              });
            }
            tableNpj.draw();

            $('#modalDoohInsert').modal('show');
            $('#modalDoohInsert').on('shown.bs.modal', function() {
              tableNpj.columns.adjust().draw();
            });
          }
        });
      });
    }
    @endcannot
  }

   $('#formDoohInsert').submit(function(e){
      e.preventDefault();
      @cannot('orderpenjualan.insertdooh')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      $.ajax({
        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
        type   : 'POST',
        url    : '{{route("orderpenjualan.insertdooh")}}',
        data   : {
          supplierid: $('#modalDoohInsert #supplierid').val(),
          keterangan: $('#modalDoohInsert #keterangan').val(),
          tempo     : $('#modalDoohInsert #tempo').val(),
          id        : $('#orderPenjualanDodoId').val(),
        },
        dataType: "json",
        success: function(data){
          console.log(data);
          if(data.success){
              swal("OK!", data.message, "success");
              $('#modalDoohInsert').modal('hide');
              window.location.reload();
          }else{
            swal("Ups!", "Gagal Copy Do Ke OH", "error");
          }
        },
        error:function(data)
        {
          swal("Ups!", "Gagal Copy Do Ke OH", "error");
        }
      });
      @endcannot
    });

  // modifier : halim
  // 17 Jan 2018
  // cegatan copy PiL hanya bisa dilakukan sekali
  function copyDODO(e,data){
    @cannot('orderpenjualan.insertdodo')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if(message != "copydodo")
    {
      swal('Ups!', message,'error'); return false;
    }
    else
    {

      $.ajax({
        type : 'GET',
        url  : '{{route("orderpenjualan.cekpil")}}',
        data : {id: data.id},
        dataType: "json",
        success : function(cek){
          if (cek.success == true){
            swal('Ups!', 'CopyPiL hanya bisa dilakukan sekali.','error');
          }else{
            swal({
              title: "Konfirmasi",
              text: "Mau copy and paste PiL nomor "+ data.nopickinglist +" tanggal "+ data.tglpickinglist +" ke PiL baru?",
              type: "info",
              showCancelButton: true,
              closeOnConfirm: false,
              showLoaderOnConfirm: true
            }, function () {
              $.ajax({
                type: 'GET',
                url: '{{route("orderpenjualan.copydodo")}}',
                data: {id: data.id},
                dataType: "json",
                success: function(data){
                  swal.close();
                  clear_column('modalDodoInsert');
                  $("#modalDodoInsert #c1").val(data.subcabanguser.kodesubcabang);
                  $('#modalDodoInsert #c2').val(data.subcabanguser.kodesubcabang);
                  $('#modalDodoInsert #c2id').val(data.subcabanguser.id);
                  $('#modalDodoInsert #noso').val(data.order.noso);
                  $('#modalDodoInsert #tglpickinglist').val(data.order.tglpickinglist);
                  $('#modalDodoInsert #nopickinglist').val();
                  $('#modalDodoInsert #toko').val(data.order.toko);
                  $('#modalDodoInsert #tokoid').val(data.order.tokoid);
                  $('#modalDodoInsert #alamat').val(data.order.alamat);
                  $('#modalDodoInsert #kota').val(data.order.kota);
                  $('#modalDodoInsert #kecamatan').val(data.order.kecamatan);
                  $('#modalDodoInsert #wilid').val(data.order.wilid);
                  $('#modalDodoInsert #idtoko').val(data.order.tokoid);
                  $('#modalDodoInsert #tipetransaksi').val(data.order.tipetransaksi);
                  $('#modalDodoInsert #statustoko').val(data.order.statustoko);
                  $('#modalDodoInsert #salesman').val(data.order.salesman);
                  $('#modalDodoInsert #salesmanid').val(data.order.salesmanid);
                  $('#modalDodoInsert #expedisi').val(data.order.namaexpedisi);
                  $('#modalDodoInsert #expedisiid').val(data.order.idexpedisi);
                  $('#modalDodoInsert #temponota').val(data.order.temponota);
                  $('#modalDodoInsert #tempokirim').val(data.order.tempokirim);
                  $('#modalDodoInsert #temposalesman').val(data.order.temposalesman);
                  $('#modalDodoInsert #catatanpenjualan').val(data.order.catatanpenjualan + " PiL Copy Dari PiL " + data.order.nopickinglist + " tanggal " + data.order.tglpickinglist );
                  $('#modalDodoInsert #catatanpembayaran').val(data.order.catatanpembayaran);
                  $('#modalDodoInsert #catatanpengiriman').val(data.order.catatanpengiriman);
                  $('#orderPenjualanDodoId').val(data.order.id);

                  tableNpjdo.clear();
                  for (var i = 0; i < data.barang.length; i++) {
                    tableNpjdo.row.add({
                      barang         : data.barang[i].namabarang,
                      satuan         : data.barang[i].satuan,
                      qtysoacc       : data.barang[i].qtysoacc,
                      hrgsatuannetto : data.barang[i].hrgsatuannetto,
                      hrgtotal       : data.barang[i].hrgtotal,
                      id             : data.barang[i].id,
                    });
                  }
                  tableNpjdo.draw();
                  $('#modalDodoInsert').modal('show');
                  $('#modalDodoInsert').on('shown.bs.modal', function() {
                    tableNpjdo.columns.adjust().draw();
                  });
                }
              });
            });
          }
        }
      });
    }
    @endcannot
  }

  $('#formDodoInsert').submit(function(e){
      e.preventDefault();
      @cannot('orderpenjualan.insertdodo')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      $.ajax({
        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
        type: 'POST',
        url: '{{route("orderpenjualan.insertdodo")}}',
        data: {
          id          : $('#orderPenjualanDodoId').val(),
        },
        dataType: "json",
        success: function(data){
          console.log(data);
          if(data.success){
              swal("OK!", data.message, "success");
              $('#modalDodoInsert').modal('hide');
              window.location.reload();
          }else{
            swal("Ups!", "Gagal Copy Do Ke DO", "error");
          }
        },
        error:function(data)
        {
          swal("Ups!", "Gagal Copy Do Ke DO", "error");
        }
      });
      @endcannot
    });

  function riwayatJual(e,data){
      window.open('{{ route('orderpenjualan.riwayatjual',null)}}/'+data.tokoid, '_blank'); 
  }
  function cetakPiL(e,data){
    var id = data.id;
    var message = $(e).data('message');
    if(message == 'cetakpil'){
      @cannot('orderpenjualan.cetakpickinglist')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
          $.ajaxSetup({
              headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
          $.ajax({
              type: 'POST',
              data: {id: data},
              dataType: "json",
              url: '{{route("orderpenjualan.detail.cekkadaluwarsa")}}',
              success: function(data){
                if(data.message != 'skip'){
                  console.log('sini');
                  swal('Ups!', data.message,'error'); return false;
                }else{
                  window.open('{{ route('orderpenjualan.cetakpickinglist',null)}}/'+id, '_blank');
                }
              },
              error: function(data){
                alert(data.message)
              }
          });
        window.open('{{ route('orderpenjualan.cetakpickinglist',null)}}/'+data.id, '_blank');
      @endcannot
    }else{
      swal('Ups!',message,'error');
    }
  }

  function batalPiL(e,data){
    var message = data.batalpil;
    if (message == 'batalpil') {
      @cannot('orderpenjualan.batalpil')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      var id = data.id;
      $.ajax({
        type: 'GET',
        url: '{{route("orderpenjualan.batalpil")}}',
        data: {id: id},
        dataType: "json",
        success: function(data){
          swal('Berhasil!','Seluruh record Picking List berhasil dibatalkan','success');
          table.ajax.reload(null, true);
          tipe_edit = null;
          setTimeout(function(){
              // table.row('#'+table_index).deselect();
              table.row('#'+table_index).select();
              table.row('#'+table_index).scrollTo(false);
          },1000);
        },
        error: function(data){
          console.log(data);
        }
      });
      @endcannot
    }else {
      swal('Ups!',message,'error');
    }
  }

  function batalPiLDetail(e,data){
    console.log(data);
    var message = $(e).data("message");
    if (message == 'batalpil') {
      @cannot('orderpenjualan.batalpildetail')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      var id = data;
      $.ajax({
        type: 'GET',
        url: '{{route("orderpenjualan.batalpildetail")}}',
        data: {id: id},
        dataType: "json",
        success: function(data){
          swal('Berhasil!','Batal record berhasil','success');
          table.ajax.reload(null, true);
          tipe_edit = null;
          setTimeout(function(){
              // table.row('#'+table_index).deselect();
              table.row('#'+table_index).select();
              table.row('#'+table_index).scrollTo(false);
          },1000);
        },
        error: function(data){
          console.log(data);
        }
      });
      @endcannot
    }else {
      swal('Ups!',message,'error');
    }
  }

  function reloadDetail()
  {
    // Load Detail Lama
    $.ajaxSetup({
      headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
    });

    $.ajax({
      type: 'POST',
      data: {
        id            : $("#headerid").val(),
        custom_search : custom_search_detail,
      },
      dataType: "json",
      url: '{{route("orderpenjualan.detail.data")}}',
      success: function(data){
        // table2.clear();
        // for (var i = 0; i < data.node.length; i++) {
        //   table2.row.add([
        //     '<div class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="deleteOrder(this,'+data.node[i][5]+')" data-message="'+data.node[i][6]+'" data-tipe="detail"><i class="fa fa-trash"></i></div>'+
        //     '<div class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Batal Picking List" onclick="batalPiLDetail(this,'+data.node[i][5]+')" data-message="'+data.node[i][7]+'" data-tipe="detail"><i class="fa fa-times"></i></div>',
        //     data.node[i][0],
        //     data.node[i][1],
        //     data.node[i][2],
        //     data.node[i][3],
        //     data.node[i][4],
        //     data.node[i][5],
        //     data.node[i][8],
        //   ]);
        // }
        // table2.draw();
        table2.clear();
        table2.rows.add(data.node);
        table2.draw();
      },
      error: function(data){
        console.log(data);
      }
    });
  }

  function hitung_netto(){
    var qtyso = Number($('#qtyso').val());
    var bruto = Number($('#hrgsatuanbrutto').val());
    var disc1 = Number($('#disc1').val())/100;
    var hrgdisc1 = Math.round((1 - disc1) * bruto, 2);
    var disc2 = Number($('#disc2').val())/100;
    var hrgdisc2 = Math.round((1 - disc2) * hrgdisc1, 2);
    var ppn = Number($('#ppn').val())/100;
    var hrgsatuannetto = Math.round((1 + ppn) * hrgdisc2, 2);
    $('#hrgdisc1').val(hrgdisc1);
    $('#hrgdisc2').val(hrgdisc2);
    $('#hrgsatuannetto').val(hrgsatuannetto);
    $('#hrgtotalnetto').val(hrgsatuannetto * qtyso);
  }

  function currencyFormat(num){
    return num.toString()
     .replace(".", ",") // replace decimal point character with ,
     .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // use . as a separator
  }

  function clear_column(id){
    for (var i = 0; i < $('#'+id+' .form-clear').length; i++) {
      element = $('#'+id+' .form-clear')[i];
      $(element).val('');
    }
  }

</script>
@endpush
