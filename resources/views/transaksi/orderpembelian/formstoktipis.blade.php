@extends('layouts.default')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Pembelian</a></li>
  <li class="breadcrumb-item"><a href="{{ route('orderpembelian.index') }}">Order Pembelian</a></li>
  <li class="breadcrumb-item active">Generate Stok Tipis</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Generate Stok Tipis</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form class="form-horizontal form-label-left" method="POST" action="{{route('orderpembelian.stoktipis.tambah')}}" novalidate>
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
                  <input type="text" id="noorder" class="form-control col-md-7 col-xs-12" value="" readonly tabindex="-1">
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
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Keterangan" readonly tabindex="-1">GENERATE STOK TIPIS</textarea>
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
                <tbody></tbody>
              </table>
            </div>
            <br/>
            <div class="form-group">
              <div class="col-md-6">
                <a href="{{route('orderpembelian.index')}}" class="btn btn-primary">Batal</a>
                @can('orderpembelian.stoktipis.tambah')
                <button type="button" id="btnSimpan" class="btn btn-success">Simpan</button>
                @endcan
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    var table, table_index;

    lookupsupplier();

    $(document).ready(function(){
      $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
          checkboxClass: 'icheckbox_flat-green',
        });
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
          url: '{{ route("orderpembelian.stoktipis.data") }}',
          data: function ( d ) {
            d.tipe       = 'stoktipis';
            d.tglmulai   = $('#tglmulai').val();
            d.tglselesai = $('#tglselesai').val();
          }
        },
        columns: [
          {
            "data"     : "edit",
            "orderable": false,
            render     : function(data, type, row) {
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
        @cannot('orderpembelian.stoktipis.tambah')
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
          $.ajaxSetup({headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }});
          $.ajax({
            type: 'POST',
            url : '{{route("orderpembelian.stoktipis.cek")}}',
            data: {
              supplierid : $('#supplierid').val(),
              keterangan : $('#keterangan').val(),
              datadetails:datadetail
            },
            success: function(datadetails){
              if(datadetails.success){
                swal({
                  title: "Konfirmasi",
                  text : "Barang "+ datadetails.namabarang +" telah diorder pada tanggal "+ datadetails.tglorder +" dengan qty "+ datadetails.qtyorder +" di no order "+ datadetails.noorder +", Apakah anda yakin ingin membuat order lagi?",
                  type : "info",
                  showCancelButton   : true,
                  closeOnConfirm     : false,
                  showLoaderOnConfirm: true
                }, function () {
                  $.ajaxSetup({headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }});
                  $.ajax({
                    type: 'POST',
                    url : '{{route("orderpembelian.stoktipis.tambah")}}',
                    data: {
                      supplierid: $('#supplierid').val(),
                      keterangan: $('#keterangan').val(),
                      datadetail:datadetail
                    },
                    success: function(datadetail){
                      swal({
                        title: 'Berhasil!',
                        text : 'Order Pembelian dari stok tipis dengan '+datadetail.result+' sudah terbentuk.',
                        type : 'success',
                      },function(){
                        window.location.href = '{{route("orderpembelian.index")}}';
                      });
                    }
                  });
                }); 
              }

              else if(datadetails.nocheck){
                $.ajax({
                  type: 'POST',
                  url: '{{route("orderpembelian.stoktipis.tambah")}}',
                  data: {
                    supplierid: $('#supplierid').val(),
                    keterangan: $('#keterangan').val(),
                    datadetail:datadetail
                  },
                  success: function(datadetail){
                    swal({
                      title: 'Berhasil!',
                      text : 'Order Pembelian dari stok tipis dengan '+datadetail.result+' sudah terbentuk.',
                      type : 'success',
                    },function(){
                      window.location.href = '{{route("orderpembelian.index")}}';
                    });
                  }
                }); 
              }

            }
          });
        }
        else {
          swal('Ups!', 'Tidak ada data yang dipilih','error');
        }
        @endcannot
      });

    });

  </script>
@endpush
