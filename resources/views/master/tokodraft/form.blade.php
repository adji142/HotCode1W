
@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Master</a></li>
<li class="breadcrumb-item"><a href="{{ route('tokodraft.index') }}">Toko Draft</a></li>
<li class="breadcrumb-item active">{{ ($tokodraft) ? 'Ubah Data' : 'Tambah Data'}}</li>
@endsection

@section('main_container')

<div class="mainmain">
  <div class="row">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ ($tokodraft) ? 'Ubah Data Toko Draft' : 'Tambah Data Toko Draft'}}</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Info Utama</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Info Tambahan</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Info Tambahan</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Toko00</a>
            </li>
          </ul>

          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="{{route('tokodraft.simpan')}}" enctype="multipart/form-data">
           {{ csrf_field() }} 
           <div id="myTabContent" class="tab-content"> 

            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
              <fieldset>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!-- <input type="hidden" id="id" name="id" value="{{ $tokodraft ? $tokodraft->id : ''}}"> -->
                <!-- <input type="hidden" id="lastupdatedby" name="lastupdatedby" class="form-control" value="{{ auth()->user()->name }}"> -->
                <div class="col-md-12 col-xs-12">
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kodetoko">Kode Toko</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="kodetoko" name="kodetoko" class="form-control" value="{{ $tokodraft ? $tokodraft->kodetoko : ''}}" readonly="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tokodraftID">Tokodraft ID</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="tokodraftID" name="tokodraftID" class="form-control" value="{{ $tokodraft ? $tokodraft->tokoidwarisan : ''}}" readonly="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmtoko">Nama Toko</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="nmtoko" name="nmtoko" class="form-control" placeholder="NAMA TOKO" value="{{ $tokodraft ? $tokodraft->namatoko : ''}}" autofocus="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="alamat">Alamat</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="alamat" name="alamat" class="form-control" placeholder="ALAMAT" value="{{ $tokodraft ? $tokodraft->alamat : ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="provinsi"><font color="red">* </font>Provinsi</label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="provinsi" name="provinsi" class="form-control select2">
                       <option></option>
                       @foreach ($prov as $p => $value)
                       <option value="{{ $p }}" @if( $tokodraft ? $tokodraft->propinsi == $value : '' ) selected="" @endif>{{$value}}</option>  
                       @endforeach
                     </select>
                   </div>
                  </div>

                  <input type="hidden" id="namaprov" name="namaprov" value="{{ $tokodraft ? $tokodraft->propinsi : ''}}">

                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kota"><font color="red">* </font>Kota / Kabupaten</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="kota" name="kota" class="form-control select2" disabled="">
                      </select>
                    </div>
                  </div>

                  <input type="hidden" id="namakab" name="namakab" value="{{ $tokodraft ? $tokodraft->namakabkota : ''}}">

                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kota"><font color="red">* </font>Kecamatan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="kecamatan" name="kecamatan" class="form-control select2" onchange="tampilkan_daerah()" disabled="">
                      </select>
                    </div>
                  </div>

                  <input type="hidden" id="namakec" name="namakec">

                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="daerah">Daerah</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" name="daerah" id="daerah" class="form-control" value="{{ $tokodraft ? $tokodraft->daerah : ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="telp">Telp</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="telp" name="telp" class="form-control" value="{{ $tokodraft ? $tokodraft->telp : ''}}" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="penanggungjawab">Penanggung Jawab</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="penanggungjawab" name="penanggungjawab" class="form-control" value="{{ $tokodraft ? $tokodraft->penanggungjawab : ''}}" placeholder="PENANGGUNG JAWAB">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="piutangB">PiutangB</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="piutangB" name="piutangB" class="form-control" value="{{ $tokodraft ? $tokodraft->piutangb : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="piutangJ">PiutangJ</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="piutangJ" name="piutangJ" class="form-control" value="{{ $tokodraft ? $tokodraft->piutangj : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plafon">Plafon</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="plafon" name="plafon" class="form-control" value="{{ $tokodraft ? $tokodraft->plafon : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="toJual">ToJual</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="toJual" name="toJual" class="form-control" value="{{ $tokodraft ? $tokodraft->tojual : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="toRetPot">ToRetPot</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="toRetPot" name="toRetPot" class="form-control" value="{{ $tokodraft ? $tokodraft->toretpot : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="jangkawaktukredit">Jangka Waktu Kredit</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="jangkawaktukredit" name="jangkawaktukredit" class="form-control" value="{{ $tokodraft ? $tokodraft->jangkawaktukredit : ''}}"  placeholder="30" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="catatan">Catatan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="catatan" name="catatan" class="form-control" value="{{ $tokodraft ? $tokodraft->catatan : ''}}" placeholder="CATATAN">
                    </div>
                  </div>    
                </div>
              </fieldset>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
              <fieldset>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-12 col-xs-12">
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl1st"><font color="red">* </font>TglOb</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      @if(!empty($tokodraft->tgldob))
                        <input type="text" id="tgl1st" name="tgl1st" class="tgl1st form-control" value="{{date('d-m-Y', strtotime($tokodraft ? $tokodraft->tgldob : ''))}}" data-inputmask="'mask': 'd-m-y'">
                      @else
                        <input type="text" id="tgl1st" name="tgl1st" class="tgl1st form-control" data-inputmask="'mask': 'd-m-y'">
                      @endif
                    </div>
                    <p class="help-block muted"><font color="red"> <== Tgl Aktif Toko Status</font></p>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="exist">Exist</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="checkbox" id="exist" name="exist" class="flat" value="1"
                      {{ old('exist', $tokodraft ? $tokodraft->exist : '') === '1' ? 'checked' : '' }} />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="classID">ClassID</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <select id="classID" name="classID" class="form-control" onchange="tampilkan_bmk()">
                        <option @if( $tokodraft ? $tokodraft->classid == 'D' : '' ) selected="" @endif value="D">D</option>
                        <option @if( $tokodraft ? $tokodraft->classid == 'L' : '' ) selected="" @endif value="L">L</option>
                      </select>
                    </div>
                    <p id="bmk">BMK 1</p>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="harikirim">Hari Kirim</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="harikirim" name="harikirim" class="form-control" value="{{ $tokodraft ? $tokodraft->harikirim : ''}}"  placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kodepos">Kode Pos</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="kodepos" name="kodepos" class="form-control" placeholder="KODE POS" value="{{ $tokodraft ? $tokodraft->kodepos : ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="grade">Grade</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="grade" name="grade" class="form-control" placeholder="GRADE" value="{{ $tokodraft ? $tokodraft->grade : ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plafon1st">Plafon1St</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="plafon1st" name="plafon1st" class="form-control" value="{{ $tokodraft ? $tokodraft->plafon1st : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="statusaktif">Status Aktif</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <!-- <input type="checkbox" id="statusaktif" name="statusaktif" class="flat" value="1"
                      {{ old('statusaktif', $tokodraft ? $tokodraft->StatusAktif : '') === '1' ? 'checked' : '' }} /> -->
                      <input type="checkbox" id="statusaktif" name="statusaktif" class="flat" value="1" @if( $tokodraft ? $tokodraft->statusaktif == '1' : '' ) checked="" @endif >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="harisales">Hari Sales</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="harisales" name="harisales" class="form-control" value="{{ $tokodraft ? $tokodraft->harisales : ''}}"  placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="alamatrumah">Alamat Rumah</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="alamatrumah" name="alamatrumah" class="form-control" value="{{ $tokodraft ? $tokodraft->alamatrumah : ''}}" placeholder="ALAMAT RUMAH">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pengelola">Pengelola</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="pengelola" name="pengelola" class="form-control" value="{{ $tokodraft ? $tokodraft->pengelola : ''}}" placeholder="PENGELOLA">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tanggallahir">Tanggal Lahir</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      @if(!empty($tokodraft->TglLahir))
                        <input type="text" id="tanggallahir" name="tanggallahir" class="tanggal form-control" value="{{date('d-m-Y', strtotime($tokodraft ? $tokodraft->tgllahir : ''))}}" data-inputmask="'mask': 'd-m-y'">
                      @else
                        <input type="text" id="tanggallahir" name="tanggallahir" class="tanggal form-control" data-inputmask="'mask': 'd-m-y'">
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="hp">HP</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="hp" name="hp" class="form-control" value="{{ $tokodraft ? $tokodraft->hp : ''}}" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="statusruko">Status Ruko</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <!-- <input type="checkbox" id="statusruko" name="statusruko" class="flat" value="1"
                      {{ old('statusruko', $tokodraft ? $tokodraft->StatusRuko : '') === '1' ? 'checked' : '' }} /> -->
                      <input type="checkbox" id="statusruko" name="statusruko" class="flat" value="1" @if( $tokodraft ? $tokodraft->statusruko == '1' : '' ) checked="" @endif >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="jmlcabang">Jumlah Cabang</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="jmlcabang" name="jmlcabang" class="form-control"  value="{{ $tokodraft ? $tokodraft->jmlcabang : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="jmlsales">Jumlah sales</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input type="text" id="jmlsales" name="jmlsales" class="form-control" value="{{ $tokodraft ? $tokodraft->jmlsales : ''}}" placeholder="0" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kinerja">Kinerja</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="kinerja" name="kinerja" class="form-control" value="{{ $tokodraft ? $tokodraft->kinerja : ''}}" placeholder="KINERJA">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="bidangusaha">Bidang Usaha</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="bidangusaha" name="bidangusaha" class="form-control" value="{{ $tokodraft ? $tokodraft->tipebisnis : ''}}" placeholder="BIDANG USAHA">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="i_spart">I_Spart</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="I_spart" name="I_spart" class="form-control select2" style="width:200px">
                        <option></option>
                        <option value="R2" @if( $tokodraft ? $tokodraft->i_spart == 'R2' : '' ) selected="" @endif>R2</option>
                        <option value="R4"  @if( $tokodraft ? $tokodraft->i_spart == 'R4' : '' ) selected="" @endif>R4</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="no_ktp">No KTP</label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                      <input type="text" id="no_ktp" name="no_ktp" class="form-control" value="{{ $tokodraft ? $tokodraft->no_ktp : ''}}" onkeypress="return hanyaAngka(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="noktp"></label>
                    <div class="col-md-3">
                      <div class="thumbnail">
                        <div class="image view view-first">
                          @if(empty($tokodraft->imagektp))
                          <img id="preview" style="width: 100%; height: 100%;" src="{{ url('assets/img/no-image.jpg') }}" title="Lihat Foto KTP" />
                          @else
                          <img id="preview" style="width: 100%; height: 100%;" src="{{ $tokodraft ? $tokodraft->imagektp : ''}}" title="Lihat Foto KTP" />
                          @endif
                        </div>
                        <div class="caption">
                          <input type="file" name="file_gambar" onchange="preview_ImageKTP(this);">
                        </div>
                      </div>
                    </div>
                  </div>  

                </div>
              </fieldset>
              <!-- </form> -->
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
              <fieldset>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-12 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="fax">Fax</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="fax" name="fax" class="form-control" value="{{ $tokodraft ? $tokodraft->fax : ''}}" placeholder="FAX">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="bangunan">Bangunan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <p>
                        <input type="radio" name="bangunan" id="bangunan" class="iradio_flat-green" value="MILIK SENDIRI" @if( $tokodraft ? $tokodraft->bangunan == 'MILIK SENDIRI' : '' ) checked="" @endif> MILIK SENDIRI &nbsp; &nbsp;

                        <input type="radio" name="bangunan" id="bangunan" class="iradio_flat-green" value="KONTRAK" @if( $tokodraft ? $tokodraft->bBangunan == 'KONTRAK' : '' ) checked="" @endif> KONTRAK
                      </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="thnberdiri">Thn Berdiri</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <input type="text" id="thnberdiri" name="thnberdiri" class="form-control" value="{{ $tokodraft ? $tokodraft->thnberdiri : ''}}" placeholder="THN BERDIRI" readonly="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="habis_kontrak">Habis Kontrak Bangunan</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      @if(!empty($tokodraft->habis_kontrak))
                        <input type="text" id="habis_kontrak" name="habis_kontrak" class="habis_kontrak form-control" value="{{date('d-m-Y', strtotime($tokodraft ? $tokodraft->habis_kontrak : ''))}}" data-inputmask="'mask': 'd-m-y'">
                      @else
                        <input type="text" id="habis_kontrak" name="habis_kontrak" class="habis_kontrak form-control" data-inputmask="'mask': 'd-m-y'">
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="jenis_produk">Jenis Produk</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="jenis_produk" name="jenis_produk" class="form-control" style="width:270px">
                        <option></option>
                        <option value="ALL (SEMUA)" @if( $tokodraft ? $tokodraft->jenis_produk == 'ALL (SEMUA)' : '' ) selected="" @endif>ALL (SEMUA)</option>
                        <option value="FAL (OLI)"  @if( $tokodraft ? $tokodraft->jenis_produk == 'FAL (OLI)' : '' ) selected="" @endif>FAL (OLI)</option>
                        <option value="FAB (BUSI)" @if( $tokodraft ? $tokodraft->jenis_produk == 'FAB (BUSI)' : '' ) selected="" @endif>FAB (BUSI)</option>
                        <option value="FAV (VANBELT)"  @if( $tokodraft ? $tokodraft->jenis_produk == 'FAV (VANBELT)' : '' ) selected="" @endif>FAV (VANBELT)</option>
                        <option value="R2" @if( $tokodraft ? $tokodraft->jenis_produk == 'R2' : '' ) selected="" @endif>R2</option>
                        <option value="R4"  @if( $tokodraft ? $tokodraft->jenis_produk == 'R4' : '' ) selected="" @endif>R4</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pemilik">Nama Pemilik</label>
                    <div class="col-md-6 col-sm-3 col-xs-12">
                     <input type="text" id="pemilik" name="pemilik" class="form-control" placeholder="NAMA PEMILIK"  value="{{ $tokodraft ? $tokodraft->pemilik : ''}}">

                   </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="jeniskelamin">Jenis Kelamin</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <p>
                        <input type="radio" class="flat" name="gender" id="gender" value="L" 
                        @if ($tokodraft ? $tokodraft->gender == 'L' : '' ) checked @endif/> 
                        LAKI-LAKI &nbsp; &nbsp;

                        <input type="radio" class="flat" name="gender" id="gender" value="P" 
                        @if ($tokodraft ? $tokodraft->gender == 'P' : '' ) checked @endif/>
                        PEREMPUAN
                      </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tempatlahir">Tempat Lahir</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="tempatlahir" name="tempatlahir" class="form-control" value="{{ $tokodraft ? $tokodraft->tempatlahir : ''}}" placeholder="TEMPAT LAHIR">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">Email</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="email" id="email" name="email" class="form-control" value="{{ $tokodraft ? $tokodraft->email : ''}}" placeholder="EMAIL">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="norek">No Rekening Bank</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="norekening" name="norekening" value="{{ $tokodraft ? $tokodraft->norekening : ''}}" class="form-control" placeholder="NO REKENING BANK">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="namabank">Nama Bank</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="namabank" name="namabank" class="form-control" value="{{ $tokodraft ? $tokodraft->namabank : ''}}" placeholder="NAMA BANK">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="no_member">No Member</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="no_member" name="no_member" value="{{ $tokodraft ? $tokodraft->no_member : ''}}" class="form-control" placeholder="NO MEMBER" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="hobi">Hobi</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="hobi" name="hobi" class="form-control" value="{{ $tokodraft ? $tokodraft->hobi : ''}}" placeholder="HOBI">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nonpwp">No N.P.W.P.</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="nonpwp" name="nonpwp" value="{{ $tokodraft ? $tokodraft->nonpwp : ''}}" class="form-control" placeholder="NO NPWP">
                    </div>
                  </div>   
                </div>
              </fieldset>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
              <fieldset>
                <div class="col-md-12 col-xs-12">
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="provinsi">Toko00</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select id="toko00" name="toko00" class="form-control" style="width:400px" > 
                      @foreach ($toko00 as $t => $value)
                      <option></option>
                       <option value="{{ $value }}" @if( $tokodraft ? array_search($value,explode(',',$kodecabang)) !== false : '' ) selected="" @endif> {{ $value }}</option>  
                      @endforeach
                     </select>
                   </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="hobi">WilID</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="wilid" name="wilid" class="form-control" value="{{ $tokodraft ? $tokodraft->wilID : ''}}" placeholder="WilID">
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>

      </div>

      <div class="col-md-2">
        <a href="{{route('tokodraft.index')}}" class="btn btn-primary">Kembali</a>
      </div>
      <div class="col-md-6">
        <button type="submit" id="btnSimpan" name="btnSimpan" class="btn btn-success">Simpan</button>
      </div>

    </form>
  </div>

</div>
</div>
</div>
</div>

<!-- Preview Foto KTP -->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
</div>

@endsection

@push('stylesheets')
<style>

.select2 { text-transform: uppercase; }

.option { text-transform: uppercase; }

#preview {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#preview:hover {opacity: 0.7;}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.9);
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

.modal-content, #caption {    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

@media only screen and (max-width: 700px){
    .modal-content {
        width: 100%;
    }
}
}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
  tampilkan_bmk();

  /*Preview Foto KTP*/
  var modal = document.getElementById('myModal');
  var img = document.getElementById('preview');
  var modalImg = document.getElementById("img01");
  img.onclick = function(){
      modal.style.display = "block";
      modalImg.src = this.src;
  }

  var span = document.getElementsByClassName("close")[0];

  span.onclick = function() { 
      modal.style.display = "none";
  }

  $(document).ready(function(){
    
    /*Setting Format Tanggal*/
    $(".tgl1st").inputmask();
    $(".tanggal").inputmask();
    $(".habis_kontrak").inputmask();

    /*Setting Dropdown*/
    $("#provinsi").select2({
      placeholder: 'Pilih Provinsi',
      allowClear: true
    });
    $("#kota").select2({
      placeholder: 'Pilih Kabupaten',
      allowClear: true
    });
    $("#kecamatan").select2({
      placeholder: 'Pilih Kecamatan',
      allowClear: true
    });
    $("#I_spart").select2({
      placeholder: 'Pilih I_spart',
      allowClear: true
    });
    $("#jenis_produk").select2({
      placeholder: 'Pilih Jenis Produk',
      allowClear: true
    });
    $("#toko00").select2({
      placeholder: 'Pilih Cabang',
      allowClear: true
    });

    /*Dropdown Wilayah*/
    $('#provinsi').change(function(){
      
      var ckota = "{!! $tokodraft ? $tokodraft->kota : '' !!}";
      var provinsiid = $(this).val();
      var k = $('select[name="kota"]');

      k.empty().prop("disabled", true);
      $('select[name="kecamatan"]').empty().prop("disabled", true);

      $.ajax({
        url: '{{ route('tokodraft.getkota',null)}}/'+provinsiid,
        type: 'GET',
        dataType: 'json',
        success: function(val) {
          $.each(val, function(key, value){
            if (ckota == value) {
            k.append('<option value="'+key+'" selected>'+value+'</option>');
            k.prop("disabled", false).trigger("change");
            } else{
              k.append('<option value="">Pilih Kabupaten</option>')
              k.append('<option value="'+key+'">'+value+'</option>');
              k.prop("disabled", false);
            }
            document.getElementById("namaprov").value = $("#provinsi option:selected").text();
          });
        }
      });
    });
    $("#provinsi").trigger("change");

    $('#kota').change(function(){

      var ckecamatan = "{!! $tokodraft ? $tokodraft->kecamatan : '' !!}";
      var kabkotaid = $(this).val();
      var k = $('select[name="kecamatan"]');

      k.empty().prop("disabled", true);

      $.ajax({
        url: '{{ route('tokodraft.getkecamatan',null)}}/'+kabkotaid,
        type: 'GET',
        dataType: 'json',
        success: function(val) {
          $.each(val, function(key, value){
            if (ckecamatan == value) {
              k.append('<option value="'+key+'" selected>'+value+'</option>');
              k.prop("disabled", false);
            } else{
              k.append('<option value="'+key+'">'+value+'</option>');
              k.prop("disabled", false).trigger("change");
            }
            document.getElementById("namakab").value = $("#kota option:selected").text();
          });
        }
      });
    });

    /*Readonly ThnBerdiri*/
    $('[name=bangunan]').click(function(){
      if (this.checked && this.value == "MILIK SENDIRI") {
        $("#thnberdiri").prop("readonly", false);
      } else {
        $("#thnberdiri").prop("readonly", true);
        document.getElementById("thnberdiri").value = "";
      }
    });
    $('input:radio[name=bangunan]:checked').trigger("click");

    /*Validasi Form Before Submit*/
    $("#formTambah").submit(function(e){

      var prov = $('#provinsi').val();
      if (prov==null || prov==""){
        swal('Ups!', "Provinsi harus diisi.",'error');
        return false;
      };

      var kot = $('#kota').val();
      if (kot==null || kot==""){
        swal('Ups!', "Kota harus diisi.",'error');
        return false;
      };

      var kec = $('#kecamatan').val();
      if (kec==null || kec==""){
        swal('Ups!', "Kecamatan harus diisi.",'error');
        return false;
      };

      var tgl1st = $('#tgl1st').val();
      if (tgl1st==null || tgl1st==""){
        swal('Ups!', "TglOb harus diisi.",'error');
        return false;
      };

      if(confirm("Data Sudah Benar ?"))
      {
        var ktp = $("#no_ktp").val();
        var x = $('#id').val();
        /*var unbind = $("#formTambah").unbind('submit').submit();*/
        e.preventDefault();
        if (ktp=="" || ktp==null) {
          $("#formTambah").unbind('submit').submit();
        } else{
          $.ajax({
            url: '{{ route('tokodraft.cekktp',null)}}/'+ktp,
            type: 'GET',
            dataType: 'json',
            success: function(data){
              if (data != "") {
                swal({
                  title: "Perhatian!",
                  text : "NO KTP sudah ada. Anda Yakin Ingin Melanjutkan ?",
                  type : "warning",
                  showCancelButton  : true,
                  confirmButtonColor: '#DD6B55',
                  confirmButtonText : "Ya",
                  cancelButtonText  : "Tidak",
                  closeOnConfirm    : true,
                  closeOnCancel     : true
                },
                function(isConfirm) {
                  if (isConfirm) {
                    $("#formTambah").unbind('submit').submit();
                  } else {
                    
                  }
                });
                /*if(confirm("NO KTP sudah ada. Anda Yakin ?")){
                  $("#formTambah").unbind('submit').submit();
                } else{

                }*/
                
              } else{
                $("#formTambah").unbind('submit').submit();
              }
            }
          });
        }

      } else{
        return false;
      }
      
    });

  });

  /*Inputan Hanya Angka*/
  function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
      return true;
  }

  /*Menampilkan Teks BMK*/
  function tampilkan_bmk(){
   var x = $('#classID').val(); 
    if (x=="D"){
      document.getElementById("bmk").innerHTML = 'BMK 1';
    } else if (x=="L"){
      document.getElementById("bmk").innerHTML = 'BMK 2';
    }
  }

  /*Menampilkan Value Daerah*/
  function tampilkan_daerah(){
   var daerah = $('#kecamatan option:selected').text();
   document.getElementById("daerah").value = daerah; 
   document.getElementById("namakec").value = daerah;
  }

  /*Preview Foto KTP*/
  function preview_ImageKTP(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#preview').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
    
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    var piutangb = document.getElementById('piutangB');
    piutangb.addEventListener('keyup', function(e){
        piutangb.value = formatRupiah(this.value);
    });

    var piutangj = document.getElementById('piutangJ');
    piutangj.addEventListener('keyup', function(e){
        piutangj.value = formatRupiah(this.value);
    });

    var plafon = document.getElementById('plafon');
    plafon.addEventListener('keyup', function(e){
        plafon.value = formatRupiah(this.value);
    });

    var toJual = document.getElementById('toJual');
    toJual.addEventListener('keyup', function(e){
        toJual.value = formatRupiah(this.value);
    });

    var toRetPot = document.getElementById('toRetPot');
    toRetPot.addEventListener('keyup', function(e){
        toRetPot.value = formatRupiah(this.value);
    });

    var plafon1st = document.getElementById('plafon1st');
    plafon1st.addEventListener('keyup', function(e){
        plafon1st.value = formatRupiah(this.value);
    });

</script>
@endpush

