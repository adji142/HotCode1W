@can('transaksi.index')
<div class="menu_section">
<h3>Transaksi</h3>
<ul class="nav side-menu">
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Pembelian</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('orderpembelian.index')
<li>
<a href="{{ route('orderpembelian.index') }}">
<span>Order Pembelian</span>
</a>
</li>
@endcan
@can('notapembelian.index')
<li>
<a href="{{ route('notapembelian.index') }}">
<span>Nota Pembelian</span>
</a>
</li>
@endcan
@can('returpembelian.index')
<li>
<a href="{{ route('returpembelian.index') }}">
<span>Retur Pembelian</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Penjualan</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('orderpenjualan.index')
<li>
<a href="{{ route('orderpenjualan.index') }}">
<span>Order Penjualan</span>
</a>
</li>
@endcan
@can('accpiutang.index')
<li>
<a href="{{ route('accpiutang.index') }}">
<span>Acc Piutang</span>
</a>
</li>
@endcan
@can('notapenjualan.index')
<li>
<a href="{{ route('notapenjualan.index') }}">
<span>Nota Penjualan</span>
</a>
</li>
@endcan
@can('salesorder.index')
<li>
<a href="{{ route('salesorder.index') }}">
<span>Sales Order</span>
</a>
</li>
@endcan
@can('returpenjualan.index')
<li>
<a href="{{ route('returpenjualan.index') }}">
<span>Retur Penjualan</span>
</a>
</li>
@endcan
@can('suratjalan.index')
<li>
<a href="{{ route('suratjalan.index') }}">
<span>Surat Jalan</span>
</a>
</li>
@endcan
@can('pengiriman.index')
<li>
<a href="{{ route('pengiriman.index') }}">
<span>Pengiriman</span>
</a>
</li>
@endcan
@can('fixed.index')
<li>
<a href="{{ route('fixed.index') }}">
<span>Fixed Route</span>
</a>
</li>
@endcan
@can('ftagih.index')
<li>
<a href="{{ route('ftagih.index') }}">
<span>Form Tagihan</span>
</a>
</li>
@endcan
@can('planjual.index')
<li>
<a href="{{ route('planjual.index') }}">
<span>Plan Jual</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
<li>
<a href="javascript:void(0)">
<span>Plan Jual Tambah</span>
</a>
</li>
@can('planjual.update')
<li>
<a href="{{ route('planjual.update') }}">
<span>Plan Jual Update</span>
</a>
</li>
@endcan
@can('planjual.upload')
<li>
<a href="{{ route('planjual.upload') }}">
<span>Plan Jual Upload</span>
</a>
</li>
@endcan
</ul>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Stock</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('opname.index')
<li>
<a href="{{ route('opname.index') }}">
<span>Opname</span>
</a>
</li>
@endcan
@can('mutasi.index')
<li>
<a href="{{ route('mutasi.index') }}">
<span>Mutasi</span>
</a>
</li>
@endcan
@can('scrab.index')
<li>
<a href="{{ route('scrab.index') }}">
<span>Scrab</span>
</a>
</li>
@endcan
@can('antargudang.index')
<li>
<a href="{{ route('antargudang.index') }}">
<span>Antar Gudang</span>
</a>
</li>
@endcan
@can('antargudangv2.index')
<li>
<a href="{{ route('antargudangv2.index') }}">
<span>Antar Gudang V2</span>
</a>
</li>
@endcan
@can('standarstock.index')
<li>
<a href="{{ route('standarstock.index') }}">
<span>Standar Stock</span>
</a>
</li>
@endcan
</ul>
</li>
</ul>
</div>
@endcan
@can('laporan.index')
<div class="menu_section">
<h3>Laporan</h3>
<ul class="nav side-menu">
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Toko</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('laporan.acchargaditolak.index')
<li>
<a href="{{ route('laporan.acchargaditolak.index') }}">
<span>ACC Harga Ditolak</span>
</a>
</li>
@endcan
@can('laporan.pengirimangudang.index')
<li>
<a href="{{ route('laporan.pengirimangudang.index') }}">
<span>Pengiriman Gudang</span>
</a>
</li>
@endcan
@can('laporan.perfomancetoko.index')
<li>
<a href="{{ route('laporan.perfomancetoko.index') }}">
<span>Perfomance Toko</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Salesman</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('rptperbandingansodonotabo.index')
<li>
<a href="{{ route('rptperbandingansodonotabo.index') }}">
<span>SODONota</span>
</a>
</li>
@endcan
@can('rptpenjualanabe.index')
<li>
<a href="{{ route('rptpenjualanabe.index') }}">
<span>Penjualan ABE</span>
</a>
</li>
@endcan
@can('rekapitulasipenjualan.index')
<li>
<a href="{{ route('rekapitulasipenjualan.index') }}">
<span>Rekapitulasi Penjualan</span>
</a>
</li>
@endcan
@can('rptpenjualanhi.index')
<li>
<a href="{{ route('rptpenjualanhi.index') }}">
<span>Penjualan Hubungan Istimewa</span>
</a>
</li>
@endcan
@can('rptsalesmannotajual.index')
<li>
<a href="{{ route('rptsalesmannotajual.index') }}">
<span>Nota Jual</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Barang</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('rptpenjualanperitem.index')
<li>
<a href="{{ route('rptpenjualanperitem.index') }}">
<span>Penjualan Per Item</span>
</a>
</li>
@endcan
@can('laporan.stock.index,kartustock')
<li>
<a href="{{ route('laporan.stock.index', array('dname' => 'kartustock')) }}">
<span>Kartu Stock</span>
</a>
</li>
@endcan
@can('laporan.stock.index,stockakhirperiode')
<li>
<a href="{{ route('laporan.stock.index', array('dname' => 'stockakhirperiode')) }}">
<span>Stock Akhir Periode</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Penjualan</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('laporan.penjualan.index')
<li>
<a href="{{ route('laporan.penjualan.index') }}">
<span>Monitoring Penjualan</span>
</a>
</li>
@endcan
@can('laporan.penjualan.git')
<li>
<a href="{{ route('laporan.penjualan.git') }}">
<span>Good In Transit</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Laporan Custom</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('laporan.custom.index')
<li>
<a href="{{ route('laporan.custom.index') }}">
<span>Daftar Laporan Custom</span>
</a>
</li>
@endcan
@can('laporan.customgroup.index')
<li>
<a href="{{ route('laporan.customgroup.index') }}">
<span>Daftar Group Laporan Custom</span>
</a>
</li>
@endcan
@can('laporan.custom.list')
<li>
<a href="{{ route('laporan.custom.list') }}">
<span>Daftar Query Laporan Custom</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Analisa</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('laporan.analisa.index,stockcompare')
<li>
<a href="{{ route('laporan.analisa.index', array('dname' => 'stockcompare')) }}">
<span>Pembandingan Item Toko</span>
</a>
</li>
@endcan
@can('laporan.analisa.index,progressskhu')
<li>
<a href="{{ route('laporan.analisa.index', array('dname' => 'progressskhu')) }}">
<span>Progress SKU Toko</span>
</a>
</li>
@endcan
@can('laporan.analisa.index,pembelianpertoko')
<li>
<a href="{{ route('laporan.analisa.index', array('dname' => 'pembelianpertoko')) }}">
<span>Pembelian Barang Per Toko</span>
</a>
</li>
@endcan
</ul>
</li>
</ul>
</div>
@endcan
@can('manajemen.index')
<div class="menu_section">
<h3>Manajemen</h3>
<ul class="nav side-menu">
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Master</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('cabang.index')
<li>
<a href="{{ route('cabang.index') }}">
<span>Cabang</span>
</a>
</li>
@endcan
@can('gudang.index')
<li>
<a href="{{ route('gudang.index') }}">
<span>Gudang</span>
</a>
</li>
@endcan
@can('tujuanexpedisi.index')
<li>
<a href="{{ route('tujuanexpedisi.index') }}">
<span>Tujuan Expedisi</span>
</a>
</li>
@endcan
@can('pemasok.index')
<li>
<a href="{{ route('pemasok.index') }}">
<span>Pemasok</span>
</a>
</li>
@endcan
@can('expedisi.index')
<li>
<a href="{{ route('expedisi.index') }}">
<span>Expedisi</span>
</a>
</li>
@endcan
@can('kolektor.index')
<li>
<a href="{{ route('kolektor.index') }}">
<span>Kolektor</span>
</a>
</li>
@endcan
@can('targetsales.index')
<li>
<a href="{{ route('targetsales.index') }}">
<span>Target Sales</span>
</a>
</li>
@endcan
@can('kelompokbarang.index')
<li>
<a href="{{ route('kelompokbarang.index') }}">
<span>Kelompok Barang</span>
</a>
</li>
@endcan
@can('toko.index')
<li>
<a href="{{ route('toko.index') }}">
<span>Toko</span>
</a>
</li>
@endcan
@can('tos.index')
<li>
<a href="{{ route('tos.index') }}">
<span>TOS Form</span>
</a>
</li>
@endcan
@can('masterstok.index')
<li>
<a href="{{ route('masterstok.index') }}">
<span>Stok</span>
</a>
</li>
@endcan
@can('hpp.index')
<li>
<a href="{{ route('hpp.index') }}">
<span>Harga Jual 11</span>
</a>
</li>
@endcan
<li>
<a href="javascript:void(0)">
<span>HPPA</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('hppatable.index')
<li>
<a href="{{ route('hppatable.index') }}">
<span>Table</span>
</a>
</li>
@endcan
@can('hppaproseshpp.index')
<li>
<a href="{{ route('hppaproseshpp.index') }}">
<span>Proses HPP Rata-rata</span>
</a>
</li>
@endcan
</ul>
</li>
@can('numerator.index')
<li>
<a href="{{ route('numerator.index') }}">
<span>Set Numerator</span>
</a>
</li>
@endcan
@can('hargajual.index')
<li>
<a href="{{ route('hargajual.index') }}">
<span>Harga Jual</span>
</a>
</li>
@endcan

