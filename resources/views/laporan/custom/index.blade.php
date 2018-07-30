@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Custom</li>
    <li class="breadcrumb-item"><a href="{{ route('laporan.custom.index') }}">Cetak Laporan Custom</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Cetak Laporan Custom</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
                    <form class="form-horizontal" style="margin-bottom: 10px;" id="formLaporan">
                        <div class="form-group">
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">Pilih Laporan</label>
                            <div class="col-md-4 col-sm-4">
                            <select class="select2_single form-control" name="laporan" id="laporan">
                                @foreach($group as $g)
                                    <optgroup label="-- {{$g->nama}} --">
                                    @foreach($report as $val)
                                        @if($g->id == $val->customreportgroupid)
                                        <option value="{{$val->id}}">{{$val->nama}}</option>
                                        @endif
                                    @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div id="buat_parameter"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
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
    $(document).ready(function(){
        $('#laporan').on('change',function(){

            if($(this).val()){
                $.ajax({
                    type    : 'GET',
                    url     : '{{ route('laporan.custom.parameter',null)}}/'+$(this).val(),
                    dataType: "json",
                    success : function(data){
                        $('#buat_parameter').html(data).find('.inputmask').inputmask();
                    },
                    error: function(data){
                        $('#buat_parameter').html('');
                    }
                });
            }
        }).change();
    });

    $('#formLaporan').on('submit', function(e){
        e.preventDefault();
        var popup  = window.open("about:blank", "_blank");
        popup.location = '{{route("laporan.custom.preview")}}'+'?'+$('#formLaporan').serialize();
        return false;
    });

    function print(){
        var popup  = window.open("about:blank", "_blank");
        popup.location = '{{route("laporan.custom.preview")}}'+'?'+$('#formLaporan').serialize();
    }
</script>
@endpush