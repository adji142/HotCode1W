<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('secure.permissions')->insert([

            // ======================================================================================================================================================
            // TIPE 0 : WISER
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3'        , 'name'=>'Manajemen'                                          , 'slug'=>'manajemen.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1'      , 'name'=>'Master'                                             , 'slug'=>'master.index'                                , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.1'    , 'name'=>'Cabang'                                             , 'slug'=>'cabang.index'                                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.2'    , 'name'=>'Gudang'                                             , 'slug'=>'gudang.index'                                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.3'    , 'name'=>'Tujuan Expedisi'                                    , 'slug'=>'tujuanexpedisi.index'                        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.4'    , 'name'=>'Pemasok'                                            , 'slug'=>'pemasok.index'                               , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.5'    , 'name'=>'Expedisi'                                           , 'slug'=>'expedisi.index'                              , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.6'    , 'name'=>'Kolektor'                                           , 'slug'=>'kolektor.index'                              , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.7'    , 'name'=>'Target Sales'                                       , 'slug'=>'targetsales.index'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.8'    , 'name'=>'Kelompok Barang'                                    , 'slug'=>'kelompokbarang.index'                        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.9'    , 'name'=>'Toko'                                               , 'slug'=>'toko.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.10'   , 'name'=>'TOS Form'                                           , 'slug'=>'tos.index'                                   , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.1' , 'name'=>'TOS Form Firebase Auth'                             , 'slug'=>'tos.fbauth'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.2' , 'name'=>'TOS Form Firebase Data'                             , 'slug'=>'tos.config,fbdata'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.3' , 'name'=>'TOS Form Insert'                                    , 'slug'=>'tos.config,new'                              , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.4' , 'name'=>'TOS Form Update'                                    , 'slug'=>'tos.config,update'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.5' , 'name'=>'TOS Form Verify'                                    , 'slug'=>'tos.config,verify'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.6' , 'name'=>'TOS Form Indexing'                                  , 'slug'=>'tos.config,indexing'                         , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.7' , 'name'=>'TOS Form Synch to Wiser'                            , 'slug'=>'tos.config,synch'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.8' , 'name'=>'TOS Form Generate Excel'                            , 'slug'=>'tos.config,excel'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.9' , 'name'=>'TOS Form Pull Data'                                 , 'slug'=>'tos.config,pull'                             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.10', 'name'=>'TOS Form Browser'                                   , 'slug'=>'tos.config,browse'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.11', 'name'=>'TOS Form Android Access'                            , 'slug'=>'tos.config,android'                          , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.10.12', 'name'=>'TOS Form Delete Obsolete Data'                      , 'slug'=>'tos.config,fbodelete'                        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.11'   , 'name'=>'Stok'                                               , 'slug'=>'masterstok.index'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.12'   , 'name'=>'Harga Jual 11'                                      , 'slug'=>'hpp.index'                                   , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.13'   , 'name'=>'HPPA'                                               , 'slug'=>'hppa.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.13.1' , 'name'=>'Table'                                              , 'slug'=>'hppatable.index'                             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.13.2' , 'name'=>'Proses HPP Rata-rata'                               , 'slug'=>'hppaproseshpp.index'                         , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.14'   , 'name'=>'Set Numerator'                                      , 'slug'=>'numerator.index'                             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.15'   , 'name'=>'Harga Jual'                                         , 'slug'=>'hargajual.index'                             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.16'   , 'name'=>'Toko Aktif Pasif'                                   , 'slug'=>'tokoaktifpasif.data'                         , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.16.1' , 'name'=>'Toko Aktif Pasif Tambah'                            , 'slug'=>'tokoaktifpasif.tambah'                       , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.16.2' , 'name'=>'Toko Aktif Pasif Hapus'                             , 'slug'=>'tokoaktifpasif.hapus'                        , 'icon'=>'fa-trash'       , ] ,

            // ======================================================================================================================================================
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.18'    , 'name'=>'Tokodraft'                                         , 'slug'=>'tokodraft.index'                             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.1'  , 'name'=>'Tambah Tokodraft'                                  , 'slug'=>'tokodraft.tambah'                            , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.2'  , 'name'=>'Ubah Tokodraft'                                    , 'slug'=>'tokodraft.ubah'                              , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.3'  , 'name'=>'Hapus Tokodraft'                                   , 'slug'=>'tokodraft.hapus'                             , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.4'  , 'name'=>'Approve Tokodraft'                                 , 'slug'=>'tokodraft.approve'                           , 'icon'=>'fa-file-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.5'  , 'name'=>'Tokodraft Rejected'                                , 'slug'=>'tokodraft.rejected'                          , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.6'  , 'name'=>'Tokodraft Batal Rejected'                                , 'slug'=>'tokodraft.batalrejected'                          , 'icon'=>'fa fa-undo'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.7'  , 'name'=>'Tokodraft History'                                , 'slug'=>'tokodraft.tdhistory'                          , 'icon'=>'fa fa-history'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.18.8'  , 'name'=>'Tokodraft Detail'                                , 'slug'=>'tokodraft.tddetail'                          , 'icon'=>'fa fa-eye'     , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.19'    , 'name'=>'TokoJW'                                         , 'slug'=>'tokojw.index'                             , 'icon'=>'fa-file-o'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.2'      , 'name'=>'Setting'                                            , 'slug'=>'setting.index'                               , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.2.1'    , 'name'=>'Change Password'                                    , 'slug'=>'setting.changepassword'                      , 'icon'=>'fa-wrench'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.3'      , 'name'=>'Security'                                           , 'slug'=>'secure.index'                                , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.3.1'    , 'name'=>'Manajemen Permission'                               , 'slug'=>'permission.index'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.1.1'  , 'name'=>'Perbarui Menu Sidebar'                              , 'slug'=>'permission.sidebar'                          , 'icon'=>'fa-refresh'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.3.2'    , 'name'=>'Manajemen Roles'                                    , 'slug'=>'role.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.2.1'  , 'name'=>'Tambah Role'                                        , 'slug'=>'role.tambah'                                 , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.2.2'  , 'name'=>'Ubah Role'                                          , 'slug'=>'role.ubah'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.2.3'  , 'name'=>'Daftar User Role'                                   , 'slug'=>'role.user'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.2.4'  , 'name'=>'Hapus Role'                                         , 'slug'=>'role.hapus'                                  , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.3.3'    , 'name'=>'Manajemen Akses'                                    , 'slug'=>'access.index'                                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.3.1'  , 'name'=>'View Akses'                                         , 'slug'=>'access.view'                                 , 'icon'=>'fa-eye'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.3.2'  , 'name'=>'Ubah Akses'                                         , 'slug'=>'access.ubah'                                 , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.3.3'  , 'name'=>'Tambah Akses User'                                  , 'slug'=>'access.tambah.user'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.3.4'  , 'name'=>'Hapus Akses'                                        , 'slug'=>'access.delete.user'                          , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.3.4'    , 'name'=>'Manajemen User'                                     , 'slug'=>'user.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.4.1'  , 'name'=>'Tambah User'                                        , 'slug'=>'user.tambah'                                 , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.4.2'  , 'name'=>'Ubah User'                                          , 'slug'=>'user.ubah'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.3.4.3'  , 'name'=>'Hapus User'                                         , 'slug'=>'user.hapus'                                  , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.4'      , 'name'=>'Approval'                                           , 'slug'=>'approval.index'                              , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.4.1'    , 'name'=>'Approval Group'                                     , 'slug'=>'approvalgrp.index'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.4.1.1'  , 'name'=>'Roles Approval Group'                               , 'slug'=>'approvalgrp.roles'                           , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.4.2'    , 'name'=>'Approval Management'                                , 'slug'=>'approvalmgmt.index'                          , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.4.2.1'  , 'name'=>'Simpan Status Approval'                             , 'slug'=>'approvalmgmt.simpan'                         , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1'        , 'name'=>'Transaksi'                                          , 'slug'=>'transaksi.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.1'      , 'name'=>'Pembelian'                                          , 'slug'=>'pembelian.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.1.1'    , 'name'=>'Order Pembelian'                                    , 'slug'=>'orderpembelian.index'                        , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.2'  , 'name'=>'Tambah Order Pembelian'                             , 'slug'=>'orderpembelian.tambah'                       , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.3'  , 'name'=>'Ubah Order Pembelian'                               , 'slug'=>'orderpembelian.ubah'                         , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.4'  , 'name'=>'Hapus Order Pembelian'                              , 'slug'=>'orderpembelian.hapus'                        , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.5'  , 'name'=>'Detail Order Pembelian Detail'                      , 'slug'=>'orderpembelian.detail.detail'                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.6'  , 'name'=>'Tambah Order Pembelian Detail'                      , 'slug'=>'orderpembelian.detail.tambah'                , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.7'  , 'name'=>'Ubah Order Pembelian Detail'                        , 'slug'=>'orderpembelian.detail.ubah'                  , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.8'  , 'name'=>'Sync 11'                                            , 'slug'=>'orderpembelian.sync'                         , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.9'  , 'name'=>'Generate Stok Tipis'                                , 'slug'=>'orderpembelian.stoktipis.tambah'             , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.10' , 'name'=>'Generate BO'                                        , 'slug'=>'orderpembelian.bo.tambah'                    , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.1.11' , 'name'=>'Approval'                                           , 'slug'=>'orderpembelian.approval'                     , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================


            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.1.2'    , 'name'=>'Nota Pembelian'                                     , 'slug'=>'notapembelian.index'                         , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.2.1'  , 'name'=>'Ubah Nota Pembelian'                                , 'slug'=>'notapembelian.ubah'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.2.2'  , 'name'=>'Ubah Nota Pembelian Detail'                         , 'slug'=>'notapembelian.detail.ubah'                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.2.3'  , 'name'=>'Koreksi Nota Pembelian Detail'                      , 'slug'=>'notapembelian.detail.koreksi'                , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.1.3'    , 'name'=>'Retur Pembelian'                                    , 'slug'=>'returpembelian.index'                        , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.1'  , 'name'=>'Tambah Retur Pembelian'                             , 'slug'=>'returpembelian.tambah'                       , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.2'  , 'name'=>'Hapus Retur Pembelian'                              , 'slug'=>'returpembelian.hapus'                        , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.3'  , 'name'=>'Tambah Retur Pembelian Detail'                      , 'slug'=>'returpembelian.detail.tambah'                , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.4'  , 'name'=>'Koreksi Retur Pembelian Detail'                     , 'slug'=>'returpembelian.detail.koreksi'               , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.5'  , 'name'=>'Update Tgl Kirim Retur Pembelian'                   , 'slug'=>'returpembelian.updatetglkirim'               , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.6'  , 'name'=>'Cetak MPR'                                          , 'slug'=>'returpembelian.cetakmpr'                     , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.1.3.7'  , 'name'=>'Sync 11'                                            , 'slug'=>'returpembelian.sync11'                       , 'icon'=>'fa-refresh'     , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2'      , 'name'=>'Penjualan'                                          , 'slug'=>'penjualan.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.1'    , 'name'=>'Order Penjualan'                                    , 'slug'=>'orderpenjualan.index'                        , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.1'  , 'name'=>'Tambah Order Penjualan'                             , 'slug'=>'orderpenjualan.tambah'                       , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.2'  , 'name'=>'Ubah Order Penjualan'                               , 'slug'=>'orderpenjualan.ubah'                         , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.3'  , 'name'=>'Hapus Order Penjualan'                              , 'slug'=>'orderpenjualan.hapus'                        , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.4'  , 'name'=>'Tambah Order Penjualan Detail'                      , 'slug'=>'orderpenjualan.detail.tambah'                , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.5'  , 'name'=>'Copy DO ke OH'                                      , 'slug'=>'orderpenjualan.insertdooh'                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.6'  , 'name'=>'Copy DO ke DO '                                     , 'slug'=>'orderpenjualan.insertdodo'                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.7'  , 'name'=>'Cetak Picking List'                                 , 'slug'=>'orderpenjualan.cetakpickinglist'             , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.8'  , 'name'=>'Batal PIL'                                          , 'slug'=>'orderpenjualan.batalpil'                     , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.9'  , 'name'=>'Detail Batal PIL'                                   , 'slug'=>'orderpenjualan.batalpildetail'               , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.10' , 'name'=>'Ajuan 11'                                           , 'slug'=>'orderpenjualan.ajuanupdate'                  , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.1.11' , 'name'=>'Update No. SO'                                      , 'slug'=>'orderpenjualan.updatenoso'                   , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.2'    , 'name'=>'Acc Piutang'                                        , 'slug'=>'accpiutang.index'                            , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.2.1'  , 'name'=>'Isi Nomor Acc Piutang'                              , 'slug'=>'accpiutang.acc.isi'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.2.3'  , 'name'=>'Isi Tanggal Terima Acc Piutang'                     , 'slug'=>'accpiutang.tglterimapil.terima'              , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.2.2'  , 'name'=>'Hapus Tanggal Terima Acc Piutang'                   , 'slug'=>'accpiutang.tglterimapil.delete'              , 'icon'=>'fa-trash-o'     , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.3'    , 'name'=>'Nota Penjualan'                                     , 'slug'=>'notapenjualan.index'                         , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.1'  , 'name'=>'Tambah Nota Penjualan'                              , 'slug'=>'notapenjualan.add'                           , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.2'  , 'name'=>'Hapus Nota Penjualan'                               , 'slug'=>'notapenjualan.delete'                        , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.3'  , 'name'=>'Hapus Nota Penjualan Detail'                        , 'slug'=>'notapenjualan.deletedetail'                  , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.4'  , 'name'=>'Hapus Tanggal Terima'                               , 'slug'=>'notapenjualan.hapustglterima'                , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.5'  , 'name'=>'Submit Checker dan Koli'                            , 'slug'=>'notapenjualan.submitchecker'                 , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.6'  , 'name'=>'Submit Tanggal Terima'                              , 'slug'=>'notapenjualan.submittglterima'               , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.7'  , 'name'=>'Cetak Nota Penjualan'                               , 'slug'=>'notapenjualan.cetaknota'                     , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.8'  , 'name'=>'Cetak Proforma Penjualan'                           , 'slug'=>'notapenjualan.cetakproforma'                 , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.9'  , 'name'=>'Koreksi Nota Penjualan'                             , 'slug'=>'notapenjualan.detail.koreksi'                , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.3.10' , 'name'=>'Edit Qty Nota Penjualan'                            , 'slug'=>'notapenjualan.detail.editqty'                , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.4'    , 'name'=>'Sales Order'                                        , 'slug'=>'salesorder.index'                            , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.1'  , 'name'=>'Tambah Sales Order'                                 , 'slug'=>'salesorder.tambah'                           , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.2'  , 'name'=>'Ubah Sales Order'                                   , 'slug'=>'salesorder.ubah'                             , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.3'  , 'name'=>'Hapus Sales Order'                                  , 'slug'=>'salesorder.hapus'                            , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.4'  , 'name'=>'Tambah Sales Order Detail'                          , 'slug'=>'salesorder.detail.tambah'                    , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.5'  , 'name'=>'Copy DO ke OH'                                      , 'slug'=>'salesorder.insertdooh'                       , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.6'  , 'name'=>'Copy DO ke DO '                                     , 'slug'=>'salesorder.insertdodo'                       , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.7'  , 'name'=>'Cetak Picking List'                                 , 'slug'=>'salesorder.cetakpickinglist'                 , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.8'  , 'name'=>'Batal PIL'                                          , 'slug'=>'salesorder.batalpil'                         , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.9'  , 'name'=>'Detail Batal PIL'                                   , 'slug'=>'salesorder.batalpildetail'                   , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.10' , 'name'=>'Ajuan 11'                                           , 'slug'=>'salesorder.ajuanupdate'                      , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.11' , 'name'=>'Update No. SO'                                      , 'slug'=>'salesorder.updatenoso'                       , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.4.12' , 'name'=>'Closing SO'                                         , 'slug'=>'salesorder.closingso'                        , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.5'    , 'name'=>'Retur Penjualan'                                    , 'slug'=>'returpenjualan.index'                        , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.1'  , 'name'=>'Tambah Retur Penjualan'                             , 'slug'=>'returpenjualan.tambah'                       , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.2'  , 'name'=>'Tambah Detail Retur Penjualan'                      , 'slug'=>'returpenjualan.tambah.detail'                , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.3'  , 'name'=>'Hapus Retur Penjualan'                              , 'slug'=>'returpenjualan.hapusretur'                   , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.4'  , 'name'=>'Hapus Detail Retur Penjualan'                       , 'slug'=>'returpenjualan.hapusdetail'                  , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.5'  , 'name'=>'Historis Retur Penjualan'                           , 'slug'=>'returpenjualan.historis'                     , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.6'  , 'name'=>'Cetak NRJ'                                          , 'slug'=>'returpenjualan.cetaknrj'                     , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.7'  , 'name'=>'Cetak SPPB'                                         , 'slug'=>'returpenjualan.cetaksppb'                    , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.8'  , 'name'=>'Update NRJ'                                         , 'slug'=>'returpenjualan.updatenrj'                    , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.9'  , 'name'=>'Update SPPB'                                        , 'slug'=>'returpenjualan.updatesppb'                   , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.5.10' , 'name'=>'Koreksi'                                            , 'slug'=>'returpenjualan.koreksi'                      , 'icon'=>'fa-file-o'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.6'    , 'name'=>'Surat Jalan'                                        , 'slug'=>'suratjalan.index'                            , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.1'  , 'name'=>'Tambah Surat Jalan'                                 , 'slug'=>'suratjalan.create'                           , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.2'  , 'name'=>'Tambah Surat Jalan Detail Titipan'                  , 'slug'=>'suratjalantitipan.create'                    , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.3'  , 'name'=>'Hapus Surat Jalan'                                  , 'slug'=>'suratjalan.hapus'                            , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.4'  , 'name'=>'Hapus Surat Jalan Detail'                           , 'slug'=>'suratjalandetail.hapus'                      , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.5'  , 'name'=>'Hapus Surat Jalan Detail Titipan'                   , 'slug'=>'suratjalantitipan.hapus'                     , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.6'  , 'name'=>'Cek Koli Surat Jalan Detail Titipan'                , 'slug'=>'suratjalan.cekkoli'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.6.7'  , 'name'=>'Cetak Surat Jalan'                                  , 'slug'=>'suratjalan.cetak'                            , 'icon'=>'fa-print'       , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.7'    , 'name'=>'Pengiriman'                                         , 'slug'=>'pengiriman.index'                            , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.7.1'  , 'name'=>'Tambah Pengiriman'                                  , 'slug'=>'pengiriman.create'                           , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.7.2'  , 'name'=>'Ubah Pengiriman'                                    , 'slug'=>'pengiriman.update'                           , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.7.3'  , 'name'=>'Tambah Pengiriman Detail'                           , 'slug'=>'pengirimandetail.create'                     , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.7.4'  , 'name'=>'Hapus Pengiriman'                                   , 'slug'=>'pengiriman.delete'                           , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.7.5'  , 'name'=>'Hapus Pengiriman Detail'                            , 'slug'=>'pengirimandetail.delete'                     , 'icon'=>'fa-trash-o'     , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.8'    , 'name'=>'Fixed Route'                                        , 'slug'=>'fixed.index'                                 , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.1'  , 'name'=>'Fixed Route Tambah Record Kunjungan'                , 'slug'=>'fixedroute.tambahrecordkunjungan'            , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.2'  , 'name'=>'Fixed Route Tambah Record Kunjungan Luar Rencana'   , 'slug'=>'fixedroute.tambahrecordkunjunganluarrencana' , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.3'  , 'name'=>'Fixed Route Ubah Tanggal Kunjungan'                 , 'slug'=>'fixedroute.ubah'                             , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.4'  , 'name'=>'Fixed Route Hapus Detail Kunjungan'                 , 'slug'=>'fixedroutedetailkunjungan.hapus'             , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.5'  , 'name'=>'Fixed Route Tambah Data Kunjungan Berdasarkan Toko' , 'slug'=>'fixedroute.tambahtglkunjungan'               , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.8.6'  , 'name'=>'Fixed Route Ubah Data Kunjungan Berdasarkan Toko'   , 'slug'=>'fixedroute.updatepertoko'                    , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.9'    , 'name'=>'Form Tagihan'                                       , 'slug'=>'ftagih.index'                                , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.2.9.1'  , 'name'=>'Form Tagihan Tambah Record Kunjungan'               , 'slug'=>'ftagih.print'                                , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3'      , 'name'=>'Stock'                                              , 'slug'=>'stock.index'                                 , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.1'    , 'name'=>'Opname'                                             , 'slug'=>'opname.index'                                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.1'  , 'name'=>'Ubah Opname'                                        , 'slug'=>'opname.update'                               , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.2'  , 'name'=>'Hapus Stok Opname'                                  , 'slug'=>'opname.delete'                               , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.3'  , 'name'=>'Rencana Opname'                                     , 'slug'=>'opname.rencana'                              , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.4'  , 'name'=>'Rencana Opname Per Barang'                          , 'slug'=>'opname.rencanaperbarang'                     , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.5'  , 'name'=>'Opname Cetak DFS'                                   , 'slug'=>'opname.cetakdfs'                             , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.6'  , 'name'=>'Opname Cetak CFS'                                   , 'slug'=>'opname.cetakcfs'                             , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.7'  , 'name'=>'Opname Closing'                                     , 'slug'=>'opname.closing'                              , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.8'  , 'name'=>'Opname Closing All Barang'                          , 'slug'=>'opname.closeallbarang'                       , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.1.9'  , 'name'=>'Laporan Analisa STOP'                               , 'slug'=>'opname.analisaclosing'                       , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.2'    , 'name'=>'Mutasi'                                             , 'slug'=>'mutasi.index'                                , 'icon'=>'fa-file-o'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.3'    , 'name'=>'Scrab'                                              , 'slug'=>'scrab.index'                                 , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.1'  , 'name'=>'Hapus Scrab'                                        , 'slug'=>'scrab.hapus'                                 , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.2'  , 'name'=>'Tambah Scrab'                                       , 'slug'=>'scrab.tambah'                                , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.3'  , 'name'=>'Tambah Scrab Detail'                                , 'slug'=>'scrabdetail.tambah'                          , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.4'  , 'name'=>'Ajuan Adm Scrab'                                    , 'slug'=>'scrab.ajuanadm'                              , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.5'  , 'name'=>'Ajuan Stok Scrab'                                   , 'slug'=>'scrab.ajuanstok'                             , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.3.6'  , 'name'=>'Ajuan Mutasi Scrab'                                 , 'slug'=>'scrab.ajuanmutasi'                           , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.4'    , 'name'=>'Antar Gudang'                                       , 'slug'=>'antargudang.index'                           , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.1'  , 'name'=>'Tambah Antar Gudang'                                , 'slug'=>'antargudang.create'                          , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.2'  , 'name'=>'Ubah Antar Gudang'                                  , 'slug'=>'antargudang.update'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.3'  , 'name'=>'Hapus Antar Gudang'                                 , 'slug'=>'antargudang.delete'                          , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.4'  , 'name'=>'Tambah Antar Gudang Detail'                         , 'slug'=>'antargudang.create.detail'                   , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.5'  , 'name'=>'Ubah Antar Gudang Detail'                           , 'slug'=>'antargudang.update.detail'                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.6'  , 'name'=>'Hapus Antar Gudang Detail'                          , 'slug'=>'antargudang.delete.detail'                   , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.7'  , 'name'=>'Sync Antar Gudang'                                  , 'slug'=>'antargudang.sync'                            , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.8'  , 'name'=>'Cetak PIL Antar Gudang'                             , 'slug'=>'antargudang.cetakpil'                        , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.4.9'  , 'name'=>'Cetak Nota Antar Gudang'                            , 'slug'=>'antargudang.cetaknota'                       , 'icon'=>'fa-print'       , ] ,
            
            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.5'    , 'name'=>'Antar Gudang V2'                                       , 'slug'=>'antargudangv2.index'                      , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.1'  , 'name'=>'Antar Gudang V2 Tambah'                                , 'slug'=>'antargudangv2.tambah'                     , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.2'  , 'name'=>'Antar Gudang V2 Detail Tambah'                         , 'slug'=>'antargudangv2.detail.tambah'              , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.3'  , 'name'=>'Antar Gudang V2 Hapus'                                 , 'slug'=>'antargudangv2.hapus'                      , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.4'  , 'name'=>'Antar Gudang V2 Detail Hapus'                          , 'slug'=>'antargudangv2.detail.hapus'               , 'icon'=>'fa-trash'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.5'  , 'name'=>'Antar Gudang V2 Cetak Pil'                             , 'slug'=>'antargudangv2.cetakpil'                   , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.6'  , 'name'=>'Antar Gudang V2 Cetak Nota'                            , 'slug'=>'antargudangv2.cetaknota'                  , 'icon'=>'fa-print'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.7'  , 'name'=>'Antar Gudang V2 Pengiriman'                            , 'slug'=>'antargudangv2.pengiriman'                 , 'icon'=>'fa-truck'       , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.8'  , 'name'=>'Antar Gudang V2 Update'                                , 'slug'=>'antargudangv2.update'                     , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.5.9'  , 'name'=>'Antar Gudang V2 Update Nota'                           , 'slug'=>'antargudangv2.updatenota'                 , 'icon'=>'fa-pencil'      , ] ,

            // ======================================================================================================================================================
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.3.6'    , 'name'=>'Standar Stock'                                         , 'slug'=>'standarstock.index'                        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'1.3.6.1'  , 'name'=>'Standar Stock Laporan'                                 , 'slug'=>'standarstock.laporan'                      , 'icon'=>'fa-file-o'      , ] ,

            // ======================================================================================================================================================
            
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.17'    , 'name'=>'Aktiva'                                              , 'slug'=>'acc.index'                                 , 'icon'=>'fa-file-o'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.17.1'  , 'name'=>'Aktiva Transaksi'                                    , 'slug'=>'aktiva.index'                              , 'icon'=>'fa-file-o'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.17.2'  , 'name'=>'Aktiva Laporan'                                      , 'slug'=>'laporanaktiva.indexx'                      , 'icon'=>'fa-file-o'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'3.1.17.3'  , 'name'=>'Aktiva Closing'                                      , 'slug'=>'aktiva.closingfa'                          , 'icon'=>'fa-file-o'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.17.1.1', 'name'=>'Aktiva Tambah'                                       , 'slug'=>'aktiva.tambah'                             , 'icon'=>'fa-plus'           , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.17.1.1', 'name'=>'Aktiva Update'                                       , 'slug'=>'aktiva.update'                             , 'icon'=>'fa-pencil'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.17.1.3', 'name'=>'Aktiva Hapus'                                        , 'slug'=>'aktiva.trash'                              , 'icon'=>'fa-remove'         , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.17.1.4', 'name'=>'Aktiva Jual'                                         , 'slug'=>'aktiva.jual'                               , 'icon'=>'fa-shopping-basket', ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'3.1.17.1.5', 'name'=>'Aktiva Penyusutan'                                   , 'slug'=>'aktiva.penyusutan'                         , 'icon'=>'fa-plus'           , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.10'    , 'name'=>'Plan Jual'                                            , 'slug'=>'planjual.index'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.10.1'  , 'name'=>'Plan Jual Tambah'                                     , 'slug'=>'planjual.tambah'                           , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.10.2'  , 'name'=>'Plan Jual Update'                                     , 'slug'=>'planjual.update'                           , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'1.2.10.3'  , 'name'=>'Plan Jual Upload'                                     , 'slug'=>'planjual.upload'                           , 'icon'=>'fa-file'        , ] ,

            // ======================================================================================================================================================

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2'        , 'name'=>'Laporan'                                            , 'slug'=>'laporan.index'                               , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.1'      , 'name'=>'Toko'                                               , 'slug'=>'laporan.toko.index'                          , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.1.1'    , 'name'=>'ACC Harga Ditolak'                                  , 'slug'=>'laporan.acchargaditolak.index'               , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.1.2'    , 'name'=>'Pengiriman Gudang'                                  , 'slug'=>'laporan.pengirimangudang.index'              , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.1.3'    , 'name'=>'Perfomance Toko'                                    , 'slug'=>'laporan.perfomancetoko.index'                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2'      , 'name'=>'Salesman'                                           , 'slug'=>'laporan.salesman'                            , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2.1'    , 'name'=>'SODONota'                                           , 'slug'=>'rptperbandingansodonotabo.index'             , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.1.1'  , 'name'=>'SODONota Sales'                                     , 'slug'=>'rptperbandingansodonotabo.report.sales'      , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.1.2'  , 'name'=>'SODONota Toko'                                      , 'slug'=>'rptperbandingansodonotabo.report.toko'       , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.2'    , 'name'=>'SODONotaDetail'                                     , 'slug'=>'rptperbandingansodonotabodetail.index'       , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2.3'    , 'name'=>'Penjualan ABE'                                      , 'slug'=>'rptpenjualanabe.index'                       , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.3.1'  , 'name'=>'Penjualan ABE'                                      , 'slug'=>'rptpenjualanabe.report'                      , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.3.2'  , 'name'=>'Penjualan ABE Netto'                                , 'slug'=>'rptpenjualanabe.report.netto'                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2.4'    , 'name'=>'Rekapitulasi Penjualan'                             , 'slug'=>'rekapitulasipenjualan.index'                 , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.4.1'  , 'name'=>'Rekapitulasi Penjualan'                             , 'slug'=>'rekapitulasipenjualan.report'                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2.5'    , 'name'=>'Penjualan Hubungan Istimewa'                        , 'slug'=>'rptpenjualanhi.index'                        , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.5.1'  , 'name'=>'Penjualan Hubungan Istimewa'                        , 'slug'=>'rptpenjualanhi.report'                       , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.2.6'    , 'name'=>'Nota Jual'                                          , 'slug'=>'rptsalesmannotajual.index'                   , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.2.6.1'  , 'name'=>'Nota Jual'                                          , 'slug'=>'rptsalesmannotajual.report'                  , 'icon'=>'fa-file-o'      , ] ,

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.3'      , 'name'=>'Barang'                                             , 'slug'=>'laporan.barang'                              , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.3.1'    , 'name'=>'Penjualan Per Item'                                 , 'slug'=>'rptpenjualanperitem.index'                   , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.3.1.1'  , 'name'=>'Penjualan Per Item'                                 , 'slug'=>'rptpenjualanperitem.report'                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.3.2'    , 'name'=>'Kartu Stock'                                        , 'slug'=>'laporan.stock.index,kartustock'              , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.3.2.1'  , 'name'=>'Preview Kartu Stock'                                , 'slug'=>'laporan.stock.preview,kartustock'            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.3.2.2'  , 'name'=>'Proses Kartu Stock'                                 , 'slug'=>'laporan.stock.process,kartustock'            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.3.3'    , 'name'=>'Stock Akhir Periode'                                , 'slug'=>'laporan.stock.index,stockakhirperiode'       , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.3.3.1'  , 'name'=>'Preview Stock Akhir Periode'                        , 'slug'=>'laporan.stock.preview,stockakhirperiode'     , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.3.3.2'  , 'name'=>'Proses Stock Akhir Periode'                         , 'slug'=>'laporan.stock.process,stockakhirperiode'     , 'icon'=>'fa-file-o'      , ] ,

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.4'      , 'name'=>'Penjualan'                                          , 'slug'=>'laporan.penjualan'                           , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.4.1'    , 'name'=>'Monitoring Penjualan'                               , 'slug'=>'laporan.penjualan.index'                     , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.4.2'    , 'name'=>'Good In Transit'                                    , 'slug'=>'laporan.penjualan.git'                       , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.5'      , 'name'=>'Laporan Custom'                                     , 'slug'=>'laporan.custom'                              , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.5.1'    , 'name'=>'Daftar Laporan Custom'                              , 'slug'=>'laporan.custom.index'                        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.5.2'    , 'name'=>'Daftar Group Laporan Custom'                        , 'slug'=>'laporan.customgroup.index'                   , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.2.1'  , 'name'=>'Tambah Group Laporan Custom'                        , 'slug'=>'laporan.customgroup.tambah'                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.2.2'  , 'name'=>'Ubah Group Laporan Custom'                          , 'slug'=>'laporan.customgroup.ubah'                    , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.2.3'  , 'name'=>'Hapus Group Laporan Custom'                         , 'slug'=>'laporan.customgroup.hapus'                   , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.5.3'    , 'name'=>'Daftar Query Laporan Custom'                        , 'slug'=>'laporan.custom.list'                         , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.3.1'  , 'name'=>'Tambah Query Laporan Custom'                        , 'slug'=>'laporan.custom.tambah'                       , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.3.2'  , 'name'=>'Ubah Query Laporan Custom'                          , 'slug'=>'laporan.custom.ubah'                         , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.5.3.3'  , 'name'=>'Hapus Query Laporan Custom'                         , 'slug'=>'laporan.custom.hapus'                        , 'icon'=>'fa-file-o'      , ] ,

            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.6'      , 'name'=>'Analisa'                                            , 'slug'=>'laporan.analisa'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.6.1'    , 'name'=>'Pembandingan Item Toko'                             , 'slug'=>'laporan.analisa.index,stockcompare'          , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.1.1'  , 'name'=>'Preview Pembandingan Item Toko'                     , 'slug'=>'laporan.analisa.preview,stockcompare'        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.1.2'  , 'name'=>'Proses Pembandingan Item Toko'                      , 'slug'=>'laporan.analisa.process,stockcompare'        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.6.2'    , 'name'=>'Progress SKU Toko'                                  , 'slug'=>'laporan.analisa.index,progressskhu'          , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.2.1'  , 'name'=>'Preview Progress SKU Toko'                          , 'slug'=>'laporan.analisa.preview,progressskhu'        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.2.2'  , 'name'=>'Proses Progress SKU Toko'                           , 'slug'=>'laporan.analisa.process,progressskhu'        , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>1    , 'nested'=>'2.6.3'    , 'name'=>'Pembelian Barang Per Toko'                          , 'slug'=>'laporan.analisa.index,pembelianpertoko'      , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.3.1'  , 'name'=>'Preview Pembelian Barang Per Toko'                  , 'slug'=>'laporan.analisa.preview,pembelianpertoko'    , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'2.6.3.2'  , 'name'=>'Proses Pembelian Barang Per Toko'                   , 'slug'=>'laporan.analisa.process,pembelianpertoko'    , 'icon'=>'fa-file-o'      , ] ,

            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4'        , 'name'=>'Synch'                                              , 'slug'=>'synch'                                       , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1'      , 'name'=>'Synch To ISADB'                                     , 'slug'=>'synch.isadb'                                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.1'    , 'name'=>'Synch orderpenjualan'                               , 'slug'=>'synch.orderpenjualan.header'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.2'    , 'name'=>'Synch orderpenjualan'                               , 'slug'=>'synch.orderpenjualan.detail'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.3'    , 'name'=>'Synch notapenjualan'                                , 'slug'=>'synch.notapenjualan.header'                  , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.4'    , 'name'=>'Synch notapenjualan'                                , 'slug'=>'synch.notapenjualan.detail'                  , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.5'    , 'name'=>'Synch notapenjualan'                                , 'slug'=>'synch.notapenjualan.koreksi'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.6'    , 'name'=>'Synch returpenjualan'                               , 'slug'=>'synch.returpenjualan.header'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.7'    , 'name'=>'Synch returpenjualan'                               , 'slug'=>'synch.returpenjualan.detail'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.8'    , 'name'=>'Synch returpenjualan'                               , 'slug'=>'synch.returpenjualan.koreksi'                , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.9'    , 'name'=>'Synch pengiriman'                                   , 'slug'=>'synch.pengiriman.header'                     , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.10'   , 'name'=>'Synch pengiriman'                                   , 'slug'=>'synch.pengiriman.detail'                     , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.11'   , 'name'=>'Synch orderpembelian'                               , 'slug'=>'synch.orderpembelian.header'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.12'   , 'name'=>'Synch orderpembelian'                               , 'slug'=>'synch.orderpembelian.detail'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.13'   , 'name'=>'Synch notapembelian'                                , 'slug'=>'synch.notapembelian.header'                  , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.14'   , 'name'=>'Synch notapembelian'                                , 'slug'=>'synch.notapembelian.detail'                  , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.15'   , 'name'=>'Synch notapembelian'                                , 'slug'=>'synch.notapembelian.koreksi'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.16'   , 'name'=>'Synch returpembelian'                               , 'slug'=>'synch.returpembelian.header'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.17'   , 'name'=>'Synch returpembelian'                               , 'slug'=>'synch.returpembelian.detail'                 , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.1.18'   , 'name'=>'Synch returpembelian'                               , 'slug'=>'synch.returpembelian.koreksi'                , 'icon'=>'fa-refresh'      , ] ,
            [ 'groupapps'=>'TRADING', 'asmenu'=>0    , 'nested'=>'4.2'      , 'name'=>'Synch Token'                                        , 'slug'=>'synch.token'                                 , 'icon'=>'fa-refresh'      , ] ,

            // groupapps : FINANCE
            // ======================================================================================================================================================

            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'1'        , 'name'=>'Transaksi'                                          , 'slug'=>'transaksi.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'1.1'      , 'name'=>'Piutang'                                            , 'slug'=>'menupiutang.index'                           , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'1.1.1'    , 'name'=>'Kartu Piutang'                                      , 'slug'=>'kp.index'                                    , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'1.1.2'    , 'name'=>'Denda Tagihan'                                      , 'slug'=>'dendatagihan.index'                          , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'1.1.3'    , 'name'=>'Register Tagihan'                                   , 'slug'=>'registertagihan.index'                       , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3'        , 'name'=>'Manajemen'                                          , 'slug'=>'manajemen.index'                             , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.2'      , 'name'=>'Setting'                                            , 'slug'=>'setting.index'                               , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.2.1'    , 'name'=>'Change Password'                                    , 'slug'=>'setting.changepassword'                      , 'icon'=>'fa-wrench'      , ] ,
         
            // ======================================================================================================================================================
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.3'      , 'name'=>'Security'                                           , 'slug'=>'secure.index'                                , 'icon'=>'fa-folder-open' , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.3.1'    , 'name'=>'Manajemen Permission'                               , 'slug'=>'permission.index'                            , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.1.1'  , 'name'=>'Perbarui Menu Sidebar'                              , 'slug'=>'permission.sidebar'                          , 'icon'=>'fa-refresh'     , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.3.2'    , 'name'=>'Manajemen Roles'                                    , 'slug'=>'role.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.2.1'  , 'name'=>'Tambah Role'                                        , 'slug'=>'role.tambah'                                 , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.2.2'  , 'name'=>'Ubah Role'                                          , 'slug'=>'role.ubah'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.2.3'  , 'name'=>'Daftar User Role'                                   , 'slug'=>'role.user'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.2.4'  , 'name'=>'Hapus Role'                                         , 'slug'=>'role.hapus'                                  , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.3.3'    , 'name'=>'Manajemen Akses'                                    , 'slug'=>'access.index'                                , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.3.1'  , 'name'=>'View Akses'                                         , 'slug'=>'access.view'                                 , 'icon'=>'fa-eye'         , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.3.2'  , 'name'=>'Ubah Akses'                                         , 'slug'=>'access.ubah'                                 , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.3.3'  , 'name'=>'Tambah Akses User'                                  , 'slug'=>'access.tambah.user'                          , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.3.4'  , 'name'=>'Hapus Akses'                                        , 'slug'=>'access.delete.user'                          , 'icon'=>'fa-trash-o'     , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>1    , 'nested'=>'3.3.4'    , 'name'=>'Manajemen User'                                     , 'slug'=>'user.index'                                  , 'icon'=>'fa-file-o'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.4.1'  , 'name'=>'Tambah User'                                        , 'slug'=>'user.tambah'                                 , 'icon'=>'fa-plus'        , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.4.2'  , 'name'=>'Ubah User'                                          , 'slug'=>'user.ubah'                                   , 'icon'=>'fa-pencil'      , ] ,
            [ 'groupapps'=>'FINANCE', 'asmenu'=>0    , 'nested'=>'3.3.4.3'  , 'name'=>'Hapus User'                                         , 'slug'=>'user.hapus'                                  , 'icon'=>'fa-trash-o'     , ] ,
            // ======================================================================================================================================================

        ]);
    }
}

