
@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{ route('tokodraft.index') }}">Toko Draft</a></li>
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
        <h2>Daftar Toko Draft</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float: right;">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="row">
          <div class="col-md-6 col-xs-12">
              <form class="form-inline">
                <div class="form-group">
                  <select class="select2_single form-control search" tabindex="-1" name="tipestatus" id="tipestatus">
                      <option value="">SEMUA</option>
                      <option value="APPROVED">SUDAH APPROVED</option>
                      <option value="-">BELUM APPROVED</option>
                  </select>
                </div>
              </form>
            </div>
            <div class="col-md-6 text-right">
              @can('tokodraft.tambah')
                <a href="{{route('tokodraft.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Toko Draft</a>
              @endcan
              @can('tokodraft.rejected')
                <a href="{{route('tokodraft.rejected')}}" class="btn btn-default">Toko Draft Rejected</a>
              @endcan
            </div>
        </div>

        <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
          <thead>
            <tr>
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
              <th>Piutang B</th>
              <th>Piutang J</th>
              <th>Plafon</th>
              <th>ClassID</th>
              <th>ToJual</th>
              <th>ToRetPot</th>
              <th>JangkaWaktuKredit</th>
              <th>TglOb</th>
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
              <th>Kecamatan</th>
              <th>Nama Kota</th>
            </tr>
          </thead>
          <tbody id="myTable"></tbody>
        </table>
        <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> STS</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> ID Tokodraft</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Nama Toko</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Toko00</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Alamat</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Kota</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i> No Telp</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="8"><i id="eye8" class="fa fa-eye"></i> Penanggung Jawab</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="9"><i id="eye9" class="fa fa-eye"></i> Kd Toko</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="10"><i id="eye10" class="fa fa-eye"></i> Daerah</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="11"><i id="eye11" class="fa fa-eye"></i> Piutang B</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="12"><i id="eye12" class="fa fa-eye"></i> Piutang J</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="13"><i id="eye13" class="fa fa-eye"></i> Plafon</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="14"><i id="eye14" class="fa fa-eye"></i> ClassID</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="15"><i id="eye15" class="fa fa-eye"></i> ToJual</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="16"><i id="eye16" class="fa fa-eye"></i> ToRetPot</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="17"><i id="eye17" class="fa fa-eye"></i> JangkaWaktuKredit</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="18"><i id="eye18" class="fa fa-eye"></i> TglOb</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="19"><i id="eye19" class="fa fa-eye"></i> Thn Berdiri</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="20"><i id="eye20" class="fa fa-eye"></i> Ruko</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="21"><i id="eye21" class="fa fa-eye"></i> Fax</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="22"><i id="eye22" class="fa fa-eye"></i> Bangunan</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="23"><i id="eye23" class="fa fa-eye"></i> Habis Kontrak</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="24"><i id="eye24" class="fa fa-eye"></i> Nama Pemilik</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="25"><i id="eye25" class="fa fa-eye"></i> No KTP</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="26"><i id="eye26" class="fa fa-eye"></i> Jml Cabang</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="27"><i id="eye27" class="fa fa-eye"></i> Jenis Kelamin</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="28"><i id="eye28" class="fa fa-eye"></i> Tempat Lahir</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="29"><i id="eye29" class="fa fa-eye"></i> Email</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="31"><i id="eye31" class="fa fa-eye"></i> No Rekening</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="32"><i id="eye32" class="fa fa-eye"></i> Nama Bank</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="33"><i id="eye33" class="fa fa-eye"></i> No Member</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="34"><i id="eye34" class="fa fa-eye"></i> Hobi</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="35"><i id="eye35" class="fa fa-eye"></i> No NPWP</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="36"><i id="eye36" class="fa fa-eye"></i> Jml Sales</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="37"><i id="eye37" class="fa fa-eye"></i> Catatan</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="38"><i id="eye38" class="fa fa-eye"></i> Hari Kirim</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="39"><i id="eye39" class="fa fa-eye"></i> Kode Pos</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="40"><i id="eye40" class="fa fa-eye"></i> Grade</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="41"><i id="eye41" class="fa fa-eye"></i> Plafon1st</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="42"><i id="eye42" class="fa fa-eye"></i> Bentrok</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="43"><i id="eye43" class="fa fa-eye"></i> Status Aktif</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="44"><i id="eye44" class="fa fa-eye"></i> Hari Sales</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="45"><i id="eye45" class="fa fa-eye"></i> Alamat Rumah</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="46"><i id="eye46" class="fa fa-eye"></i> Pengelola</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="47"><i id="eye47" class="fa fa-eye"></i> Tgl Lahir</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="48"><i id="eye48" class="fa fa-eye"></i> HP</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="49"><i id="eye49" class="fa fa-eye"></i> Kinerja</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="50"><i id="eye50" class="fa fa-eye"></i> Bidang Usaha</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="51"><i id="eye51" class="fa fa-eye"></i> I_Spart</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="52"><i id="eye52" class="fa fa-eye"></i> LastUpdatedBy</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="53"><i id="eye53" class="fa fa-eye"></i> LastUpdatedTime</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="54"><i id="eye54" class="fa fa-eye"></i> Jenis Produk</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="55"><i id="eye55" class="fa fa-eye"></i> Kecamatan</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis" data-column="56"><i id="eye56" class="fa fa-eye"></i> Nama Kota</a>
            </p>
        </div>
      
      </div>

    </div>

  </div>
