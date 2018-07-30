@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.perfoutlet.index') }}">Perfomance Outlet</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Perfomance Outlet</h2>
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
							<label class="col-sm-1 control-label">Periode</label>
                            <div class="col-sm-2">
                            <select class="select2_single form-control bulan opsi" tabindex="-1" name="bulan" id="bulan">
                                @foreach($bulan as $k => $v)
                                    <option value="{{$k}}" {{ date('m') == $k ? 'selected' : '' }}>{{$v}}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="col-sm-1">
                                <select class="select2_single form-control tahun opsi" tabindex="-1" name="tahun" id="tahun">
                                    @foreach($tahun as $t)
                                        <option value="{{$t}}" {{ date('Y') == $t ? 'selected' : '' }}>{{$t}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Type</label>
                            <div class="col-sm-3">
                                <select class="select2_single form-control" tabindex="-1" name="type" id="type">
                                    @foreach($type as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
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
	function print(){
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var type  = $('#type').val();
		url = '{{route("laporan.perfoutlet.cetak")}}'+'?bulan='+bulan+'&tahun='+tahun+'&type='+type;
		window.open(url);
	}
</script>
@endpush