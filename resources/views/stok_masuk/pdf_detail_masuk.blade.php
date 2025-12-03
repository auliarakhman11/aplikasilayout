<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checker Stok Masuk</title>

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
                    <td style="border: none !important;">:</td>
                    <td style="border: none !important;">{{ $stok ? date("d-m-Y", strtotime($stok[0]->tgl)) : '' }}</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Jam Muat</td>
                    <td style="border: none !important;">:</td>
                    <td style="border: none !important;">{{ $stok ? date("H:i", strtotime($stok[0]->created_at)) : '' }}</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Warehouse</td>
                    <td style="border: none !important;">:</td>
                    <td style="border: none !important;">CR01</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Checker</td>
                    <td style="border: none !important;">:</td>
                    <td style="border: none !important;"></td>
                </tr>
            </tbody>
        </table>

        <center><h5>FORM CHECKER BARANG MASUK</h5></center>
    </div>
    

    <div class="container">
        <table class="table table-sm table-bordered" style="font-size: 12px;" width="100%">
            <thead style="text-align: center;">
                <tr>
                    <th>Nama Barang</th>
                    <th>Batch</th>
                    <th>Kode Barang</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>QTY</th>
                    <th>Lokasi</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $d)
                    <tr>
                        <td>{{ $d->barang->nm_barang }}</td>
                        <td>{{ $d->kd_gabungan }}</td>
                        <td>{{ $d->barang->kode_barang }}</td>
                        <td>{{ date("d/m/Y", strtotime($d->tgl_exp)) }}</td>
                        <td style="text-align: center;">{{ $d->debit_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $d->debit_pak }} Pack<br>{{ $d->debit_kg }} Kg</td>
                        <td style="text-align: center;">{{ $d->block->nm_block }} <br>{{ $d->cell->nm_cell }} <br>{{ $d->rak->nm_rak }} <br>{{ $d->pallet->nm_pallet }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
    
{{-- <center></center> --}}

</body>
</html>