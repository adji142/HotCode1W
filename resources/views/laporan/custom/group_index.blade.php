@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Custom</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.customgroup.index') }}">Daftar Group Laporan Custom</a></li>
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
				<h2>Daftar Group Laporan Custom</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <p>
                            @can('laporan.customgroup.tambah')
                            <a href="{{route('laporan.customgroup.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Group Laporan</a>
                            @endcan
                        </p>
                    </div>
                </div>
				<div class="row">
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Nama Group Laporan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var table,  table_index, table_focus, tipe_edit;
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var custom_search = [];
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var column_index  = 0;
    var last_index    = '';

    $(document).ready(function() {
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
        });

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("laporan.customgroup.data") }}',
                type: "POST",
                data: { custom_search : custom_search},
            },
            order   : [[ 1, 'asc' ]],
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            stateLoadParams: function (settings, data) {
                for (var i = 0; i < data.columns.length; i++) {
                    data.columns[i].search.search = "";
                }
            },
            columns: [
                { "data": "action", "orderable": false},
                { "data": "nama"},
            ],
        });

        @can('laporan.customgroup.hapus')
        $(document).on('submit', '.formDelete', function(e){
            e.preventDefault();
            var token  = $(this).closest('tr').find('[name="_token"]:first').val();
            var action = $(this).attr('action');

            swal({
                title: "Apakah Anda yakin?",
                text : "Data akan terhapus!",
                type : "warning",
                showCancelButton  : true,
                confirmButtonClass: "btn-danger",
                confirmButtonText : "Ya, hapus data!",
                confirmButtonColor: "#ec6c62",
                closeOnConfirm    : false
            },
            function(){
                $.ajax({
                    type   : 'DELETE',
                    url    : action,
                    headers: {'X-CSRF-TOKEN': token},
                    success: function(data){
                        swal({
                            title: "Sukses",
                            text : "Data berhasil dihapus.",
                            type : "success"
                        },function(){
                            table.ajax.reload();
                        })
                    },
                    error: function(data){
                        console.log(data);
                        swal("Ups!", "Terjadi kesalahan pada sistem.", "error");
                    }
                });
            });
        });
        @endcan
    });
</script>
@endpush