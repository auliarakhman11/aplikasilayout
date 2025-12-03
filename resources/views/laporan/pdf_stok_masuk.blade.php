<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Masuk</title>
    

    <style>
        
        table, th, td, th {
        border: 1px solid black;
        border-collapse: collapse;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="container">
        <img class="float-start" width="50 px;" src="{{asset('img')}}/cp.jpeg">
        <center><h4 style="margin-top: -50px;">PT. CHAROEN POKPHAND INDONESIA</h4></center><br>
        <center><h4 style="margin-top: -40px;">PLANT BANDUNG</h4></center>

        <hr style="border-top: 1px;">
    </div>

    <div class="container">
        <table style="border: none !important;">
            <tbody>
                <tr>
                    <td style="border: none !important;" width="100px;">Tanggal</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; {{ $stok ? date("d-m-Y", strtotime($stok[0]->tgl)) : '' }}</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Jam</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; {{ $stok ? date("H:i", strtotime($stok[0]->created_at)) : '' }}</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Warehouse</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; CR01</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Checker</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; {{ $stok ? $stok[0]->userChecker->name : '' }}</td>
                </tr>
            </tbody>
        </table>

        <center><h5>TALLY SHEET HARIAN BARANG MASUK</h5></center>
    </div>
    

    <div class="container">
        <table class="table table-sm table-bordered" style="font-size: 12px;">
            <thead style="text-align: center;">
                <tr>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Batch</th>
                    <th rowspan="2">Kode Barang</th>
                    <th rowspan="2">Tanggal Kadaluarsa</th>
                    <th rowspan="2">Jumlah ({{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }})</th>
                    <th rowspan="2">Jumlah (Pack)</th>
                    <th rowspan="2">Jumlah (Kg)</th>
                    <th rowspan="2">Qty Transaksi</th>
                    <th colspan="4">Lokasi Penyimpanan</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Block</th>
                    <th>Cell</th>
                    <th>Lantai</th>
                    <th>Pallet</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $d)
                    <tr>
                        <td>{{ $d->barang->nm_barang }}</td>
                        <td>{{ $d->kd_gabungan }}</td>
                        <td>{{ $d->barang->kode_barang }}</td>
                        <td>{{ date("d/m/Y", strtotime($d->tgl_exp)) }}</td>
                        <td style="text-align: center;">{{ $d->debit_box }}</td>
                        <td style="text-align: center;">{{ $d->debit_pak }}</td>
                        <td style="text-align: center;">{{ $d->debit_kg }}</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;">{{ preg_replace("/Block/","", $d->block->nm_block) }}</td>
                        <td style="text-align: center;">{{ preg_replace("/Cell/"," ", $d->cell->nm_cell) }}</td>
                        <td style="text-align: center;">{{ preg_replace("/Lantai/"," ", $d->rak->nm_rak) }}</td>
                        <td style="text-align: center;">{{ preg_replace("/Pallet/"," ", $d->pallet->nm_pallet) }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="border: none !important; margin-top: 100px;" width="100%">
            <tbody>
                <tr>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Admin Warehouse</td>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Foreman</td>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Cheker IN</td>
                </tr>
                <tr>
                    <td style="border: none !important; text-align: center;">____________________</td>
                    <td style="border: none !important; text-align: center;">____________________</td>
                    <td style="border: none !important; text-align: center;">____________________</td>
                </tr>
            </tbody>
        </table>
    </div>


</div>
    
{{-- <center></center> --}}

</body>
</html>