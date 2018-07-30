@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Toko</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.pengirimangudang.index') }}">Pengiriman Gudang</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Pengiriman Gudang</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-9">
						<form class="form-inline">
			                <div class="form-group">
			                	<label style="margin-right: 10px;">Tanggal</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="Tanggal" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
			                </div>
		                </form>
					</div>
					<div class="col-md-3 text-right">
						<p>
							<a onclick="print()" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
	var table;
	$(document).ready(function(){
		$(".tgl").inputmask();
	});

	function print(){
		var tglmulai = $('#tglmulai').val();
		url = '{{route("laporan.pengirimangudang.cetak")}}'+'?tgl='+tglmulai;
		window.open(url);
	}
</script>
@endpush