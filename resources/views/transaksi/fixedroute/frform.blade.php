@extends('layouts.default')
@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed.index') }}">Fixed Route</a></li>
    <li class="breadcrumb-item active">Tambah Tanggal Kunjungan</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tambah Tanggal Kunjungan </h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @can('fixedroute.tambahtglkunjungan')
          <form class="form-horizontal" id="tambahTanggal" method="post" action="{{route('fixedroute.tambahtglkunjungan')}}">
          @else
          <form class="form-horizontal" id="tambahTanggal" method="post" action="#">
          @endcan
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="idtoko" name="idtoko" value="{{$toko->id}}">
          <input type="hidden" id="idkaryawan" name="idkaryawan" value="{{$karyawan->id}}">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="namatoko">Toko</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="namatoko"  class="form-control" tabindex="-1" value="{{$toko->namatoko}}" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="namasales">Nama Sales</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="namasales" class="form-control" tabindex="-1" value="{{$karyawan->namakaryawan}}" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="alamat" class="form-control" tabindex="-1" value="{{$toko->alamat}}" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodesales">Kode Sales</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kodesales" class="form-control" tabindex="-1" value="{{$karyawan->kodesales}}" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kota" class="form-control" tabindex="-1" value="{{$toko->kota}}" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <br/><br/>
            @foreach($data as $key=> $d)
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                    <label class="control-label col-md-12 col-xs-12" for="tglkunjungan" style="text-align: right">Kunjungan {{$key+1}} : </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="tglkunjungan" class="form-control" value="{{$d->tglkunjung}}" readonly tabindex="-1">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="realisasi" class="form-control" value="{{$d->keteranganrealisasi}}" readonly tabindex="-1">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan :</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="kettambahan" class="form-control" value="{{$d->keterangantambahan}}" readonly tabindex="-1">
                        </div>
                      </div>
                  </div>
               </div>
            @endforeach
            @for($i=count($data)+1;$i<=5;$i++)
              <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                    <label class="control-label col-md-12 col-xs-12" for="tglkunjungan" style="text-align: right">Kunjungan {{$i}} : </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="tglkunjung" name="tglkunjung[]" class="tgl form-control" placeholder="Tgl. Kunjung"  data-inputmask="'mask': 'd-m-y'" onfocus="enabled(this)" onblur="tutup(this)">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <select class="form-control" name="realisasi[]" id="realisasi" disabled="">
                             <option value="">PILIH REALISASI</option>
                            @foreach($realisasi as $r)
                             <option value="{{$r->realisasi}}">{{$r->realisasi}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan :</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="kettambahan" class="form-control" tabindex="-1" name="kettambahan[]" disabled="">
                        </div>
                      </div>
                  </div>
               </div>
            @endfor
          </div>
          <div class="form-group">
            <div class="ln_solid"></div>
            <div class="col-md-12" align="right">
              @can('fixedroute.tambahtglkunjungan')
              <button type="submit" id="tambahTanggal" class="btn btn-success">Simpan</button>
              @endcan
              <a href="{{route('fixed.index')}}" class="btn btn-default">Batal</a>
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
    var table1;

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
     });
    function enabled(ele){
      //console.log($(ele).parent().parent().next().next().find('#kettambahan'));
      var realisasi =$(ele).parent().parent().next().find('#realisasi');
      var ket  =$(ele).parent().parent().next().next().find('#kettambahan');
      $(realisasi).prop('disabled',false);
      $(ket).attr('disabled',false);
    }
    function tutup(ele){
      
      var realisasi =$(ele).parent().parent().next().find('#realisasi');
      var ket  =$(ele).parent().parent().next().next().find('#kettambahan');
      if(!$(ele).val()){
        $(realisasi).prop('disabled',true);
        $(ket).attr('disabled',true);
      }
    }

   $('#tambahTanggal').submit(function(e){
        e.preventDefault();
        @cannot('fixedroute.tambahtglkunjungan')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var ele= $('input[name="tglkunjung[]"]');
        var ele2= $('select[name="realisasi[]"]');
        var ele3= $('input[name="kettambahan[]"]');
        var tglkunjung = [];
        var realisasi = [];
        var kettambahan = [];
        for (var i = 0; i < ele.length; i++) {
          tglkunjung[i] =$(ele[i]).val();
          realisasi[i]  =$(ele2[i]).val();
          kettambahan[i]=$(ele3[i]).val();
        }
        console.log(realisasi);
       
        $.ajax({
          type: 'POST',
          url: '{{route("fixedroute.tambahtglkunjungan")}}',
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: {
            tglkunjung      : tglkunjung,
            realisasi       : realisasi,
            kettambahan     : kettambahan,
            idtoko          : $('#idtoko').val(),
            idkaryawan      : $('#idkaryawan').val(),
          },
          success: function(data){
            console.log(data);
            if (data.success) {
              swal("Yes!",data.message, "success");
              window.location.href = '{{route("fixed.index")}}';
            }
            else{
              swal("Ups!",data.message, "error");
            }
           
          },
          error: function(data){
            console.log(data);
          }
        });
        @endcannot
      });
  </script>
@endpush


  
