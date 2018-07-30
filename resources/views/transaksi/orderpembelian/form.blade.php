@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orderpembelian.index') }}">Order Pembelian</a></li>
    <li class="breadcrumb-item active">Tambah Data</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tambah Order Pembelian - {{$subcabanguser}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="#">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglorder">Tgl. Order</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tglorder" name="tglorder" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noorder">No. Order</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="noorder" name="noorder" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="supplier" class="form-control col-md-7 col-xs-12" autofocus placeholder="Supplier" autocomplete="off" required="required" value={{$supplierdefault->nama}}>
                  <input type="hidden" id="supplierid" name="supplierid" value="{{$supplierdefault->id}}">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tempo">Tempo <span class="required"></span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="input-group">
                    <input type="number" id="tempo" name="tempo" class="form-control" value="0" min="0" required readonly>
                    <span class="input-group-addon" id="basic-addon1">hari</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Keterangan"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              @can('orderpembelian.tambah')
              <button type="submit" id="btnSimpan" class="btn btn-success">Simpan</button>
              @endcan
              @can('orderpembelian.detail.tambah')
              <button type="button" id="btnTambah" class="btn btn-default" data-toggle="modal" data-target="#modalDetail"><i class="fa fa-plus"></i> Tambah Detail</button>
              @endcan
            </div>
          </form>
          <table id="tableDetail" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" width="40%">Barang</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Qty Order</th>
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
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6">
              <a href="{{route('orderpembelian.index')}}" class="btn btn-primary">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal tambah detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Tambah Detail Order Pembelian</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="orderpembelianid" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                <input type="hidden" id="barangid">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
              <div class="form-group row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyrataratajual">Qty Rata2 Jual</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtyrataratajual" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokmin">Qty Stok Minimum</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtystokmin" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtystokakhir">Qty Stok Akhir</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtystokakhir" class="form-control" readonly tabindex="-1">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtypenjualanbo">Qty Penjualan BO</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtypenjualanbo" class="form-control" readonly tabindex="-1">
              </div>
              <p class="help-block muted">Tgl. picking list antara h-7 sd. h-1 tgl server</p>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyorder">Qty Order <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qtyorder" class="form-control hitungnetto" placeholder="Qty Order" readonly required="required"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
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
                  <input type="number" id="hrgsatuanbrutto" class="form-control hitungnetto" placeholder="Harga Brutto" required="required" value="0" readonly>
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
@endsection

@push('scripts')
  <!-- Form Validation -->
  <!-- <script src="{{asset('assets/js/validator.js')}}"></script> -->

  <script type="text/javascript">
    var table1,fokus;

    {{-- @include('lookupbarang') --}}

    // Run Lookup
    lookupsupplier();
    lookupbarang(null,null,'hitungbo','hpp');

    $(document).ready(function(){
      $(".select2_single").select2({
        placeholder: "PILIH SUB CABANG",
        allowClear: true
      });
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

      $('#modalDetail').on('keydown', function (e) {
        ele = document.activeElement;
        if(e.keyCode == 13) {
          if(ele.id == 'qtyorder') {
            hitung_netto();
            $('#formDetail').submit();
          }
        }
      });

      $('#btnTambah').on('click', function(){
        if ($('#orderpembelianid').val()=='') {
          swal("Ups!", "Simpan Order terlebih dulu.", "error");
          return false;
        }else {
          return true;
        }
      });

      $("#barang, #barangid").on("change", function(){
        hitung_netto()
      });

      $('.hitungnetto').on('keyup', function(){
        hitung_netto();
      });

      @can('orderpembelian.tambah')
      $('#formTambah').submit(function(e){
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: '{{route("orderpembelian.tambah")}}',
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: {
            subcabang : "{{session('subcabang')}}",
            supplierid: $('#supplierid').val(),
            tempo     : $('#tempo').val(),
            keterangan: $('#keterangan').val(),
          },
          dataType: "json",
          success: function(data){
            console.log(data);
            $('#noorder').val(data.noorder);
            $('#tglorder').val(data.tglorder);
            $('#btnSimpan').hide();
            $('#supplier').attr('disabled',true);
            $('#tempo').attr('disabled',true);
            $('#keterangan').attr('disabled',true);
            $('#modalDetail #orderpembelianid').val(data.orderid);
            $('#modalDetail').modal('show');
          },
          error: function(data){
            console.log(data);
          }
        });
      });
      @else
      $('#formTambah').submit(function(e){ e.preventDefault(); swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;});
      @endcan

      @can('orderpembelian.detail.tambah')
      $('#formDetail').on('submit', function(e){
        e.preventDefault();
        
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
        } else {
          // Cek Barang
          $.ajax({
            type    : 'GET',
            url     : '{{route("orderpembelian.cekbarang")}}',
            dataType: "json",
            data    : {
              id     : $('#modalDetail #orderpembelianid').val(),
              stockid: $('#modalDetail #barangid').val(),
            },
            success: function(respon){
              if(respon) {
                swal('Ups!', "Barang "+$('#modalDetail #barang').val()+" sudah dipilih lebih dari satu pada header yang sama, insert barang akan dibatalkan!",'error');
                $('#modalDetail').modal('hide');
                $('#modalDetail').find('input').val('');
              }else{
                $.ajax({
                  dataType: "json",
                  type    : 'POST',
                  url     : '{{route("orderpembelian.detail.tambah")}}',
                  headers : {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                  data    : {
                    orderpembelianid: $('#orderpembelianid').val(),
                    stockid         : $('#barangid').val(),
                    qtyrataratajual : $('#qtyrataratajual').val(),
                    qtypenjualanbo  : $('#qtypenjualanbo').val(),
                    qtystokakhir    : $('#qtystokakhir').val(),
                    qtyorder        : $('#qtyorder').val(),
                    qtytambahan     : $('#qtytambahan').val(),
                    hrgsatuanbrutto : $('#hrgsatuanbrutto').val(),
                    disc1           : $('#disc1').val(),
                    disc2           : $('#disc2').val(),
                    ppn             : $('#ppn').val(),
                    hrgsatuannetto  : $('#hrgsatuannetto').val(),
                    keterangandetail: $('#keterangandetail').val()
                  },
                  success: function(data){
                    console.log(data);
                    table1.destroy();
                    var x = '<tr>';
                    x += '<td>'+ $('#barang').val() +'</td>';
                    x += '<td>'+ $('#satuan').val() +'</td>';
                    x += '<td class="text-right">'+ parseInt($('#qtyorder').val()) + '</td>';
                    x += '<td class="text-right">'+ $('#hrgsatuannetto').val().toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") +'</td>';
                    x += '<td class="text-right">'+ $('#hrgtotalnetto').val().toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") +'</td>';
                    x += '</tr>';

                    $('#tbodyDetail').append(x);
                    hitung_total();

                    $('#kodebarang').val('');
                    $('#barang').val('');
                    $('#barangid').val('');
                    $('#satuan').val('');
                    $('#qtyrataratajual').val('');
                    $('#qtystokmin').val('');
                    $('#qtystokakhir').val('');
                    $('#qtypenjualanbo').val('');
                    $('#qtyorder').val('');
                    $('#qtytambahan').val('');
                    $('#hrgsatuanbrutto').val('');
                    $('#disc1').val(0);
                    $('#hrgdisc1').val('');
                    $('#disc2').val(0);
                    $('#hrgdisc2').val('');
                    $('#hrgsatuannetto').val('');
                    $('#hrgtotalnetto').val('');
                    $('#keterangandetail').val('');

                    reloadtabledetail();
                    $('#barang').focus();
                    return false;
                  },
                  error: function(data){
                    console.log(data);
                  }
                });
              }
            }
          });
        }
      });

      @else
      $('#formDetail').on('submit', function(e){e.preventDefault(); swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;});
      @endcan

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

    function hitung_total(){
      var total = 0;
      $('#tbodyDetail tr').each(function(){
        total += Number($(this).find('td').eq(4).text().toString().replace(/[^\d\,\-\ ]/g, ''));
      });
      $('#txt_total').text('Rp. ' + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
    }

    function reloadtabledetail(){
      table1 = $('#tableDetail').DataTable({
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
          { "data": "qtyorder" },
          { "data": "hrgsatuannetto" },
          { "data": "hrgtotalnetto" },
        ]
      });
    }

  </script>
@endpush
