@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item"><a href="{{ route('orderpembelian.index') }}">Order Pembelian</a></li>
@endsection

@section('main_container')
<input type="hidden" id="rowid">
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Daftar Order Pembelian</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float: right;">
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="row">
            <div class="col-md-4">
              <form class="form-inline">
                <div class="form-group">
                  <label style="margin-right: 10px;">Tgl. Order</label>
                  <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                  <label>-</label>
                  <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                </div>
              </form>
            </div>
            <div class="col-md-8 text-right">
              @can('orderpembelian.tambah')
              <a href="{{route('orderpembelian.tambah')}}" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah Order Pembelian - Ins</a>
              @endcan
              @can('orderpembelian.sync')
              <button type="button" id="btnSync11" data-id="skeyF11" class="btn btn-default">Sync 11 - F11</button>
              @endcan
              @can('orderpembelian.stoktipis.tambah')
              <a href="{{route('orderpembelian.stoktipis.tambah')}}" class="btn btn-default">Generate Stok Tipis</a>
              @endcan
              @can('orderpembelian.bo.tambah')
              <a href="{{route('orderpembelian.bo.tambah')}}" class="btn btn-default">Generate BO</a>
              @endcan
            </div>
          </div>

          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th>Tgl. Order</th>
                <th>No. Order</th>
                <th>Supplier</th>
                <th>Status ACC</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis" data-column="1">
                <i id="eye1" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl. Order
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="2">
                &nbsp;&nbsp;<i id="eye2" class="fa fa-eye"></i>&nbsp;&nbsp;No. Order
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="3">
                &nbsp;&nbsp;<i id="eye3" class="fa fa-eye"></i>&nbsp;&nbsp;Supplier
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="4">
                &nbsp;&nbsp;<i id="eye3" class="fa fa-eye"></i>&nbsp;&nbsp;Status ACC
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
                <th>Qty Ttl. Order</th>
                <th>Hrg. Sat. Netto</th>
                <th>Hrg. Total</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
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
                &nbsp;&nbsp;<i id="eye-detail3" class="fa fa-eye"></i>&nbsp;&nbsp;Qty Ttl. Order
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
        <form id="formKewenangan" action="#" class="form-horizontal" method="post">
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

  <!-- modal update -->
  <div class="modal fade" id="modalUpdateOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Pembelian - Update Order Pembelian</h4>
        </div>
        <form id="formUpdateOrder" class="form-horizontal" method="post">
          <input type="hidden" id="orderId" value="">
          <input type="hidden" id="recowner" value="">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglOrder">Tgl. Order</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="tglOrder" class="form-control" placeholder="Tgl. Order" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noOrder">No. Order</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="noOrder" class="form-control" placeholder="No. Order" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="supplier" class="form-control" placeholder="Supplier">
                  <input type="hidden" id="supplierid">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tempo">Tempo</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="input-group">
                    <input type="number" id="tempo" class="form-control" placeholder="0" min="0">
                    <span class="input-group-addon" id="basic-addon1">hari</span>
                  </div>
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

  <!-- modal detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="labelModalDetail">Pembelian</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="orderpembelianid" value="">
          <input type="hidden" id="orderIdDetail" value="">
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
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyrataratajual">Qty Rata2 Jual</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtyrataratajual" name="qtyrataratajual" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty Stok Minimum</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtystokmin" name="qtystokmin" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokakhir">Qty Stok Akhir</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtystokakhir" name="qtystokakhir" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtypenjualanbo">Qty Penjualan BO</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtypenjualanbo" name="qtypenjualanbo" class="form-control" readonly tabindex="-1">
              </div>
              <p class="help-block muted">Tgl. picking list antara h-7 sd. h-1 tgl server</p>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyorder">Qty Order <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtyorder" name="qtyorder" class="form-control hitungnetto" readonly placeholder="Qty Order" required="required"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
              <p class="help-block muted">Default = Minimum - akhir + BO</p>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtytambahan">Qty Tambahan <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtytambahan" class="form-control hitungnetto" placeholder="Qty Tambahan" required="required"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
              <p class="help-block muted"></p>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangandetail">Keterangan</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="keterangandetail" rows="3" class="form-control" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-9 col-sm-9 col-xs-12 col-md-push-3 col-sm-push-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuanbrutto">Harga Satuan Brutto <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgsatuanbrutto" class="form-control hitungnetto" value="0" placeholder="Harga Brutto" required="required" readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disc1">Disc 1</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                  <input type="number" id="disc1" class="form-control hitungnetto" step="0.01" value="0" placeholder="Disc 1" required="required" readonly>
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
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangandetail">Keterangan</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="keterangandetail" rows="3" class="form-control" placeholder="Keterangan"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer hidden">
            <button type="submit" id="btnSubmitDetail" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal detail -->

  <!-- Data order pembelian -->
  <div class="modal fade" id="modalDataOrderPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Order Pembelian</h4>
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
  <!-- end of data order pembelian -->

  <!-- Data order pembelian detail -->
  <div class="modal fade" id="modalDataDetailOrderPembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Order Pembelian Detail</h4>
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
  <!-- end of data order pembelian detail -->

