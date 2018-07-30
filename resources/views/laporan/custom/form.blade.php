@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Custom</li>
    <li class="breadcrumb-item"><a href="{{ route('laporan.custom.list') }}">Daftar Query Laporan Custom</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>{{ $report ? 'Ubah' : 'Tambah' }} Query Laporan Custom</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
            <div class="x_content">
            @if($report)
                @can('laporan.custom.ubah')
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="{{route('laporan.custom.ubah',$report->id)}}">
                @endcan
            @else
                @can('laporan.custom.tambah')
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="{{route('laporan.custom.tambah')}}">
                @else
                <form class="form-horizontal form-label-left" id="formCustom" method="POST" action="#">
                @endcan
            @endif
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nama">Nama Laporan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <input style="text-transform: none;" required type="text" id="nama" name="nama" class="form-control col-md-7 col-xs-12" value="{{ old('nama') ? old('nama') : ($report ? $report->nama : '')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Pilih Group Laporan</label>
                        <div class="col-md-4 col-sm-4">
                        <select class="select2_single form-control" name="customreportgroupid" id="customreportgroupid">
                            @foreach($group as $val)
                                <option value="{{$val->id}}" {{ old('customreportgroupid') && old('customreportgroupid') == $val->id ? 'selected' : ($report && $report->customreportgroupid == $val->id ? 'selected' : '')}}>{{$val->nama}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Aktif ?</label>
                        <div class="col-md-4 col-sm-4">
                            <label>
                                <input type="checkbox" id="aktif" name="aktif" class="js-switch" value="1" {{ old('aktif') ? 'checked' :  (($report) && $report->aktif ? 'checked' : '')}} />
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="isi_query">Query Laporan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <textarea style="text-transform: none;" required id="isi_query" name="isi_query" class="form-control col-md-7 col-xs-12" rows="15">{{ old('isi_query') ? old('isi_query') : ($report ? $report->query : '')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="field">Field Laporan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12" id="wadah_field">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <strong>Nama Field di Preview</strong>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <strong>Tipe Field</strong>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <strong>nama_field_di_query</strong>
                                </div>
                            </div>
                            @if($report && !empty($report->field))
                            @foreach($report->field as $field)
                            <div class="row baris_field">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="ketfield[]" name="ketfield[]" class="form-control" value="{{$field->ketfield}}">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select required class="select2_single form-control" id="tipefield[]" name="tipefield[]">
                                        @foreach($tipe_field as $k=>$v)
                                            <option value="{{$k}}" {{$k == $field->tipefield ? 'selected' : ''}}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="namafield[]" name="namafield[]" class="form-control" value="{{$field->namafield}}">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    @if($loop->last)
                                    <button type="button" class="btn btn-success addField">Tambah Field</button>
                                    <button type="button" class="btn btn-danger remField" style="display: none;">Hapus Field</button>
                                    @else
                                    <button type="button" class="btn btn-success addField" style="display: none;">Tambah Field</button>
                                    <button type="button" class="btn btn-danger remField">Hapus Field</button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="row baris_field">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="ketfield[]" name="ketfield[]" class="form-control" value="">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select required class="select2_single form-control" id="tipefield[]" name="tipefield[]">
                                        @foreach($tipe_field as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="namafield[]" name="namafield[]" class="form-control" value="">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <button type="button" class="btn btn-success addField">Tambah Field</button>
                                    <button type="button" class="btn btn-danger remField" style="display: none;">Hapus Field</button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="field">Parameter Laporan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12" id="wadah_param">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <strong>Nama Parameter di Preview</strong>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <strong>Tipe Parameter</strong>
                                </div>
                            </div>
                            @if($report && !empty($report->param))
                            @foreach($report->param as $param)
                            <div class="row baris_param">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="ketparam[]" name="ketparam[]" class="form-control" value="{{$param->ketparam}}">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select required class="select2_single form-control" id="tipeparam[]" name="tipeparam[]">
                                        @foreach($tipe_field as $k=>$v)
                                            <option value="{{$k}}" {{$k == $param->tipeparam ? 'selected' : ''}}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    @if($loop->last)
                                    <button type="button" class="btn btn-success addParameter">Tambah Parameter</button>
                                    <button type="button" class="btn btn-danger remParameter" style="display: none;">Hapus Parameter</button>
                                    @else
                                    <button type="button" class="btn btn-success addParameter" style="display: none;">Tambah Parameter</button>
                                    <button type="button" class="btn btn-danger remParameter">Hapus Parameter</button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="row baris_param">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input style="text-transform: none;" required type="text" id="ketparam[]" name="ketparam[]" class="form-control" value="">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select required class="select2_single form-control" id="tipeparam[]" name="tipeparam[]">
                                        @foreach($tipe_field as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <button type="button" class="btn btn-success addParameter">Tambah Parameter</button>
                                    <button type="button" class="btn btn-danger remParameter" style="display: none;">Hapus Parameter</button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-push-2 col-sm-push-2 col-md-8 col-sm-8 col-xs-12">
                            @can('laporan.custom.tambah')
                            <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                            @endcan
                            <a href="{{route('laporan.custom.index')}}" class="btn btn-primary">Kembali</a>
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
    $(document).on('click','.addField',function(){
        var duplicate = $(this).parents('.baris_field').clone();
        duplicate.find('input').val('');

        // clearing data
        $(this).parents('.baris_field').find('.addField').hide();
        $(this).parents('.baris_field').find('.remField').show();

        $('#wadah_field').append(duplicate);
    });

    $(document).on('click','.remField',function(){
        $(this).parents('.baris_field').remove();
    });

    $(document).on('click','.addParameter',function(){
        var duplicate = $(this).parents('.baris_param').clone();
        duplicate.find('input').val('');

        // clearing data
        $(this).parents('.baris_param').find('.addParameter').hide();
        $(this).parents('.baris_param').find('.remParameter').show();

        $('#wadah_param').append(duplicate);
    });

    $(document).on('click','.remParameter',function(){
        $(this).parents('.baris_param').remove();
    });

    $('#formCustom').on('submit',function(e){
        e.preventDefault();

    @if($report)
        @cannot('laporan.custom.ubah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        <?php $bisa = true;?>
        @endcan
    @else
        @cannot('laporan.custom.tambah')
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