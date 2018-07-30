@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Security</li>
    <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Manajemen Permission</a></li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        @if(session('message'))
        <div class="alert alert-{{session('message')['status']}}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            {{ session('message')['desc'] }}
        </div>
        @endif
        <div class="x_panel">
            <div class="x_title">
                <h2>Daftar Permission</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @can('permission.sidebar')
                <p><a href="{{ route('permission.sidebar')}}" class="btn btn-success"><i class="fa fa-refresh"></i> Perbarui Menu Sidebar</a></p>
                @endcan
                <table id="datatable" class="table table-striped table-bordered display nowrap mbuhsakarepku" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Ikon</th>
                            <th>Menu?</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($dataObj) > 0)
                        @foreach($dataObj as $n=>$data)
                        <tr>
                            <th scope="row">{{ ++$n }}</th>
                            <td>{{ $data->nested }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->slug }}</td>
                            <td><i class="fa {{ $data->icon }}"></i>&nbsp;&nbsp;&nbsp;{{ $data->icon }}</td>
                            <td>{{ ($data->asmenu) ? 'Yes' : 'No' }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center">no records</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')\
<!-- Datatables -->
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            dom     : 'lrtp',//lrtip -> lrtp
            // scrollY : 190,
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
        });
    });
</script>
<!-- /Datatables -->
@endpush