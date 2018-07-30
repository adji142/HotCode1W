@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css">
@endpush
@section('main_container')
    <a href="#" download class="btn btn-success" id="dl-excel">Save to file Excel</a>

    <div class="row">
        <div class="col-md-12">
            <strong>Satandar Stock</strong>
            <table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table1" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">Kd. Barang</th>
                        <th width="10%" class="text-center">Nama Barang</th>
                        <th width="10%" class="text-center">Rata2 Jual</th>
                        <th width="10%" class="text-center">Min. Index (Rp)</th>
                        <th width="10%" class="text-center">Max. Index</th>
                        <th width="10%" class="text-center">Stock Min.</th>
                        <th width="10%" class="text-center">Stock Max.</th>
                        <th width="10%" class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($a as $key => $val)
                        <tr>
                            <td>{{$val->kodebarang}}</td>
                            <td>{{$val->namabarang}}</td>
                            <td>{{$val->rataratajual}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
            <br/>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js"></script>
@endpush