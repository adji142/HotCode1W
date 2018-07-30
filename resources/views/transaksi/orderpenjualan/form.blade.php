@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orderpenjualan.index') }}">Order Penjualan</a></li>
    <li class="breadcrumb-item active">Tambah Data</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tambah Order Penjualan - {{$subcabanguser->kodesubcabang}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @can('orderpenjualan.tambah')
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="{{route('orderpenjualan.tambah')}}">
          @else
          <form class="form-horizontal form-label-left" method="POST" action="#">
          @endcan
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c1">C1</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="c1" name="c1" class="form-control" value="{{$subcabanguser->kodesubcabang}}" readonly tabindex="-1">
                </div>
                <p class="help-block muted">Kode Cabang Omset</p>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="c2">C2 <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="c2" class="form-control" value="{{$subcabanguser->kodesubcabang}}" placeholder="C2" autocomplete="off" autofocus required>
                    <input type="hidden" id="c2id" name="c2" value="{{$subcabanguser->id}}">
                  </div>
                </div>
                <p class="help-block muted">Kode Cabang Pengirim</p>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="noso">No. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="noso" name="noso" class="form-control" autocomplete="off" placeholder="No. SO" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglso">Tgl. SO <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="tglso" name="tglso" class="tgl form-control" placeholder="Tgl. SO" value="{{date('d-m-Y')}}" data-inputmask="'mask': 'd-m-y'" required>
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
                    <input type="text" id="toko" class="form-control" placeholder="Toko" autocomplete="off" required>
                    <input type="hidden" id="tokoid" name="tokoid">
                    {{-- <input type="hidden" id="tokoid" name="tokoid"> --}}
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
                    <input type="text" id="salesman" value="{{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? auth()->user()->karyawan->kodesales : ''}}" class="form-control" autocomplete="off" {{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? 'readonly' : ''}}>
                    <input type="hidden" id="salesmanid" value="{{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? auth()->user()->karyawan->id : ''}}">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="expedisi">Expedisi <span class="required">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="expedisi" class="form-control" placeholder="Expedisi" value="{{isset($expedisi)? $expedisi->namaexpedisi:''}}" autocomplete="off" required>
                    <input type="hidden" id="expedisiid" name="expedisiid" value="{{isset($expedisi)? $expedisi->id:''}}">
                  </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="kodeexpedisi" class="form-control" value="{{isset($expedisi)? $expedisi->kodeexpedisi:''}}" autocomplete="off" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tipetransaksi">Tipe Transaksi <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control" name="tipetransaksi" id="tipetransaksi" required>
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
                  <input type="text" id="catatanpenjualan" name="catatanpenjualan" class="form-control" placeholder="Catatan Penjualan">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatanpembayaran">Catatan Pembayaran</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpembayaran" name="catatanpembayaran" class="form-control" placeholder="Catatan Pembayaran">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatanpengiriman">Catatan Pengiriman</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatanpengiriman" name="catatanpengiriman" class="form-control" placeholder="Catatan Pengiriman">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-offset-2 col-sm-offset-2 col-md-6 col-sm-6">
                  @can('orderpenjualan.tambah')
                  <button type="submit" id="btnSimpan" class="btn btn-success">Simpan</button>
                  @endcan
                  @can('orderpenjualan.detail.tambah')
                  <button type="button" id="btnTambah" class="btn btn-default" data-toggle="modal" data-target="#modalDetail"><i class="fa fa-plus"></i> Tambah Detail</button>
                  @endcan
                </div>
              </div>
            </div>
          </form>
          <table id="tableDetail" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" width="25%">Barang</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Qty SO</th>
                <th class="text-center">Harga Satuan Netto</th>
                <th class="text-center">Harga Total</th>
              </tr>
            </thead>
            <tbody id="tbodyDetail"></tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">Total</th>
                <th class="text-right" id="txt_total">Rp. 0</th>
              </tr>
            </tfoot>
          </table>
          <div class="form-group">
            <div class="ln_solid"></div>
            <div class="col-md-6">
              <a href="{{route('orderpenjualan.index')}}" class="btn btn-primary">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @can('orderpenjualan.detail.tambah')
  <!-- modal tambah detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Tambah Detail Order Penjualan</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="orderpenjualanid" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                <input type="hidden" id="barangid">
                <input type="hidden" id="arrhrgbmk">
                <input type="hidden" id="hrgpricelist">
                <input type="hidden" id="riwayatorder">
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
                  <input type="hidden" id="kategoriPenjualan">
                </div>
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
  <!-- end of modal tambah detail -->
  @endcan
@endsection

@push('scripts')
  <script type="text/javascript">
    var table1;

    {{-- @include('lookupbarang') --}}

    // Run Lookup
    lookuptoko();
    lookupsubcabang();
    lookupexpedisi();
    lookupsalesman();
    lookupbarang('bmk','riwayatorder');

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
      }).on('hidden.bs.modal', function () {
        var orderpenjualanid = $('#modalDetail #orderpenjualanid').val();
        $('#modalDetail').find('input').val('');
        $('#modalDetail').find('textarea').val('');
        $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
        $('#modalDetail').find('#ppn').val({{$ppn}});
        $('#modalDetail').find('#disc1').val(0);
        $('#modalDetail').find('#disc2').val(0);
        $('#modalDetail').find('#qtystockgudang').val(0);
      });

      $('#btnTambah').on('click', function(){
        if ($('#orderpenjualanid').val()=='') {
          swal("Ups!", "Simpan Order terlebih dulu.", "error");
          return false;
        }else {
          return true;
        }
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
                      var orderpenjualanid = $('#modalDetail #orderpenjualanid').val();
                      $('#modalDetail').find('input').val('');
                      $('#modalDetail').find('textarea').val('');
                      $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
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

      $('#qtyso').on('blur', function(){
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

      $('.tbodySelect').on('click', 'tr', function(){
        $('.selected').removeClass('selected');
        $(this).addClass("selected");
      });

      $('.hitungnetto').on('keyup', function(){
        $('#qtysoacc').val($('#qtyso').val());
        hitung_netto();
      });

      $('#formTambah').submit(function(e){
        e.preventDefault();
        @cannot('orderpenjualan.tambah')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        if ($('#c2id').val()=='') {
          $('#c2').focus();
          swal("Ups!", "C2 belum dipilih.", "error");
          return false;
        }else if ($('#tokoid').val()=='') {
          $('#toko').focus();
          swal("Ups!", "Toko belum dipilih.", "error");
          return false;
        }else if ($('#salesmanid').val()=='') {
          $('#salesman').focus();
          swal("Ups!", "Salesman belum dipilih.", "error");
          return false;
        }else if ($('#expedisiid').val()=='') {
          $('#expedisi').focus();
          swal("Ups!", "Expedisi belum dipilih.", "error");
          return false;
        }else if($("#tglso").val()) {
          var dateStr = $("#tglso").val().split("-");
          var dateOne = new Date(dateStr[2],dateStr[1]-1,dateStr[0]);
          var dateTwo = new Date({{date('Y,m-1,d')}});

          if(dateOne.getTime() > dateTwo.getTime()) {
            swal("Ups!", "Tidak boleh isi tanggal SO dengan tanggal masa depan !", "error");
            $("#tglso").val('').focus();
            return false;
          }
        }

          $.ajax({
            type: 'POST',
            url: '{{route("orderpenjualan.tambah")}}',
            headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
            data: {
              c2                 : $('#c2id').val(),
              noso               : $('#noso').val(),
              tglso              : $('#tglso').val(),
              tokoid             : $('#tokoid').val(),
              statustoko         : $('#statustoko').val(),
              karyawanidsalesman : $('#salesmanid').val(),
              expedisiid         : $('#expedisiid').val(),
              tipetransaksi      : $('#tipetransaksi').val(),
              temponota          : $('#temponota').val(),
              tempokirim         : $('#tempokirim').val(),
              temposalesman      : $('#temposalesman').val(),
              catatanpenjualan   : $('#catatanpenjualan').val(),
              catatanpembayaran  : $('#catatanpembayaran').val(),
              catatanpengiriman  : $('#catatanpengiriman').val()
            },
            dataType: 'json',
            success: function(data){
              $('#nopickinglist').val(data.nopickinglist);
              $('#tglpickinglist').val(data.tglpickinglist);
              $('#btnSimpan').hide();
              $('#c2').attr('disabled',true);
              $('#noso').attr('disabled',true);
              $('#tglso').attr('disabled',true);
              $('#toko').attr('disabled',true);
              $('#salesman').attr('disabled',true);
              $('#expedisi').attr('disabled',true);
              $('#tipetransaksi').attr('disabled',true);
              $('#temponota').attr('disabled',true);
              $('#catatanpenjualan').attr('disabled',true);
              $('#catatanpembayaran').attr('disabled',true);
              $('#catatanpengiriman').attr('disabled',true);
              $('#modalDetail #orderpenjualanid').val(data.orderid);
              $('#modalDetail').modal('show');
            },
            error: function(data){
              console.log(data);
            }
          });

        @endcannot
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
          // var tipetran = $("#tipetransaksi").val().substring(0,1);
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
        var sttstoko = $('#statustoko').val();
        var tipetran = $("#tipetransaksi").val().substring(0,1);
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
        // Cek Barang
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

      // $('#formDetail').submit(function(e){
      //   e.preventDefault();

      //   @cannot('orderpenjualan.detail.tambah')
      //       swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      //   @else

      //   // Cek Barang
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
      //           $.ajax({
      //             type   : 'GET',
      //             url    : '{{route("orderpenjualan.cekbarang")}}',
      //             data   : {
      //               id     : $('#orderpenjualanid').val(),
      //               stockid: $('#barangid').val(),
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
      //         id     : $('#orderpenjualanid').val(),
      //         stockid: $('#barangid').val(),
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
      //   @endcannot
      // });

      function ajax_formdetail(lanjut){
        var orderpenjualanid = $('#modalDetail #orderpenjualanid').val();
        if(lanjut == true) {
          $.ajax({
            type: 'POST',
            url: '{{route("orderpenjualan.detail.tambah")}}',
            headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
            data: {
              orderpenjualanid: $('#orderpenjualanid').val(),
              stockid         : $('#barangid').val(),
              qtyso           : $('#qtyso').val(),
              qtysoacc        : $('#qtysoacc').val(),
              hrgbmk          : $('#hrgbmk').val(),
              hrgsatuanbrutto : $('#hrgsatuanbrutto').val(),
              disc1           : $('#disc1').val(),
              disc2           : $('#disc2').val(),
              ppn             : $('#ppn').val(),
              hrgsatuannetto  : $('#hrgsatuannetto').val(),
              qtystockgudang  : $('#qtystockgudang').val(),
              catatandetail   : $('#catatandetail').val()
            },
            success: function(data){
              if(data.cek_opjd) {
                swal('Perhatian!', data.cek_opjd, 'warning');
              }

              console.log(data);
              table1.destroy();
              var x = '<tr>';
              x += '<td>'+ $('#barang').val() +'</td>';
              x += '<td>'+ $('#satuan').val() +'</td>';
              x += '<td class="text-right">'+ parseInt($('#qtyso').val()) +'</td>';
              x += '<td class="text-right">'+ currencyFormat($('#hrgsatuannetto').val()) +'</td>';
              x += '<td class="text-right">'+ currencyFormat($('#hrgtotalnetto').val()) +'</td>';
              x += '</tr>';

              $('#tbodyDetail').append(x);
              hitung_total();

              $('#modalDetail').find('input').val('');
              $('#modalDetail').find('textarea').val('');
              $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
              $('#modalDetail').find('#ppn').val({{$ppn}});
              $('#modalDetail').find('#disc1').val(0);
              $('#modalDetail').find('#disc2').val(0);
              $('#modalDetail').find('#qtystockgudang').val(0);
              $('#modalDetail').find('#catatandetail').val('');
              reloadtabledetail();
              $('#barang').focus();
              return false;
            },
            error: function(data){
              console.log(data);
            }
          });
        }else{
          // $('#kodebarang').val('');
          // $('#barang').val('');
          // $('#barangid').val('');
          // $('#satuan').val('');
          // $('#qtyso').val('');
          // $('#qtysoacc').val('');
          // $('#hrgsatuanbrutto').val('');
          // $('#disc1').val(0);
          // $('#hrgdisc1').val('');
          // $('#disc2').val(0);
          // $('#hrgdisc2').val('');
          // $('#hrgsatuannetto').val('');
          // $('#hrgtotalnetto').val('');
          // $('#hrgbmk').val('');
          // $('#arrhrgbmk').val('');
          // $('#riwayatorder').val('');
          // $('#qtystockgudang').val(0);
          // $('#catatandetail').val('');
          $('#modalDetail').find('input').val('');
          $('#modalDetail').find('textarea').val('');
          $('#modalDetail').find('#orderpenjualanid').val(orderpenjualanid);
          $('#modalDetail').find('#ppn').val({{$ppn}});
          $('#modalDetail').find('#disc1').val(0);
          $('#modalDetail').find('#disc2').val(0);
          $('#modalDetail').find('#qtystockgudang').val(0);
          $('#modalDetail').find('#catatandetail').val('');
          reloadtabledetail();
          $('#barang').focus();
          
        }
      }
    });

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

    function hitung_total(){
      var total = 0;
      $('#tbodyDetail tr').each(function(){
        total += Number($(this).find('td').eq(4).text().toString().replace(/[^\d\,\-\ ]/g, ''));
      });
      $('#txt_total').text('Rp. ' + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
    }

    function reloadtabledetail(){
      table1 = $('#tableDetail').DataTable({
        "destroy"  : true,
        "searching": false,
        "ordering" : false,
        "info"     : false,
        "paging"   : false,
        responsive : true,
        language   : {
          "emptyTable": "Tidak ada detail",
        },
        "columns": [
          { "data": "barang" },
          { "data": "satuan" },
          { "data": "qtyso" },
          { "data": "hrgsatuannetto" },
          { "data": "hrgtotalnetto" },
        ],
      });
    }

    function currencyFormat(num){
      return num.toString()
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // use . as a separator
    }
  </script>
@endpush
