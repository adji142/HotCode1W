@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/circle.css') }}">
<style>
.loader {
	border: 10px solid #f3f3f3;
	border-radius: 50%;
	border-top: 10px solid #3498db;
	width: 120px;
	height: 120px;
	-webkit-animation: loader-spin 2s linear infinite; /* Safari */
	animation: loader-spin 2s linear infinite;
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
</style>
@endpush

@section('main_container')
<div style="display: block; overflow: auto; position: fixed; top: 0; left: 0; right: 0; bottom: 0; padding: 10px;">
	<div class="notices" style="display: none; margin-bottom: 10px;"></div>
	<div class="exports"></div>

	<div class="heads" style="display: none; margin-top: 25px"></div>
	<table style="display: none;" class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
	            @foreach ($uicols['h'] as $k2 => $v)
	                @if ($uicols['h'][0] !== $v) <tr> @endif
	                @foreach ($v as $k2 => $v2)
	                    @if (gettype($v2) == 'array')
	                        <th
	                            style="border-bottom: 1px solid #000;"
	                            @if (isset($v2['cols'])) colspan="{{ $v2['cols'] }}" @endif
	                            @if (isset($v2['rows'])) rowspan="{{ $v2['rows'] }}" @endif

	                        >{{ $v2['text'] }}</th>
	                    @elseif ($v2 !== false)
	                        <th style="border-bottom: 1px solid #000;">{{ $v2 }}</th>
	                    @endif
	                @endforeach
	                @if (end($uicols['h']) !== $v) </tr> @endif
	            @endforeach
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<div id="table_status" class="well" style="display: none; margin-top: 25px;">
		<h4>Laporan proses</h4>
		<table class="table display nowrap table-stripped" cellspacing="0">
			<tbody></tbody>
		</table>
	</div>
</div>
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
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script name="swalHTML" type="text/template">
	<!--<span class="status" style="display: block; margin-bottom: 3px; text-align: left;">Loading...</span>-->
	<div style="display: inline-block;">
		@if ($info['approxTotal'] <= 0)
			<div class="loader circle-dat"></div>
		@else
			<div class="circle-bar2 loader"></div>
			<div class="circle-bar c100 p0" style="display: none;">
	        	<span class="curcle-dat">0%</span>
	        	<div class="slice">
	            	<div class="bar"></div>
	            	<div class="fill"></div>
	        	</div>
	    	</div>
		@endif
	</div>
	<p style="display: block; margin-top: 5px;">
		Tunggu hingga proses selesai
	</p>
</script>
<script type="text/javascript">
	var itDone = false;

	window.addEventListener("beforeunload", function (e) {
        if (itDone) return undefined;
        var cmsg = 'Laporan masih dalam proses, yakin akan di gagalkan?';

        (e || window.event).returnValue = cmsg;
        return cmsg;
	});

	$(document).ready(function() {
	    swal({
    		title: "Progress",
    		text: $("script[name=swalHTML]").text(),
    		showConfirmButton: false,
    		allowOutsideClick: false,
    		html: true
		});

	    var i = 0,
	    	err = false,
	    	spar = $(".sweet-alert", document),
	    	asize = {{ $info['approxTotal'] }},
	    	lcls = 'p{{ $info["approxTotal"] > 0 ? 0 : 100 }}',
	    	msg = '';
		xhr = new XMLHttpRequest();
		xhr.open("GET", "{!! $procurl !!}" , true);
		xhr.onprogress = function(e) {
			if (err) return;

			var str = e.currentTarget.responseText;
			if (str[str.length - 1] == ",") {
				msg = str.substr(i, (str.length - i - 1));
				i += msg.length + 1;
				try {
					msg = JSON.parse("[" + msg + "]");
					msg = msg.pop();

				} catch (ex) { err = "Error parsing data: " + msg; }

			} else err = true;

			if (err) return;
			try {
				if (typeof msg.done != 'undefined') {
					err = msg.done !== true ? msg.msg : false;
					if (msg.done === true) {
						if (typeof msg.exports != 'undefined') {
							var expar = $(".exports", document);
							for (var k in msg.exports) {
								var str = $("<a href='" + msg.exports[k].url + "' class='btn btn-success' target='_blank'></a>");
								str.append("<i class='fa fa-file-" + k.toLowerCase() + "-o'></i> " + msg.exports[k].text);
								expar.append(str);
							}
						}

						if (typeof msg.head != 'undefined' && Array.isArray(msg.head)) {
							var hdpar = $(".heads", document).show();
							for (var hs in msg.head) {
								var hsc = msg.head[hs];
								if (typeof hsc == 'string' && hsc.length > 0 && hsc[0] == "!") {
									if (hsc.length > 1 && hsc[1] != '!') hsc = hsc.substr(1);
									else hsc = hsc.substr(1) + '<br />';
								} else hsc += '<br />';
								hdpar.append(hsc);
							}
						}

						if (typeof msg.data != 'undefined' && Array.isArray(msg.data)) {
							$('#table_report').DataTable({
								dom: 'rt',
								paging: false,
								scrollCollapse: true,
								buttons: [
									{
										extend: 'pdfHtml5',
										orientation: 'landscape',
										pageSize: 'LEGAL',
										download: 'open',
										text: '<i class="fa fa-file-pdf-o"></i>',
										title: '{{ $info['title'] }}'
									}
								],
								data: msg.data
							});
						}

						if (msg.total > 1000) {
							if (typeof msg.notice == 'undefined') msg.notice = [];
							else if (!Array.isArray(msg.notice)) msg.notice = [];
							msg.notice.push({
								'type': 'info',
								'text': 'Data tidak dapat di tampilkan dalam browser, karena terlalu besar'
							});
						}

						if (typeof msg.notice != 'undefined' && Array.isArray(msg.notice)) {
							var ntpar = $(".notices", document);
							for (var k in msg.notice) {
								var str = $("<div class='alert alert-" + (typeof msg.notice[k].type != 'undefined' ? msg.notice[k].type : 'warning') + "' style='margin-bottom: 5px;'>" + msg.notice[k].text + "</div>");
								ntpar.append(str);
							}
							ntpar.show();
						}
					}

				} else if (typeof msg.msg != 'undefined') {
					err = msg.msg;

				} else if (typeof msg.status != 'undefined' && typeof msg.value == 'undefined') {
					$("h2:first", spar).text(msg.status);

				} else if (typeof msg.value != 'undefined') {
					if (typeof msg.status != 'undefined') $("h2:first", spar).text(msg.status);
					if (asize > 0) {
						var cperc = Math.round((msg.value / asize) * 100);
						$(".circle-bar", spar).removeClass(lcls).addClass('p' + cperc);
						$(".curcle-dat", spar).text(cperc + '%')
						lcls = 'p' + cperc;

						if (cperc == 100) {
							$(".circle-bar2", spar).show();
							$(".circle-bar", spar).hide();
						} else {
							$(".circle-bar2", spar).hide();
							$(".circle-bar", spar).show();
						}

					} else $(".curcle-dat", spar).text(msg.value + " items");
				}

			} catch (ex) { err = "Internal error: " + ex.message; }
		};

		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				itDone = true;
				if (err) swal("Oops!", $("<div>" + (err === true ? xhr.responseText : err) + "</div>").text(), 'error');
				else if (typeof msg.done == 'undefined') swal("Hmm...", "Sepertinya ada sesuatu yang salah?!", "warning");
				else if (msg.total > 0) {
					if (msg.total <= 1000) $("#table_report").show();
					swal("Selesai", "Laporan berhasil di proses \nDurasi proses: " + msg.interval, "success");

				} else swal("Oops!", "Tidak ada data untuk di tampilkan", "warning");

				if (!$("#table_report").is(":visible")) {
					var scols = {
						'done': 'Status',
						'interval': "Durasi",
						'mem': "Memory",
						'total': "Total"
					};
					var stbl = $("#table_status");
					for (var k in scols) {
						var c = typeof msg[k] != 'undefined' ? msg[k] : null;
						switch (k) {
							case 'done': c = c ? 'Berhasil' : 'Gagal'; break;
							case 'mem': c += ' MB'; break;
						}
						if (c !== null) $("tbody", stbl).append("<tr><td width='1'>" + scols[k] + "</td><td>" + c + "</td></tr>");
					}	
					stbl.show();				
				}
			}
		};
		xhr.send();
	});
</script>
@endpush