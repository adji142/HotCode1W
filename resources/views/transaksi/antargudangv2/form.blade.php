@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</a></li>
    <li class="breadcrumb-item"><a href="{{ route('antargudangv2.index') }}">Antar Gudang V2</a></li>
    <li class="breadcrumb-item active">Tambah Data</li>
@endsection
@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tambah Antar Gudang V2 - {{$subcabanguser}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @can('antarcabangv2.tambah')
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="{{route('antargudangv2.tambah')}}">
          @else
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="#">
          @endcan
          {!! csrf_field() !!}
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="norqag">No. Rq. AG</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="norqag" name="norqag" class="form-control" value="{{$numerator}}" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglrqag">Tgl. Rq. AG</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="tglrqag" name="tglrqag" class="form-control" value="{{date('d-m-Y')}}" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="subcabangpengirim">Subcabang Pengirim</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="subcabangpengirim" name="subcabangpengirim" class="form-control" value="{{$subcabanguser}}" readonly tabindex="-1">
                  <input type="hidden" id="pengirimid" name="pengirimid" value="{{$subcabangid}}">
                </div>
              </div>
              <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="gudang">Subcabang Penerima</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type="text" id="gudang" name="gudang" class="form-control" tabindex="-1">
                  <input type="hidden" id="gudangid" name="gudangid">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatan">Catatan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="catatan" name="catatan" class="form-control" placeholder="Catatan">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-offset-2 col-sm-offset-2 col-md-6 col-sm-6">
                  @can('antargudangv2.tambah')
                  <button type="submit" id="btnSimpan" class="btn btn-success">Simpan</button>
                  @endcan
                  @can('antargudangv2.detail.tambah')
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
                <th class="text-center">Qty Rq. AG</th>
                <th class="text-center">Catatan</th>
              </tr>
            </thead>
            <tbody id="tbodyDetail"></tbody>
            
          </table>
          <div class="form-group">
            <div class="ln_solid"></div>
            <div class="col-md-6">
              <a href="{{route('antargudangv2.index')}}" class="btn btn-primary">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @can('antargudangv2.detail.tambah')
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Detail Antar Gudang V2</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barang">Barang <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="barang" name="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                                <input type="hidden" id="barangid" name="barangid">
                                <input type="hidden" id="qtystockgudang">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" id="satuan" name="satuan" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyrqag">Qty Rq. AG</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtyrqag" name="qtyrqag" class="form-control text-right" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatandetail">Catatan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="catatandetail" name="catatandetail" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="antargudangid" name="antargudangid" value="" readonly tabindex="-1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  @endcan

@endsection

@push('scripts')
<script src="{{asset('assets/js/validator.js')}}"></script>
<script type="text/javascript">
var table1,fokus;
lookupbarang();
lookupsubcabang();
lookupstaff();

$(document).ready(function(){
  $("#gudang").focus();
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

  $('#btnTambah').on('click', function(){
    if ($("#antargudangid").val() == ''){
      swal("Ups!", "Data Antar Gudang belum disimpan.","error"); return false;
    }else{
      return true;
    }
  });

  $('#formTambah').on('submit', function(e){
    e.preventDefault();

    @cannot('antargudangv2.tambah')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
      var subcabangpenerima = $("#gudangid").val();
      var subcabangpengirim = $("#pengirimid").val();
      if (subcabangpenerima == '' || subcabangpenerima == null){
        swal("Ups!", "Subcabang penerima belum dipilih.","error");
        return false;
      }
      if (subcabangpenerima == subcabangpengirim){
        swal("Ups!","Subcabang pengirim dan penerima tidak boleh sama. Hubungi Manager Anda!","error"); $("#gudang").focus(); return false;
      }
      $.ajax({
        type    : 'POST',
        url     : $(this).attr('action'),
        data    : $(this).serialize(),
        dataType: "json",
        success : function(data){
            console.log(data);
            $("#antargudangid").val(data.antargudangid);
            $('#btnSimpan').hide();
            $('#gudang').attr('disabled',true);
            $("#catatan").attr("disabled",true);
            $('#modalDetail').modal('show');
            $('#barang').focus();
        },
        error: function(data){
            console.log(data);
        }
    });
    @endcannot
    return false;
  });

  $("#formDetail").on("submit", function (e){
    e.preventDefault();
    @cannot("antargudangv2.detail.tambah")
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
      if ($("#antargudangid").val() == '' || $("#antargudangid").val() == null){
        swal("Ups!","Tidak bisa simpan record detail. Data antar gudang belum disimpan.","error"); $("#barang").focus(); return false;
      }
      if ($("#barangid").val() == '' || $("#barangid").val() == null){
        swal("Ups!","Barang belum dipilih.","error"); $("#barang").focus(); return false;
      }
      if (parseInt($("#qtyrqag").val()) < 1 || $("#qtyrqag").val() == null){
        swal("Ups!","Qty Rq. AG belum diisi","error"); $("#qtyrqag").focus(); return false;
      }

      var stok = $("#qtystockgudang").val();
      if (parseFloat($("#qtyrqag").val()) > parseFloat(stok)){
        $('#qtyrqag').val(0);
        swal("Ups!","Qty melebihi stock gudang.","warning"); return false;
      }else{
        if (stok == ''){
          $('#qtyrqag').val('');
          swal("Ups!", "Stock gudang kosong", "error"); return false;
        }
      }
      
      $.ajax({
        type      : 'POST',
        url       :'{{route("antargudangv2.detail.tambah")}}',
        data      : $(this).serialize(),
        dataType  : "json",
        success   : function(data){
          
          table1.destroy();
          var x = '<tr>';
          x += '<td>'+ $('#barang').val() +'</td>';
          x += '<td>'+ $('#satuan').val() +'</td>';
          x += '<td class"text-right">'+ $('#qtyrqag').val() +'</td>';
          x += '<td>'+ $('#catatandetail').val() +'</td>';
          x += '</tr>';

          $('#tbodyDetail').append(x);
          $("#barang").val('');
          $("#barangid").val('');
          $("#satuan").val('');
          $("#qtyrqag").val(0);
          $("#catatandetail").val('');
          reloadtabledetail();
          $("#barang").focus();
          return false;
        },
        error: function(data){
          console.log(data);
        }
      });
    @endcannot
  });
  $('#tbodyDetail').on('click', '.btnRemove', function(){
      $(this).closest('tr').remove();
      // hitung_total();
  });
});
//end ready function

$("#qtyrqag").keyup(function(){
  var stok = $("#qtystockgudang").val();
  if (parseFloat($("#qtyrqag").val()) > parseFloat(stok)){
    $('#qtyrqag').val(0);
    swal("Ups!","Qty melebihi stock gudang.","warning");
  }else{
    if (stok == ''){
      $('#qtyrqag').val('');
      swal("Ups!", "Stock gudang kosong", "error");
    }
  }
});

$('#modalDetail').on('shown.bs.modal', function () {
  $('#barang').focus();
});

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
          { "data": "namabarang" },
          { "data": "satuan" },
          { "data": "qtyrqag" },
          { "data": "catatandetail" },
          // { "data": "action" },
      ],
  });
}

function simpanDetail(){
  $.ajax({
    type      : 'POST',
    url       :'{{route("antargudangv2.detail.tambah")}}',
    data      : $(this).serialize(),
    dataType  : "json",
    success   : function(data){
      console.log(data);
      $("#barang").val('');
      $("#barangid").val('');
      $("#satuan").val('');
      $("#qtyrqag").val(0);
      $("#catatandetail").val('');
      $("#barang").focus();
      reloadtabledetail();
      return false;
    },
    error: function(data){
      console.log(data);
    }
  });
}


</script>
@endpush
