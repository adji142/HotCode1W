@extends('layouts.default')

@section('breadcrumb')
		<li class="breadcrumb-item">Laporan</li>
		<li class="breadcrumb-item">{{ ucfirst($rpname) }}</li>
		<li class="breadcrumb-item"><a href="{{ route('laporan.' . $rpname . '.index', ['dname' => $dname]) }}">{{ $info['title'] }}</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>{{ $info['title'] }}</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-10 col-sm-12">
						<form name="mainFrm" class="form-label-left" style="margin-bottom: 10px;">
							<div class="item form-group">
								<label class="control-label">Cabang</label>
								<select name="subcid" class="select2_single form-control search" tabindex="-1" name="subcabang" id="subcabang" disabled>
									@foreach($data['subcabang'] as $sub)
										@if(session('subcabang') == $sub->id)
											<option value="{{$sub->id}}" selected>{{$sub->kodesubcabang}} | {{$sub->namasubcabang}}</option>
										@else
											<option value="{{$sub->id}}">{{$sub->kodesubcabang}} | {{$sub->namasubcabang}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="item form-group">
								<label class="control-label">Tanggal</label>
								<div style="display: inline-block; width:100%;">
									<input name="dstart" type="text" id="tglmulai" class="tgl form-control" placeholder="Tgl. mulai" data-inputmask="'mask': 'd-m-y'" value="{{ session('tglmulai') }}" style="width:45%; float:left;">
									<span style="display: inline-block; float: left; text-align: center; width: 10%; padding: 5px 0;"> - </span>
									<input name="dend" type="text" id="tglselesai" class="tgl form-control" placeholder="Tgl. selesai" data-inputmask="'mask': 'd-m-y'" value="{{ session('tglselesai') }}" style="width: 45%; float: left;">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Mode</label>
								<div>
									<select name="mode" class="form-control">
										<option value="specific">Stock Terpilih</option>
										<option value="all">Semua Brg</option>
										<option value="group">Kelompok Brg</option>
									</select>
								</div>
							</div>
							<div xopt="all|group" class="form-group" style="display: none;">
								<label class="control-label">Status</label>
								<div>
									<div class="radio-inline">
										<label><input type="radio" name="status" value="active">AKTIF</label>
									</div>
									<div class="radio-inline">
										<label><input type="radio" name="status" value="passive">PASSIVE</label>
									</div>
									<div class="radio-inline">
										<label><input type="radio" name="status" value="all" checked>SEMUA</label>
									</div>
								</div>
							</div>
							<div xopt="group" class="form-group" style="display: none;">
								<label class="control-label">Kelompok</label>
								<select class="select2_single form-control search" tabindex="-1" name="stcgid" id="stcgroup">
									<option value="?" selected>-- Pilih group --</option>
									<option value="fb2">FB2</option>
									<option value="fb4">FB4</option>
									<option value="fe2">FE2</option>
									<option value="fe4">FE4</option>
								</select>
							</div>
							<div xopt="specific" class="form-group">
								<label class="control-label">Barang</label>
								<div>
									<input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
									<input name="stcid" type="hidden" id="barangid">
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-2 col-sm-12 text-right">
						<p>
							<a onclick="$('form[name=mainFrm]').submit()" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		$(".tgl").inputmask();

		lookupbarang(null, null, 'hitungbo');

		$('.modal', document).on('show.bs.modal', function(event) {
			var idx = $('.modal:visible').length;
			$(this).css('z-index', 1040 + (10 * idx));

			idx = ($('.modal:visible').length) - 1; // raise backdrop after animation.
			$('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
			$('.modal-backdrop').not('.stacked').addClass('stacked');
		});

		$(document).on('hidden.bs.modal', '.modal', function () { // fix modal's scroll
			$('.modal:visible').length && $(document.body).addClass('modal-open');
		});

		var frm = $("form[name=mainFrm]", document).submit(function (e) {
			e.preventDefault();

			var allow = false;
			var md = $("select[name=mode]", document).val();
			var dt = {
				'tglawal'   : $("[name=dstart]", this).val().trim(),
				'tglakhir'  : $("[name=dend]", this).val().trim()
			};

			switch (md) {
				case "all":
					switch ($("[name=status]:checked", this).val()) {
						case "active": dt['status'] = 1; break;
						case "passive": default: dt['status'] = 0; break;
						case "all": dt['status'] = 2; break;
					}
					break;
				case "specific":
					dt['stockid'] = $("[name=stcid]", this).val().trim();
					break;
				case "group":
					dt['groupid'] = $("[name=stcgid]", this).val().trim();
					switch ($("[name=status]:checked", this).val()) {
						case "active": dt['status'] = 1; break;
						case "passive": default: dt['status'] = 0; break;
						case "all": dt['status'] = 2; break;
					}
					if (dt['groupid'] == '?') return swal('Ups!!', 'Pilih dahulu kelompok barang nya', 'error');
					break;
			}

			var tmp = dt.tglawal.split("-");
			if (tmp.length != 3) return swal("Ups!!", "Tanggal yang anda masukkan tidak valid", "error");
			else dt.tglawal = tmp[2] + "-" + tmp[1] + "-" + tmp[0];

			tmp = dt.tglakhir.split("-");
			if (tmp.length != 3) return swal("Ups!!", "Tanggal yang anda masukkan tidak valid", "error");
			else dt.tglakhir = tmp[2] + "-" + tmp[1] + "-" + tmp[0];

			if (dt.subcabang == "" || (dt.stockid == "" && md == 'specific')) return swal("Ups!!", "Lengkapi data terlebih dahulu", "error");

			allow = true;
			if (allow) {
				var dtp = "";
				for(var k in dt) {
					if (dtp !== "") dtp += "&";
					dtp += encodeURIComponent(k) + "=" + encodeURIComponent(dt[k]);
				}
				var url = '{{ route("laporan." . $rpname . ".preview", ["dname"=>$dname]) }}?' + dtp;
				window.open(url);
			}
		});

		$("select[name=mode]", frm).change(function () {
			var cur = $(this).val();
			$("*[xopt]", frm).each(function (i) {
				var attr = $(this).attr("xopt").split("|");
				if (attr.indexOf(cur) >= 0) $(this).show();
				else $(this).hide();
			});

		}).trigger("change");

	});
</script>
@endpush