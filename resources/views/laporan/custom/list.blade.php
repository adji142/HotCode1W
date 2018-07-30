@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Custom</li>
  	<li class="breadcrumb-item"><a href="{{ route('laporan.custom.list') }}">Daftar Query Laporan Custom</a></li>
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
				<h2>Daftar Query Laporan Custom</h2>
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
                            @can('laporan.custom.tambah')
                            <a href="{{route('laporan.custom.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Query Laporan</a>
                            @endcan
                        </p>
                    </div>
                </div>
				<div class="row">
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Nama Laporan</th>
                                <th>Kel. Laporan</th>
                                <th>Query Laporan</th>
                                <th>Field Laporan</th>
                                <th>Updated By</th>
                                <th>Updated On</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Nama Laporan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Kel. Laporan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Query Laporan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Field Laporan</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Updated By</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Updated On</a>
                        </p>
                    </div>
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

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("laporan.custom.data") }}',
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
                { "data": "nama"  , "className": "menufilter textfilter"},
                { "data": "namagroup"  , "className": "menufilter textfilter"},
                { "data": "query" , "className": "menufilter textfilter"},
                { "data": "field" , "className": "menufilter textfilter"},
                { "data": "lastupdatedby" , "className": "menufilter textfilter"},
                { "data": "lastupdatedon" , "className": "menufilter numberfilter"},
            ],
        });

        @can('laporan.custom.hapus')
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