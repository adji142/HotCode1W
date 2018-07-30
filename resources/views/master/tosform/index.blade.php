@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('tos.index') }}">TOS</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>DAFTAR TOS</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float: right;">
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="panel" style="margin-bottom: 10px;">
            <span style="display: block; margin-bottom: 3px;">Inputor:</span>
            <select id="inputor" class="form-control" @cannot("tos.config,browse") disabled @endcannot>
              <option empty></option>
              <option value="*">SEMUA INPUTOR</option>
              @can("tos.fbauth") <option value="!">REGISTER INPUTOR</option> @endcan
            </select>
            <div class="vtable" style="margin-top: 5px;">
              <div class="vcell vcell-fix">
                <div id="drange">
                  <span style="display: block;">Tanggal:</span>
                  <table>
                    <tr>
                      <td><input type="text" value="{{ session('tglmulai') }}" name="fromdate" placeholder="Dari..." class="date-range form-control"></td>
                      <td style="padding: 0 5px; vertical-align: middle;">s/d</td>
                      <td><input type="text" value="{{ session('tglselesai') }}" name="todate" placeholder="Sampai..." class="date-range form-control"></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="vcell">
                <span style="display: block;">Pencarian:</span>
                <input id="search" class="form-control" placeholder="CARI APAPUN..." type="text" />
              </div>
            </div>
          </div>
          <table
            id = "table01"
            class = "table table-bordered table-striped display nowrap"
            cellspacing = "0"
            width = "100%"
          ></table>
          <div style="margin-top: 5px;">
            @can("tos.config,new")
            <button id="iadd" type="button" class="btn btn-sm btn-primary" style="margin-right: 0px; text-transform: uppercase;">
              <i class="fa fa-plus"></i> &nbsp;Tambah
            </button>
            @endcan
          </div>
        </div>
      </div>
      <div class="x_panel">
        <div class="x_title">
          <h2>PREVIEW</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float: right;">
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div id="tbpreview" class="x_content">
          <ul class="nav nav-tabs" style="display: none;"></ul>
          <div class="tab-content" style="display: none;"></div>
          <span class="empty">Tidak ada data</span>
        </div>
      </div>
    </div>
  </div>

  <div id="floatlayer"></div>
  <ul id="floatmenu" class="mfb-component--br  mfb-slidein" data-mfb-toggle="click" data-mfb-state="closed">
    <li class="mfb-component__wrap">
      <a href="#" class="mfb-component__button--main">
        <i class="mfb-component__main-icon--resting ion-plus-round"></i>
        <i class="mfb-component__main-icon--active ion-close-round"></i>
      </a>
      <ul class="mfb-component__list">
        @can("tos.fbauth")
        @can("tos.config,indexing")
        <li>
          <a id="iindex" href="#" data-mfb-label="Indexing" class="mfb-component__button--child">
            <i class="mfb-component__child-icon ion-eye"></i>
          </a>
        </li>
        @endcan
        @endcan
        @can("tos.fbauth")
        @can("tos.config,fbodelete")
        <li>
          <a id="ifbodelete" href="#" data-mfb-label="Hapus Data Usang" class="mfb-component__button--child">
            <i class="mfb-component__child-icon ion-scissors"></i>
          </a>
        </li>
        @endcan
        @endcan
        @can("tos.config,excel")
        <li>
          <a id="iexcel" href="#" data-mfb-label="Generate Excel" class="mfb-component__button--child">
            <i class="mfb-component__child-icon ion-briefcase"></i>
          </a>
        </li>
        @endcan
        @can("tos.fbauth")
        @can("tos.config,pull")
        <li>
          <a id="ipull" href="#" data-mfb-label="Tarik Data" class="mfb-component__button--child">
            <i class="mfb-component__child-icon ion-code-download"></i>
          </a>
        </li>
        @endcan
        @endcan
      </ul>
    </li>
  </ul>

  <!-- modal detail -->
  <div class="modal fade" id="modalDetailEdit" role="dialog" aria-labelledby="labelModalDetailEdit" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="labelModalDetailEdit">TOS Form</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <div class="modal-body">
            <!-- HERE WE GO -->
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" style="margin-right: 0px;">OKE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 0px;">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal detail -->
@endsection

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ionicons.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/viewer.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/circle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/material-float-button.css') }}">
<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid {!! $dat['color'] !!};
  width: 120px;
  height: 120px;
  -webkit-animation: loader-spin 2s linear infinite; /* Safari */
  animation: loader-spin 2s linear infinite;
}

ul.mfb-component--br a:hover,
ul.mfb-component--br a:focus {
  color: #fff;
}

#floatlayer {
  background-color: rgba(0, 0, 0, 0.1);
  position: fixed;
  bottom: 0; right: 0;
  top: 0; left: 0;
  display: none;
  z-index: 30;
}

.mfb-component__button--main, .mfb-component__button--child {
  background-color: {!! $dat['color'] !!};
}

/* Safari */
@-webkit-keyframes loader-spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes loader-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loader2 {
  width: 60px;
  height: 60px;
  background-color: {!! $dat['color'] !!};

  margin: 25px auto;
  -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
  animation: sk-rotateplane 1.2s infinite ease-in-out;
}

@-webkit-keyframes sk-rotateplane {
  0% { -webkit-transform: perspective(120px) }
  50% { -webkit-transform: perspective(120px) rotateY(180deg) }
  100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
}

@keyframes sk-rotateplane {
  0% { 
    transform: perspective(120px) rotateX(0deg) rotateY(0deg);
    -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg) 
  } 50% { 
    transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
    -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg) 
  } 100% { 
    transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
    -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
  }
}

.tosbuttons {
  margin-top: 5px;
}
.tosbuttons li a {
  padding: 8px 15px;
  font-size: 13px;
}

.form-control.date-range {
  display: inline-block;
  width: 115px;
}

.panel.imagepicker {
  border: 1px solid #d8d8d8;
  overflow: hidden;
  padding: 0;
}
.panel.imagepicker img {
  width: 100%;
}
.panel.imagepicker .btn {
  border-radius: 0;
  border: none;
  width: 100%;
  margin: 0;
}
.panel.imagepicker input[type=file] {
  display: none;
}

div.dataTables_wrapper div.dataTables_length, div.dataTables_wrapper div.dataTables_filter, div.dataTables_wrapper div.dataTables_info, div.dataTables_wrapper div.dataTables_paginate {
  text-align: left;
}
div.dataTables_wrapper div.dataTables_paginate ul.pagination {
  margin: 5px 0;
}
.dataTables_paginate a {
  background-color: #fff !important;
  border-color: #ddd !important;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
  background-color: #337ab7 !important;
  border-color: #337ab7 !important;
}

#tbpreview > * {
  text-transform: uppercase;
}
#tbpreview img {
  box-shadow: 0 0 3px rgb(150, 150, 150);
  border: 1px solid #fff;
  border-radius: 5px;
  max-height: 250px;
  max-width: 100%;
  cursor: zoom-in;
  width: auto;
}
.material-switch > input[type="checkbox"] {
  display: none;   
}

.material-switch > label {
  cursor: pointer;
  height: 0px;
  position: relative; 
  width: 40px;  
}

.material-switch > label::before {
  background: rgb(0, 0, 0);
  box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
  border-radius: 8px;
  content: '';
  height: 16px;
  margin-top: -8px;
  position:absolute;
  opacity: 0.3;
  transition: all 0.4s ease-in-out;
  width: 40px;
}
.material-switch > label::after {
  background: rgb(255, 255, 255);
  border-radius: 16px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
  content: '';
  height: 24px;
  left: -4px;
  margin-top: -8px;
  position: absolute;
  top: -4px;
  transition: all 0.3s ease-in-out;
  width: 24px;
}
.material-switch > input[type="checkbox"]:checked + label::before {
  background: inherit;
  opacity: 0.5;
}
.material-switch > input[type="checkbox"]:checked + label::after {
  background: inherit;
  left: 20px;
}

.alert.alert-warning, .alert.alert-info, .alert.alert-error, .alert.alert-success {
  color: #fff;
}

.vtable {
  display: table;
  width: 100%;
}
.vtable .vcell {
  display: table-cell;
  padding-left: 10px;
}
.vtable .vcell:first-child {
  padding: 0px;
}
.vtable .vcell.vcell-fix {
  white-space: nowrap;
  width: 1px;
}
.vtable.passive {
  display: block;
}
.vtable.passive .vcell {
  display: inline-block;
  padding: 5px 0 0;
  width: 100%;
}
.vtable.passive .vcell:first-child {
  padding: 0px;
}
.vtable.passive #drange table, .vtable.passive #drange .form-control.date-range {
  width: 100%;
}
</style>
@endpush

@push('scripts')
<script name="swalHTML" type="text/template">
  <div style="display: inline-block;">
    <!--<div class="loader2"></div>-->
    <div class="loader circle-dat"></div>
  </div>
  <p style="display: block; margin-top: 5px;">
    Tunggu sebentar...
  </p>
