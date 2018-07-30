@extends('layouts.default')
@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed.index') }}">Fixed Route</a></li>
    <li class="breadcrumb-item active">Form Update Per Toko</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Per Toko </h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" id="tableDetail">
          <form class="form-horizontal" method="post" action="#">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="idtoko" name="idtoko" value="{{$toko->id}}">
          <input type="hidden" id="idkaryawan" name="idkaryawan" value="{{$karyawan->id}}">
          <input type="hidden" id="bulan" name="bulan" value="{{$bulan}}">
          <input type="hidden" id="tglserver" name="tglserver" class="form-control"  value="{{ Carbon\Carbon::now()->format("d-m-Y") }}">
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
            <div id="content">
              @foreach($data as $key=> $d)
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="col-md-3 col-sm-3 col-xs-12">
                            {{-- <div class="checkbox checkboxaction" style="text-align: right"><input type="checkbox" class="flat disabled" value='{{$d->id}}' disabled=""></div> --}}
                            <input type="hidden" id="idkunjungan" name="idkunjungan[]" class="form-control" value="{{$d->id}}" tabindex="-1">
                      </div>
                      <label class="control-label col-md-3 col-sm-3  col-xs-12" for="tglkunjungan" style="text-align: right">Kunjungan {{$key+1}} : </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="tglkunjung" name="tglkunjung[]" class="tgl form-control" placeholder="Tgl. Kunjung"  data-inputmask="'mask': 'd-m-y'" value="{{$d->tglkunjung}}" readonly tabindex="-1"="">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                           <select class="form-control" name="realisasi[]" id="realisasi" disabled>
                            <option value="">PILIH REALISASI</option>
                            @foreach($realisasi as $r)
                             <option value="{{$r->realisasi}}" {{ $d->keteranganrealisasi == $r->realisasi ? 'selected' : '' }}>{{$r->realisasi}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan :</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="kettambahan" name="kettambahan[]" class="form-control" value="{{$d->keterangantambahan}}" readonly tabindex="-1"="">
                        </div>
                      </div>
                  </div>
               </div>
            @endforeach
            </div>
             <div id="contentshow" style="display: none;">
              @foreach($data as $key=> $d)
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="col-md-3 col-sm-3 col-xs-12">
                            {{-- <div class="checkbox checkboxaction" style="text-align: right"><input type="checkbox" class="flat" value='{{$d->id}}'></div> --}}
                            <input type="hidden" id="idkunjungan" name="idkunjungan[]" class="form-control" value="{{$d->id}}" tabindex="-1">
                      </div>
                      <label class="control-label col-md-3 col-sm-3  col-xs-12" for="tglkunjungan" style="text-align: right">Kunjungan {{$key+1}} : </label>
                    </div>
                  </div>
                  <div class="col-md-6">

                     @if($d->tglkunjung > Carbon\Carbon::now()->format("d-m-Y"))
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="tglkunjung" name="tglkunjung[]" class="tgl form-control" placeholder="Tgl. Kunjung"  data-inputmask="'mask': 'd-m-y'" value="{{$d->tglkunjung}}">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                           <select class="form-control" name="realisasi[]" id="realisasi" readonly tabindex="-1">
                            <option value="">PILIH REALISASI</option>
                            @foreach($realisasi as $r)
                             <option value="{{$r->realisasi}}" {{ $d->keteranganrealisasi == $r->realisasi ? 'selected' : '' }}>{{$r->realisasi}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan :</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="kettambahan" name="kettambahan[]" class="form-control" value="{{$d->keterangantambahan}}" readonly tabindex="-1">
                        </div>
                      </div>
                     @else
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="tglkunjung" name="tglkunjung[]" class="tgl form-control" placeholder="Tgl. Kunjung"  data-inputmask="'mask': 'd-m-y'" value="{{$d->tglkunjung}}" readonly tabindex="-1"="">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi : </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                           <select class="form-control" name="realisasi[]" id="realisasi">
                            <option value="">PILIH REALISASI</option>
                            @foreach($realisasi as $r)
                             <option value="{{$r->realisasi}}" {{ $d->keteranganrealisasi == $r->realisasi ? 'selected' : '' }}>{{$r->realisasi}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan :</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="kettambahan" name="kettambahan[]" class="form-control" value="{{$d->keterangantambahan}}" tabindex="-1" >
                        </div>
                      </div>
                     @endif
                     
                  </div>
               </div>
            @endforeach
            </div>
          </div>
          <div class="form-group">
            <div class="ln_solid"></div>
            <div class="col-md-12" align="right">
              <button type="button" id="EditData" class="btn btn-primary">Edit</button>
              {{-- <button type="button" id="BatalKunjungan" class="btn btn-danger" disabled="">Batal Kunjungan</button> --}}
              @can('fixedroute.updatepertoko')
              <button type="button" id="SimpanData" class="save btn btn-success" disabled="">Simpan</button>
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
        $(".tgl").inputmask("99/99/9999");
        $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
          checkboxClass: 'icheckbox_flat-green',
        });
      });
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

    // $('#BatalKunjungan').on('click', function(){
    //   var count = 0;
    //   var datadetail = [];
    //   $('#tableDetail').find('input[type="checkbox"]:checked:not(:disabled)').each(function () {
    //     count += 1;
    //     datadetail.push($(this).val());
    //   });
    //   //var idtoko    = datadetail;
    //   if (count > 0) {
    //       $.ajaxSetup({
    //         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
    //       });
        
    //      $.ajax({
    //         type: 'POST',
    //         url: '{{route("fixedroute.batalkunjungan")}}',
    //         data: {
    //           idkunjungan : datadetail
    //         },
    //         success: function(data){
    //           if (data.success == false) {
    //              swal('Ups!', data.message ,'error');
    //           }
    //           else{
    //             swal('Yes!', 'Kunjungan berhasil dibatalkan' ,'success');
    //           }
            
    //         }
    //       });
    //   }
    //   else {
    //     swal('Ups!', 'Belum ada kunjungan yang dipilih.','error');
    //   }
    // });

    $('#EditData').on('click', function(){
      $('#content').hide();
      $('#contentshow').show();
      $('#BatalKunjungan').attr('disabled',false);
      $('#SimpanData').attr('disabled',false);
      $('#EditData').attr('disabled',true);
    });

    $('#SimpanData').on('click', function(e){
        e.preventDefault();
        @cannot('fixedroute.updatepertoko')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var ele  = $('input[name="tglkunjung[]"]');
        var ele2 = $('select[name="realisasi[]"]');
        var ele3 = $('input[name="kettambahan[]"]');
        var ele4 = $('input[name="idkunjungan[]"]');
        var tglkunjung  = [];
        var realisasi   = [];
        var kettambahan = [];
        var idkunjungan = [];

        for (var i = 0; i < ele.length; i++) {
          tglkunjung[i] =$(ele[i]).val();
          realisasi[i]  =$(ele2[i]).val();
          kettambahan[i]=$(ele3[i]).val();
          idkunjungan[i]=$(ele4[i]).val();
        }
        console.log(realisasi);
       
        $.ajax({
          type: 'POST',
          url: '{{route("fixedroute.updatepertoko")}}',
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: {
            tglkunjung      : tglkunjung,
            realisasi       : realisasi,
            kettambahan     : kettambahan,
            idkunjungan     : idkunjungan,
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


  
