@extends('layouts.default')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Pembelian</a></li>
  <li class="breadcrumb-item"><a href="{{ route('orderpembelian.index') }}">Order Pembelian</a></li>
  <li class="breadcrumb-item active">Generate BO</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Generate BO</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
           <form class="form-horizontal form-label-left" method="POST" action="{{route('orderpembelian.bo.tambah')}}" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6 col-xs-12">
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglorder">Tgl. Order</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tglorder" name="tglorder" class="form-control col-md-7 col-xs-12" value="{{date('d-m-Y')}}" readonly tabindex="-1">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noorder">No. Order</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="noorder" name="noorder" class="form-control col-md-7 col-xs-12" value="" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="supplier" class="form-control col-md-7 col-xs-12" autofocus placeholder="Supplier" autocomplete="off" required="required" value={{$supplierdefault->nama}}>
                 <input type="hidden" id="supplierid" name="supplierid" value="{{$supplierdefault->id}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Keterangan" readonly tabindex="-1">GENERATE ORDER STOK TIPIS</textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
               <table id="tableDetail" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="1%">A</th>
                    <th class="text-center">Barang</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center">Qty Order</th>
                    <th class="text-center">Qty Rata2 Jual</th>
                    <th class="text-center">Qty Stok Minimum</th>
                    <th class="text-center">Qty Stok Akhir</th>
                    <th class="text-center">Qty Penjualan BO</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="kosong">
                    <td class="text-center" colspan="7">Tidak ada detail</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="form-group">
              <div class="col-md-6">
                <a href="{{route('orderpembelian.index')}}" class="btn btn-primary">Batal</a>
                @can('orderpembelian.bo.tambah')
                <button type="button" id="btnSimpan" class="btn btn-success">Simpan</button>
                @endcan
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Supplier</h4>
        </div>
        <form class="form-horizontal" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySupplier">Masukkan kata kunci pencarian</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <input type="text" id="txtQuerySupplier" class="form-control" placeholder="Kode/Nama Supplier">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table class="table table-bordered table-striped tablepilih">
                  <thead>
                    <tr>
                      <th class="text-center">Kode</th>
                      <th class="text-center">Nama Supplier</th>
                    </tr>
                  </thead>
                  <tbody id="tbodySupplier">
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
            <button type="button" id="btnPilihSupplier" class="btn btn-primary">Pilih</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    var table, table_index;
    $(document).ready(function(){
       $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
          checkboxClass: 'icheckbox_flat-green',
        });
      });
      $('#supplier').on('keypress', function(e){
         if (e.keyCode == '13') {
          var recowner = "{{session('subcabang')}}";
          $('#modalSupplier').modal('show');
          $('#txtQuerySupplier').val($(this).val());
          search_supplier(recowner, $(this).val());

          return false;
        }
      });
      $('#txtQuerySupplier').on('keypress', function(e){
        if (e.keyCode == '13') {
          var recowner = $('#subcabang').val();
          search_supplier(recowner, $(this).val());
          return false;
        }
      });

      $('#tbodySupplier').on('click', 'tr', function(){
        $('.selected').removeClass('selected');
        $(this).addClass("selected");
      });

      $('#btnPilihSupplier').on('click', function(){
        pilih_supplier();
      });
      $('#modalSupplier table.tablepilih tbody').on('dblclick', 'tr', function(){
           pilih_supplier();
      });
      $('#modalSupplier').on('keypress', function(e){
        if (e.keyCode == '13') {
          pilih_supplier();
        }
      });
      table = $('#tableDetail').DataTable({
      dom       : 'lrtp',
      select: {style:'single'},
      keys: {keys: [38,40]},
      serverSide: true,
      scrollY : "33vh",
      scrollX : true,
      scroller: {
        loadingIndicator: true
      },
      ajax : {
        url: '{{ route("orderpembelian.bo.data") }}',
        data: function ( d ) {
          d.tglmulai      = $('#tglmulai').val();
          d.tglselesai    = $('#tglselesai').val();
        }
      },
      columns: [
        {
          "data" : "edit",
          "orderable" : false,
          render : function(data, type, row) {
              return "<div class='checkbox checkboxaction'><input type='checkbox' class='flat disabled' value='"+JSON.stringify(row)+"'></div>";
            }
        },
        {"data" : "namabarang"},
        {"data" : "satuan"},
        {"data" : "qtyorder"},
        {"data" : "rataratajual"},
        {"data" : "qtystockmin"},
        {"data" : "qtystokakhir"},
        {"data" : "qtybo"},
        
      ]
    });

    $('#btnSimpan').on('click', function(){
      @cannot('orderpembelian.bo.tambah')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      sync           = true;
      var count      = 0;
      var datadetail = [];

      $('#tableDetail').find('input[type="checkbox"]:checked:not(:disabled)').each(function () {
        count += 1;
        datadetail.push($(this).val());
      });
      if (count > 0) {
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
         $.ajax({
            type: 'POST',
            url: '{{route("orderpembelian.stoktipis.cek")}}',
            data: {
              supplierid  : $('#supplierid').val(),
              keterangan  : $('#keterangan').val(),
              datadetails :datadetail
            },
            success: function(datadetails){
              if(datadetails.success){
                  swal({
                      title: "Konfirmasi",
                      text: "Barang "+ datadetails.namabarang +" telah diorder pada tanggal "+ datadetails.tglorder +" dengan qty "+ datadetails.qtyorder +" di no order "+ datadetails.noorder +", Apakah anda yakin ingin membuat order lagi?",
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
                        url: '{{route("orderpembelian.bo.tambah")}}',
                        data: {
                          supplierid: $('#supplierid').val(),
                          keterangan: $('#keterangan').val(),
                          datadetail:datadetail
                        },
                        success: function(datadetail){
                          console.log(datadetail);
                          swal('Berhasil!', 'Order Pembelian dari Generete Bo sudah terbentuk.','success');
                          window.location.href = '{{route("orderpembelian.index")}}';
                        }
                      });
                    }); 
              }
              else if(datadetails.nocheck){
                $.ajax({
                  type: 'POST',
                  url: '{{route("orderpembelian.bo.tambah")}}',
                  data: {
                    supplierid: $('#supplierid').val(),
                    keterangan: $('#keterangan').val(),
                    datadetail:datadetail
                  },
                  success: function(datadetail){
                    console.log(datadetail);
                    swal('Berhasil!', 'Order Pembelian dari Generete Bo sudah terbentuk.','success');
                    window.location.href = '{{route("orderpembelian.index")}}';
                  }
                }); 
              }
            
            }
          });
      }
      else {
        swal('Ups!', 'Tidak ada data yang dipilih','error');
      }
      @endcan
    });

    });

    function search_supplier(recowner, query){
      $.ajax({
        type: 'GET',
        url: '{{route("lookup.getsupplier",[null,null])}}/' + recowner + '/' + query,
        success: function(data){
          var supplier = JSON.parse(data);
          $('#tbodySupplier tr').remove();
          var x = '';
          if (supplier.length > 0) {
            for (var i = 0; i < supplier.length; i++) {
              x += '<tr>';
              x += '<td>'+ supplier[i].kode +'<input type="hidden" class="id_sup" value="'+ supplier[i].id +'"></td>';
              x += '<td>'+ supplier[i].nama +'</td>';
              x += '</tr>';
            }
          }else {
            x += '<tr><td colspan="7" class="text-center">Tidak ada detail</td></tr>';
          }
          $('#tbodySupplier').append(x);
        },
        error: function(data){
          console.log(data);
        }
      });
    }

    function pilih_supplier(){
      if ($('#tbodySupplier').find('tr.selected td').eq(1).text() == '') {
        swal("Ups!", "Supplier belum dipilih.", "error");
        return false;
      }else {
        $('#supplier').val($('#tbodySupplier').find('tr.selected td').eq(1).text());
        $('#supplierid').val($('#tbodySupplier').find('tr.selected td .id_sup').val());
        $('#modalSupplier').modal('hide');
      }
    }
  </script>
@endpush