</script>
<script type="text/javascript" src="{{ asset('assets/js/base64.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/viewer.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/material-float-button.js') }}"></script>
<script type="text/javascript">
  var cbox1   = $("#inputor", document),
      search1 = $('#search', document),
      drange1 = $("#drange", document),
      tbprev  = $("#tbpreview", document),
      fltmenu = $("#floatmenu", document),
      fltlayr = $("#floatlayer", document),
      mdldet  = $("#modalDetailEdit", document),
      skip    = {{ empty(session("subcabang")) ? 'true' : 'false' }},
      cuser   = "{!! strToUpper(auth()->user()->username) !!}",
      ccabang = "{!! $dat['kodecabang'] !!}",
      cgudang = "{!! $dat['kodegudang'] !!}",
      cver    = {!! $dat['version'] !!},
      gmKey   = "{!! $gmKey !!}",
      spar    = document,
      itFine  = false,
      table1  = null;

  $(document).ready(function () {

    fltlayr.on("click", () => {
      fltmenu.attr("data-mfb-state", 'closed');
      fltlayr.hide();
    });

    $("a", fltmenu).on("click", function (s) {
      s.preventDefault();
      if ($(this).is(".mfb-component__button--child")) fltmenu.attr("data-mfb-state", 'closed');
      fltlayr[(fltmenu.is("[data-mfb-state=open]") ? "show" : "hide")]();
    });

    var _vtbr = () => $(".vtable", document)[($(window).width() > 450 ? "removeClass" : "addClass")]("passive");
    $(window).on("resize", _vtbr);
    _vtbr();

    if (skip) return;

    swal({
      title: "Inisialisasi komponen",
      text: $("script[name=swalHTML]").text(),
      showConfirmButton: false,
      allowOutsideClick: false,
      html: true
    });
    spar = $(".sweet-alert", document);

    $('.modal').on('show.bs.modal', function(event) {
      var idx = $('.modal:visible').length;
      $(this).css('z-index', 1040 + (10 * idx));
    });

    $('.modal').on('shown.bs.modal', function(event) {
      var idx = ($('.modal:visible').length) -1;
      $('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
      $('.modal-backdrop').not('.stacked').addClass('stacked');
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    $(".date-range", drange1).inputmask({"mask": "99-99-9999", 'greedy': false});

    cbox1.select2({
      'width': "100%",
      'allowClear': true,
      'placeholder': "PILIHAN",
    });
    mdldet.on('shown.bs.modal', () => $('input:visible:first', mdldet).focus());

    table1 = $('#table01', document).DataTable({
      dom        : 'lrtp',
      stateSave  : true,
      scrollX    : true,
      select     : {
        style: 'single'
      },
      columns: [
        {
          title: "#A",
          orderable : false,
          render : (data, type, row, stats) => {
            return ""
                  @can("tos.config,update")
                  + "<div class='btn btn-warning btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Edit'"
                  + " index='" + stats.row + "' mode='update' ftitle='Memperbarui TOS' data-tipe='header'><i class='fa fa-pencil'></i></div>"
                  @endcan
                  @can("tos.config,verify")
                  + "<div class='btn btn-primary btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Verify'"
                  + " index='" + stats.row + "' mode='verify' ftitle='Verifikasi TOS' data-tipe='header'><i class='fa fa-check'></i></div>"
                  @endcan
                  @can("tos.config,synch")
                  + "<div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Synch to Wiser'"
                  + " index='" + stats.row + "' mode='synch' ftitle='Synch to Wiser' data-tipe='header'><i class='fa fa-cloud-upload'></i></div>";
                  @endcan
          }
        },
        {
          title: "Inputor",
          data : "inputor"
        },
        {
          title: "Tanggal",
          data: 'created_at',
          render: (data, type, row) => {
            var nd = (new Date(row.updated_at || data));

            var nds = nd.getUTCDate().toString().padStart(2, "0") + "/";
            nds += (nd.getUTCMonth() + 1).toString().padStart(2, "0") + "/";
            nds += nd.getUTCFullYear().toString().padStart(4, "0");
            return nds;
          }
        },
        {
          title: "Nama",
          data : "nama_outlet"
        },
        {
          title: "Kode Toko",
          data : "kode_toko"
        },
        {
          title: "Tipe",
          data : "bentuk_badan_usaha"
        },
        {
          title: "NPWP",
          data : "npwp"
        },
        {
          title: "Berdiri",
          data : "tahun_berdiri"
        },
        {
          title: "Alamat",
          data : "alamat"
        },
        {
          title: "Verified",
          data: "verified",
          render: data => {
            return (data ? "SUDAH" : "BELUM");
          }
        }
      ]
    });
    search1.val(table1.search());
  });

  @can("tos.fbauth")
  $.getScript("https://www.gstatic.com/firebasejs/4.9.0/firebase.js")
  .done(() => {
  @endcan
  @cannot("tos.fbauth")
  (function () {
  @endcannot
    if (skip) return;
    if (
      @can("tos.fbauth") typeof firebase == 'undefined' || @endcan
      typeof Base64 == 'undefined'

    ) return swal("Ops!", "Gagal menginisialisasi komponen", "error");

    var _init = {
      @can("tos.fbauth")
      'fba': function (cb) {
        $("h2:first", spar).text("Mengautentikasi...");
        $.ajax({
          url: "{{ route('tos.fbauth') }}",
          type: "POST",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: h => {
            _init.fba = h;
            if (typeof cb == "function") cb(true);
          },
          error: () => swal("Ops!", "Tidak dapat mengautentikasi!\nKode: larauth", "error")
        });
      },
      'fa': function (cb) {
        var jd = this.od;
        try {
          if (typeof this.od != "object") jd = JSON.parse(this.od);
          firebase.initializeApp(JSON.parse(Base64.decode(this.fba)));
          firebase.auth().signInAnonymously().catch(e => swal("Ops!", "Tidak dapat mengautentikasi!\nKode: fbauth", "error"));

        } catch (ex) {
          return swal("Ops!", "Terjadi galat saat autentikasi, pesan: " + ex.message, "error");
        }
        firebase.auth().onAuthStateChanged(u => { if (typeof cb == "function") cb((u ? true : false)); });
      },
      @endcan
      'od': function (cb) {
        $("h2:first", spar).text("Mengautentikasi...");
        $.ajax({
          url: "{{ route('tos.config', ['name' => 'odat']) }}",
          type: "POST",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: h2 => {
            _init.od = h2;
            if (typeof cb == "function") cb(true);
          },
          error: () => swal("Ops!", "Tidak dapat mengautentikasi!\nKode: laraconf", "error")
        });
      },
      'lst': function (od, db, st) {
        if (typeof {!! $dat['fineToken'] !!} == 'function') {!! $dat['fineToken'] !!}(od, db, st);
        else swal("Ops!", "Gagal menginisialisasi komponen tos form", "error");
      }
    };

    @can("tos.fbauth")
    _init.fba(() => _init.od(() => _init.fa(r => {
      if (r) _init.lst(_init.od, firebase.database(), firebase.storage());
      else swal("Ops!", "Gagal menginisialisasi komponen tos form", "error");
    })))
    @endcan
    @cannot("tos.fbauth")
    _init.od(() => _init.lst(_init.od));
    @endcannot
  @can("tos.fbauth")
  })
  .fail(() => swal("Ops!", "Gagal menginisialisasi komponen firebase", "error"));
  @endcan
  @cannot("tos.fbauth")
  })();
  @endcannot

  var _{!! $dat['fineToken'] !!} = {};

  {!! $dat['fineToken'] !!} = function (od, db, file) {
    if (itFine) return false;
    else itFine = true;

    var

    sver = cver,
    time = new Date(),
    @can("tos.fbauth")
    defr = db.ref("/datas"),
    indr = db.ref("/indexs"),
    @endcan
    od = $.extend({}, {
      tmp: {},
      img: {},
      idx: {}
    }, od),
    xas = {
      "provinsi": (e, p) => {
        e = $(e).empty().attr("dynamic", true);

        var ts = e.attr("xas-target").split("|");
        if (ts.length < 2) return;

        $("[xas=kabupaten][name=" + ts[0] + "], [xas=kecamatan][name=" + ts[1] + "]", p).empty().prop("disabled", true);
        $("<option />").appendTo(e);

        var org = e.attr("origin");
        for (var i in od.reg) {
          $("<option />")
          .prop("selected", (org == i))
          .attr('value', i)
          .html(i)
          .appendTo(e);
        }
        e.trigger("change");
      },
      "kabupaten": (e, p) => {
        e = $(e).empty().attr("dynamic", true);

        var ts = e.attr("xas-target").split("|");
        if (ts.length < 2) return;

        $("[xas][name=" + ts[0] + "]", p).on("change", function () {
          $(e).empty();
          $("[xas=kecamatan][name=" + ts[1] + "]", p).empty();
          $("<option />").appendTo(e);

          var org = e.attr("origin");
          var prov = $("option:selected", this).val();
          if (typeof od.reg[prov] != "undefined") {
            for (var i in od.reg[prov]) {
              $("<option />")
              .prop("selected", (org == i))
              .attr('value', i)
              .html(i)
              .appendTo(e);
            }
            e.prop("disabled", false).trigger("change");

          } else e.prop("disabled", true);

        }).trigger("change");
      },
      "kecamatan": (e, p) => {
        e = $(e).empty().attr("dynamic", true);

        var ts = e.attr("xas-target").split("|");
        if (ts.length < 2) return;

        $("[xas=provinsi][name=" + ts[0] + "], [xas=kabupaten][name=" + ts[1] + "]", p).on("change", function () {
          $(e).empty();
          $("<option />").appendTo(e);

          var org  = e.attr("origin");
          var prov = $("[xas=provinsi][name=" + ts[0] + "] option:selected", p).val();
          var kab  = $("[xas=kabupaten][name=" + ts[1] + "] option:selected", p).val();
          if (typeof od.reg[prov] != "undefined" && typeof od.reg[prov][kab] != "undefined") {
            for (var i in od.reg[prov][kab]) {
              $("<option />")
              .prop("selected", (org == od.reg[prov][kab][i]))
              .attr('value', od.reg[prov][kab][i])
              .html(od.reg[prov][kab][i])
              .appendTo(e);
            }
            e.prop("disabled", false).trigger("change");

          } else e.prop("disabled", (kab.trim() == ""));

        }).trigger("change");
      },
      "inputor": (e, p) => {
        e = $(e).empty();

        fn.loading("Mengambil data...");
        $("<option value='*' selected>SEMUA INPUTOR</option>").appendTo(e);
        fn.getDataIndex(idx => {
          od.idx = idx;
          for (var i in idx) {
            $("<option />")
            .attr('value', i)
            .html(i)
            .appendTo(e);
          }
          swal.close();
        });
      }
    },
    fn = {
      currencyFormat: function (num) {
        return num.toString()
               .replace(".", ",")
               .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
      },

      currentDateTime: function (now, spt, jdate) {
        now = now || time || new Date();

        var nows = now.getDate().toString().padStart(2, "0") + (spt || "/");
        nows += (now.getMonth() + 1).toString().padStart(2, "0") + (spt || "/");
        nows += now.getFullYear().toString().padStart(4, "0");
        if (jdate !== true) {
          nows += " ";
          nows += now.getHours().toString().padStart(2, "0") + ":";
          nows += now.getMinutes().toString().padStart(2, "0") + ":";
          nows += now.getSeconds().toString().padStart(2, "0") + ".";
          nows += now.getMilliseconds();
        }
        return nows;
      },

      synchTime: function (cb) {
        if (typeof cb != "function") fn.loading("Sinkronisasi tanggal...");
        $.ajax({
          url: "{{ route('tos.config', ['name' => 'datetime']) }}",
          type: "POST",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: h => {
            var jd = h;
            try {
              if (typeof h != "object") jd = JSON.parse(h);
              jd.now = new Date(jd.def);
              sver = jd.ver;
              if (typeof cb != "function") {
                time = jd.now;
                swal.close();

              } else cb(true, jd);

            } catch (ex) {
              if (typeof cb != "function") swal("Ops!", "Terjadi galat saat mensinkron tanggal, pesan: " + ex.message, "error");
              else cb(false, ex.message);
            }
          },
          error: () => {
            if (typeof cb != "function") swal("Ops!", "Gagal mensinkron tanggal", "error");
            else cb(false, "Gagal mengambil tanggal");
          }
        });
      },

      loadingTitle: function (nm) {
        $("h2:first", spar)
        .text(nm);
      },

      checkVersion: function () {
        if (cver != sver) {
          if (!mdldet.is(":visible") && !spar.is("visible")) {
            cver = sver;
            return swal({
              title: "Informasi!",
              text: "Server telah diperbarui, browser anda akan kami reload!",
              type: "info"
            }, () => location.reload());
          }
        }
      },

      downloadText: function (fnm, txt) {
        var e = document.createElement('a');
        e.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(txt));
        e.setAttribute('download', fnm);

        e.style.display = 'none';
        document.body.appendChild(e);
        e.click();
        document.body.removeChild(e);
      },

      toDate: function (dt) {
        return fn.
        strToDate(fn.currentDateTime(dt || time));
      },

      dateToStr: function (val, jdate) {
        val = val || time || new Date();

        var nows = val.getFullYear().toString().padStart(4, "0") + "-";
        nows += (val.getMonth() + 1).toString().padStart(2, "0") + "-";
        nows += val.getDate().toString().padStart(2, "0");
        if (jdate !== true) {
          nows += " ";
          nows += val.getHours().toString().padStart(2, "0") + ":";
          nows += val.getMinutes().toString().padStart(2, "0") + ":";
          nows += val.getSeconds().toString().padStart(2, "0") + ".";
          nows += val.getMilliseconds();
        }
        return nows;
      },

      strToDate: function (val, spt) {
        switch (typeof val) {
          case "string":
            val = val.toString().split(spt || "/");
            if (val.length >= 3){
              return new Date(val[2].split(" ").shift() + "-" + val[1] + "-" + val[0] + " 00:00:00");
            } 
            else return null;

          case "number":
            var cd = new Date(val);
            return fn.strToDate(cd.getDate() + "/" + (cd.getMonth() + 1) + "/" + cd.getFullYear());
        }
        return null;
      },

      loading: function (nm, cb) {
        swal({
          title: nm,
          text: $("script[name=swalHTML]").text(),
          showConfirmButton: false,
          allowOutsideClick: false,
          html: true
        });
        if (typeof cb == "function") setTimeout(() => cb(), 150);
      },

      getCrf: function () {
        return $('meta[name="csrf-token"]', document)
               .attr('content');
      },

      getDRange: function () {
        var dfrm = $(".date-range[name=fromdate]", drange1),
            dto  = $(".date-range[name=todate]", drange1);

        var vdfrm = fn.strToDate(dfrm.val(), "-"),
            vdto  = fn.strToDate(dto.val(), "-");

        if (vdto < vdfrm) vdto = vdfrm;
        if (!vdfrm) vdfrm = time;
        if (!vdto) vdto = time;

        dfrm.val(fn.currentDateTime(vdfrm, "-"));
        dto.val(fn.currentDateTime(vdto, "-"));

        return {
          'from': fn.toDate(vdfrm || time),
          'to': fn.toDate(vdto || time)
        };
      },

      getUserList: function (cb) {
        if (typeof cb != 'function') return false;

        var list = [];
        @cannot("tos.config,fbdata")
        $.ajax({
          url: "{{ route('tos.config', ['name' => 'index']) }}",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "POST",
          success: (h) => {
            try {
              if (typeof h == "string") h = JSON.parse(h);              
            } catch (ex) { cb(list); }

            if (h.result && h.data) {
              for (var ck in h.data) {
                for (var i in h.data[ck]) {
                  for (var i2 in od.tbs) if (typeof h.data[ck][i][i2] == "undefined") h.data[ck][i][i2] = null;
                  h.data[ck][i].key = i;
                }

                list.push({ 'name': ck });
                od.tmp[ck] = h.data[ck];
              }
              @cannot("tos.config,browse")
              list = [{ 'name': cuser}];
              @endcannot
              cb(list);
            }
          },
          error: () => cb(list)
        });
        @endcannot
        @can("tos.fbauth")
        @can("tos.config,fbdata")
        indr.child("inputors").once('value', sn => {
          var ttl = sn.numChildren();
          if (!sn.exists() || ttl <= 0) return cb(list);
          sn.forEach(c => {
            var cv = c.val();
            if (cv) {
              var ck = c.key.toUpperCase();
              if (c.val()) list.push({ 'name': ck });
              if (cv !== true) {
                for (var i in cv) for (var i2 in od.tbs) if (typeof cv[i][i2] == "undefined") cv[i][i2] = null;
                od.tmp[ck] = cv;
              }
            }

            ttl -= 1;
            if (ttl <= 0) {
              @cannot("tos.config,browse")
              list = [{ 'name': cuser}];
              @endcannot
              cb(list);
            }
          });
        });
        @endcan
        @endcan
      },

      refreshUserList: function (cb, sel) {
        if (typeof cb != "function") fn.loading("Mengambil data...");
        fn.getUserList(d => {
          @can("tos.fbauth")
          @can("tos.config,browse")
          $("option[empty]", cbox1).prop("selected", true);
          $("option:not([empty]):not([value='*']):not([value='!'])", cbox1).remove();
          @endcan
          @endcan
          @cannot("tos.config,browse")
          cbox1.prop("disabled", true).empty();
          @endcannot

          for (var i in d) {
            $("<option />")
            .prop("selected", (!sel ? false : d[i].name == sel))
            .html(d[i].name)
            .attr("value", d[i].name)
            .appendTo(cbox1);
          }
          if (typeof cb != "function") {
            cbox1.trigger("change");
            swal.close();

          } else cb(true);
        });
      },

      getTokoList: function (key, cb) {
        if (typeof cb != 'function' || typeof key != 'string') return false;
        key = key.toUpperCase();

        var list = [];
        if (typeof od.tmp[key] == "object") {
          for (var i in od.tmp[key]) {
            list.push($.extend({}, od.def, od.tmp[key][i], {
              'loaded': false,
              'inputor': key,
              'key': (typeof od.tmp[key][i].key == "undefined" ? i : od.tmp[key][i].key),
              @can("tos.fbauth") 'ref': defr.child(key + "/" + od.tmp[key][i].key) @endcan
            }));
          }
          list.sort((a, b) => {
            if (a.created_at < b.created_at) return 1;
            else if (a.created_at > b.created_at) return -1;
            else return 0;
          });
          cb(list);

        } else {
          @can("tos.fbauth")
          defr.child(key).once('value', sn => {
            ttl = sn.numChildren();
            sn.forEach(c => {
              list.push($.extend({}, od.def, c.val(), {
                'loaded': true,
                'inputor': key,
                'key': c.key,
                'ref': c.ref
              }));

              ttl -= 1;
              if (ttl <= 0) {
                list.sort((a, b) => {
                  if (a.created_at < b.created_at) return 1;
                  else if (a.created_at > b.created_at) return -1;
                  else return 0;
                });
                cb(list);
              }
            });
          });
          @endcan
          @cannot("tos.fbauth")
          cb(list);
          @endcannot
        }
      },

      getImageSrc: function (val, cb, def, head) {
        var t = [
          (typeof head != 'undefined' ? head : "data:image/jpeg;base64,"),
          (typeof def != 'undefined' ? def : "{{ asset('assets/img/error.png') }}")
        ];
        if (typeof val == "string") {
          if (val.length < 500) {
            if (!Base64.is(val)) {
              if (typeof od.img[val] == "string") return od.img[val];
              @can("tos.fbauth")
              else {
                file.ref(val).getDownloadURL()
                .then(u => {
                  od.img[val] = u;
                  cb(u)
                })
                .catch(e => cb(t[1]));
                return true;
              }
              @endcan
              @cannot("tos.fbauth")
              else return t[1];
              @endcannot
            }
          }
          return t[0] + val;
        } else return t[1];
      },

      getResizeImage: function (src, cw, ch, cb) {
        var img = $("<img />").get(0);
        img.onload = () => {
          var
          mw = (cw > 0 ? cw : 500),
          mh = (ch > 0 ? ch : 300),
          w = img.width,
          h = img.height;

          if (w > h) {
            if (w > mw) {
              h *= mw / w;
              w = mw;
            }
          } else {
            if (h > mh) {
              w *= mh / h;
              h = mh;
            }
          }

          var cv = $("<canvas />").attr({
            width: w,
            height: h
          }).get(0);

          var ctx = cv.getContext("2d");
          ctx.drawImage(img, 0, 0, w, h);
          cb(true, cv.toDataURL("image/jpeg"));
        };
        img.onerror = () => cb(false)
        $(img).attr('src', src);
      },

      getUIName: function (name) {
        var ns = name.split("_");
        for (var i in ns) {
          ns[i] = ns[i][0].toUpperCase() + ns[i].substring(1);
        }
        return ns.join(" ");
      },

      getGMapsUrl: function (dat) {
        if (typeof dat != "string") return "!0x001";
        else if (dat.length <= 0) return "!0x002";

        var id = "?";
        var url = "https://www.google.com/maps/embed/v1/place?key=" + gmKey + "&q=";
        if (dat[0] == "[" && dat[dat.length - 1] == "]") {
          dat = dat.substring(1, dat.length - 2);
          dat = dat.split(",");
          if (dat.length < 3) return "!0x004";
          url += dat[1] + "," + dat[2] + "&zoom=18";
          id = dat[0];

        } else return "!0x003";
        return [id, url];
      },

      checkStoreCode: function (inp, frm, kd, kg) {
        if (typeof kd != "string") return false;
        else if (kd.trim() == "") return false;

        for (var i in od.tmp) {
          for (var i2 in od.tmp[i]) {
            if (
              (i != inp && i2 != frm) &&
              typeof od.tmp[i][i2].kode_toko == "string" &&
              od.tmp[i][i2].kode_toko.toString().trim().toUpperCase() == kd.trim().toUpperCase() &&
              od.tmp[i][i2].kode_gudang.toString().trim().toUpperCase() == kd.trim().toUpperCase()
            ) return true;
          }
        }
        return false;
      },

      checkNIKCode: function (inp, frm, kd) {
        if (typeof kd != "string") return false;
        else if (kd.trim() == "") return false;
        return false; // nik is not unique

        for (var i in od.tmp) {
          for (var i2 in od.tmp[i]) {
            if (
              (i != inp && i2 != frm) &&
              typeof od.tmp[i][i2].no_ktp == "string" &&
              od.tmp[i][i2].no_ktp.toString().trim().toUpperCase() == kd.trim().toUpperCase()
            ) return true;
          }
        }
        return false;
      },

      setBtnListener: function () {
        table1.$(".btn[index][mode]").on("click", function () {
          var thisx = $(this);
          switch (thisx.attr("mode")) {
            @can("tos.config,new") case "new": @endcan
            @can("tos.config,update") case "update": @endcan
            @can("tos.config,verify") case "verify": @endcan
            case "":
              fn.showTokoForm(
                thisx.attr("mode"),
                thisx.attr("ftitle") || "TOS Form",
                thisx.attr("index")
              );
              break;

            @can("tos.config,synch")
            case "synch":
              fn.synchToWiser(thisx.attr("index"));
              break;
            @endcan
          }
        });
      },

      setImage: function (path) {
        if (!path) return null;
        return "<img alt='Scan KTP' src='{{ asset('assets/img/spinner-200px.gif') }}'"
               + " storage='" + path.split("'").join("\\'") + "' />";
      },

      setMaps: function (ttl, dat) {
        if (typeof dat != "string") return null;

        var url = fn.getGMapsUrl(dat);
        if (url[1][0] == "!") return "Location is not valid [code " + url[1].substring(1) + "]";

        var es = "<tr><td colspan='3'><span>" + ttl + " (" + url[0].toUpperCase() + "):</span>";
        es += "<iframe frameborder='0' width='100%' height='300' allowfullscreen src='" + url[1] + "'></iframe>";
        es += "</td></tr>";
        return $(es);
      },

      getFullData: function (idx, cb, sl, cl) {
        var dat = table1.row(idx).data();
        if (typeof dat == "object" && dat.loaded === false) {
          if (dat.loaded === false) {
            if (sl !== false) fn.loading("Mengambil data...");
            @cannot("tos.config,fbdata")
            $.ajax({
              url: "{{ route('tos.config', ['name' => 'data']) }}",
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              data: {
                'inputor': dat.inputor,
                'key': dat.key,
                'id': dat.id
              },
              type: "POST",
              success: (h) => {
                try {
                  if (typeof h == "string") h = JSON.parse(h);              
                } catch (ex) { cb(list); }

                if (h.result && h.data) {
                  dat = $.extend(dat, h.data);
                  dat.key = (dat.key ? dat.key : dat.id);
                  @can("tos.fbauth") dat.ref = defr.child(dat.inputor + '/' + dat.key); @endcan
                  dat.loaded = true;

                  if (cl !== false) swal.close();
                  if (typeof cb == "function") cb();
                  
                } else swal("Ops!", "Tidak dapat mengambil detail data", "warning");
              },
              error: () => swal("Ops!", "Terjadi galat saat pengambilan data", "error")
            });
            @endcannot
            @can("tos.fbauth")
            @can("tos.config,fbdata")
            var datk = dat.key;
            defr.child(dat.inputor + "/" + dat.key).once("value", sn => {
              var ttl = sn.numChildren();
              if (!sn.exists() || ttl <= 0) swal("Ops!", "Data tidak di temukan", "error");
              sn.forEach(c => {
                dat[c.key] = c.val();

                ttl -= 1;
                if (ttl <= 0) {
                  dat.key = datk;
                  dat.ref = sn.ref;
                  dat.loaded = true;
                  if (cl !== false) swal.close();
                  if (typeof cb == "function") cb();
                }
              });

            }).catch(() => swal("Ops!", "Terjadi galat saat pengambilan data", "error"));            
            @endcan
            @endcan

          } else if (typeof cb == "function") cb();
        } else if (typeof cb == "function") cb();
      },

      getTokoFieldType: function (nm, def) {
        var typ = "str";
        if (typeof od.typ[nm] != 'undefined') typ = od.typ[nm];
        else typ = (typeof def == 'undefined' ? "str" : def);

        if (typeof typ != "string") return null;
        typ = typ.split("::");

        var res = {
          val: typ[0],
          opt: []
        };
        for (var i = 1; i < typ.length; i++) res.opt.push(typ[i]);
        return res;
      },

      getTokoFieldGroup: function (nm) {
        var r = {
          "step": -1,
          "name": "unknown",
          "title": "Unknown",
          "list": "*"
        };
        for (var i in od.grp) {
          var cur = od.grp[i];
          if (typeof cur.list == "undefined") continue;
          if (Array.isArray(cur.list)) {
            for (var i2 in cur.list) {
              if (cur.list[i2] == nm) return cur;
            }

          } else if (typeof cur.list == "string") {
            switch (cur.list) {
              case "*": r = cur; break;
              default: if (cur.list == nm) return cur; break;
            }
          }
        }
        return r;
      },

      elmMaker: function (dat) {
        if (typeof dat != "object" || dat === null) return null;
        if (typeof dat.type != "object" || dat.type === null) return null;
        if (!dat.show) return null;

        var str = '<div class="form-group">';
        str += '<label class="control-label col-md-3 col-sm-3 col-xs-3">' + (dat.title || fn.getUIName(dat.name));
        str += (typeof dat.need != "undefined" && dat.need ? ' <span style="color: red;">*</span>' : '');
        str += '</label>';
        str += '<div class="col-md-7 col-sm-7 col-xs-9">';
        str += '<div class="input-group" style="width: 100%;">';
        str += '</div></div></div></div>';
        str = $(str);

        var elm = "";
        switch (dat.type.val) {
          case "string": case "str": default:
            if (dat.title !== null && typeof dat.title != 'undefined') dat.title = dat.title.toString().toUpperCase(); else dat.title = null;
            if (dat.data !== null && typeof dat.data != 'undefined') dat.data = dat.data.toString().toUpperCase(); else dat.data = null;

            elm += '<input type="text"';
            elm += (typeof dat.need != "undefined" && dat.need ? " required" : "");
            elm += (!dat.editable ? ' readonly' : ' name="' + dat.name + '"') + ' class="form-control"';
            elm += ' origin="' + (dat.data === null ? "" : dat.data) + '" value="' + (dat.data === null ? "" : dat.data) + '"';
            elm += 'placeholder="' + (dat.title || fn.getUIName(dat.name)) + '" ' + (dat.required ? 'required="required"' : "") + ' />';
            elm = $(elm);

            var ctopt = dat.type.opt;
            if (dat.type.val != "string" && dat.type.val != "str") break;
            if (typeof ctopt[0] != "undefined") {
              switch (ctopt[0]) {
                case "date":
                  if (typeof ctopt[1] != "undefined" && ctopt[1] != "@") elm.inputmask({"mask": ctopt[1], 'greedy': false});
                  else elm.inputmask({"mask": "99/99/9999[ 99:99][:99][.9{1,4}]", 'greedy': false});
                  if (!dat.data && typeof ctopt[2] != "undefined") {
                    switch (ctopt[2]) {
                      case "now":
                        elm.val(fn.currentDateTime());
                        break;

                      default: elm.val(ctopt[2]); break;
                    }
                  }
                  break;

                case "numeric":
                  elm.inputmask({"mask": "9{1,255}", 'greedy': false});
                  break;

                case "email":
                  elm.inputmask({
                    'mask': "*{1,50}[.*{1,35}][.*{1,30}][.*{1,25}]@*{1,25}.*{2,10}[.*{1,5}]",
                    'onBeforePaste': pv => {
                      return pv.toLowerCase().replace("mailto:", "");
                    },
                    'greedy': false
                  });
                  break;

                case "int":
                  var mx = 0;
                  elm.on("change", () => {
                    var cv = parseInt(elm.val()) || 0;
                    if (mx > 0 && cv > mx) cv = mx;
                    elm.val(cv);
                  });
                  if (typeof ctopt[1] != "undefined") {
                    switch (ctopt[1]) {
                      case "year":
                        elm.inputmask({"mask": "9{1,4}"});
                        mx = 9999;
                        break;
                      case "month":
                        elm.inputmask({"mask": "9{1,2}"});
                        mx = 12;
                        break;
                      case "day":
                        elm.inputmask({"mask": "9{1,2}"});
                        mx = 32;
                        break;
                      case "hour":
                        elm.inputmask({"mask": "9{1,2}"});
                        mx = 24;
                        break;
                      case "minute":
                        elm.inputmask({"mask": "9{1,2}"});
                        mx = 59;
                        break;
                      case "second":
                        elm.inputmask({"mask": "9{1,2}"});
                        mx = 59;
                        break;

                      default:
                         elm.inputmask({"mask": "9{1,25}"});
                         break;
                    }

                  } else elm.inputmask({"mask": "9{1,25}"});
                  break;

                default:
                  if (!dat.data && typeof ctopt[1] != "undefined") elm.val(ctopt[1]);
                  break;
              }
            }
            break;

          case "bool": case "boolean": case "checkbox":
            elm += '<div class="material-switch pull-right">';
            elm += '<input type="checkbox"';
            elm += (!dat.editable ? ' readonly' : ' name="' + dat.name + '"');
            elm += (typeof dat.need != "undefined" && dat.need ? " required" : "");
            elm += ' origin="' + (dat.data ? 'true' : 'false') + '" ' + (dat.data ? 'checked' : '') + ' id="checkbox_' + dat.name + '" />';
            elm += '<label for="checkbox_' + dat.name + '" class="label-' + (typeof dat.type.opt[0] != "undefined" ? dat.type.opt[0] : "primary") + '"></label>';
            elm += '</div>';
            elm = $(elm);
            break;

          case "list": case "select": case "array": case "object":
            if (dat.data !== null && typeof dat.data != 'undefined') dat.data = dat.data.toString().toUpperCase();

            elm += '<select type="select"';
            elm += (typeof dat.need != "undefined" && dat.need ? " required" : "");
            elm += (!dat.editable ? ' readonly' : ' name="' + dat.name + '"') + '" class="form-control"';
            elm += ' origin="' + (dat.data === null ? "" : dat.data) + '" value="' + (dat.data === null ? "" : dat.data) + '"';
            elm += ' ' + (dat.required ? 'required="required"' : "") + '>';
            elm += '<option></option>';
            elm += '</select>';
            elm = $(elm);

            if (typeof dat.type.opt[0] == "string" && dat.type.opt[0].length > 0) {
              switch (dat.type.opt[0][0]) {
                case "!":
                  elm.attr({
                    "xas": dat.type.opt[0].substring(1)
                  });
                  if (typeof dat.type.opt[1] != "undefined") elm.attr("xas-target", dat.type.opt[1]);
                  if (typeof dat.type.opt[2] != "undefined") elm.attr("xas-data", dat.type.opt[2]);
                  break;

                case "[": case "{":
                  var opt0 = JSON.parse(dat.type.opt[0]);
                  var opt0a = Array.isArray(opt0);

                  if (typeof dat.data != "string") dat.data = (dat.data || "").toString();
                  for (var i2 in opt0) {
                    $("<option />")
                    .prop("selected", (dat.data.trim().toUpperCase() == (opt0a ? (opt0[i2] || "").toString().trim().toUpperCase() : i2.toString().trim().toUpperCase())))
                    .attr('value', ((opt0a ? opt0[i2] : i2) || "").toString().trim().toUpperCase())
                    .html(opt0[i2].toString().trim().toUpperCase())
                    .appendTo(elm);
                  }
                  break;
              }
            }
            break;

          case "text":
            if (dat.title !== null && typeof dat.title != 'undefined') dat.title = dat.title.toString().toUpperCase();
            if (dat.data !== null && typeof dat.data != 'undefined') dat.data = dat.data.toString().toUpperCase();

            elm += '<textarea';
            elm += (typeof dat.need != "undefined" && dat.need ? " required" : "");
            elm += (!dat.editable ? ' readonly' : ' name="' + dat.name + '"') + ' placeholder="' + (dat.title || fn.getUIName(dat.name)) + '" class="form-control">';
            elm += (dat.data === null ? "" : dat.data) + '</textarea>';
            elm = $(elm);
            break;

          case "image": case "img":
            elm += '<div class="panel' + (dat.editable ? ' imagepicker' : '') + '">';
            if (dat.editable) {
              elm += '<input type="file" style="display: none;" />';
              elm += '<input identity="image" name="' + dat.name + '" type="hidden" value="" />';
            }
            elm += '<img';
            elm += (typeof dat.need != "undefined" && dat.need ? " required" : "");
            elm += ' type="image" key="' + dat.name + '" target="' + dat.name + '" src="" alt="" style="display: none;" />';
            if (dat.editable) elm += '<input type="button" value="Ganti foto" class="btn btn-default" />';
            elm += '</div>';
            elm = $(elm);

            if (dat.data) {
              var url = fn.getImageSrc(dat.data, r => {
                $("img[target]", elm).attr("src", r);
                if (r != "") $("img[target]", elm).show();

              });
              if (url !== true) {
                $("img[target]", elm).attr("src", url);
                if (url != "") $("img[target]", elm).show();
              }
            }
            break;

          case "alert":
            str = $("<div class='alert alert-" + (dat.type.opt[0] || "info") + "'>" + (dat.data || "") + "</div>");
            break;
        }

        if (elm != "") $(".input-group:first", str).append(elm);
        return str;
      },

      setElmListener: function () {
        var par = $(".modal-body:first", mdldet);

        $("[xas]", par).each((i, e) => {
          e = $(e);
          if (typeof xas[e.attr("xas")] == "function") {
            xas[e.attr("xas")](e, par);
          }
        });

        $(".panel.imagepicker", par).each((i, e) => {
          $(".btn", e).on("click", () => $("input[type=file]", e).click());
          $("input[type=file]", e).on("change", function () {
            if (this.files && this.files[0]) {
              fn.loading("Membaca gambar....");
              var rdr = new FileReader();
              rdr.onload = (d) => {
                var src = d.target.result;
                fn.getResizeImage(src, 1024, 1024, (r, d) => {
                  if (r) {
                    $("img", e).attr('src', d).show();
                    var i3 = d.indexOf("base64,");
                    if (i3 > 1) {
                      $("input[type=hidden]", e)
                      .val(d.substring(i3 + 7))
                      .attr("modified", true);
                    }
                    $(".btn", e).val(this.files[0].name);
                    swal.close();

                  } else swal("Ops!", "Terjadi galat saat membaca gambar", "error");
                });
              };
              rdr.onerror = () => swal("Ops!", "Terjadi galat saat membaca data", "error");
              rdr.readAsDataURL(this.files[0]);

            } else $(".btn", e).val("Pilih foto");
          });
        });

        $("input[type][type!=button][type!=hidden]", par)
        .on("change", function () {
          var thisx = $(this), modified = false;
          switch (thisx.attr("type")) {
            case "checkbox":
              modified = (thisx.prop("checked") != (thisx.attr("origin") == "true"));
              break;
            default:
              modified = ((thisx.val() || "").toUpperCase() != (thisx.attr("origin") || "").toUpperCase());
              break;
          }
          thisx.attr("modified", modified);
        })
        .on("keyup", function (e) {
          if(e.which == 9) $(this).next("input[type][type!=button][type!=hidden]:visible:first").focus();
        });

        $("textarea", par).on("change", function () {
          $(this).attr("modified", true);
        });

        $("select[type=select]", par).on("change", function () {
          var modified = (($("option:selected", this).val() || "").toUpperCase() != ($(this).attr("origin") || "").toUpperCase());
          if ($("option:selected", this).val() == "") modified = false;
          $(this).attr("modified", modified);

        }).select2({
          'placeholder': "PILIHAN",
          'allowClear': true,
          'width': "100%",
        });
      },

      indexing: function (kd, cb, ck) {
        @can("tos.fbauth")
        ck = ck || $("option:selected", cbox1).val();
        if (typeof cb != "function") fn.loading("Mengindex data");
        if (typeof kd == "string" && kd.length > 0 && kd[0] == "!") {
          ck = kd.substring(1);
          kd = null;
        }

        if (ck.length <= 0 || ck == "*" || ck == "!") {
          if (typeof cb != "function") swal("Ops!", "Inputor tidak valid", "error");
          else cb(false, "Inputor tidak valid");
          return;
        }

        @cannot("tos.config,indexing")
        if (!kd) return swal("Ops!", "Anda tidak dapat mengindex data", "warning");
        defr.child(ck + "/" + kd)
        @endcannot
        @can("tos.config,indexing")
        defr.child(ck + (kd ? "/" + kd : ""))
        @endcan

        .once("value", sn => {
          var ttl = sn.numChildren(), stmp = {};
          if (!sn.exists() || ttl <= 0) {
            if (typeof cb != "function") swal("Ops!", "Tidak ada data untuk di index", "error");
            else cb(true, "Tidak ada data untuk di index");
            return;
          }
          var cd = {}, cv = null;
          sn.forEach(c => {
            if (!kd) {
              cd = $.extend({}, od.def, c.val());
              cv = {};

            } else if (cv === null) cv = $.extend({}, od.def);

            if (!kd) {
              for (var i2 in od.tbs) cv[i2] = (typeof cd[i2] != "undefined" ? cd[i2] : null);
              cv.verified = (cd.no_ktp && cd.verifikasi_foto_ktp && cd.kode_toko && cd.scan_ktp ? true : false);
              cv.updated_at = (cd.updated_at || cd.created_at || null);
              stmp[c.key] = cv;

            } else {
              cv[c.key] = c.val();
              if (typeof od.tbs[c.key] != "undefined") cd[c.key] = c.val();
            }

            ttl -= 1;
            if (ttl == 0) {
              var fx;
              if (!kd) fx = indr.child("inputors/" + ck).set(stmp);
              else {
                cd.verified = (cv.no_ktp && cv.verifikasi_foto_ktp && cv.kode_toko && cv.scan_ktp ? true : false);
                cd.updated_at = (cv.updated_at || cv.created_at || null);
                fx = indr.child("inputors/" + ck + "/" + kd).update(cd);
              }

              fx.then(() => {
                @can("tos.fbauth")
                @can("tos.config,fbdata")
                if (!kd) od.tmp[ck] = stmp;
                else od.tmp[ck][kd] = cd;
                @endcan
                @endcan
                
                if (typeof cb != "function") {
                  swal("Yeay", "Data untuk inputor " + ck + " berhasil di index", "success");
                  cbox1.trigger("change");
                }
                else cb(true);
              })
              .catch(e => {
                if (typeof cb != "function") swal("Ops!", "Terjadi galat saat indexing\n" + e.message, "error");
                else cb(false, "Terjadi galat saat indexing\n" + e.message);
              });
            }
          });

        }).catch(e => {
          if (typeof cb != "function") swal("Ops!", "Terjadi galat saat indexing\n" + e.message, "error");
          else cb(false, "Terjadi galat saat indexing\n" + e.message);
        });
        @endcan
        @cannot("tos.fbauth")
        if (typeof cb != "function") swal("Ops!", "Tidak memiliki akses untuk indexing", "error");
        else cb(false, "Tidak memiliki akses untuk indexing");
        @endcannot
      },

      @can("tos.fbauth")
      @can("tos.config,fbodelete")
      deleteObsoleteData: function (opt) {
        opt = $.extend({
          around: "IDX",
          download: true,
          progress: null,
          untilDate: '*',
          inputor: '*',
          cb: null
        }, opt);

        opt.untilDate = fn.strToDate(opt.untilDate);
        if (typeof opt.cb != "function") fn.loading("Mengecek data...");
        if (typeof opt.progress == "function") opt.progress("PREPARE", -1);

        var tdb;
        switch (opt.around.toLowerCase()) {
          case "idx": tdb = indr.child("inputors"); break;
          case "*": tdb = defr; break;
          default:
            if (typeof opt.cb == "function") opt.cb(false, 0, "Lingkup tidak di ketahui");
            return false;
        }

        var

        ttl = 0,
        max = 0,

        len = 0,
        ord = {},
        ord2 = {},
            
        lst1 = od.tmp,
        lst2 = {};

        _do = (k, r, cb) => {
          var ttl2 = r.numChildren(),
              max2 = ttl2;
          if (!r.exists() || ttl2 <= 0) return cb();
          r.forEach(c => {
            var v2 = c.val(),
                k2 = c.key,
                ok = true;

            if (opt.untilDate !== null) {
              if (v2["created_at"]) {
                if (fn.strToDate(v2["created_at"]) > opt.untilDate) ok = false;

              } else if (v2["updated_at"]) {
                if (fn.strToDate(v2["updated_at"]) > opt.untilDate) ok = false;
              }
            }

            @cannot("tos.config,fbdata")
            if (typeof lst1[k][k2] != "object") ok = false;
            @endcannot

            if (ok) {
              if (typeof lst2[k] == "undefined") lst2[k] = [];
              if (lst2[k].indexOf(k2) < 0) {
                if (opt.around == "*" && opt.download) {
                  if (typeof ord[k] == "undefined") ord[k] = {};
                  ord[k][k2] = v2;
                }
                lst2[k].push(k2);
                len += 1;
              }
            }

            ttl2 -= 1;
            var p = (max2 - ttl2) / max2;
            if (typeof opt.progress != "function") fn.loadingTitle("Mengecek data " + Math.round((((max - ttl) + p) / max) * 100, 2) + "%");
            else opt.progress("CHECK", Math.round((((max - ttl) + p) / max) * 100, 2));
            if (ttl2 == 0) cb();
          });
        },

        _next = (d) => {
          if (len > 0) {
            if (typeof opt.progress != "function") fn.loadingTitle("Memverifikasi data...");
            else opt.progress("VERIFY", -1);
            $.ajax({
              url: "{{ route('tos.config', ['name' => 'check']) }}",
              headers: { 'X-CSRF-TOKEN': fn.getCrf() },
              data: {
                'datas': d
              },
              type: "POST",
              success: (h) => {
                try {
                  if (typeof h == "string") h = JSON.parse(h);              
                } catch (ex) { cb(list); }

                if (h.result && typeof h.data == "object") {
                  len = 0;
                  var mdx1 = {}, mdx2 = {};
                  for (var k in h.data) {
                    for (var k2 in h.data[k]) {
                      len += 1;
                      mdx1[k + "/" + h.data[k][k2]] = null;
                      mdx2["inputors/" + k + "/" + h.data[k][k2]] = null;
                    }
                  }

                  var _final = () => {
                    defr.update(mdx1)
                    .then(() => {
                      indr.update(mdx2);
                      if (typeof opt.cb == "function") opt.cb(true, len);
                      else swal("Yey!", "Data usang " + len + " item(s), berhasil di hapus", "success");
                    })
                    .catch(e => {
                      if (typeof opt.cb == "function") opt.cb(false, len, "Error: " + e.message);
                      else swal("Ops!", "Error: " + e.message, "error");
                    });
                  };

                  if (len > 0 && opt.download) {
                    var fnm = "TOSFormObsoleteData" + (new Date()).valueOf() + ".json";
                    if (opt.around == "*") {
                      for (var dk in ord) for (var dk2 in ord[dk]) {
                        if (mdx1[dk + "/" + dk2] === null) {
                          if (typeof ord2[dk] == "undefined") ord2[dk] = {};
                          ord2[dk][dk2] = ord[dk][dk2];
                        }
                      }
                      fn.downloadText(fnm, JSON.stringify(ord2));
                      _final();

                    } else {
                      var _cur = 0, _kys = Object.keys(mdx1);
                      var _do2 = () => {
                        defr.child(_kys[_cur]).once("value", sn => {
                          var _kys2 = _kys[_cur].split("/");
                          if (typeof ord2[_kys2[0]] == "undefined") ord2[_kys2[0]] = {};
                          ord2[_kys2[0]][_kys2[1]] = sn.val();

                          _cur += 1;
                          if (_cur == _kys.length) {
                            fn.downloadText(fnm, JSON.stringify(ord2));
                            _final();

                          } else _do2();
                        });
                      };
                      _do2();
                    }

                  } else _final();
                  
                } else {
                  if (typeof opt.cb == "function") opt.cb(false, len, "Tidak dapat memverifikasi data");
                  else swal("Ops!", "Tidak dapat memverifikasi data", "warning");
                }
              },
              error: () => {
                if (typeof opt.cb == "function") opt.cb(false, len, "Terjadi galat saat verifikasi data");
                else swal("Ops!", "Terjadi galat saat verifikasi data", "error");
              }
            });

          } else {
            if (typeof opt.cb == "function") opt.cb(true, 0);
            else swal("Sip!", "Tidak ada data usang", "success");
          }
        };

        if (opt.inputor != "*") tdb = tdb.child(opt.inputor);
        tdb.once('value', sn => {
          ttl = (opt.inputor == "*" ? sn.numChildren() : 1);
          max = ttl;
          if (!sn.exists() || ttl <= 0) {
            if (typeof opt.cb != "function") swal("Selesai", "Tidak ada data yang usang", "success");
            else opt.cb(true, 0);
            return;
          }

          if (opt.inputor == "*") {
            sn.forEach(c => {
              c.ref.once("value", sn2 => _do(c.key, sn2, () => {
                ttl -= 1;
                if (ttl == 0) _next(lst2);
              }));
            });

          } else {
            _do(sn.key, sn, () => {
              ttl -= 1;
              if (ttl == 0) _next(lst2);
            });
          }
        });
      },
      @endcan
      @endcan

      @can("tos.config,synch")
      synchToWiser: function (idx) {
        var dat = table1.row(idx).data();
        var _next = () => {
          if ((dat.no_ktp && dat.verifikasi_foto_ktp && dat.kode_toko && dat.scan_ktp ? false : true))
            return swal("Ops!", "Data belum lulus verifikasi", "error");

          swal({
            title              : "Tunggu sebentar!",
            text               : "Anda yakin data sudah benar dan akan di synch ke wiser?",
            type               : "warning",
            showCancelButton   : true,
            confirmButtonColor : "#27ae60",
            confirmButtonText  : "Ya",
            cancelButtonColor  : "#7f8c8d",
            cancelButtonText   : "Tidak",
            closeOnConfirm     : true,
            closeOnCancel      : true

          }, ok => {
            if (ok) {
              setTimeout(() => {
                fn.loading("Mengirim data...");

                var ttl = 0;
                var fdat = $.extend({}, od.def, dat, {
                  loaded: null,
                  ref: null
                });

                var
                _final = (r, m) => {
                  if (r) {
                    swal("Yeay", "Data berhasil di synch ke wiser", "success");
                  } else swal("Ops!", (m ? m : "Synch gagal karena beberapa hal"), "error");
                },
                _do = () => {
                  ttl -= 1;
                  if (ttl == 0) {
                    $.ajax({
                      url: "{{ route('tos.config', ['name' => 'synch']) }}",
                      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                      type: "POST",
                      data: { 'data': fdat },
                      success: (h) => {
                        try {
                          if (typeof h == "string") h = JSON.parse(h);
                          if (h.result) _final(true);
                          else _final(false, h.msg);
                          
                        } catch (ex) { _final(false, "Terjadi galat saat menyimpan data,\n" + ex.message); }
                      },
                      error: () => _final(false, "Terjadi galat saat menyimpan data [code 0x001]")
                    });
                  }
                };

                var tmp = fn.getImageSrc(fdat.foto_toko, r => {
                  fdat.foto_toko = r;
                  _do();

                }, null, "");
                if (tmp === true) ttl += 1;
                else fdat.foto_toko = tmp;

                tmp = fn.getImageSrc(fdat.scan_ktp, r => {
                  fdat.scan_ktp = r;
                  _do();

                }, null, "");
                if (tmp === true) ttl += 1;
                else fdat.scan_ktp = tmp;

                if (ttl == 0) {
                  ttl = 1;
                  _do();
                }
              }, 150);
            }
          });
        };

        if (typeof dat != "object") return swal("Ops!", "Data tidak valid", "error");
        if (dat.loaded === false) fn.getFullData(idx, _next, true, false);
        else _next();
      },
      @endcan

      // by afif
      @can("tos.fbauth")
      @can("tos.config,pull")
      getDataIndex: function (cb) {
        var list = {};
        indr.child("inputors").once('value', sn => {
          var ttl = sn.numChildren();
          if (!sn.exists() || ttl <= 0) {
            if (typeof cb == "function") cb(list);
            return;
          }
          sn.forEach(c => {
            var cv = c.val();
            if (cv) {
              var ck = c.key.toUpperCase();
              list[ck] = cv;
            }
            ttl -= 1;
            if (ttl == 0 && typeof cb == "function") cb(list);
          });
        })
        .catch(() => {
          if (typeof cb == "function") cb(list);
        })
        ;
      },

      pullFireBase: function (opt) {
        opt = $.extend({
          inputor: "*",
          fromDate: "*",
          toDate: "*"

        }, opt || {});

        opt.fromDate = fn.strToDate(opt.fromDate);
        opt.toDate = fn.strToDate(opt.toDate);

        fn.loadingTitle("Mencari data...");
        var lst = [];
        for (var i in od.idx) {
          for (var i2 in od.idx[i]) {
            var v = od.idx[i][i2], cd = fn.strToDate(v.updated_at || v.created_at), o = true;
            if (o && opt.fromDate !== null) if (cd) o = (cd >= opt.fromDate); else o = false;
            if (o && opt.toDate !== null) if (cd) o = (cd <= opt.toDate); else o = false;
            if (o && opt.inputor !== null && opt.inputor != "*") o = (i == opt.inputor);
            if (o) {
              lst.push({
                inputor: i,
                key: i2
              });
            }
          }
        }

        var i = -1, s = 0, f = 0, m = 0;
        var _do = () => {
          i += 1;
          fn.loadingTitle("Progress " + Math.round(i / lst.length * 100, 2) + "%");
          if (i >= lst.length) {
            var msg = "- Berhasil : " + s + "\n" + "- Gagal : " + f;
            if (s == m) msg = "Semua berhasil di pull ke Postgres";
            else if (f == m) msg = "Semua gagal di pull ke Postgres";
            return swal({
              title: (s == m ? "Yeay" : (f == m ? "Ops!" : "Selesai!")),
              type: (s == m ? "success" : (f == m ? "error" : "warning")),
              text: msg

            }, () => {
              setTimeout(() => {
                fn.loading("Menyegarkan daftar...");
                fn.refreshUserList(() => {
                  $(cbox1).trigger("change");
                  mdldet.modal("hide");
                  swal.close();

                }, (opt.inputor && opt.inputor != "*" ? opt.inputor : $("option:selected", cbox1).val()));

              }, 150);
            });
          }

          var v = lst[i];
          defr.child(v.inputor + "/" + v.key).once('value')
          .then(sn => {
            if (sn.exists()) {
              m += 1;
              lst[i] = $.extend({}, od.def, lst[i], sn.val());
              lst[i].verified = (lst[i].kode_toko && lst[i].no_ktp && lst[i].scan_ktp && lst[i].verifikasi_foto_ktp ? true : false);

              var _send = () => {
                $.ajax({
                  url: "{{ route('tos.config', ['name' => 'set']) }}",
                  type: "POST",
                  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                  data: {
                    "data": JSON.stringify(lst[i])
                  },
                  success: (h) => {
                    try {
                      if (typeof h != "object") h = JSON.parse(h);
                    } catch (ex) {
                      f += 1;
                      console.log("0x005", v.inputor + "/" + v.key, ex.message);
                      return _do();
                    }

                    if (h.result) s += 1;
                    else {
                      console.log("0x004", v.inputor + "/" + v.key, h.msg);
                      f += 1;
                    }
                    _do();
                  },
                  error: () => {
                    console.log("0x003", v.inputor + "/" + v.key, "Server connection");
                    f += 1;
                    _do();
                  }
                });
              };

              var tmp, _gc = 0;
              tmp = fn.getImageSrc(lst[i].foto_toko, r => {
                lst[i].foto_toko = r;

                _gc -= 1;
                if (_gc == 0) _send();

              }, null, "");
              if (tmp === true) _gc += 1;
              else lst[i].foto_toko = tmp;

              tmp = fn.getImageSrc(lst[i].scan_ktp, r => {
                lst[i].scan_ktp = r;

                _gc -= 1;
                if (_gc == 0) _send();

              }, null, "");
              if (tmp === true) _gc += 1;
              else lst[i].scan_ktp = tmp;

              if (_gc == 0) _send();

            } else {
              console.log("0x002", v.inputor + "/" + v.key, "FB not exists");
              f += 1;
              _do();
            }
          })
          .catch(() => {
            console.log("0x001", v.inputor + "/" + v.key, "FB Error");
            f += 1;
            _do();
          });
        };

        if (lst.length > 0) _do();
        else swal("Ops!", "Tidak ditemukan apapun", "warning");
      },
      @endcan
      @endcan

      @can("tos.config,excel")
      generateExcel: function (opt) {
        opt = $.extend({
          fromDate: "*",
          toDate: "*",
          pt: "*"

        }, opt || {});
        opt.fromDate = fn.strToDate(opt.fromDate);
        opt.toDate = fn.strToDate(opt.toDate);

        fn.loading("Membuat excel...");
        return $.ajax({
          url: "{{ route('tos.config', ['name' => 'excel']) }}",
          type: "POST",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            "from": fn.dateToStr(opt.fromDate, true),
            "to": fn.dateToStr(opt.toDate, true),
            "pt": opt.pt,
            "type": "xls"
          },
          success: (h) => {
            try {
              if (typeof h != "object") h = JSON.parse(h);

            } catch (ex) {
              swal("Ops!", "Terjadi galat saat memproses,\n" + ex.message, "error");
              return false;
            }

            if (h.result) {
              swal({
                title: "Yeay",
                text: "Daftar excel telah di buat,<br><a href='" + h.url + "' target='_blank'><button class='btn btn-success'>Unduh Excel</button></a>",
                type: "success",
                html: true
              }, () => mdldet.modal("hide"));

            } else swal("Ops!", "Terjadi galat saat memproses data", "error");
          },
          error: () => swal("Ops!", "Terjadi galat saat memproses data", "error")
        });
      },
      @endcan

      showTokoForm: function (mode, title, idx, force) {
        var nc = false;
        switch (mode) {
          @can("tos.config,new") case "new": @endcan
          @can("tos.config,update") case "update": @endcan
          @can("tos.config,verify") case "verify": @endcan
          case "":
            nc = true;
            break;
        }

        if (!force && nc && typeof od.reg == "undefined") {
          fn.loading("Inisialisasi komponen");
          var _do = () => {
            @can("tos.fbauth")
            db.ref("/wilayah").once('value', sn => {
              var ttl = sn.numChildren(),
                  list = {};

              if (ttl <= 0 || !sn.exists()) return swal("Ops!", "Tidak dapat menginisialisasi komponen", "error");
              sn.forEach(c => {
                list[c.key] = c.val();

                ttl -= 1;
                if (ttl <= 0) {
                  od.reg = {};
                  for (var i0 in list['provinsi']) {
                    var cprov = list.provinsi[i0].name.toUpperCase();
                    var dat0 = od.reg[cprov] || {};
                    for (var i1 in list['kabupaten']) {
                      var ckap = "KABUPATEN " + list.kabupaten[i1].name.toUpperCase();
                      if (list.kabupaten[i1].provinces_id == list.provinsi[i0].provinces_id) {
                        var dat1 = dat0[ckap] || [];
                        if (typeof od.reg[cprov] == "object") dat1 = od.reg[cprov][ckap] || [];
                        for (var i2 in list['kecamatan']) {
                          if (list.kecamatan[i2].city_id == list.kabupaten[i1].city_id) dat1.push(list.kecamatan[i2].name.toUpperCase());
                        }
                        dat0[ckap] = dat1;
                      }
                    }
                    od.reg[cprov] = dat0;
                  }

                  swal.close();
                  fn.showTokoForm(mode, title, idx, true);
                }
              });
            });
            @endcan
            @cannot("tos.fbauth")
            $.ajax({
              url: "{{ route('tos.config', ['name' => 'regions']) }}",
              type: "POST",
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              success: (h) => {
                try {
                  if (typeof h != "object") h = JSON.parse(h);

                } catch (ex) {
                  return swal("Ops!", "Terjadi galat saat initialisasi komponen,\n" + ex.message, "error");
                }

                if (h && typeof h == "object") {
                  od.reg = h;
                  swal.close();
                  fn.showTokoForm(mode, title, idx, true);

                } else swal("Ops!", "Tidak dapat menginisialisasi komponen", "error");
              },
              error: () => swal("Ops!", "Tidak dapat menginisialisasi komponen", "error")
            })
            @endcannot
          };

          fn.getFullData(idx, _do, false, false);
          return true;

        } else if (!force && nc) {
          fn.getFullData(idx, () => fn.showTokoForm(mode, title, idx, true));
          return;
        }

        $("#labelModalDetailEdit", mdldet).text(title || "TOS Form");
        mdldet.attr({
          "mode": mode,
          "index": idx
        });

        var dat = table1.row(idx).data() || {};
        var lst = [];
        switch (mode) {
          @can("tos.fbauth")
          @can("tos.config,pull")
          case "pull":
            var ftd = fn.getDRange();
            lst = [
              {
                name: "Info",
                data: "PERHATIAN! Data yang akan di generate hanya yang sudah di index",
                type: fn.getTokoFieldType('*', 'alert::warning'),
                show: true
              },
              {
                title: "Inputor",
                name: 'inputor',
                type: fn.getTokoFieldType('*', 'list::!inputor'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Dari Tgl",
                name: 'from_date',
                data: fn.currentDateTime(ftd.from),
                type: fn.getTokoFieldType('*', 'str::date::99/99/9999'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Sampai Tgl",
                name: 'to_date',
                data: fn.currentDateTime(ftd.to),
                type: fn.getTokoFieldType('*', 'str::date::99/99/9999'),
                editable: true,
                need: true,
                show: true
              }
            ];
            break;
          @endcan
          @endcan

          @can("tos.config,new")
          case "new":
            for (var i in od.def) {
              var cval = od.def[i];
              switch (i) {
                case "kode_cabang": cval = ccabang; break;
                case "kode_gudang": cval = cgudang; break;
              }

              lst.push({
                name: i,
                title: fn.getUIName(i),
                type: fn.getTokoFieldType(i),
                editable: (typeof od.edt[i] == "undefined" ? true : od.edt[i]),
                show: (typeof od.ign[i] == "undefined" ? true : od.ign[i]),
                need: od.mst[i] || false,
                data: cval
              });
            }
            break;
          @endcan

          @can("tos.config,update")
          case "update":
            for (var i in od.def) {
              lst.push({
                name: i,
                title: fn.getUIName(i),
                need: od.mst[i] || false,
                type: fn.getTokoFieldType(i),
                data: (typeof dat[i] == "undefined" ? od.def[i] : dat[i]),
                show: (typeof od.ign[i] == "undefined" ? true : od.ign[i]),
                editable: (typeof od.edt[i] == "undefined" ? true : od.edt[i])
              });
            }

            for (var i in dat) {
              if (typeof od.def[i] == 'undefined') {
                lst.push({
                  name: i,
                  data: dat[i],
                  title: fn.getUIName(i),
                  need: od.mst[i] || false,
                  type: fn.getTokoFieldType(i, typeof dat[i]),
                  editable: (typeof od.edt[i] == "undefined" ? true : od.edt[i]),
                  show: (typeof od.ign[i] == "undefined" ? true : od.ign[i])
                });
              }
            }
            break;
          @endcan

          @can("tos.config,verify")
          case "verify":
            lst = [
              {
                title: "Kode Toko",
                name: 'kode_toko',
                data: dat['kode_toko'] || od.def['kode_toko'] || "",
                type: fn.getTokoFieldType('kode_toko'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "No KTP",
                name: 'no_ktp',
                data: dat['no_ktp'] || od.def['no_ktp'] || "",
                type: fn.getTokoFieldType('no_ktp'),
                editable: true,
                show: true
              },
              {
                title: "Foto Toko Verified",
                name: 'verifikasi_foto_toko',
                data: dat['verifikasi_foto_toko'] || od.def['verifikasi_foto_toko'] || false,
                type: fn.getTokoFieldType('verifikasi_foto_toko', 'bool'),
                editable: true,
                show: true
              },
              {
                title: "Foto Toko",
                name: 'foto_toko',
                data: dat['foto_toko'] || od.def['foto_toko'] || null,
                type: fn.getTokoFieldType('foto_toko', 'img'),
                editable: true,
                show: true
              },
              {
                title: "Foto KTP Verified",
                name: 'verifikasi_foto_ktp',
                data: dat['verifikasi_foto_ktp'] || od.def['verifikasi_foto_ktp'] || false,
                type: fn.getTokoFieldType('verifikasi_foto_ktp', 'bool'),
                editable: true,
                show: true
              },
              {
                title: "Foto KTP",
                name: 'scan_ktp',
                data: dat['scan_ktp'] || od.def['scan_ktp'] || null,
                type: fn.getTokoFieldType('scan_ktp', 'img'),
                editable: true,
                show: true
              }
            ];
            break;
          @endcan

          @can("tos.config,browse")
          case "register":
            lst = [
              @cannot("tos.config,fbdata")
              {
                name: "Info",
                data: "PERHATIAN! Inputor tidak langsung muncul, akan muncul ketika data inputor tersebut sudah di tarik",
                type: fn.getTokoFieldType('*', 'alert::warning'),
                show: true
              },
              @endcannot
              {
                title: "Nama Inputor",
                name: 'inputor',
                type: fn.getTokoFieldType('*', 'str'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Indexing",
                name: 'index',
                data: true,
                type: fn.getTokoFieldType('*', 'bool'),
                editable: true,
                show: true
              }
            ];
            break;
          @endcan

          @can("tos.config,excel")
          case "excel":
            var ftd = fn.getDRange();
            lst = [
              {
                title: "PT",
                name: 'pt',
                data: 'SEMUA',
                type: fn.getTokoFieldType('*', 'list::["SEMUA", "SASA", "SAP"]'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Dari Tgl",
                name: 'from_date',
                data: fn.currentDateTime(ftd.from),
                type: fn.getTokoFieldType('*', 'str::date::99/99/9999'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Sampai Tgl",
                name: 'to_date',
                data: fn.currentDateTime(ftd.to),
                type: fn.getTokoFieldType('*', 'str::date::99/99/9999'),
                editable: true,
                need: true,
                show: true
              }
            ];
            break;
          @endcan

          @can("tos.config,fbodelete")
          case "fbodelete":
            lst = [
              {
                title: "Lingkup",
                name: 'around',
                data: 'IDX',
                type: fn.getTokoFieldType('*', 'list::{"*": "SEMUA", "IDX": "INDEXED"}'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Hingga Tanggal",
                name: 'until_date',
                data: fn.currentDateTime(new Date((new Date()).valueOf() + (1000 * 60 * 60 * 24 * 7 * -1))),
                type: fn.getTokoFieldType('*', 'str::date::99/99/9999'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Inputor",
                name: 'inputor',
                type: fn.getTokoFieldType('*', 'list::!inputor'),
                editable: true,
                need: true,
                show: true
              },
              {
                title: "Download Data",
                name: 'download',
                data: true,
                type: fn.getTokoFieldType('*', 'bool::success'),
                editable: true,
                show: true
              }
            ];
            break;
          @endcan

          default:
            swal("Ops!", "Mode " + mode + " tidak dapat di tangani oleh anda", "warning");
            return;
        }

        var telm = $(".modal-body", mdldet).empty();
        for (var i in lst) {
          var itm = fn.elmMaker(lst[i]);
          if (itm !== null) itm.appendTo(telm);
        }
        fn.setElmListener();
        mdldet.modal("show");
      }
    };

    _{!! $dat['fineToken'] !!} = od;

    @can("tos.config,new")
    $("#iadd", document).on("click", () => fn.showTokoForm("new", "Tambah TOS"));
    @endcan
    @can("tos.config,excel")
    $("#iexcel", document).on("click", () => fn.showTokoForm("excel", "Generate ke Excel"));
    @endcan

    @can("tos.fbauth")
    @can("tos.config,pull")
    $("#ipull", document).on("click", () => fn.showTokoForm("pull", "Tarik Data Firebase ke Postgres"));
    @endcan

    @can("tos.config,indexing")
    $("#iindex", document).on("click", () => {
      @cannot("tos.config,fbdata")
      swal({
        title              : "Informasi!",
        text               : "Anda dapat mengindex data di firebase, tetapi tidak dapat melihat data. Apakah akan tetap dilanjutkan?",
        type               : "info",
        showCancelButton   : true,
        confirmButtonColor : "#27ae60",
        confirmButtonText  : "Ya",
        cancelButtonColor  : "#7f8c8d",
        cancelButtonText   : "Tidak",
        closeOnConfirm     : true,
        closeOnCancel      : true

      }, ok => {
        if (ok) setTimeout(() => fn.indexing(), 150);
      });
      @endcannot
      @can("tos.config,fbdata")
      fn.indexing();
      @endcan
    });
    @endcan

    @can("tos.config,fbodelete")
    $("#ifbodelete", document).on("click", () => fn.showTokoForm("fbodelete", "Hapus Data Usang"));
    @endcan
    @endcan

    $("form", mdldet).on("submit", function (e) {
      e.preventDefault();
      fn.loading("Mengirim data");

      var md = mdldet.attr("mode");
      var i = parseInt(mdldet.attr("index"));
      var dat = (md != "new" ? table1.row(i).data() : null);
      var msg = [
        "Data berhasil di simpan",
        "Data kosong!?",
        "Data yang anda masukkan belum lengkap"
      ];

      var ok = false,
          modck = true;
      switch (md) {
        case "register":
          msg[0] = "Inputor berhasil di tambahkan";
        @can("tos.config,new") case "new": @endcan
        @can("tos.config,excel") case "excel": @endcan
        @can("tos.config,pull") case "pull": @endcan
        @can("tos.config,fbodelete") case "fbodelete": @endcan
          ok = true;
          break;
        default: ok = false; break;
      }

      switch (md) {
        case "register":
        @can("tos.config,excel") case "excel": @endcan
        @can("tos.config,pull") case "pull": @endcan
        @can("tos.config,fbodelete") case "fbodelete": @endcan
          modck = false;
          break;
      }

      if (ok === false) {
        msg[0] = (md == "update" ? "Data berhasil di update" : "Data berhasil di verifikasi");
        msg[1] = "Tidak ada perubahan data";
      }

      if (
        ok === true ||
        (ok === false && i >= 0 && (typeof dat.ref == 'object' || typeof dat.id == 'number'))
      ) {
        var

        elms = [],
        njd = {},
        _ttl = 0,

        _do = function () {
          _ttl -= 1;
          if (_ttl == 0) {
            if (Object.keys(njd).length > 0) {
              var fe = function (r, m, m2) {
                if (r) {
                  var _final = () => {
                    swal({
                      title: "Yeay",
                      type: "success",
                      text: msg[0]

                    }, () => {
                      switch (md) {
                        @can("tos.config,new")
                        case "new":
                          if (typeof od.tmp[cuser] != "object") od.tmp[cuser] = {};

                          if (m != "!") njd.key = m;
                          njd.id = parseInt(m2);
                          od.tmp[cuser][(m != "!" ? njd.key : njd.id)] = njd;

                          var cbo = $("option[value=" + cuser + "]", cbox1);
                          if (cbo.length > 0) {
                            if (!cbo.prop("selected")) cbo.prop("selected", true);
                          } else $("<option selected value='" + cuser + "'>" + cuser + "</option>").appendTo(cbox1);
                          break;
                        @endcan

                        @can("tos.config,update")
                        case "update":
                          var okey = dat.key;
                          dat = $.extend(dat, njd, {
                            key: okey
                          });
                          od.tmp[dat.inputor][dat.key] = $.extend(od.tmp[dat.inputor][dat.key], njd);
                          break;
                        @endcan

                        @can("tos.config,verify")
                        case "verify":
                          var okey = dat.key;
                          dat = $.extend(dat, njd, {
                            key: okey
                          });
                          od.tmp[dat.inputor][dat.key] = $.extend(od.tmp[dat.inputor][dat.key], njd);
                          break;
                        @endcan

                        @can("fbauth")
                        case "register":
                          $("<option />")
                          .attr("value", njd.inputor)
                          .prop("selected", true)
                          .html(njd.inputor)
                          .appendTo(cbox1);
                          break;
                        @endcan
                      }

                      cbox1.trigger("change");
                      mdldet.modal("hide");
                    });
                  };
                  if (m) fn.indexing(m, () => _final(), cuser);
                  else _final();

                } else swal("Ops!", m || msg[1], "error");
              };

              switch (md) {
                @can("tos.config,new")
                case "new":
                  njd = $.extend({}, od.def, njd);
                  njd.updated_at = njd.created_at = time.getTime();
                  njd.submission_date = (fn.currentDateTime()).substring(0, 16);
                  njd.inputor = cuser.toUpperCase();
                  njd.kode_gudang = cgudang;
                  njd.kode_cabang = ccabang;

                  if (fn.checkStoreCode(cuser, "*", njd.kode_toko, cgudang)) return swal("Ops!", "Kode toko sudah di pakai", "error");
                  else if (fn.checkNIKCode(cuser, "*", njd.no_ktp)) return swal("Ops!", "No KTP sudah di pakai", "warning");

                  @can("tos.fbauth")
                  var xu = {};
                  var nkey = defr.child(cuser).push().key;
                  xu[defr.path.n.join("/") + "/" + cuser + "/" + nkey] = njd;

                  db.ref().update(xu).then(() => {
                  @endcan
                  @cannot("tos.fbauth")
                  var nkey = "!";
                  (function () {
                  @endcannot
                    $.ajax({
                      url: "{{ route('tos.config', ['name' => 'set']) }}",
                      type: "POST",
                      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                      data: {
                        "data": JSON.stringify($.extend({}, njd, {
                          'kode_gudang': cgudang,
                          'key': nkey
                        }))
                      },
                      success: (h) => {
                        try {
                          if (!h) return fe(false, "Error output data");
                          else if (typeof h != "object") h = JSON.parse(h);

                        } catch (ex) {
                          return fe(false, ex.message);
                        }

                        if (h.result) fe(true, nkey, h.id);
                        else fe(false, h.msg);
                      },
                      error: () => fe(false, "Terjadi galat saat menyimpan data [code 0x004]")
                    });
                  @can("tos.fbauth")
                  })
                  .catch(() => fe(false, "Terjadi galat saat menyimpan data [code 0x003]"));
                  @endcan
                  @cannot("tos.fbauth")
                  })();
                  @endcannot
                  break;
                @endcan

                @can("tos.config,verify")
                case "verify":
                  if (njd.kode_toko && fn.checkStoreCode(dat.inputor, dat.key, njd.kode_toko, dat.kode_gudang)) return swal("Ops!", "Kode toko sudah di pakai", "error");
                  else if (njd.no_ktp && fn.checkNIKCode(dat.inputor, dat.key, njd.no_ktp)) return swal("Ops!", "No KTP sudah di pakai", "warning");

                  njd.updated_at = time.getTime();
                  var _tfn = () => {
                    $.ajax({
                      url: "{{ route('tos.config', ['name' => 'set']) }}",
                      type: "POST",
                      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                      data: {
                        "data": JSON.stringify($.extend({}, dat, njd, {
                          'kode_gudang': dat.kode_gudang,
                          'inputor': dat.inputor,
                          'id': dat.id || null,
                          'key': dat.key,
                          'ref': null
                        }))
                      },
                      success: (h) => {
                        try {
                          if (!h) return fe(false, "Error output data");
                          else if (typeof h != "object") h = JSON.parse(h);

                        } catch (ex) {
                          return fe(false, ex.message);
                        }

                        if (h.result) fe(true, dat.key);
                        else fe(false, h.msg);
                      },
                      error: () => fe(false, "Terjadi galat saat menyimpan data [code 0x004]")
                    });
                  };

                  @can("tos.fbauth")
                  if ($.isNumeric(dat.key) || !dat.key) _tfn();
                  else dat.ref.update(njd).then(_tfn).catch(() => fe(false, "Terjadi galat saat menyimpan data [code 0x003]"));
                  @endcan
                  @cannot("tos.fbauth")
                  _tfn();
                  @endcannot
                  break;
                @endcan

                @can("tos.config,update")
                case "update":
                  if (njd.kode_toko && fn.checkStoreCode(dat.inputor, dat.key, njd.kode_toko, dat.kode_gudang)) return swal("Ops!", "Kode toko sudah di pakai", "error");
                  else if (njd.no_ktp && fn.checkNIKCode(dat.inputor, dat.key, njd.no_ktp)) return swal("Ops!", "No KTP sudah di pakai", "warning");

                  njd.updated_at = time.getTime();
                  var _tfn = () => {
                    $.ajax({
                      url: "{{ route('tos.config', ['name' => 'set']) }}",
                      type: "POST",
                      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                      data: {
                        "data": JSON.stringify($.extend({}, njd, {
                          'kode_gudang': dat.kode_gudang,
                          'inputor': dat.inputor,
                          'id': dat.id || null,
                          'key': dat.key,
                          'ref': null
                        }))
                      },
                      success: (h) => {
                        try {
                          if (!h) return fe(false, "Error output data");
                          else if (typeof h != "object") h = JSON.parse(h);

                        } catch (ex) {
                          return fe(false, ex.message);
                        }

                        if (h.result) fe(true, dat.key);
                        else fe(false, h.msg);
                      },
                      error: () => fe(false, "Terjadi galat saat menyimpan data [code 0x004]")
                    });
                  };

                  @can("tos.fbauth")
                  if ($.isNumeric(dat.key) || !dat.key) _tfn();
                  else dat.ref.update(njd).then(_tfn).catch(() => fe(false, "Terjadi galat saat menyimpan data [code 0x003]"));
                  @endcan
                  @cannot("tos.fbauth")
                  _tfn();
                  @endcannot
                  break;
                @endcan

                @can("tos.fbauth")
                @can("tos.config,browse")
                case "register":
                  var _tfn = () => {
                    fn.loading("Meregister inputor...");
                    njd.inputor = njd.inputor.trim().toUpperCase();
                    if ($("option[value='" + njd.inputor + "']", cbox1).length > 0) return swal("Ops!", "Inputor '" + njd.inputor + "' sudah terdaftar", "error");

                    if (njd.index === false) {
                      fn.loadingTitle("Memeriksa data...");
                      defr.child(njd.inputor).once("value")
                      .then(sn => {
                        if (sn.exists()) {
                          indr.child("inputors/" + njd.inputor).set(true)
                          .then(() => fe(true))
                          .catch(() => fe(false, "Terjadi kegagalan, silahkan coba lagi"));

                        } else fe(false, "Inputor tidak di temukan");
                      })
                      .catch(() => fe(false, "Terjadi kegagalan, silahkan coba lagi"));

                    } else {
                      fn.loadingTitle("Mengindex data...");
                      fn.indexing("!" + njd.inputor, (r, m) => fe((m ? false : true), m));
                    }
                  };

                  @cannot("tos.config,fbdata")
                  swal({
                    title              : "Informasi!",
                    text               : "Anda dapat register inputor di firebase, tetapi tidak dapat melihat data. Apakah akan tetap dilanjutkan?",
                    type               : "info",
                    showCancelButton   : true,
                    confirmButtonColor : "#27ae60",
                    confirmButtonText  : "Ya",
                    cancelButtonColor  : "#7f8c8d",
                    cancelButtonText   : "Tidak",
                    closeOnConfirm     : true,
                    closeOnCancel      : true

                  }, ok => {
                    if (ok) setTimeout(() => _tfn(), 150);
                  });
                  @endcannot
                  @can("tos.fbauth")
                  @can("tos.config,fbdata")
                  _tfn();
                  @endcan
                  @endcan
                  break;
                @endcan
                @endcan

                @can("tos.config,excel")
                case "excel":
                  fn.generateExcel({
                    fromDate: njd.from_date,
                    toDate: njd.to_date,
                    pt: njd.pt
                  });
                  break;
                @endcan

                @can("tos.fbauth")
                @can("tos.config,pull")
                case "pull":
                  fn.pullFireBase({
                    inputor: njd.inputor,
                    fromDate: njd.from_date,
                    toDate: njd.to_date
                  });
                  break;
                @endcan
                @endcan

                @can("tos.fbauth")
                @can("tos.config,fbodelete")
                case "fbodelete":
                  fn.deleteObsoleteData({
                    around: njd.around,
                    inputor: njd.inputor,
                    untilDate: njd.until_date,
                    download: njd.download,
                    progress: (n, p) => {
                      var msg = "Progress...";
                      switch (n) {
                        case "PREPARE": msg = "Mengecek data"; break;
                        case "CHECK": msg = "Mengecek data"; break;
                        case "VERIFY": msg = "Memverifikasi data"; break;
                      }
                      if (p >= 0) msg += " " + p + "%";
                      else if (p == -1) msg += "...";
                      fn.loadingTitle(msg);
                    },
                    cb: (r, c, m) => {
                      if (r) {
                        if (c > 0) {
                          swal({
                            title: "Yeay",
                            text: "Data usang " + c + " item(s), berhasil di hapus",
                            type: "success"
                          }, () => mdldet.modal("hide"));
                        
                        } else {
                          swal({
                            title: "Sip!",
                            text: "Tidak ada data usang",
                            type: "success"
                          }, () => mdldet.modal("hide"));
                        }

                      } else swal("Ops!", m, "error");
                    }
                  });
                  break;
                @endcan
                @endcan

                default:
                  return swal("Ops!", "Mode tidak dapat di tangani", "error");
              }

            } else swal("Ops!", msg[1], "warning");
          }
        },

        _next = () => {
          elms = $(".modal-body *[type][name]", mdldet);
          if (modck) elms = elms.filter("[modified=true]");
          _ttl = elms.length;

          elms.each((i2, e2) => {
            e2 = $(e2);
            if (md == "new" && !e2.is("[modified=true]")) njd[e2.attr("name")] = null;
            else {
              switch (e2.attr("type")) {
                case 'text': default:
                  njd[e2.attr("name")] = (e2.val() || "").trim().toUpperCase();
                  break;
                case 'checkbox':
                  njd[e2.attr("name")] = e2.prop("checked");
                  break;
                case 'select':
                  var cval = $("option:selected", e2).val().trim();
                  if (cval != "") njd[e2.attr("name")] = cval.toUpperCase();
                  break;
                case 'hidden':
                  switch (e2.attr("identity")) {
                    case "image":
                      njd[e2.attr("name")] = (e2.val() || null);
                      break;
                  }
                  break;
              }
            }
            _do();
          });

          if (elms.length <= 0) {
            swal("Oke deh!", msg[1], "success");
            mdldet.modal("hide");
          }
        };

        elms = $(".modal-body *[type][required]", mdldet);
        _ttl = elms.length;

        var isValid = true;
        elms.each((i, e) => {
          if (!isValid) return;
          switch ($(e).attr("type")) {
            case "image":
              isValid = ($(e).attr("src").trim() != "");
              if (!isValid) swal("Ops!", msg[2], "info");
              break;
          }

          _ttl -= 1;
          if (_ttl == 0) _next();
        });

      } else swal("Hmm...", "Ada sesuatu yang salah!", "warning");
    });

    cbox1.on("change", function () {
      @can("tos.config,browse")
      var key = $("option:selected", this).val();
      @endcan
      @cannot("tos.config,browse")
      var key = cuser;
      @endcannot

      table1.clear();
      $(".empty", tbprev).show();
      $(".nav-tabs, .tab-content", tbprev).hide();
      $("tbody tr, ul.nav-tabs li, .tab-content .tab-pane", tbprev).remove();

      if (key != "") {
        if (key == "!") {
          @can("tos.fbauth")
          fn.showTokoForm("register", "Register Inputor");
          table1.draw();
          @endcan
          @cannot("tos.fbauth")
          swal("Ops!", "Inputor tidak valid", "error");
          @endcannot

        } else if (key == "*") {
          fn.loading("Mengambil data...", () => {
            var lst = $("option[value]:not([value='*']):not([value='!'])", cbox1);
            var ttl = lst.length;

            lst.each((ii, ee) => {
              var ck = $(ee).val();
              fn.getTokoList(ck, d => {
                for (var i in d) table1.row.add(d[i]);

                ttl -= 1;
                if (ttl == 0) {
                  table1.draw();
                  fn.setBtnListener();
                  swal.close();
                }
              });
            });
          });

        } else {
          fn.loading("Mengambil data...", () => {
            fn.getTokoList(key, d => {
              for (var i in d) table1.row.add(d[i]);
              table1.draw();

              fn.setBtnListener();
              swal.close();
            });
          });
        }

      } else table1.draw();
    });

    var sto = null, stod = null;
    $.fn.dataTableExt.afnFiltering.push((s, d) => {
      if (stod) {
        var dt = fn.strToDate(d[2]);
        if (dt >= stod.from && dt <= stod.to) return true;
        else return false;
      } else return true;
    });

    search1.on("keyup", function () {
      if (sto !== null) clearTimeout(sto);
      sto = setTimeout(() => table1.search(search1.val()).draw(), 500);
    });

    $(".date-range", drange1).on("change", function () {
      stod = fn.getDRange();
      table1.draw();
    })
    .filter(":first").trigger("change");

    $('#table01 tbody').on('click', 'tr', function (e) {
      if (!$(this).hasClass('selected')) {
        table1.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');

        $("tbody tr, ul.nav-tabs li, .tab-content .tab-pane", tbprev).remove();
        if ($(e.target).is(".btn, .fa")) return;

        var custom = {
          'ref': null, 'key': null, 'loaded': null, 'id': null,
          'created_at': null, 'updated_at': null, 'order': null,
          'detected_location': d => (fn.setMaps("Detected location", d.detected_location)),
          'lokasi': d => (fn.setMaps("Lokasi", d.lokasi)),
          'scan_ktp': d => (fn.setImage(d.scan_ktp)),
          'foto_toko': d => (fn.setImage(d.foto_toko)),
          'verified': d => (d.verified ? "Sudah" : "Belum"),
          'verifikasi_foto_ktp': d => (d.verifikasi_foto_ktp ? "Sudah" : "Belum"),
          'verifikasi_foto_toko': d => (d.verifikasi_foto_toko ? "Sudah" : "Belum")
        };

        var _do = () => {
          var dat = table1.row(this).data();
          for (var k in dat) {
            var val = dat[k];
            if (typeof custom[k] != 'undefined') {
              if (typeof custom[k] == "function") val = custom[k](dat);
              else val = custom[k];
            }

            if (val !== null) {
              var cg = fn.getTokoFieldGroup(k);
              var mpar = $(".tab-content #preview_menu_" + cg.name, tbprev);
              if (mpar.length <= 0) {
                $("<li><a data-toggle='tab' href='#preview_menu_" + cg.name + "'>" + cg.title + "</a></li>")
                .appendTo($("ul.nav-tabs", tbprev));

                mpar = "<div id='preview_menu_" + cg.name + "' class='tab-pane fade'>";
                mpar += "<table class='table table-striped'>";
                mpar += "<tbody></tbody>";
                mpar += "</table>";
                mpar += "</div>";

                mpar = $(mpar);
                mpar.appendTo($(".tab-content", tbprev));
              }

              if (val instanceof jQuery) val.appendTo($("tbody", mpar));
              else {
                $("<tr><td>" + fn.getUIName(k) + "</td><td width='1'>:</td><td>" + val + "</td></tr>")
                .appendTo($("tbody", mpar));
              }
            }
          }

          var ca = $("ul.nav-tabs li:first", tbprev);
          if (ca.length > 0) {
            ca.addClass("in active");
            $(".tab-content " + $("a:first", ca).attr("href"), tbprev).addClass("in active");
            $(".nav-tabs, .tab-content", tbprev).show();
            $(".empty", tbprev).hide();
          }

          $("img[storage]", tbprev).each((i, e) => {
            var thisx = $(e);
            var st = thisx.attr("storage");

            var src = fn.getImageSrc(thisx.attr("storage"), r => {
              thisx.removeAttr("storage").attr("src", r);
            });
            if (src !== true) thisx.removeAttr("storage").attr("src", src);

          }).viewer({
            toolbar: false,
            navbar: false
          });
        };

        fn.getFullData(table1.row(this).index(), _do);
      }
    });

    fn.loadingTitle("Sinkronisasi tanggal...");
    fn.synchTime((r, d) => {
      time = d.now;
      setInterval(() => {
        time = new Date(time.getTime() + 150)
        fn.checkVersion();

      }, 150);
      setInterval(() => fn.synchTime((r2, d2) => {
        if (r2) time = d2.now;
      }), 5 * 60 * 1000);

      var rdate = fn.currentDateTime(time).substring(0, 11);
      if (fn.currentDateTime(new Date()).substring(0, 11) != rdate) {
        return swal({
          title: "Ops!",
          text: "Tanggal komputer anda tidak benar,\nSekarang tanggal " + rdate + "\n\nReload browser jika sudah dibenahi",
          type: "warning",
          showConfirmButton: false,
          allowOutsideClick: false
        });
      }

      fn.loadingTitle("Mengambil data...");
      fn.refreshUserList(() => {
        cbox1.trigger("change");
        swal.close();
      });
    });
  };

</script>
@endpush
