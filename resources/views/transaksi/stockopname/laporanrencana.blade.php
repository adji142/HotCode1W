<html>
    <table>
        <tr>
            <td valign="middle" colspan="5" style="text-align: center;"><strong>LAPORAN RENCANA STOP</strong></td>
        </tr>
        <tr>
            <td valign="middle" colspan="5" style="text-align: center;"><strong>{{ $status }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td>Kode gudang</td>
            <td>: {{ $gudang }}
        </tr>
        <tr>
            <td>Tgl. rencana</td>
            <td>: {{ $req['tgl'] }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td>: {{ (array_key_exists('namabarang',$req)) ? (($req['namabarang']) ? strtoupper($req['namabarang']) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Kode Barang</td>
            <td>: {{ (array_key_exists('kodebarang',$req)) ? (($req['kodebarang']) ? strtoupper($req['kodebarang']) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Status aktif</td>
            <td>: {{ (array_key_exists('statusaktif',$req)) ? (($req['statusaktif'] == 0) ? 'PASIF' : (($req['statusaktif'] == 1) ? 'AKTIF' : 'SEMUA')) : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Rak 1</td>
            <td>: {{ (array_key_exists('rak1',$req)) ? (($req['rak1']) ? strtoupper($req['rak1']) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Rak 2</td>
            <td>: {{ (array_key_exists('rak2',$req)) ? (($req['rak2']) ? strtoupper($req['rak2']) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Rak 3</td>
            <td>: {{ (array_key_exists('rak3',$req)) ? (($req['rak3']) ? strtoupper($req['rak3']) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
        <tr>
            <td>Penanggung Jawab Rak</td>
            <td>: {{ (array_key_exists('penanggungjawabrak',$req)) ? (($req['penanggungjawabrak']) ? strtoupper(PenanggungJawabArea::find($req['penanggungjawabrak'])->karyawan->namakaryawan) : 'SEMUA') : 'SEMUA' }}</td>
        </tr>
    </table>
    <table>
        @if($tipe == 0)
            <tr>
                <td width="25" style="text-align:left;font-weight: bold;border: 2px solid #000000;">Stockid</td>
                <td width="100" style="font-weight: bold;border: 2px solid #000000;">Nama Barang</td>
                <td width="20" style="font-weight: bold;border: 2px solid #000000;">Kode Barang</td>
                <td width="15" style="font-weight: bold;border: 2px solid #000000;">Tgl. Rencana</td>
                <td width="40" style="font-weight: bold;border: 2px solid #000000;">Keterangan Rencana</td>
            </tr>
        @elseif($tipe == 1)
            <tr>
                <td width="25" style="text-align:left;font-weight: bold;border: 2px solid #000000;">Stockid</td>
                <td width="100" style="font-weight: bold;border: 2px solid #000000;">Nama Barang</td>
                <td width="20" style="font-weight: bold;border: 2px solid #000000;">Kode Barang</td>
                <td width="15" style="font-weight: bold;border: 2px solid #000000;">namaTable.namakolom</td>
                <td width="40" style="font-weight: bold;border: 2px solid #000000;">Tgl. Transaksi</td>
            </tr>
        @elseif($tipe == 2 || $tipe == 3)
            <tr>
                <td width="25" style="text-align:left;font-weight: bold;border: 2px solid #000000;">Stockid</td>
                <td width="100" style="font-weight: bold;border: 2px solid #000000;">Nama Barang</td>
                <td width="20" style="font-weight: bold;border: 2px solid #000000;">Kode Barang</td>
                <td width="40" style="font-weight: bold;border: 2px solid #000000;">Keterangan Rencana</td>
            </tr>
        @endif

        @if($tipe == 0)
            @foreach($barang as $brg)
            <tr>
                <td width="25" style="text-align:left;border:2px solid #000000;">{{ $brg->id }}</td>
                <td width="100" style="border:2px solid #000000;">{{ $brg->namabarang }}</td>
                <td width="20" style="border:2px solid #000000;">{{ $brg->kodebarang }}</td>
                <td width="15" style="border:2px solid #000000;">{{ $brg->tglrencana }}</td>
                <td width="40" style="border:2px solid #000000;">{{ $brg->keteranganrencana }}</td>
            </tr>
            @endforeach
        @elseif($tipe == 1)
            @foreach($barang as $key => $brg)
            @if($key == 'npj')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglnota</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->nota->tglnota }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'rpj')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglnotaretur</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->returpenjualan->tglnotaretur }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'npb')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglterima</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->nota->tglterima }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'rpb')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglprb</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->returpembelian->tglprb }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'gstrans')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tgltransaksi</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->gs->tgltransaksi }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'gslink')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tgllink</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->gs->tgllink }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'agkirim')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglkirim</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->ag->tglkirim }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'agtrima')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglterima</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->ag->tglterima }}</td>
                </tr>
                @endforeach
                @endif
            @elseif($key == 'mt')
                @if($brg->isNotEmpty())
                @foreach($brg[0]->details as $detail)
                <tr>
                    <td width="25" style="text-align:left;border:2px solid #000000;">{{ $detail->stockid }}</td>
                    <td width="100" style="border:2px solid #000000;">{{ $detail->barang->namabarang }}</td>
                    <td width="20" style="border:2px solid #000000;">{{ $detail->barang->kodebarang }}</td>
                    <td width="35" style="border:2px solid #000000;">{{ $brg[0]->getTable() }}.tglmutasi</td>
                    <td width="40" style="border:2px solid #000000;">{{ $detail->mt->tglmutasi }}</td>
                </tr>
                @endforeach
                @endif
            @endif
            @endforeach
        @elseif($tipe == 2)
            @foreach($barang as $brg)
            <tr>
                <td width="25" style="text-align:left;border:2px solid #000000;">{{ $brg->id }}</td>
                <td width="100" style="border:2px solid #000000;">{{ $brg->namabarang }}</td>
                <td width="20" style="border:2px solid #000000;">{{ $brg->kodebarang }}</td>
                <td width="40" style="border:2px solid #000000;">{{ $brg->keteranganrencana }}</td>
            </tr>
            @endforeach
        @elseif($tipe == 3)
            @foreach($barang as $brg)
            <tr>
                <td width="25" style="text-align:left;border:2px solid #000000;">{{ $brg->id }}</td>
                <td width="100" style="border:2px solid #000000;">{{ $brg->namabarang }}</td>
                <td width="20" style="border:2px solid #000000;">{{ $brg->kodebarang }}</td>
                <td width="40" style="border:2px solid #000000;">{{ $req['ket'] }}</td>
            </tr>
            @endforeach
        @endif
    </table>
</html>