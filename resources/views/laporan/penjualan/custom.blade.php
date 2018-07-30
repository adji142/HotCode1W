@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.penjualan.custom') }}">Laporan Custom</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Laporan Custom</h2>
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
							<label class="col-sm-1 control-label">Pilih Laporan</label>
                            <div class="col-sm-2">
                            <select class="select2_single form-control laporan opsi" tabindex="-1" name="laporan" id="laporan">
                                @foreach($laporan as $k => $v)
                                    <option value="{{$k}}">{{$v['nama']}}</option>
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
        var laporan = $('#laporan').val();
        var popup1  = window.open("about:blank", "_blank");
        popup1.location = '{{route("laporan.penjualan.custompreview")}}'+'?laporan='+laporan;

	}
</script>
@endpush