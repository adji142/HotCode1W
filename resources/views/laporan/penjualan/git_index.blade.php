@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.penjualan.git') }}">Laporan Good In Transit</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Laporan Good In Transit</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<form class="form-inline">
                        <div class="form-group">
                            <label style="margin-right: 10px;">Periode</label>
                            <input type="text" id="periode" class="tgl form-control" placeholder="Periode" data-inputmask="'mask': 'd-m-y'" value="{{date('d-m-Y')}}">
                            <input type="hidden" id="type" value="all">
                        </div>
                        <div class="form-group">
                            <label style="margin-right: 10px;">Cabang</label>
                            <select id="cabang" name="cabang" class="form-control"></select>
                        </div>
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-11">
								<a onclick="print()" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
							</div>
						</div>
	                </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(".tgl").inputmask();

    $.ajax({
        url : "{{route('home.data')}}",
        type : "GET",
        success : function(data){
            var result = data.data;
            var temp = $("#cabang")[0].selectize;

            $.each(result, function(k, v){
                $('#cabang').append($('<option>', {
                    text : v.kodesubcabang + " | " + v.namasubcabang,
                    value : v.id,
                }));
            });

            $("#cabang").val("{{session('subcabang')}}").change();
        }
    });

	function print(){
        var periode = $('#periode').val();
        var cabang = $('#cabang').val();
        var type  = $('#type').val();

        var popup1  = window.open("about:blank", "_blank");
        popup1.location = '{{route("laporan.penjualan.gitpreview")}}'+'?periode='+periode+'&cabang='+cabang+'&type='+type;
	}
</script>
@endpush