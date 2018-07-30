@extends('layouts.default')
@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item"><a href="{{ route('salesorder.index') }}">Standar Stok</a></li>
@endsection
@section('main_container')
	<div class="row">
		<div class="x_panel">
			<form method="POST" action="{{route('standarstock.laporanStandarStock')}}">
				<div class="form-group">
					<div class="col-lg-4">
		            	<label style="margin-right: 10px;">Bulan</label>
		            	<input type="hidden" name="_token" value="{{csrf_token()}}">
		            	<select class="form-control bul" value="{{date('m')}}" name="bulan">
		            		<option value="">Pilih Bulan</option>
		            		<option value="01">Januari</option>
		            		<option value="02">Februari</option>
		            		<option value="03">Maret</option>
		            		<option value="04">April</option>
		            		<option value="05">Mei</option>
		            		<option value="06">Juni</option>
		            		<option value="07">Juli</option>
		            		<option value="08">Agustus</option>
		            		<option value="09">September</option>
		            		<option value="10">Oktober</option>
		            		<option value="11">November</option>
		            		<option value="12">Desember</option>
		            	</select>
	            	</div>
	            	<div class="col-lg-4">
						<label>Tahun</label>
						<input type="text" id="tahun" name="tahun" class="form-control" placeholder="tahun"value="{{date('Y')}}">
					</div>
					<div class="col-lg-4" style="padding-top: 24px;">
						<button type="submit" class="btn btn-success">Print</button>
					</div>
	            </div>
            </form>
		</div>
	</div>
@endsection