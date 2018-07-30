@extends('layouts.report')

@section('main_container')

<div id="headbar">
    <div class="col-md-12">

        <a id="btnSimpanXls" target="_blank" href="@if(isset($linkExcel)) {{ $linkExcel }} @else {{ '#' }} @endif" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
        <a id="btnSimpanPdf" target="_blank" href="@if(isset($linkPdf)) {{ $linkPdf }} @else {{ '#' }} @endif" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a>
        <a id="btnCetak" class="btn btn-info"><i class="fa fa-file-word-o"></i> Cetak </a>
        <a id="btnClose" class="btn btn-danger" onclick="window.close()"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

    </div>

    <div class="col-md-12">
        <hr>
    </div>
    
</div>

<input type="hidden" value="@if(isset($img1)) {{ $img1 }} @endif" id="logoImgSrc">

<div id="databar" class="col-md-12">
    @if($laporan == "penjualanhi")
        @include('laporan.salesman.rptpenjualanhi')
    @elseif($laporan == "rekapitulasisales")
        @include('laporan.salesman.rptrekapitulasipenjualan')
    @elseif($laporan == "penjualanabe")
        @include('laporan.salesman.rptpenjualanABE')
    @elseif($laporan == "salesmannotajual")
        @include('laporan.salesman.rptsalesmannotajual')
    @endif
</div>

@endsection

@push('scripts')
    <!-- Datatables -->
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.keyTable.min.js')}}"></script>
    <script type="text/javascript">
        var table ;

        $("#dataTablesSpinner").hide();
        $(document).ready(function(){
            $.each($(".table-data"), function(k,v){
                setTimeout(function(){
                    table = $(v).DataTable({
                        dom         : 'lrtp',
                        select      : true,
                        scrollY     : 250,
                        scrollX     : true,
                        order       : [[ 1, 'asc' ]],
                        columnDefs  : [
                            {
                                "targets" : [0],
                                "visible" : false,
                            }
                        ],
                        bPaginate   : false,
                    });
                }, 3000);
            });

            // RefreshData();
            // $("#logoImg").css("background-image", "url(" + $("#logoImgSrc").val() + ")");
            // $("#logoImg").css("background-repeat", "no-repeat");
            // $("#logoImg").css("background-size", "100% 100%");
        });

        function fnCetak()
        {
            var divContents = $("#databar").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>SAS - {{ $laporan }}</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        $("#btnCetak").click(function(){
            // table.column(10).visible(false);
            fnCetak();

            setTimeout(function(){ window.close(); }, 300);
        });

        $("a[id^='btn']").click(function(e){
            setTimeout(function(){ window.close(); }, 300);
            // if($(this).attr("href") != "")
            // {
            //  setTimeout(function(){ 
            //      $.ajax({
            //          url : "{{ route('laporan.hapus') }}",
            //          success : function(data)
            //          {
            //              if(data.success)
            //              {
                                
            //                  // window.close();
            //              }
            //          }
            //      })
            //  }, 300);
            // }
        });


    </script>
@endpush