<li>
<a href="javascript:void(0)">
<span>Aktiva</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('aktiva.index')
<li>
<a href="{{ route('aktiva.index') }}">
<span>Transaksi Aktiva</span>
</a>
</li>
@endcan
@can('laporanaktiva.index')
<li>
<a href="{{ route('laporanaktiva.index') }}">
<span>Laporan Aktiva</span>
</a>
</li>
@endcan
@can('aktiva.closingfa')
<li>
<a href="{{ route('aktiva.closingfa') }}">
<span>Closing Aktiva</span>
</a>
</li>
@endcan
</ul>
</li>

@can('tokodraft.index')
<li>
<a href="{{ route('tokodraft.index') }}">
<span>Tokodraft</span>
</a>
</li>
@endcan

@can('tokojw.index')
<li>
<a href="{{ route('tokojw.index') }}">
<span>Toko JW</span>
</a>
</li>
@endcan

</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Setting</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('setting.changepassword')
<li>
<a href="{{ route('setting.changepassword') }}">
<span>Change Password</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Security</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('permission.index')
<li>
<a href="{{ route('permission.index') }}">
<span>Manajemen Permission</span>
</a>
</li>
@endcan
@can('role.index')
<li>
<a href="{{ route('role.index') }}">
<span>Manajemen Roles</span>
</a>
</li>
@endcan
@can('access.index')
<li>
<a href="{{ route('access.index') }}">
<span>Manajemen Akses</span>
</a>
</li>
@endcan
@can('user.index')
<li>
<a href="{{ route('user.index') }}">
<span>Manajemen User</span>
</a>
</li>
@endcan
</ul>
</li>
<li>
<a href="javascript:void(0)">
<i class="fa fa-folder-open"></i>
<span>Approval</span>
<span class="fa fa-chevron-down"></span></a>
<ul class="nav child_menu">
@can('approvalgrp.index')
<li>
<a href="{{ route('approvalgrp.index') }}">
<span>Approval Group</span>
</a>
</li>
@endcan
@can('approvalmgmt.index')
<li>
<a href="{{ route('approvalmgmt.index') }}">
<span>Approval Management</span>
</a>
</li>
@endcan
</ul>
</li>
</ul>
</div>
@endcan
