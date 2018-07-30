@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Security</a></li>
    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Manajemen Roles</a></li>
    <li class="breadcrumb-item active">{{ ($dataSet) ? 'Ubah Data' : 'Tambah Data'}}</li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ ($dataSet) ? 'Ubah : '.$dataSet->name : 'Tambah Baru'}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($dataSet)
                @can('role.ubah')
                <form class="form-horizontal form-label-left" method="POST" action="{{route('role.ubah',$dataSet->id)}}">
                @endcan
                @elsecan('role.tambah')
                <form class="form-horizontal form-label-left" method="POST" action="{{route('role.tambah')}}">
                @else
                <form class="form-horizontal form-label-left" method="POST" action="#">
                @endcan
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Role</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="name" value="{{ $dataSet ? $dataSet->name : ''}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Hak Akses Laporan</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="check-box">
                                <ul class="no-style">
                                @foreach($customreportgroupObj as $row)
                                <li>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="flat check_tree" name="customreportgroup[{{$row->id}}]" {{$row->access ? "checked" : ""}}> {{$row->nama}}
                                        </label>
                                    </div>
                                </li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Permission</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="check-box">
                                <?php echo $checkbox_loop; ?>
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                       <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="{{route('role.index')}}" class="btn btn-primary">Batal</a>
                            @if($dataSet)
                                @can('role.ubah')
                                <button type="submit" class="btn btn-success">Simpan</button>
                                @endcan
                            @elsecan('role.tambah')
                            <button type="submit" class="btn btn-success">Simpan</button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    $(function() {
        $('.check_tree').on('ifClicked', function(e){
            var $this     = $(this),
                checked   = $this.prop("checked"),
                container = $this.closest("li"),
                parents   = container.parents("li").first().find('.check_tree').first(),
                all       = true;

            // Centang juga anak2nya
            container.find('.check_tree').each(function() {
                if(checked) {
                    $(this).iCheck('uncheck');
                }else{
                    $(this).iCheck('check');
                }
            });

            // Cek sodaranya
            container.siblings().each(function() {
                return all = ($(this).find('.check_tree').first().prop("checked") === false);
            });

            // Cek bapaknya
            if(checked) {
                parents.iCheck('check');
            }
        });

        $('.check_tree').on('ifChanged', function(e){
                var $this     = $(this),
                    checked   = $this.prop("checked"),
                    parents   = $this.closest("li").parents("li").first().find('.check_tree').first(),
                    all       = true;
            
                // Cek bapaknya
                if(checked) {
                    parents.iCheck('check');
                }
        });
    });
    </script>
@endpush
