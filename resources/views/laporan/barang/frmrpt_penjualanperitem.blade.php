  @extends('layouts.default')

  @section('breadcrumb')
      <li class="breadcrumb-item">Laporan</a></li>
      <li class="breadcrumb-item">Barang</li>
      <li class="breadcrumb-item active">Laporan Penjualan Per Item</li>
  @endsection

  @section('main_container')
    <div class="mainmain">
      <div class="row">
        <div class="x_panel">
          <div class="x_title">
            <h2>Laporan Penjualan Per Item</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form id="formView" class="form-horizontal form-label-left" method="POST" action="{{route('rptpenjualanperitem.report')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="jenis1" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                    <div class="col-md-7 col-sm-7 col-xs-12" style="padding:0.5%;">
                        <input type="radio" name="jenis1" id="jenis1_fixed" value="fixed" class="iCheck" CHECKED> Fixed     
                        <input type="radio" name="jenis1" id="jenis1_contain" value="contain" class="iCheck"> Contain
                    </div>
                </div>
                <div class="form-group">
                    <label for="jenis2" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                    <div class="col-md-7 col-sm-7 col-xs-12" style="padding:0.5%;">
                        <input type="radio" name="jenis2" value="brutto" class="iCheck" CHECKED> Brutto     
                        <input type="radio" name="jenis2" value="netto" class="iCheck"> Netto
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglmulai">Tanggal</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="@if(session('tglmulai')!=''){{session('tglmulai')}}@else{{date('01/m/Y')}}@endif"  tabindex="1">
                    <label>-</label>
                    <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="@if(session('tglselesai')!=''){{session('tglselesai')}}@else{{date('d/m/Y')}}@endif"  tabindex="2">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salesid">Salesman<span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="namasales" class="form-control" placeholder="Sales" autocomplete="off" required="required">
                    <input type="hidden" id="salesid">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodesales">Kode Sales</label>
                  <div class="form-group row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="text" id="kodesales" class="form-control" readonly tabindex="-1">
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota <span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="kota" class="form-control" placeholder="Kota" autocomplete="off" required="required">
                  </div>
                </div>


                <div class="form-group" id="div_namatoko2" hidden="hidden">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tokoid2">Toko<span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="namatoko2" class="form-control" placeholder="Toko" autocomplete="off" required="required">
                  </div>
                </div>

                <div class="form-group" id="div_namatoko">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tokoid">Toko<span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="namatoko" class="form-control" placeholder="Toko" autocomplete="off" required="required">
                    <input type="hidden" id="tokoid">
                  </div>
                </div>
                <div class="form-group" id="div_alamattoko">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamattoko">Alamat Toko</label>
                  <div class="form-group row">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" id="alamattoko" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
                </div>
                <div class="form-group" id="div_kodetoko">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodetoko">KodeToko/WILID</label>
                  <div class="form-group row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="text" id="kodetoko" class="form-control" readonly tabindex="-1">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="text" id="wilid" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                    <label for="jenis3" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                    <div class="col-md-7 col-sm-7 col-xs-12" style="padding:0.5%;">
                        <input type="radio" name="jenis3" id="jenis3_tokoall" value="tokoall" class="iCheck" CHECKED> All
                        <input type="radio" name="jenis3" id="jenis3_tokopareto" value="tokopareto" class="iCheck"> Toko Pareto     
                    </div>
                </div>

                <div class="form-group" id="div_barang2" hidden="hidden">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid2">Barang <span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="barang2" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                  </div>
                </div>
                <div class="form-group" id="div_barang">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                    <input type="hidden" id="barangid">
                  </div>
                </div>
                <div class="form-group" id="div_satuan">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
                  <div class="form-group row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kelompokbarang">Kelompok Barang</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <select id="kelompokbarang" name="kelompokbarang" tabindex="4"></select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemilik_omset">C1</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <select id="pemilik_omset" name="pemilik_omset" tabindex="4"></select>
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="hidden" id="gudang_pemilik_omset" name="gudang_pemilik_omset" class="form-control col-md-7 col-xs-12" readonly tabindex="-1" value="{{session('subcabang')}}">
                    </div>
                </div>


                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="katPenjualan">Kat Penjualan</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <select id="katPenjualan" name="katPenjualan" tabindex="4"></select>
                  </div>
                </div>

                <div class="form-group" id="div_rekap" hidden="hidden">
                    <label for="jenis4" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                    <div class="col-md-7 col-sm-7 col-xs-12" style="padding:0.5%;">
                        <input type="radio" name="jenis4" id="jenis4_detail" value="detail" class="iCheck" CHECKED> Detail
                        <input type="radio" name="jenis4" id="jenis4_rekap"  value="rekap" class="iCheck"> Rekap     
                    </div>
                </div>


              <div class="col-md-12">
                <button type="submit" id="btnProses" class="btn btn-success">Proses</button>
              </div>
            </form>
            
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6">
                <a href="#" class="btn btn-primary">Kembali</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection

  @push('scripts')
    <script src="{{asset('assets/js/microplugin.js')}}"></script>
    <script src="{{asset('assets/js/sifter.js')}}"></script>
    <script src="{{asset('assets/js/selectize.js')}}"></script>
    <!-- Form Validation -->
    <!-- <script src="{{asset('assets/js/validator.js')}}"></script> -->

    <script type="text/javascript">
      var table1,fokus;

      lookupsalesman();
      lookuptoko();
      lookupbarang();

      $(document).ready(function(){

        document.getElementById("jenis1_fixed").checked = true;
        document.getElementById("jenis3_tokoall").checked = true;
        document.getElementById("jenis4_detail").checked = true;

        $(".tgl").inputmask();

        $('input.iCheck').iCheck({
                radioClass: 'iradio_flat-green'
          });


        $("#kelompokbarang").selectize({
            maxItems : 1,
          }); 

        $("#pemilik_omset").selectize({
            maxItems : 1,
          }); 

        $("#katPenjualan").selectize({
            maxItems : 1,
          });

        $('input[name=jenis1]').on('ifChecked', function(event){
            if(document.getElementById("jenis1_fixed").checked)
            {
                document.getElementById("div_namatoko").style.display = 'block' ;
                document.getElementById("div_kodetoko").style.display = 'block' ;
                document.getElementById("div_alamattoko").style.display = 'block' ;
                document.getElementById("div_barang").style.display = 'block' ;
                document.getElementById("div_satuan").style.display = 'block' ;

                document.getElementById("div_namatoko2").style.display = 'none' ;
                document.getElementById("div_barang2").style.display = 'none' ;
                
            }
            else
            {
                document.getElementById("div_namatoko").style.display = 'none' ;
                document.getElementById("div_kodetoko").style.display = 'none' ;
                document.getElementById("div_alamattoko").style.display = 'none' ;
                document.getElementById("div_barang").style.display = 'none' ;
                document.getElementById("div_satuan").style.display = 'none' ;

                document.getElementById("div_namatoko2").style.display = 'block' ;
                document.getElementById("div_barang2").style.display = 'block' ;
            }
        });

        $('input[name=jenis3]').on('ifChecked', function(event){
            if(document.getElementById("jenis3_tokopareto").checked)
            {
                document.getElementById("div_rekap").style.display = 'block' ;
            }
            else
            {
                document.getElementById("div_rekap").style.display = 'none' ;
            }
        });



        $("#btnProses").click(function(e){
          e.preventDefault();


            var url = "{{route('rptpenjualanperitem.report')}}" + 
              "?fromdate=" + $("#tglmulai").val() + 
              "&todate=" + $("#tglselesai").val() +
              "&salesid=" + $("#salesid").val() +
              "&kota=" + $("#kota").val() +
              "&omsetsubcabangid=" + $("#pemilik_omset").val() +
              "&kelompokbarang=" + $("#kelompokbarang").val() +
              "&jenislaporan=" + $('input[name="jenis2"]:checked').val() +
              "&katpenjualan=" + $("#katPenjualan").val() +
              "&tokopareto=" + $('input[name="jenis3"]:checked').val() +
              "&rekap=" + $('input[name="jenis4"]:checked').val();

            if(document.getElementById("jenis1_fixed").checked)
            {
              url = url + 
                    "&tokoid=" + $("#tokoid").val() +
                    "&kodebarang=" + $("#kodebarang").val();
            }
            else
            {
              url = url + 
                    "&namatoko=" + $("#namatoko2").val() +
                    "&namabarang=" + $("#barang2").val();
            }
            var myWindow = window.open(url, "", "width=1200,height=600");
        });

        // // Lookup Salesman
        // $('#namasales').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     var recowner = "{{session('subcabang')}}";
        //     $('#modalSales').modal('show');
        //     $('#txtQuerySales').val($(this).val());
        //     search_sales(recowner, $(this).val());
        //     return false;
        //   }
        // });
  
        // $('#modalSales').on('shown.bs.modal', function () {
        //   $('#txtQuerySales').focus();
        // });

        // $('#tbodySales').on('click', 'tr', function(){
        //   $('.selected').removeClass('selected');
        //   $(this).addClass("selected");
        // });

        // $('#modalSales').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     pilih_sales();
        //   }
        // });

        // $('#btnPilihSales').on('click', function(){
        //   pilih_sales();
        // });


        // // Lookup Toko
        // $('#namatoko').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     $('#modalToko').modal('show');
        //     $('#txtQueryToko').val($(this).val());
        //     search_toko($(this).val());
        //     return false;
        //   }
        // });
  
        // $('#modalToko').on('shown.bs.modal', function () {
        //   $('#txtQueryToko').focus();
        // });

        // $('#tbodyToko').on('click', 'tr', function(){
        //   $('.selected').removeClass('selected');
        //   $(this).addClass("selected");
        // });

        // $('#modalToko').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     pilih_toko();
        //   }
        // });

        // $('#btnPilihToko').on('click', function(){
        //   pilih_toko();
        // });


        // // Lookup barang
        // $('#barang').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     $('#modalBarang').modal('show');
        //     $('#txtQueryBarang').val($(this).val());
        //     search_barang($(this).val());
        //     return false;
        //   }
        // });
  
        // $('#modalBarang').on('shown.bs.modal', function () {
        //   $('#txtQueryBarang').focus();
        // });


        // $('#tbodyBarang').on('click', 'tr', function(){
        //   fokus = 'lookupbarang';
        //   $('.selected').removeClass('selected');
        //   $(this).addClass("selected");
        // });

        // $('#modalBarang').on('keypress', function(e){
        //   if (e.keyCode == '13') {
        //     pilih_barang();
        //   }
        // });

        // $('#btnPilihBarang').on('click', function(){
        //   pilih_barang();
        // });
     
      });

    // function search_sales(recowner, query){
    //   $.ajax({
    //     type: 'GET',
    //     url: '{{route("hr.getsalesman",[null,null])}}/' + recowner + '/' + query, 
    //     success: function(data){
    //       var sales = JSON.parse(data);
          
    //       $('#tbodySales tr').remove();
    //       var x = '';
    //       if (sales.length > 0) {
    //         for (var i = 0; i < sales.length; i++) {
    //           x += '<tr>';
    //           x += '<td>' + sales[i].kodesales + '</td>';
    //           x += '<td>' + sales[i].namakaryawan + '<input type="hidden" class="id_sales" value="'+ sales[i].id +'"></td>';
    //           x += '</tr>';
    //         }
    //       }else {
    //         x += '<tr><td colspan="2" class="text-center">Tidak ada detail</td></tr>'
    //       }
    //       $('#tbodySales').append(x);
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    //   });
    // }

    // function pilih_sales(){
    //   if ($('#tbodySales').find('tr.selected td').eq(1).text() == '') {
    //     swal("Ups!", "Sales belum dipilih.", "error");
    //     return false;
    //   }else {
    //     $('#kodesales').val($('#tbodySales').find('tr.selected td').eq(0).text());
    //     $('#namasales').val($('#tbodySales').find('tr.selected td').eq(1).text());
    //     $('#salesid').val($('#tbodySales').find('tr.selected td .id_sales').val());
    //     $('#modalSales').modal('hide');
    //   }
    // }


    // function search_toko(query){
    //   $.ajax({
    //     type: 'GET',
    //     url: '{{route("mstr.gettoko",[null])}}/' + query, 
    //     success: function(data){
    //       var toko = JSON.parse(data);
    //       $('#tbodyToko tr').remove();
    //       var x = '';
    //       if (toko.length > 0) {
    //         for (var i = 0; i < toko.length; i++) {
    //           x += '<tr>';
    //           x += '<td>' + toko[i].kodetoko + '</td>';
    //           x += '<td>' + toko[i].namatoko + '<input type="hidden" class="id_toko" value="'+ toko[i].id +'"></td>';
    //           x += '<td>' + toko[i].customwilayah + '</td>';
    //           x += '<td>' + toko[i].alamat + '</td>';
    //           x += '<td>' + toko[i].kecamatan + '</td>';
    //           x += '<td>' + toko[i].kota + '</td>';
    //           x += '</tr>';
    //         }
    //       }else {
    //         x += '<tr><td colspan="10" class="text-center">Toko tidak ada detail</td></tr>'
    //       }
    //       $('#tbodyToko').append(x);
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    //   });
    // }

    // function pilih_toko(){
    //   if ($('#tbodyToko').find('tr.selected td').eq(1).text() == '') {
    //     swal("Ups!", "Toko belum dipilih.", "error");
    //     return false;
    //   }else {
    //     $('#kodetoko').val($('#tbodyToko').find('tr.selected td').eq(0).text());
    //     $('#namatoko').val($('#tbodyToko').find('tr.selected td').eq(1).text());
    //     $('#wilid').val($('#tbodyToko').find('tr.selected td').eq(2).text());
    //     $('#alamattoko').val($('#tbodyToko').find('tr.selected td').eq(3).text());
    //     $('#kotatoko').val($('#tbodyToko').find('tr.selected td').eq(4).text());
    //     $('#tokoid').val($('#tbodyToko').find('tr.selected td .id_toko').val());
    //     $('#modalToko').modal('hide');
    //   }
    // }


    // function search_barang(query){
    //   $.ajax({
    //     type   : 'GET',
    //     url    : '{{route("lookup.getbarang",null)}}/' + query,
    //     data   : {filter : barang_custom_search},
    //     success: function(data){
    //       var barang = JSON.parse(data);
    //       $('#tbodyBarang tr').remove();
    //       var x = '';
    //       if (barang.length > 0) {
    //         for (var i = 0; i < barang.length; i++) {
    //           x += '<tr>';
    //           x += '<td class="menufilter textfilter">'+ barang[i].kodebarang;
    //           x += '<input type="hidden" class="id_brg" value="'+ barang[i].id +'">';
    //           x += '<input type="hidden" class="satuan" value="'+barang[i].satuan+'"></td>';
    //           x += '<td class="menufilter textfilter">'+ barang[i].namabarang +'</td>';
    //           x += '<td>'+ barang[i].jmlgudang +'</td>';
    //           x += '</tr>';
    //         }
    //       // }else {
    //       //     x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
    //       }

    //       if ($.fn.DataTable.isDataTable("#tablebarang")) {
    //           $('#tablebarang').DataTable().clear().destroy();
    //       }
    //       $('#tbodyBarang').append(x);
    //       $('#tablebarang').DataTable({ dom: 'rt', paging: false, "order": [[ 1, 'asc' ]],});
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    //   });
    // }

    // function pilih_barang(){
    //   if ($('#tbodyBarang').find('tr.selected td').eq(1).text() == '') {
    //     swal("Ups!", "Barang belum dipilih.", "error");
    //     return false;
    //   }else {
    //     $('#kodebarang').val($('#tbodyBarang').find('tr.selected td').eq(0).text());
    //     $('#barang').val($('#tbodyBarang').find('tr.selected td').eq(1).text());
    //     $('#barangid').val($('#tbodyBarang').find('tr.selected td .id_brg').val());
    //     $('#satuan').val($('#tbodyBarang').find('tr.selected td .satuan').val());
    //     $('#modalBarang').modal('hide');
    //     $('#qtyorder').focus();
    //   }
    // }

     $.ajax({
        url : '{{ route("kelompokbarang.data") }}',
        type : "GET",
        success : function(data){
          var result = data.data;
          var temp = $("#kelompokbarang")[0].selectize;
          
          // console.log(result);
          $.each(result, function(k, v){
            temp.addOption({
              text : v.kode + " | " + v.keterangan,
              value : v.kode,
            });
          });
        }
      });

      $.ajax({
        url : '{{ route("mstr.getsubcabang") }}',
        type : "GET",
        success : function(data){
          var result = data.data;
          // console.log(result);

          var temp = $("#pemilik_omset")[0].selectize;
          
          $.each(result, function(k, v){
            temp.addOption({
              text : v.kodesubcabang + " | " + v.namasubcabang,
              value : v.id,
            });
          });

          temp.setValue($("#gudang_pemilik_omset").val());
        }
      });

      $.ajax({
        url : '{{ route("mstr.getkategoripenjualan") }}',
        type : "GET",
        success : function(data){
          var result = data.data;
          var temp = $("#katPenjualan")[0].selectize;
          
          // console.log(result);
          $.each(result, function(k, v){
            temp.addOption({
              text :  v.kategori,
              value : v.id,
            });
          });
        }
      });

    </script>
  @endpush
