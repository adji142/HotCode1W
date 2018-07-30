@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Security</a></li>
    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Manajemen Roles</a></li>
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
                <h2>Daftar Role</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @can('role.tambah')
                <p><a href="{{ route('role.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Role</a></p>
                @endcan

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Role</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($dataObj) > 0)
                        @foreach($dataObj as $n=>$data)
                        <tr>
                            <th scope="row">{{ ++$n }}</th>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->createdby }}</td>
                            <td>{{ $data->updatedby }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td>{{ $data->updated_at }}</td>
                            <td>
                                @can('role.hapus')
                                <form class="formDelete" action="{{ route('role.hapus', $data->id) }}" method="post">
                                @else
                                <form class="formDelete" action="#" method="post">
                                @endcan
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    @can('role.user')
                                    <a href="{{ route('role.user',$data->id)}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Daftar User</a>
                                    @else
                                    <a href="#" class="btn btn-xs btn-info" onclick="this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;"><i class="fa fa-eye"></i> Daftar User</a>
                                    @endcan
                                    @can('role.ubah')
                                    <a href="{{ route('role.ubah',$data->id)}}" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Ubah</a>
                                    @else
                                    <a href="#" class="btn btn-xs btn-warning" onclick="this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;"><i class="fa fa-pencil"></i> Ubah</a>
                                    @endcan
                                    @can('role.hapus')
                                    <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                    @else
                                    <a href="#" class="btn btn-xs btn-danger" onclick="this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;"><i class="fa fa-trash"></i> Hapus</a>
                                    @endcan
                                </form>
                            </td>
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
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data",
                }
            });
        });
    </script>
    <!-- /Datatables -->
@endpush