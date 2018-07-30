  @extends('layouts.default')

  @section('breadcrumb')
      <li class="breadcrumb-item">Laporan</a></li>
      <li class="breadcrumb-item">Salesman</li>
      <li class="breadcrumb-item active">Laporan SO, DO, BO dan Nota</li>
  @endsection

  @section('main_container')
    <div class="mainmain">
      <div class="row">
        <div class="x_panel">
          <div class="x_title">
            <h2>Laporan SO, DO, BO dan Nota</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <form id="formView" class="form-horizontal form-label-left" method="POST" action="{{route('rptperbandingansodonotabo.report')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="col-md-12 col-xs-12">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglmulai">Tanggal</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <div class="col-md-5 col-sm-5 col-xs-12">
                      <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="@if(session('tglmulai')!=''){{session('tglmulai')}}@else{{date('01/m/Y')}}@endif"  tabindex="1">
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12 text-center">
                      <label><b>-</b></label>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                      <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="@if(session('tglselesai')!=''){{session('tglselesai')}}@else{{date('d/m/Y')}}@endif"  tabindex="2">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label for="jenis" class="control-label col-md-3 col-sm-3 col-xs-12">Jenis</label>
                    <div class="col-md-7 col-sm-7 col-xs-12" style="padding:0.5%;" >
                        <input type="radio" name="jenis" value="sales" class="iCheck" CHECKED> Salesman     
                        <input type="radio" name="jenis" value="toko" class="iCheck"> Toko
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kelompokbarang">Kelompok Barang</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <select id="kelompokbarang" name="kelompokbarang" tabindex="4"></select>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <button type="submit" id="btnProses" class="btn btn-success">Proses</button>
              </div>
            </form>
            
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6">
                <a href="#" class="btn btn-primary">Kembali</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection

  @push('scripts')
    <script src="{{asset('assets/js/microplugin.js')}}"></script>
    <script src="{{asset('assets/js/sifter.js')}}"></script>
    <script src="{{asset('assets/js/selectize.js')}}"></script>
    <!-- Form Validation -->
    <!-- <script src="{{asset('assets/js/validator.js')}}"></script> -->

    <script type="text/javascript">
      var table1;
      $(document).ready(function(){
        $(".tgl").inputmask();

        $('input.iCheck').iCheck({
                radioClass: 'iradio_flat-green'
            });

        $("#kelompokbarang").selectize({
          maxItems : 1,
        });
      });

      $.ajax({
        url : '{{ route("kelompokbarang.data") }}',
        type : "GET",
        success : function(data){
          var result = data.data;
          var temp = $("#kelompokbarang")[0].selectize;
          
          // console.log(result);
          $.each(result, function(k, v){
            temp.addOption({
              text : v.kode + " | " + v.keterangan,
              value : v.kode,
            });
          });
        }
      });

      $("#btnProses").click(function(e){
        e.preventDefault();

        var url = "{{route('rptperbandingansodonotabo.report')}}" + 
          "?tglmulai=" + $("#tglmulai").val() + 
          "&tglselesai=" + $("#tglselesai").val() +
          "&jenis=" + $('input[name="jenis"]:checked').val() + 
          "&kelompokbarang=" + $("#kelompokbarang").val();

          var myWindow = window.open(url, "", "width=1200,height=600");

        var url2 = "{{route('rptperbandingansodonotabodetail.report')}}" + 
          "?tglmulai=" + $("#tglmulai").val() + 
          "&tglselesai=" + $("#tglselesai").val() +
          "&jenis=" + $('input[name="jenis"]:checked').val() + 
          "&kelompokbarang=" + $("#kelompokbarang").val();

        var myWindow2 = window.open(url2, "", "width=1200,height=600");

      });
    </script>
  @endpush
