<html>
    <table>
        <tr>
            <td valign="middle" colspan="20" style="text-align: center;"><strong>LAPORAN ANALISA STOP</strong></td>
        </tr>
        <tr>
            <td colspan="20"></td>
        </tr>
        <tr>
            <td colspan="20"></td>
        </tr>
        <tr>
            <td>Kode gudang</td>
            <td>: 000
        </tr>
        <tr>
            <td>Tgl. rencana</td>
            <td>: {{ $req['tgl'] }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td>: {{ $req['namabarang'] }}</td>
        </tr>
        <tr>
            <td>Kode Barang</td>
            <td>: {{ $req['kodebarang'] }}</td>
        </tr>
        <tr>
            <td>Status aktif</td>
            <td>: {{ $req['statusaktif'] }}</td>
        </tr>
        <tr>
            <td>Rak 1</td>
            <td>: {{ $req['rak1'] }}</td>
        </tr>
        <tr>
            <td>Rak 2</td>
            <td>: {{ $req['rak2'] }}</td>
        </tr>
        <tr>
            <td>Rak 3</td>
            <td>: {{ $req['rak3'] }}</td>
        </tr>
        <tr>
            <td>Penanggung Jawab Rak</td>
            <td>: {{ $req['penanggungjawabrak'] }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td width="25" style="text-align:left;font-weight: bold;border: 2px solid #000000;">Stockid</td>
            <td width="100" style="font-weight: bold;border: 2px solid #000000;">Nama Barang</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Kode Barang</td>
            <td width="15" style="font-weight: bold;border: 2px solid #000000;">Status Aktif</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Rak 1</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Rak 2</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Rak 3</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Sat.</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. Awal</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. Baik</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. Rusak</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. GS</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. GIT AG</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. STOP</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Qty. Selisih</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Tgl. STOP</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Penghitung</td>
            <td width="20" style="font-weight: bold;border: 2px solid #000000;">Pemeriksa</td>
            <td width="40" style="font-weight: bold;border: 2px solid #000000;">Keterangan Rencana</td>
            <td width="40" style="font-weight: bold;border: 2px solid #000000;">Keterangan Hasil Hitung</td>
        </tr>
        @foreach($barang as $brg)
        <tr>
            <td width="25" style="text-align:left;border:2px solid #000000;">{{ $brg->id }}</td>
            <td width="100" style="border:2px solid #000000;">{{ $brg->namabarang }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->kodebarang }}</td>
            <td width="15" style="border:2px solid #000000;">{{ $brg->statusaktif }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->area1 }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->area2 }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->area3 }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->satuan }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtyawal }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtybaik }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtyrusak }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtygs }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtyaggit }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtystop }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->qtystop-$brg->qtyawal }}</td>
            <td width="20" style="border:2px solid #000000;">{{ $brg->tglstop }}</td>
            <td width="20" style="border:2px solid #000000;">{{ ($brg->penghitung) ? $brg->penghitung->namakaryawan : '' }}</td>
            <td width="20" style="border:2px solid #000000;">{{ ($brg->pemeriksa) ? $brg->pemeriksa->namakaryawan : '' }}</td>
            <td width="40" style="border:2px solid #000000;">{{ $brg->keteranganrencana }}</td>
            <td width="40" style="border:2px solid #000000;">{{ $brg->keteranganhasilhitung }}</td>
        </tr>
        @endforeach
    </table>
</html>