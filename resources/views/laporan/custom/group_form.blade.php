@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Custom</li>
    <li class="breadcrumb-item"><a href="{{ route('laporan.customgroup.index') }}">Daftar Group Laporan Custom</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>{{ $group ? 'Ubah' : 'Tambah' }} Group Laporan Custom</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
            <div class="x_content">
            @if($group)
                @can('laporan.customgroup.ubah')
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="{{route('laporan.customgroup.ubah',$group->id)}}">
                @endcan
            @else
                @can('laporan.customgroup.tambah')
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="{{route('laporan.customgroup.tambah')}}">
                @else
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="#">
                @endcan
            @endif
                    {!! csrf_field() !!}
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nama">Nama Group Laporan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <input style="text-transform: none;" required type="text" id="nama" name="nama" class="form-control col-md-7 col-xs-12" value="{{ old('nama') ? old('nama') : ($group ? $group->nama : '')}}">
                        </div>
                    </div>
                    <div class="item form-group">
                        <div class="col-md-push-2 col-sm-push-2 col-md-8 col-sm-8 col-xs-12">
                            @can('laporan.customgroup.tambah')
                            <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                            @endcan
                            <a href="{{route('laporan.customgroup.index')}}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#formCustom').on('submit',function(e){
        e.preventDefault();

    @if($group)
        @cannot('laporan.customgroup.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        <?php $bisa = true;?>
        @endcan
    @else
        @cannot('laporan.customgroup.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        <?php $bisa = true;?>
        @endcan
    @endif

    @if($bisa == true)
        $.ajax({
            type    : 'POST',
            url     : $('#formCustom').attr('action'),
            data    : $('#formCustom').serialize(),
            dataType: "json",
            success : function(data){
                if(data.status) {
                    swal({
                        title: "Sukses!",
                        text : data.desc,
                        type : "success"
                    },function(){
                        window.location.href = data.redirect;
                    });
                }else{
                    swal('Ups!', data.desc,'error');
                }
                return false;
            },
            error: function(data){
                console.log(data);
            }
        });
    });
    @endif
});
</script>
@endpush