@endsection

@push('scripts')
<!-- Select2 -->
<script>
  $(document).ready(function() {
      $(".select2_tipe").select2({
        placeholder: "Pilih tipe",
         width: '100px',
        allowClear: true
      });
      $(".select2_filter").select2({
        placeholder: "Pilih filter",
        width: '100px',
        allowClear: true
      });

      $(".select2_multiple").select2({
          maximumSelectionLength: 3,
          placeholder: "With Max Selection limit 3",
          allowClear: true
        });
  });
</script>
<!-- /Select2 -->
<script type="text/javascript">
  var tipe_edit, index, table, table_index,table2_index, table2, tabledataorder, tabledatadetailorder,fokus;
  var context_menu_number_state = 'hide';
  var context_menu_text_state   = 'hide';
  var last_index    = '';
  var sync          = '';
  var filter_number = ['<=','<','=','>','>='];
  var filter_text   = ['=','!='];
  var tipe          = ['Find','Filter'];
  var column_index  = 0;
  var custom_search = [
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
  ];

  {{-- @include('lookupbarang') --}}

  // Run Lookup
  lookupsupplier();
  lookupbarang(null,null,'hitungbo','hpp');

  $(document).ready(function() {
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

    $('#modalKewenangan').on('shown.bs.modal', function () {
      $('#uxserKewenangan').focus();
    });
    $('#modalUpdateOrder').on('shown.bs.modal', function () {
      $('#supplier').focus();
    });
    $('#modalDetail').on('shown.bs.modal', function () {
      $('#barang').focus();
    });
    
    $('#modalDetail').on('keydown', function (e) {
      ele = document.activeElement;
      if(e.keyCode == 13) {
        if(ele.id == 'qtyorder') {
          hitung_netto();
          $('#formDetail').submit();
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
        url : '{{ route("orderpembelian.data") }}',
        data: function ( d ) {
          d.custom_search = custom_search;
          d.tglmulai      = $('#tglmulai').val();
          d.tglselesai    = $('#tglselesai').val();
          d.issync        = sync;
          d.tipe_edit     = tipe_edit;
        }
      },
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
        {"data" : "action","orderable" : false,},
        {"data" : "tglorder","className": "menufilter numberfilter"},
        {"data" : "noorder","className": "menufilter textfilter"},
        {"data" : "suppliernama","className": "menufilter textfilter"},
        {"data" : "status","className": "menufilter textfilter"},
      ],
      // drawCallback : function(result)
      // {
      //   if($("#rowid").val() != "")
      //   {
      //     var rowTable = $("[data-rowid="+$("#rowid").val()+"]").closest("tr");
      //     var rowTableIndx = rowTable.index();
      //     table.row(rowTableIndx).select();

      //     $("#rowid").val("");
      //   }
      // }
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
        //   name: "Tipe",
        //   type: 'select',
        //   options: {1: 'Find', 2: 'Filter'},
        //   selected: 1
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
      // console.log("key:", e.keyCode);
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

    $('.tgl').change(function(){
      table.ajax.reload(null, false);
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
      columns: [
        {"data" : "action","orderable" : false,},
        {"data" : "barang",},
        {"data" : "satuan",},
        {"data" : "qtyorder","className" : "text-right",},
        {"data" : "hrgsatuannetto","className" : "text-right",},
        {"data" : "hrgtotalnetto","className" : "text-right",},
      ],
    });

    table.on('select', function ( e, dt, type, indexes ){
      var rowData = table.rows(indexes).data().toArray();
      $.ajax({
        type: 'POST',
        data: {id: rowData[0].id, _token  :"{{ csrf_token() }}",},
        dataType: "json",
        url: '{{route("orderpembelian.detail.data")}}',
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

      tabledataorder.clear();
      tabledataorder.rows.add([
        {'0':'<div class="text-right">1.</div>', '1':'Tgl. Order', '2':data.tglorder},
        {'0':'<div class="text-right">2.</div>', '1':'No. Order', '2':data.noorder},
        {'0':'<div class="text-right">3.</div>', '1':'Supplier', '2':data.suppliernama},
        {'0':'<div class="text-right">4.</div>', '1':'Tempo', '2':data.tempo},
        {'0':'<div class="text-right">5.</div>', '1':'Keterangan', '2':data.keterangan},
        {'0':'<div class="text-right">6.</div>', '1':'Approval Status', '2':data.status},
        {'0':'<div class="text-right">7.</div>', '1':'Tgl. Approval', '2':data.tglapproval},
        {'0':'<div class="text-right">8.</div>', '1':'Keterangan Approval', '2':data.keteranganapproval},
        {'0':'<div class="text-right">9.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
        {'0':'<div class="text-right">10.</div>', '1':'Last Updated On', '2':data.lastupdatedon}
      ]);
      tabledataorder.draw();
      $('#modalDataOrderPembelian').modal('show');
    });

    $('#table2 tbody').on('dblclick', 'tr', function(){
      var data = table2.row(this).data();
      console.log(data);//untuk pop up grid

      $.ajax({
        type: 'POST',
        url: '{{route("orderpembelian.detail.detail")}}',
        data: {id: data.id,_token  :"{{ csrf_token() }}",},
        dataType: "json",
        success: function(data){
          tabledatadetailorder.clear();
          tabledatadetailorder.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'Barang', '2':data.namabarang},
            {'0':'<div class="text-right">2.</div>', '1':'Qty Ttl. Order', '2':data.qtyorder},
            {'0':'<div class="text-right">3.</div>', '1':'Qty Penjualan BO', '2':data.qtypenjualanbo},
            {'0':'<div class="text-right">4.</div>', '1':'Qty Rata2 Jual', '2':data.qtyrataratajual},
            {'0':'<div class="text-right">5.</div>', '1':'Qty Stok Akhir', '2':data.qtystokakhir},
            {'0':'<div class="text-right">6.</div>', '1':'Hrg Sat Brutto', '2':data.hrgsatuanbrutto},
            {'0':'<div class="text-right">7.</div>', '1':'Disc 1', '2':data.disc1},
            {'0':'<div class="text-right">8.</div>', '1':'Hrg Setelah Disc 1', '2':data.hrgdisc1},
            {'0':'<div class="text-right">9.</div>', '1':'Disc 2', '2':data.disc2},
            {'0':'<div class="text-right">10.</div>', '1':'Hrg Setelah Disc 2', '2':data.hrgdisc2},
            {'0':'<div class="text-right">11.</div>', '1':'PPN', '2':data.ppn},
            {'0':'<div class="text-right">12.</div>', '1':'Hrg Sat Netto', '2':data.hrgsatuannetto},
            {'0':'<div class="text-right">13.</div>', '1':'Hrg Total Netto', '2':data.hrgtotalnetto},
            {'0':'<div class="text-right">14.</div>', '1':'Keterangan', '2':data.keteranganbarang},
            {'0':'<div class="text-right">15.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">16.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatadetailorder.draw();
          $('#modalDataDetailOrderPembelian').modal('show');
        },
      });
    });

    $('#formUpdateOrder').submit(function(e){
      e.preventDefault();
      console.log(e);
      tipe_edit = 'ubah';
      var data = {
        orderid    : $('#modalUpdateOrder #orderId').val(),
        supplier   : $('#supplierid').val(),
        tempo      : $('#tempo').val(),
        keterangan : $('#keterangan').val(),
      };
      $.ajax({
        type: 'POST',
        url: '{{route("orderpembelian.ubah")}}',
        data: {data: data},
        dataType: "json",
        success: function(data){
          $('#modalUpdateOrder').modal('hide');
          //window.location.reload();
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
              table.row('#'+table_index).select();
            },1000);
        },
      });
    });

    $('.hitungnetto').on('keyup', function(){
      hitung_netto();
    });

    $('#formDetail').submit(function(e){
      e.preventDefault();
      var action = '';
      if ($('#orderIdDetail').val() != '') {
        @cannot('orderpembelian.detail.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        tipe_edit = 'ubah';
        action = '{{route("orderpembelian.detail.ubah")}}';
        @endcannot
      }else{
        @cannot('orderpembelian.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        tipe_edit = 'tambah';
        action = '{{route("orderpembelian.detail.tambah")}}';
        @endcannot
      }

      // if($('#hrgsatuanbrutto').val() < 1) {
      //   swal('Ups!', "Harga Satuan Bruto masih kosong",'error'); return false;
      if($('#barangid').val() == '') {
        swal('Ups!', "Barang masih kosong! Hubungi Manager Anda.",'error');
        $('#barang').focus();
        return false;
      } else if($('#qtyorder').val() == '') {
        swal('Ups!', "Qty Order masih kosong! Hubungi Manager Anda.",'error');
        $('#qtyorder').focus();
        return false;
      }
      // } else if(action) {
        // Cek Barang
        $.ajax({
          type    : 'GET',
          url     : '{{route("orderpembelian.cekbarang")}}',
          dataType: "json",
          data    : {
            id           : $('#modalDetail #orderpembelianid').val(),
            stockid      : $('#modalDetail #barangid').val(),
            orderiddetail: $('#modalDetail #orderIdDetail').val(),
          },
          success: function(respon){
            if(respon) {
              swal('Ups!', "Barang "+$('#modalDetail #barang').val()+" sudah dipilih lebih dari satu pada header yang sama, insert/update barang akan dibatalkan!",'error');
              $('#modalDetail').modal('hide');
              $('#modalDetail').find('input').val('');
            }else{
              $.ajax({
                type    : 'POST',
                url     : action,
                data    : {
                  orderpembelianid : $('#modalDetail #orderpembelianid').val(),
                  orderdetailid    : $('#modalDetail #orderIdDetail').val(),
                  stockid          : $('#modalDetail #barangid').val(),
                  qtystokmin       : $('#modalDetail #qtystokmin').val(),
                  qtyrataratajual  : $('#modalDetail #qtyrataratajual').val(),
                  qtystokakhir     : $('#modalDetail #qtystokakhir').val(),
                  qtypenjualanbo   : $('#modalDetail #qtypenjualanbo').val(),
                  qtyorder         : $('#modalDetail #qtyorder').val(),
                  qtytambahan      : $('#modalDetail #qtytambahan').val(),
                  hrgsatuanbrutto  : $('#modalDetail #hrgsatuanbrutto').val(),
                  disc1            : $('#modalDetail #disc1').val(),
                  disc2            : $('#modalDetail #disc2').val(),
                  ppn              : $('#modalDetail #ppn').val(),
                  hrgsatuannetto   : $('#modalDetail #hrgsatuannetto').val(),
                  keterangandetail : $('#modalDetail #keterangandetail').val(),
                },
                dataType: 'json',
                success: function(data){
                  console.log(data);
                  $('#modalDetail').modal('hide');
                  $('#modalDetail').find('input').val('');
                  //window.location.reload();
                  table.ajax.reload(null, true);
                  tipe_edit = null;
                  setTimeout(function(){
                    table.row('#'+table_index).select();
                  },1000);
                }
              });
            }
          }
        });
      // }
    });

    $('#formKewenangan').submit(function(e){
      e.preventDefault();
      tipe_edit = 'hapus';
      $.ajax({
        type: 'POST',
        url: '{{route("orderpembelian.kewenangan")}}',
        data: {
          username  : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
          password  : $('#modalKewenangan #pxassKewenangan').val(),
          orderid   : $('#modalKewenangan #orderIdHapus').val(),
          tipe      : $('#modalKewenangan #tipe').val(),
          permission: 'orderpembelian.hapus',
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
            url: '{{route("orderpembelian.sync")}}',
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
    });
  });

  $(document).ajaxComplete(function() {
    $('input[type=checkbox]').iCheck({
      checkboxClass: 'icheckbox_flat-green',
    });
  });
  
  function tambahDetail(e){
    var message = $(e).data('message');
    var data = table.row($(e).parents('tr')).data();

    @cannot('orderpembelian.detail.tambah')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    if(message == 'add') {
      $('#modalDetail #orderpembelianid').val(data.id);
      $('#modalDetail #orderIdDetail').val('');
      $('#modalDetail #kodebarang').val('');
      $('#modalDetail #barang').val('');
      $('#modalDetail #barangid').val('');
      $('#modalDetail #satuan').val('');
      $('#modalDetail #qtyrataratajual').val('');
      $('#modalDetail #qtystokmin').val('');
      $('#modalDetail #qtystokakhir').val('');
      $('#modalDetail #qtypenjualanbo').val('');
      $('#modalDetail #qtyorder').val('');
      $('#modalDetail #qtytambahan').val('');
      $('#modalDetail #hrgsatuanbrutto').val('');
      $('#modalDetail #disc1').val(0);
      $('#modalDetail #hrgdisc1').val('');
      $('#modalDetail #disc2').val(0);
      $('#modalDetail #hrgdisc2').val('');
      $('#modalDetail #ppn').val('{{$ppn}}');
      $('#modalDetail #hrgsatuannetto').val('');
      $('#modalDetail #hrgtotalnetto').val('');
      $('#modalDetail #keterangandetail').val('');

      $('#labelModalDetail').text('Pembelian - Tambah Order Pembelian Detail');
      $('#modalDetail').modal('show');
    }else {
      swal('Ups!', message,'error');
    }
    @endcannot
  }

  function update(e){
    var message = $(e).data('message');
    var tipe = $(e).data('tipe');
    if($(e).parents('table').attr('id') == 'table2') {
      var data = table2.row($(e).parents('tr')).data();
    }else{
      var data = table.row($(e).parents('tr')).data();
    }

    
    if(tipe == 'header') {
      @cannot('orderpembelian.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      if(message == 'edit') {
        $('#modalUpdateOrder #orderId').val(data.id);
        $('#modalUpdateOrder #recowner').val(data.recordownerid);
        $('#modalUpdateOrder #tglOrder').val(data.tglorder);
        $('#modalUpdateOrder #noOrder').val(data.noorder);
        $('#modalUpdateOrder #supplierid').val(data.supplierid);
        $('#modalUpdateOrder #supplier').val(data.suppliernama);
        $('#modalUpdateOrder #tempo').val(data.tempo);
        $('#modalUpdateOrder #keterangan').val(data.keterangan);

        $('#modalUpdateOrder').modal('show');
      }else{
        swal('Ups!', message,'error');
      }
      @endcannot
    }else {
      @cannot('orderpembelian.detail.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      if(message == 'edit') {
        $.ajax({
          type    : 'POST',
          url     : '{{route("orderpembelian.detail.detail")}}',
          data    : {id: data.id},
          dataType: "json",
          success : function(data){
            $('#modalDetail #orderpembelianid').val(data.orderpembelianid);
            $('#modalDetail #orderIdDetail').val(data.orderdetailid);
            $('#modalDetail #kodebarang').val(data.kodebarang);
            $('#modalDetail #barang').val(data.namabarang);
            $('#modalDetail #barangid').val(data.barangid);
            $('#modalDetail #satuan').val(data.satuan);
            $('#modalDetail #qtyorder').val(data.qtyorder);
            $('#modalDetail #qtytambahan').val(data.qtytambahan);
            $('#modalDetail #hrgsatuanbrutto').val(data.hrgsatuanbrutto);
            $('#modalDetail #disc1').val(data.disc1);
            $('#modalDetail #hrgdisc1').val(data.hrgdisc1);
            $('#modalDetail #disc2').val(data.disc2);
            $('#modalDetail #hrgdisc2').val(data.hrgdisc2);
            $('#modalDetail #ppn').val(data.ppn);
            $('#modalDetail #hrgsatuannetto').val(data.hrgsatuannetto);
            $('#modalDetail #hrgtotalnetto').val(data.hrgtotalnetto);
            $('#modalDetail #keterangandetail').val(data.keteranganbarang);
            $('#modalDetail #qtypenjualanbo').val(data.qtypenjualanbo);
            $('#modalDetail #qtyrataratajual').val(data.qtyrataratajual);
            $('#modalDetail #qtystokakhir').val(data.qtystokakhir);
            $('#labelModalDetail').text('Pembelian - Ubah Order Pembelian Detail');
            $('#modalDetail').modal('show');
          }
        });
      }else{
        swal('Ups!', message,'error');
      }
      @endcannot
    }
  }

  function deleteOrder(e){
    var message = $(e).data('message');
    var tipe = $(e).data('tipe');
    if($(e).parents('table').attr('id') == 'table2') {
      var data = table2.row($(e).parents('tr')).data(); 
    }else{
      var data = table.row($(e).parents('tr')).data();
    }
    
    if(message == 'auth') {
      $('#modalKewenangan #orderIdHapus').val(data.id);
      $('#modalKewenangan #tipe').val(tipe);
      $('#modalKewenangan').modal('show');
    }else {
      swal('Ups!',message,'error');
    }
  }

  function approval(e){
    var message = $(e).data('message');
    var data = table.row($(e).parents('tr')).data();
    var id   = data.id;
    
    @cannot('orderpembelian.approval')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else

    if(message == 'reapproval'){
      var message_text = "<b>Data ini sudah pernah diajukan!</b> <br/> Apakah Anda Yakin ingin mengulang Pengajuan Approval?";
      var confirm      =  "Ya, Ajukan Kembali!";
    }else{
      var message_text = "Apakah Anda Yakin ingin mengajukan Approval?";
      var confirm      =  "Ya, Ajukan!";
    }

    tipe_edit = true;
    $.ajax({
      type    : 'GET',
      url     : '{{route("orderpembelian.ceksbp")}}',
      data    : {id: id,_token: "{{ csrf_token() }}"},
      dataType: "json",
      beforeSend: function() {
        swal({
          title: "Proses!",
          text : "Sedang Proses Penghitungan Budget!",
          type : "warning",
          html : true,
          showCancelButton: false,
          showConfirmButton: false,
          showLoading : true,
        });
      },
      success   : function(data){
        swal.close();
        if(data.sbp < 0) {
            swal({
              title: "Ups!",
              text : data.pesan,
              type : "error",
              html : true,
            },
            function(){
              tipe_edit = null;
            });

              return false;
          }else{
            setTimeout(function(){
              swal({
                title: "Anda Yakin?",
                text : message_text,
                type : "warning",
                html : true,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: confirm,
                closeOnConfirm: false,
                showLoaderOnConfirm:true,
              },
              function(){
                $("#rowid").val(id);
                $.ajax({
                  type: 'POST',
                  url: '{{route("orderpembelian.approval")}}',
                  data: {id: id,_token: "{{ csrf_token() }}"},
                  dataType: "json",
                  success: function(result){
                    swal({
                        title: "Sukses!",
                        text : "Data berhasil diajukan approval.",
                        type : "success",
                        html : true,
                    },function(){
                        var selectedIdx = $("#table1 tr.selected").index();
                        table.row(selectedIdx).deselect();
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                    });
                  },
                  error: function(jqXHR, textStatus, errorThrown){
                    if(errorThrown == "Internal Server Error")
                    {
                      swal({
                          title: "Sukses!",
                          text : "Data berhasil diajukan approval.",
                          type : "success",
                          html : true,
                      },function(){
                          var selectedIdx = $("#table1 tr.selected").index();
                          table.row(selectedIdx).deselect();
                          table.ajax.reload(null, true);
                          tipe_edit = null;
                      });
                    }
                  },
                });
              });
            }, 1000);
          }
        }
      });
    @endcannot
  }

  function hitung_netto(){
    var qtyorder    = Number(($('#qtyorder').val() == "" ? $('#qtyorder').val() : "0"));
    var qtytambahan = Number(($('#qtytambahan').val() == "" ? $('#qtytambahan').val() : "0"));
    var bruto    = Number(($('#hrgsatuanbrutto').val() == "" ? $('#hrgsatuanbrutto').val() : "0"));
    var disc1    = Number(($('#disc1').val() == "" ? $('#disc1').val() : "0"))/100;
    var hrgdisc1 = Math.round((1 - disc1) * bruto, 2);
    var disc2    = Number(($('#disc2').val() == "" ? $('#disc2').val() : "0"))/100;
    var hrgdisc2 = Math.round((1 - disc2) * hrgdisc1, 2);
    var ppn      = Number(($('#ppn').val() == "" ? $('#ppn').val() : "0"))/100;
    var hrgsatuannetto = Math.round((1 + ppn) * hrgdisc2, 2); 
    $('#hrgdisc1').val(hrgdisc1);
    $('#hrgdisc2').val(hrgdisc2);
    $('#hrgsatuannetto').val(hrgsatuannetto);
    $('#hrgtotalnetto').val(hrgsatuannetto * (qtyorder+qtytambahan));
  }


  function cetakoh(e,tipe){
    var message = $(e).data('message');
    var data = table.row($(e).parents('tr')).data();
    if (tipe == 'pdf') {
        var popup1  = window.open("about:blank", "_blank");
        popup1.location = '{{route("orderpembelian.cetakoh.pdf")}}?id='+data.id;
    } else {
      $.ajax({
        url    : '{{route("orderpembelian.cetakoh.excel")}}?id='+data.id,
        cache  : false,
        success: function(href){
          var popup1  = window.open("about:blank", "_blank");
          popup1.location = href;
        }
      });
    }
  }

</script>
@endpush
