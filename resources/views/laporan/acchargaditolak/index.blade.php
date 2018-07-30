@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Toko</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.acchargaditolak.index') }}">ACC Harga Ditolak</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>ACC Harga Ditolak</h2>
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
			                	<label style="margin-right: 10px;">Tgl. PiL</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="Tgl. mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
								<label>-</label>
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="Tgl. selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
								<select class="select2_single form-control search" tabindex="-1" name="subcabang" id="subcabang" disabled>
                                    @foreach($subcabang as $sub)
                                    @if(session('subcabang') == $sub->id)
                                    <option value="{{$sub->id}}" selected>{{$sub->kodesubcabang}} | {{$sub->namasubcabang}}</option>
                                    @else
                                    <option value="{{$sub->id}}">{{$sub->kodesubcabang}} | {{$sub->namasubcabang}}</option>
                                    @endif
                                    @endforeach
                                </select>
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
		var tglselesai = $('#tglselesai').val();
		var subcabang = $('#subcabang').val();
		url = '{{route("laporan.acchargaditolak.cetak")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&subcabang='+subcabang;
		window.open(url);
	}
</script>
@endpush