@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
    <a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
    <table class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">ID WIL</th>
                <th rowspan="2" class="text-center">NAMA TOKO</th>
                <th rowspan="2" class="text-center">P/N</th>
                <th rowspan="2" class="text-center">RODA</th>
                <th rowspan="2" class="text-center">NO TELP</th>
                <th rowspan="2" class="text-center">KODE SALES</th>
                <th rowspan="2" class="text-center">TANGGAL FIXEDROUTE</th>
                <th rowspan="2" class="text-center">SISA PLAFON</th>
                <th rowspan="2" class="text-center">SKU</th>
                <th rowspan="2" class="text-center">EFEKTIF OA%</th>
                <th rowspan="2" class="text-center">TARGET TOKO</th>
                <th colspan="2" class="text-center">ACHIEVEMENT</th>
                @for($i=1;$i<=31;$i++)
                <th rowspan="2" class="text-center">{{$i}}</th>
                @endfor
            </tr>
            <tr>
                <th class="text-center">RP.</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            {!! $data !!}
        </tbody>
    </table>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js "></script>
<script type="text/javascript">
    $(document).ready(function(){
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        // Strip $ from salary column to make it numeric
                        return column === 8 ?
                            data.replace( /[$,]/g, '' ) :
                            data;
                    }
                }
            }
        };

        $('#table_report').DataTable({
            dom: 'rt',
            paging: false,
            scrollX: true,
            scrollY: '70vh',
            scrollCollapse: true,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    download: 'open',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    title: 'Laporan Perfomance Outlet"'
                }
            ]
        });
    });
</script>
@endpush