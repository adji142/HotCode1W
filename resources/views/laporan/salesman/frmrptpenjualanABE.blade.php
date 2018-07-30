@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</a></li>
<li class="breadcrumb-item">Salesman</li>
<li class="breadcrumb-item active">Rekap Penjualan Sales (ABE)</li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Rekap Penjualan Sales (ABE)</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="formTambah" class="form-horizontal form-label-left" method="GET" action="#">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglmulai">Tanggal</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" name="tglmulai" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}"  tabindex="1">
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <input type="text" name="tglselesai" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}"  tabindex="2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subcabang">Gudang</label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <select id="subcabang" name="subcabang" tabindex="3"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cabang">Cabang</label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <select id="cabang" name="cabang" tabindex="4"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" id="gudang" name="gudang" class="form-control col-md-7 col-xs-12" readonly tabindex="-1" value="{{session('subcabang')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" id="cabang1" name="cabang1" class="form-control col-md-7 col-xs-12" readonly tabindex="-1" value="{{Session('cabang')}}">
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
                <a href="{{route('home')}}" class="btn btn-primary">Kembali</a>
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
            
            $("#subcabang").selectize({
                maxItems : 1,
            });
        
            $("#cabang").selectize({
                maxItems : 1,
            });

            $("#harga1").prop("checked", true);

            $('input.iCheck').iCheck({
                radioClass: 'iradio_flat-green'
            });
        });

        $.ajax({
            url : "{{route('home.data')}}",
            type : "GET",
            success : function(data){
                var result = data.data;
                var temp = $("#subcabang")[0].selectize;

                $.each(result, function(k, v){
                    temp.addOption({
                        text : v.kodesubcabang + " | " + v.namasubcabang,
                        value : v.id,
                    });
                });

                temp.setValue($("#gudang").val());
            }
        });

        $.ajax({
            url : "{{route('cabang.data')}}",
            type : "GET",
            success : function(data){
                var result = data.data;
                var temp = $("#cabang")[0].selectize;

                $.each(result, function(k, v){
                    temp.addOption({
                        text : v.kodecabang + " | " + v.namacabang,
                        value : v.kodecabang,
                    });
                });

                temp.setValue($("#cabang1").val());
            }
        });

        $("#btnProses").click(function(e){
            e.preventDefault();
            $("#formTambah").submit();

            var url = "{{route('rptpenjualanabe.report')}}" + 
            "?tglmulai=" + $("#tglmulai").val() + 
            "&tglselesai=" + $("#tglselesai").val() +
            "&subcabang=" + $("#subcabang").val() + 
            "&cabang=" + $("#cabang").val() +
            "&jenis=" + 1;

            var myWindow = window.open(url);

            var url = "{{route('rptpenjualanabe.report')}}" + 
            "?tglmulai=" + $("#tglmulai").val() + 
            "&tglselesai=" + $("#tglselesai").val() +
            "&subcabang=" + $("#subcabang").val() + 
            "&cabang=" + $("#cabang").val() +
            "&jenis=" + 2;

            var myWindow = window.open(url);

            var url = "{{route('rptpenjualanabe.report')}}" + 
            "?tglmulai=" + $("#tglmulai").val() + 
            "&tglselesai=" + $("#tglselesai").val() +
            "&subcabang=" + $("#subcabang").val() + 
            "&cabang=" + $("#cabang").val() +
            "&jenis=" + 3;

            var myWindow = window.open(url);

            var url = "{{route('rptpenjualanabe.report')}}" + 
            "?tglmulai=" + $("#tglmulai").val() + 
            "&tglselesai=" + $("#tglselesai").val() +
            "&subcabang=" + $("#subcabang").val() + 
            "&cabang=" + $("#cabang").val() +
            "&jenis=" + 4;

            var myWindow = window.open(url);

            var url = "{{route('rptpenjualanabe.reportNetto')}}" + 
            "?tglmulai=" + $("#tglmulai").val() + 
            "&tglselesai=" + $("#tglselesai").val() +
            "&subcabang=" + $("#subcabang").val() + 
            "&cabang=" + $("#cabang").val();

            var myWindow = window.open(url);
        });
</script>
@endpush
