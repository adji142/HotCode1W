@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('aktiva.closingfa') }}">Close Penyusutan Aktiva</a></li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Close Penyusutan Aktiva</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-9">
                    <form class="form-inline" style="margin-bottom: 10px;" id="frmClosing" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label style="margin-right: 10px;">Bulan</label>
                            <select class="select2_single form-control bulan opsi" tabindex="-1" name="bulan" id="bulan">
                                @foreach($bulan as $k => $v)
                                    <option value="{{$k}}" {{ date('m') == $k ? 'selected' : '' }}>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="margin-right: 10px;">Tahun</label>
                            <select class="select2_single form-control tahun opsi" tabindex="-1" name="tahun" id="tahun">
                                @foreach($tahun as $t)
                                    <option value="{{$t}}" {{ date('Y') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                        </div>
                            {{--  <div class="form-group">
                                <label></label>
                                <input type="submit" class="btn-success" style="padding: 6px 12px; border-radius: 3px; text-align:center;" value="Tampilkan Data"></input>
                        </div>  --}}
                            <div class="form-group">
                                <label></label>
                                <button type="submit" class="btn btn-success"> <i class="fa fa-print"></i> Closing </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $("#frmClosing").on("submit", function(e){
            e.preventDefault();

            var bulan = parseInt($("#bulan").val());
            var tahun = parseInt($("#tahun").val());

            $.ajax({
                type        : "POST",
                headers     : {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                url         : '{{ route("aktiva.closingsubmit") }}',
                data        : { tahun : tahun, bulan : bulan },
                dataType    : "json",
                success     : function(data){
                    swal("Ups!", data.message, "success");
                    // if (data.success == true){
                    //     swal("Ups!", "Closing Berhasil.", "success");
                    // }else{
                    //     swal("Ups!", data.message, "warning");
                    // }
                    if (data.success == true){
                        swal("Success!", "Closing Berhasil.", "success");
                    }else{
                        swal("Ups!", data.message, "error");
                    }
                },
            });

        });
    });
</script>
@endpush