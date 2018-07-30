@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</a></li>
<li class="breadcrumb-item">Salesman</li>
<li class="breadcrumb-item active">Nota Jual</li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Salesman Nota Jual</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="{{route('rptsalesmannotajual.report')}}" target="_blank">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class="col-md-12 col-xs-12">
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglmulai">Tgl. Nota</label>
                          <div class="col-md-3 col-sm-3 col-xs-12">
                              <input type="text" name="tglmulai" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}"  tabindex="1">
                          </div>
                          <div class="col-md-3 col-sm-3">
                              <input type="text" name="tglselesai" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}"  tabindex="2">
                          </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salesid">Salesman<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="namasales" name="namasales" class="form-control" placeholder="Sales" autocomplete="off" required="required">
                          <input type="hidden" id="salesid" name="salesid">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodesales">Kode Sales</label>
                        <div class="form-group row">
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="kodesales" name="kodesales" class="form-control" tabindex="-1" readonly>
                          </div>
                      </div>

                       <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="wilid">WilID</label>
                          <div class="col-md-7 col-sm-7 col-xs-12">
                              <input type="text" id="wilid" name="wilid" tabindex="4" class="form-control" placeholder="wilid" autocomplete="off"></select>
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pengirimsubcabang">Cabang Pengirim</label>
                          <div class="col-md-7 col-sm-7 col-xs-12">
                              <select id="pengirimsubcabang" name="pengirimsubcabang" tabindex="3"></select>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-12">
                    <a href="{{route('home')}}" class="btn btn-primary">Kembali</a>
                    <button type="submit" id="btnProses" class="btn btn-success">Proses</button>
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end of modal pilih Sales -->
@endsection

@push('scripts')
<script src="{{asset('assets/js/microplugin.js')}}"></script>
<script src="{{asset('assets/js/sifter.js')}}"></script>
<script src="{{asset('assets/js/selectize.js')}}"></script>
<!-- Form Validation -->
<!-- <script src="{{asset('assets/js/validator.js')}}"></script> -->

<script type="text/javascript">
    var table1;
    lookupsalesman();
    $(document).ready(function(){
        $(".tgl").inputmask();
        
        $("#pengirimsubcabang").selectize({
            maxItems : 1,
        });
    
    });

    $.ajax({
        url : "{{route('home.data')}}",
        type : "GET",
        success : function(data){
            var result = data.data;
            var temp = $("#pengirimsubcabang")[0].selectize;

            $.each(result, function(k, v){
                temp.addOption({
                    text : v.kodesubcabang + " | " + v.namasubcabang,
                    value : v.id,
                });
            });
        }
    });

    $("#btnProses").click(function(e){
        e.preventDefault();
        $("#formTambah").submit();
        // var url = "{{route('rptpenjualanhi.report')}}" + 
        // "?tglmulai=" + $("#tglmulai").val() + 
        // "&tglselesai=" + $("#tglselesai").val() +
        // "&omsetsubcabang=" + $("#omsetsubcabang").val() + 
        // "&pengirimsubcabang=" + $("#pengirimsubcabang").val() +
        // "&harga=" + ($("#harga1")[0].checked ? "hbeli" : "hppa"); 

        // var myWindow = window.open(url, "", "width=1200,height=600");
    });

</script>
@endpush