</div>

<!-- modals -->
<div class="modal fade bs-approve-modal-sm" id="modal-status" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal form-label-left" method="POST" action="{{route('tokodraft.approve')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="rowid" name="rowid">
        <input type="hidden" id="statusacc" name="statusacc">
        <input type="hidden" id="useracc" name="useracc" value="{{ auth()->user()->name }}"> 
        
        <div class="form-group">
          <label>Alasan</label>
            <textarea id="alasan" name="alasan" class="form-control" rows="3" value=""></textarea>
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success" >Simpan</button>
        </div>
       </form>
      </div>

    </div>
  </div>
</div>
<!-- /modals -->

<!-- modals -->
<div class="modal fade bs-approve-modal-sm" id="modal-history" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4>Toko Draft History</h4>
      </div>
      <div class="modal-body">
        <div style="overflow-x: auto; height: 400px">
        <table id="historyData" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
            <tr>
              <th>Tglobsolete</th>
              <th>ID Tokodraft</th>
              <th>Nama Toko</th>
              <th>Toko00</th>
              <th>Alamat</th>
              <th>Kota</th>
              <th>No Telp</th>
              <th>Penanggung Jawab</th>
              <th>Kd Toko</th>
              <th>Daerah</th>
              <th>Piutang B</th>
              <th>Piutang J</th>
              <th>Plafon</th>
              <th>ClassID</th>
              <th>ToJual</th>
              <th>ToRetPot</th>
              <th>JangkaWaktuKredit</th>
              <th>TglOb</th>
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
            <tbody></tbody>
        </table>
        <div style="cursor: pointer; margin-top: 10px;" class="hidden">
          <p>
            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
            <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tglobsolete</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> ID Tokodraft</a> 
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Nama Toko</a> | 
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Toko00</a> | 
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Alamat</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Kota</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="7"><i id="eye-detail7" class="fa fa-eye"></i> No Telp</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="8"><i id="eye-detail8" class="fa fa-eye"></i> Penanggung Jawab</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="9"><i id="eye-detail9" class="fa fa-eye"></i> Kd Toko</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="10"><i id="eye-detail10" class="fa fa-eye"></i> Daerah</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="11"><i id="eye-detail11" class="fa fa-eye"></i> Piutang B</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="12"><i id="eye-detail12" class="fa fa-eye"></i> Piutang J</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="13"><i id="eye-detail13" class="fa fa-eye"></i> Plafon</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="14"><i id="eye-detail14" class="fa fa-eye"></i> ClassID</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="15"><i id="eye-detail15" class="fa fa-eye"></i> ToJual</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="16"><i id="eye-detail16" class="fa fa-eye"></i> ToRetPot</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="17"><i id="eye-detail17" class="fa fa-eye"></i> JangkaWaktuKredit</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="18"><i id="eye-detail18" class="fa fa-eye"></i> TglOb</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="19"><i id="eye-detail19" class="fa fa-eye"></i> Thn Berdiri</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="20"><i id="eye-detail20" class="fa fa-eye"></i> Ruko</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="21"><i id="eye-detail21" class="fa fa-eye"></i> Fax</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="22"><i id="eye-detail22" class="fa fa-eye"></i> Bangunan</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="23"><i id="eye-detail23" class="fa fa-eye"></i> Habis Kontrak</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="24"><i id="eye-detail24" class="fa fa-eye"></i> Nama Pemilik</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="25"><i id="eye-detail25" class="fa fa-eye"></i> No KTP</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="26"><i id="eye-detail26" class="fa fa-eye"></i> Jml Cabang</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="27"><i id="eye-detail27" class="fa fa-eye"></i> Jenis Kelamin</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="28"><i id="eye-detail28" class="fa fa-eye"></i> Tempat Lahir</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="29"><i id="eye-detail29" class="fa fa-eye"></i> Email</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="30"><i id="eye-detail30" class="fa fa-eye"></i> No Rekening</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="31"><i id="eye-detail31" class="fa fa-eye"></i> Nama Bank</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="32"><i id="eye-detail32" class="fa fa-eye"></i> No Member</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="33"><i id="eye-detail33" class="fa fa-eye"></i> Hobi</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="34"><i id="eye-detail34" class="fa fa-eye"></i> No NPWP</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="35"><i id="eye-detail35" class="fa fa-eye"></i> Jml Sales</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="36"><i id="eye-detail36" class="fa fa-eye"></i> Catatan</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="37"><i id="eye-detail37" class="fa fa-eye"></i> Hari Kirim</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="38"><i id="eye-detail38" class="fa fa-eye"></i> Kode Pos</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="39"><i id="eye-detail39" class="fa fa-eye"></i> Grade</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="40"><i id="eye-detail40" class="fa fa-eye"></i> Plafon1st</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="41"><i id="eye-detail41" class="fa fa-eye"></i> Bentrok</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="42"><i id="eye-detail42" class="fa fa-eye"></i> Status Aktif</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="43"><i id="eye-detail43" class="fa fa-eye"></i> Hari Sales</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="44"><i id="eye-detail44" class="fa fa-eye"></i> Alamat Rumah</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="45"><i id="eye-detail45" class="fa fa-eye"></i> Pengelola</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="46"><i id="eye-detail46" class="fa fa-eye"></i> Tgl Lahir</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="47"><i id="eye-detail47" class="fa fa-eye"></i> HP</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="48"><i id="eye-detail48" class="fa fa-eye"></i> Kinerja</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="49"><i id="eye-detail49" class="fa fa-eye"></i> Bidang Usaha</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="50"><i id="eye-detail50" class="fa fa-eye"></i> I_Spart</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="51"><i id="eye-detail51" class="fa fa-eye"></i> LastUpdatedBy</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="52"><i id="eye-detail52" class="fa fa-eye"></i> LastUpdatedTime</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="53"><i id="eye-detail53" class="fa fa-eye"></i> Jenis Produk</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="54"><i id="eye-detail54" class="fa fa-eye"></i> Nama Kecamatan</a> |
            <a style="padding:0 5px;" class="toggle-vis-2" data-column="55"><i id="eye-detail55" class="fa fa-eye"></i> Nama Kota</a> |
          </p>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- /modals -->

<!-- modals -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4>Toko Draft Detail</h4>
      </div>
      <div class="modal-body" style="overflow-y: auto;height: 450px">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="tbldetail" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Kolom</th>
                    <th class="text-center">Nilai</th>
                  </tr>
                </thead>
                <tbody id="tblDblClickData">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- /modals -->

@endsection

@push('scripts')
<script type="text/javascript">
  var table, tabledetail, tablehistory;
    var context_menu_nodata_state         = 'hide';
    var context_menu_number_state         = 'hide';
    var context_menu_text_state           = 'hide';
    var last_index                        = '';
    var custom_search = [
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
      { text : '', filter : '=' },
    ];
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var tipe          = ['Find','Filter'];
    var column_index  = 0;

  $(document).ready(function(){

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        var col = $(this).attr('data-column');
        var column = table.column(col);
        column.visible( ! column.visible() );
        $('#eye'+col).toggleClass('fa-eye-slash');
    });

    table = $("#table1").DataTable({
      dom        : 'lrtp',//lrtip -> lrtp
      serverSide : true,
      stateSave  : true,
      deferRender: true,
      select: {style:'single'},
      keys: {keys: [38,40]},
        ajax       : {
        url : '{{ route("tokodraft.data") }}',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        data    : function ( d ) {
          d.custom_search = custom_search;
          d.tipestatus  = $('#tipestatus').val();
          },
      },
      order   : [[ 1, 'asc' ]],
      scrollY : 400,
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
          {"data": "action", "orderable": false,},
          {
            "data" : 'statusacc',
            "className" : 'menufilter textfilter',
          },
          {
            "data" : 'tokodraftid',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'namatoko',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'toko00',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'alamat',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'kota',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'telp',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'penanggungjawab',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'kodetoko',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'daerah',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'piutangb',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'piutangj',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'plafon',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'classid',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'tojual',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'toretpot',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'jangkawaktukredit',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'tgldob',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'thnberdiri',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'statusruko',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'fax',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'bangunan',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'habis_kontrak',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'pemilik',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'no_ktp',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'jmlcabang',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'gender',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'tempatlahir',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'email',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'norekening',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'namabank',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'no_member',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'hobi',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'nonpwp',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'jmlsales',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'catatan',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'harikirim',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'kodepos',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'grade',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'plafon1st',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'bentrok',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'statusaktif',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'harisales',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'alamatrumah',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'pengelola',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'tgllahir',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'hp',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'kinerja',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'tipebisnis',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'i_spart',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'lastupdatedby',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'lastupdatedon',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'jenis_produk',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'kecamatan',
            "className" : 'menufilter textfilter'
          },
          {
            "data" : 'namakabkota',
            "className" : 'menufilter textfilter'
          },
        ]
    });

    @include('master.javascript');

    $('.search').change(function(){
        table.ajax.reload(null, false);
    });

    tabledetail = $("#tbldetail").DataTable({
      dom   : 'lrtp',
      paging: false,
    });

    tablehistory = $("#historyData").DataTable({
      dom     : 'lrtp',//lrtip -> lrtp
      select: {style:'single'},
      scrollY   : 360,
      scrollX   : true,
      scroller  : {
        loadingIndicator: true
      },
      /*order     : [[1, "desc"],],*/
      columns   : [
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
      ],
    });

  });

  function tambahApprove(e)
  {
    var data = table.row($(e).parents('tr')).data();
    $("#modal-status").modal("show");
    $(".modal-title").text("Tambah Toko Approved");
    $("#modal-status #rowid").val(data.rowid);
    $("#modal-status #statusacc").val("APPROVED");
  }

  function tambahReject(e){
    var data = table.row($(e).parents('tr')).data();
    $("#modal-status").modal("show");
    $(".modal-title").text("Tambah Toko Rejected");
    $("#modal-status #rowid").val(data.rowid);
    $("#modal-status #statusacc").val("REJECTED");
  }

  function tampilDetail(e){
    var data = table.row($(e).parents('tr')).data();
    $.ajax({
      type   : 'GET',
      url: '{{route("tokodraft.tddetail")}}',
      data   : {kodetoko: data.kodetoko},
      dataType: "json",
      success: function(data){
         tabledetail.clear();
          tabledetail.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'ID Tokodraft', '2':data.tokodraftid},
            {'0':'<div class="text-right">2.</div>', '1':'Kode Toko', '2':data.kodetoko},
            {'0':'<div class="text-right">3.</div>', '1':'NamaToko', '2':data.namatoko},
            {'0':'<div class="text-right">4.</div>', '1':'Alamat', '2':data.alamat},
            {'0':'<div class="text-right">5.</div>', '1':'Provinsi', '2':data.propinsi},
            {'0':'<div class="text-right">6.</div>', '1':'Kota', '2':data.kota},
            {'0':'<div class="text-right">7.</div>', '1':'Kecamatan', '2':data.kecamatan},
            {'0':'<div class="text-right">8.</div>', '1':'Telp', '2':data.telp},
            {'0':'<div class="text-right">9.</div>', '1':'Fax', '2':data.fax},
            {'0':'<div class="text-right">10.</div>', '1':'PenanggungJawab', '2':data.penanggungjawab},
            {'0':'<div class="text-right">11.</div>', '1':'TglDob', '2':data.tgldob},
            {'0':'<div class="text-right">12.</div>', '1':'Catatan', '2':data.catatan},
            {'0':'<div class="text-right">13.</div>', '1':'Nama Pemilik', '2':data.pemilik},
            {'0':'<div class="text-right">14.</div>', '1':'Jenis Kelamin', '2':data.gender},
            {'0':'<div class="text-right">15.</div>', '1':'Tempat Lahir', '2':data.tempatlahir},
            {'0':'<div class="text-right">16.</div>', '1':'Tanggal Lahir', '2':data.tgllahir},
            {'0':'<div class="text-right">17.</div>', '1':'Email', '2':data.email},
            {'0':'<div class="text-right">18.</div>', '1':'No Rekening', '2':data.norekening},
            {'0':'<div class="text-right">19.</div>', '1':'Nama Bank', '2':data.namabank},
            {'0':'<div class="text-right">20.</div>', '1':'No NPWP', '2':data.nonpwp},
            {'0':'<div class="text-right">21.</div>', '1':'Bidang Usaha', '2':data.tipebisnis},
            {'0':'<div class="text-right">22.</div>', '1':'LastUpdatedBy', '2':data.lastupdatedby},
            {'0':'<div class="text-right">23.</div>', '1':'LastUpdatedTime', '2':data.lastupdatedon},
            {'0':'<div class="text-right">24.</div>', '1':'HP', '2':data.hp},
            {'0':'<div class="text-right">25.</div>', '1':'No KTP', '2':data.no_ktp},
            {'0':'<div class="text-right">26.</div>', '1':'Piutang B', '2':data.piutangb},
            {'0':'<div class="text-right">27.</div>', '1':'Piutang J', '2':data.piutangj},
            {'0':'<div class="text-right">28.</div>', '1':'Plafon', '2':data.plafon},
            {'0':'<div class="text-right">29.</div>', '1':'ToJual', '2':data.tojual},
            {'0':'<div class="text-right">30.</div>', '1':'ToRetPot', '2':data.toretpot},
            {'0':'<div class="text-right">31.</div>', '1':'JangkaWaktuKredit', '2':data.jangkawaktukredit},
            {'0':'<div class="text-right">32.</div>', '1':'Cabang2', '2':data.cabang2},
            {'0':'<div class="text-right">33.</div>', '1':'Exist', '2':data.exist},
            {'0':'<div class="text-right">34.</div>', '1':'ClassID', '2':data.classid},
            {'0':'<div class="text-right">35.</div>', '1':'Hari Kirim', '2':data.harikirim},
            {'0':'<div class="text-right">36.</div>', '1':'Kode Pos', '2':data.kodepos},
            {'0':'<div class="text-right">37.</div>', '1':'Grade', '2':data.grade},
            {'0':'<div class="text-right">38.</div>', '1':'Plafon1st', '2':data.plafon1st},
            {'0':'<div class="text-right">39.</div>', '1':'Flag', '2':data.flag},
            {'0':'<div class="text-right">40.</div>', '1':'Bentrok', '2':data.bentrok},
            {'0':'<div class="text-right">41.</div>', '1':'StatusAktif', '2':data.statusaktif},
            {'0':'<div class="text-right">42.</div>', '1':'Hari Sales', '2':data.harisales},
            {'0':'<div class="text-right">43.</div>', '1':'Daerah', '2':data.daerah},
            {'0':'<div class="text-right">44.</div>', '1':'Alamat Rumah', '2':data.alamatrumah},
            {'0':'<div class="text-right">45.</div>', '1':'Pengelola', '2':data.pengelola},
            {'0':'<div class="text-right">46.</div>', '1':'Status', '2':data.status},
            {'0':'<div class="text-right">47.</div>', '1':'Tahun Berdiri', '2':data.thnberdiri},
            {'0':'<div class="text-right">48.</div>', '1':'Status Ruko', '2':data.statusruko},
            {'0':'<div class="text-right">49.</div>', '1':'Jumlah Cabang', '2':data.jmlcabang},
            {'0':'<div class="text-right">50.</div>', '1':'Jumlah Sales', '2':data.jmlsales},
            {'0':'<div class="text-right">51.</div>', '1':'Kinerja', '2':data.kinerja},
            {'0':'<div class="text-right">52.</div>', '1':'No Toko', '2':data.no_toko},
            {'0':'<div class="text-right">53.</div>', '1':'Exp Norm', '2':data.exp_norm},
            {'0':'<div class="text-right">54.</div>', '1':'I Spart', '2':data.i_spart},
            {'0':'<div class="text-right">55.</div>', '1':'Bangunan', '2':data.bangunan},
            {'0':'<div class="text-right">56.</div>', '1':'Habis Kontrak', '2':data.habis_kontrak},
            {'0':'<div class="text-right">57.</div>', '1':'Jenis Produk', '2':data.jenis_produk},
            {'0':'<div class="text-right">58.</div>', '1':'No Member', '2':data.no_member},
            {'0':'<div class="text-right">59.</div>', '1':'Hobi', '2':data.hobi},
            {'0':'<div class="text-right">60.</div>', '1':'Nama Kab Kota', '2':data.namakabkota},
            {'0':'<div class="text-right">61.</div>', '1':'Bentuk Badan Usaha', '2':data.bentukbadanusaha},
            {'0':'<div class="text-right">62.</div>', '1':'Luas Gudang', '2':data.luasgudang},
            {'0':'<div class="text-right">63.</div>', '1':'Armada Mobil', '2':data.armadamobil},
            {'0':'<div class="text-right">64.</div>', '1':'Armada Motor', '2':data.armadamotor},
            {'0':'<div class="text-right">65.</div>', '1':'Wilayah Pemasaran', '2':data.wilayahpemasaran},
            {'0':'<div class="text-right">66.</div>', '1':'No Rek BGCH', '2':data.norekbgch},
          ]);
          tabledetail.draw();
          $('#modal-detail').modal('show');
      }
    });
  }

  function tampilHistory(e){
    var data = table.row($(e).parents('tr')).data();
    $.ajax({
      url   : '{{route("tokodraft.tdhistory")}}',
      type  : "GET",
      data   : {rowid: data.rowid},
      //dataType: "json",
      success : function(result)
      {
        $("#modal-history").modal("show");
        $(".modal-title").text("History Toko Draft");

        tablehistory.clear();
        $.each(result.data, function(k, v)
        {
          tablehistory.row.add([
            v.tglobsolete,
            v.tokodraftID,
            v.NamaToko,
            v.Toko00,
            v.Alamat,
            v.Kota,
            v.Telp,
            v.PenanggungJawab,
            v.KodeToko,
            v.Daerah,
            v.PiutangB,
            v.PiutangJ,
            v.Plafon,
            v.ClassID,
            v.ToJual,
            v.ToRetPot,
            v.JangkaWaktuKredit,
            v.Tgl1st,
            v.ThnBerdiri,
            v.StatusRuko,
            v.Fax,
            v.Bangunan,
            v.Habis_kontrak,
            v.nama_pemilik,
            v.no_ktp,
            v.JmlCabang,
            v.jenis_kelamin,
            v.tempat_lhr,
            v.email,
            v.no_rekening,
            v.nama_bank,
            v.no_member,
            v.hobi,
            v.no_npwp,
            v.JmlSales,
            v.Catatan,
            v.HariKirim,
            v.KodePos,
            v.Grade,
            v.Plafon1st,
            v.Bentrok,
            v.StatusAktif,
            v.HariSales,
            v.AlamatRumah,
            v.Pengelola,
            v.TglLahir,
            v.HP,
            v.Kinerja,
            v.BidangUsaha,
            v.I_spart,
            v.LastUpdatedBy,
            v.LastUpdatedTime,
            v.Jenis_produk,
            v.NamaKecamatan,
            v.NamaKabKota,
          ]);
        });
        tablehistory.draw();
      }
    });
  }

  function hapus(e) {
    @can('tokodraft.hapus')
    var data = table.row($(e).parents('tr')).data();
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
        type   : 'POST',
        url    : '{{route("tokodraft.hapus")}}',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data   : {kodetoko:data.kodetoko},
        success: function(data){
          if(data){
            swal({
              title: "Sukses",
              text: "Data berhasil dihapus.",
              type: "success"
            },function(){
              table.ajax.reload(null, false);
            });
          }else{
            swal("Ups!", "Terjadi kesalahan pada sistem.", "error");
          }
        },
        error: function(data){
          console.log(data);
          swal("Ups!", "Terjadi kesalahan pada sistem.", "error");
        }
      });
    });
  @else
    swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;
  @endcan
  }

</script>
@endpush
