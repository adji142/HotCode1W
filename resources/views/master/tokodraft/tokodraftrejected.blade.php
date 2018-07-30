
@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item">Toko Draft Rejected</li>
@endsection

@section('main_container')
<div class="mainmain">
  <div class="row">
    @if(session('message'))
    <div class="alert alert-{{session('message')['status']}}">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {{ session('message')['desc'] }}
    </div>
    @endif
    <div class="x_panel">
      <div class="x_title">
        <h2>Daftar Toko Draft Rejected</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float: right;">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="col-md-9 left">
          <a href="{{route('tokodraft.index')}}" class="btn btn-primary"><i class="fa fa-book"></i> Toko Draft</a>
        </div>
        <div class="col-md-3 right">
          <input type="text" id="cari" name="cari" class="form-control" placeholder="CARI DATA" title="Tekan Enter Untuk Mencari">
        </div>

        <table id="datatables" class="table table-bordered table-striped display nowrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Pilihan</th>
              <th>STS</th>
              <th>ID Tokodraft</th>
              <th>Nama Toko</th>
              <th>Toko00</th>
              <th>Alamat</th>
              <th>Kota</th>
              <th>No Telp</th>
              <th>Penanggung Jawab</th>
              <th>Kd Toko</th>
              <th>Daerah</th>
              <th>D/L</th>
              <th>Piutang B</th>
              <th>Piutang J</th>
              <th>Plafon</th>
              <th>ClassID</th>
              <th>ToJual</th>
              <th>ToRetPot</th>
              <th>JangkaWaktuKredit</th>
              <th>Cabang2</th>
              <th>Tgl1st</th>
              <th>Status</th>
              <th>Thn Berdiri</th>
              <th>Ruko</th>
              <th>Fax</th>
              <th>Bangunan</th>
              <th>Habis Kontrak</th>
              <th>Nama Pemilik</th>
              <th>No KTP</th>
              <th>Jml Cabang</th>
              <th>Jenis Kelamin</th>
              <th>Tempat Lahir</th>
              <th>Email</th>
              <th>No Rekening</th>
              <th>Nama Bank</th>
              <th>No Member</th>
              <th>Hobi</th>
              <th>No NPWP</th>
              <th>Jml Sales</th>
              <th>Catatan</th>
              <th>Hari Kirim</th>
              <th>Kode Pos</th>
              <th>Grade</th>
              <th>Plafon1st</th>
              <th>Bentrok</th>
              <th>Status Aktif</th>
              <th>Hari Sales</th>
              <th>Alamat Rumah</th>
              <th>Pengelola</th>
              <th>Tgl Lahir</th>
              <th>HP</th>
              <th>Kinerja</th>
              <th>Bidang Usaha</th>
              <th>I_Spart</th>
              <th>LastUpdatedBy</th>
              <th>LastUpdatedTime</th>
              <th>Jenis Produk</th>
              <th>Nama Kecamatan</th>
              <th>Nama Kota</th>
            </tr>
          </thead>
          <tbody id="myTable">
          @if(count($tokodraft) > 0)
            @foreach ($tokodraft as $value)
            <tr>
              <td class="text-left">{{$loop->iteration}}.</td>
              <td class="text-center">
              @can('tokodraft.batalrejected') 
                <a href="{{route('tokodraft.batalrejected',$value->rowid)}}" class="btn btn-xs btn-warning" onclick="return confirm('Yakin Batalkan Rejected Data ini ?')" title="Batalkan Rejected"><i class="fa fa-undo"></i></a>
              @endcan
              </td>
              <td>
                @if ($value->statusACC == 'REJECTED')
                R
                @endif
              </td>
              <td>{{$value->tokodraftID}}</td>
              <td>{{$value->NamaToko}}</td>
              <td>{{$value->Toko00}}</td>
              <td>{{$value->Alamat}}</td>
              <td>{{$value->Kota}}</td>
              <td>{{$value->Telp}}</td>
              <td>{{$value->PenanggungJawab}}</td>
              <td>{{$value->KodeToko}}</td>
              <td>{{$value->Daerah}}</td>
              <td>
                @if ($value->No_Toko == null)
                0
                @else
                {{$value->No_Toko}}
                @endif
              </td>
              <td>{{ number_format($value->PiutangB,0,',','.') }}</td>
              <td>{{ number_format($value->PiutangJ,0,',','.') }}</td>
              <td>{{ number_format($value->Plafon,0,',','.') }}</td>
              <td>{{$value->ClassID}}</td>
              <td>{{ number_format($value->ToJual,0,',','.') }}</td>
              <td>{{ number_format($value->ToRetPot,0,',','.') }}</td>
              <td>{{$value->JangkaWaktuKredit}}</td>
              <td>{{$value->Cabang2}}</td>
              <td>
                @if ($value->Tgl1st == null)
                {{$value->Tgl1st}}
                @else
                {{date('d-m-Y', strtotime($value->Tgl1st))}}
                @endif
              </td>
              <td>
                @if ($value->Status == null)
                0
                @else
                {{$value->Status}}
                @endif
              </td>
              <td>{{$value->ThnBerdiri}}</td>
              <td>
                @if ($value->StatusRuko == null)
                False
                @else
                True
                @endif
              </td>
              <td>{{$value->Fax}}</td>
              <td>{{$value->Bangunan}}</td>
              <td>
                @if ($value->Habis_kontrak == null)
                {{$value->Habis_kontrak}}
                @else
                {{date('d-m-Y', strtotime($value->Habis_kontrak))}}
                @endif
              </td>
              <td>{{$value->nama_pemilik}}</td>
              <td>{{$value->no_ktp}}</td>
              <td>{{$value->JmlCabang}}</td>
              <td>{{$value->jenis_kelamin}}</td>
              <td>{{$value->tempat_lhr}}</td>
              <td>{{$value->email}}</td>
              <td>{{$value->no_rekening}}</td>
              <td>{{$value->nama_bank}}</td>
              <td>{{$value->no_member}}</td>
              <td>{{$value->hobi}}</td>
              <td>{{$value->no_npwp}}</td>
              <td>{{$value->JmlSales}}</td>
              <td>{{$value->Catatan}}</td>
              <td>{{$value->HariKirim}}</td>
              <td>{{$value->KodePos}}</td>
              <td>{{$value->Grade}}</td>
              <td>{{ number_format($value->Plafon1st,0,',','.') }}</td>
              <td>
                @if ($value->Bentrok == null)
                0
                @else
                {{$value->Bentrok}}
                @endif
              </td>
              <td>
                <div>
                  <center>
                    <input type="checkbox" class="flat" @if( $value ? $value->StatusAktif == '1' : '' ) checked="" @endif>
                  </center>
                </div>
              </td>
              <td>{{$value->HariSales}}</td>
              <td>{{$value->AlamatRumah}}</td>
              <td>{{$value->Pengelola}}</td>
              <td>
                @if ($value->TglLahir == null)
                {{$value->TglLahir}}
                @else
                {{date('d-m-Y', strtotime($value->TglLahir))}}
                @endif
              </td>
              <td>{{$value->HP}}</td>
              <td>{{$value->Kinerja}}</td>
              <td>{{$value->BidangUsaha}}</td>
              <td>{{$value->I_spart}}</td>
              <td>{{$value->LastUpdatedBy}}</td>
              <td>{{date('d-m-Y H:i:s', strtotime($value->LastUpdatedTime))}}</td>
              <td>{{$value->Jenis_produk}}</td>
              <td>{{$value->NamaKecamatan}}</td>
              <td>{{$value->NamaKabKota}}</td>
            </tr>
            @endforeach
          @else
            <tr>
                <td colspan="10" class="text-center">Tidak Ada Data.</td>
            </tr>
          @endif
          </tbody>
        </table>
      
      </div>

    </div>

  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){

    $('#datatables').DataTable({
      searching: false,
      select: true,
      paging: false,
      "scrollX": true,
      "scrollY": 345,
      responsive: true
    });

    $("#cari").change(function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    $(".open-modal").click(function(){
       var id = $(this).data('id');
       var statusacc = $(this).data('status');
       var alasan = $(this).data('alasan');

       $(".modal-title").text( statusacc );
       $(".modal-body #id").val( id );
       $(".modal-body #statusacc").val( statusacc );
       $(".modal-body #alasan").val( alasan );
    });


  });

</script>
@endpush
