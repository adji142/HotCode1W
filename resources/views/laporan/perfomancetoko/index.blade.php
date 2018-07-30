@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Toko</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.perfomancetoko.index') }}">Perfomance Toko</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Perfomance Toko</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<form class="form-horizontal" style="margin-bottom: 10px;">
						<div class="form-group">
							<label class="col-sm-1 control-label">Tanggal</label>
							<div class="col-sm-2">
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="Tgl. Mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
							</div>
							<div class="col-sm-2">
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="Tgl. Selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">Nama Toko</label>
							<div class="col-sm-11">
								<div class="input-group" style="margin-bottom: 0;">
									<div class="input-group-addon"><i class="fa fa-search"></i></div>
									<input type="text" id="toko" class="form-control" placeholder="Nama Toko">
									<input type="hidden" id="tokoid" class="form-control" >
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">Nama Sales</label>
							<div class="col-sm-11">
								<div class="input-group" style="margin-bottom: 0;">
									<div class="input-group-addon"><i class="fa fa-search"></i></div>
									<input type="text" id="salesman" class="form-control" placeholder="Nama Sales">
									<input type="hidden" id="salesmanid" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">Gudang</label>
							<div class="col-sm-11">
								<div class="input-group" style="margin-bottom: 0;">
									<div class="input-group-addon"><i class="fa fa-search"></i></div>
									<input type="text" id="gudang" class="form-control" placeholder="Gudang">
									<input type="hidden" id="gudangid" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">Kota</label>
							<div class="col-sm-11">
								<input type="text" id="kota" class="form-control" placeholder="Kota">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">WIL ID</label>
							<div class="col-sm-11">
								<input type="text" id="wilid" class="form-control" placeholder="WIL id">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-11">
								<p>
									<a onclick="print()" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
								</p>
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
	var table;

    lookuptoko();
    lookupsalesman();
    lookupsubcabang();

	$(document).ready(function(){
		$(".tgl").inputmask();
	});

    function print(){
        var tglmulai   = $('#tglmulai').val();
        var tglselesai = $('#tglselesai').val();
        var toko       = $('#tokoid').val();
        var salesman   = $('#salesmanid').val();
        var gudang     = $('#gudangid').val();
        var kota       = $('#kota').val();
        var wilid      = $('#wilid').val();
        url = '{{route("laporan.perfomancetoko.cetak")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&toko='+toko+'&salesman='+salesman+'&gudang='+gudang+'&kota='+kota+'&wilid='+wilid;
        urlBulan = '{{route("laporan.perfomancetoko.cetakBulan")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&toko='+toko+'&salesman='+salesman+'&gudang='+gudang+'&kota='+kota+'&wilid='+wilid;
        window.open(url);
        window.open(urlBulan);
    }
</script>
@endpush