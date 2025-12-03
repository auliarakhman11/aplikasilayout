<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penerimaan dan Pengiriman</title>
    

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
                    <td style="border: none !important;">&nbsp; &nbsp; {{ date('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Warehouse</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; CR01</td>
                </tr>
                <tr>
                    <td style="border: none !important;" width="100px;">Periode</td>
                    <td style="border: none !important;">: </td>
                    <td style="border: none !important;">&nbsp; &nbsp; {{ date("d/m/Y", strtotime($tgl)) }}</td>
                </tr>
            </tbody>
        </table>

        <center><h5>LAPORAN PENERIMAAN DAN PENGIRIMAN</h5></center>
    </div>
    

    <div class="container">
        <table class="table table-sm table-bordered" style="font-size: 12px;" width="100%">
            <thead style="text-align: center;">
                <tr>
                    <th rowspan="2">Barang</th>
                    <th rowspan="2">Kode Barang</th>
                    <th rowspan="2">Tanggal<br>Kadaluarsa</th>
                    <th colspan="4">Lokasi Penyimpanan</th>
                    <th colspan="3">Penerimaan</th>
                    <th colspan="3">Pengiriman</th>
                    <th colspan="3">Stok Akhir</th>
                </tr>
                <tr>
                    <th>Block</th>
                    <th>Cell</th>
                    <th>Lantai</th>
                    <th>Pallet</th>
                    <th>{{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</th>
                    <th>Pack</th>
                    <th>Kg</th>
                    <th>{{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</th>
                    <th>Pack</th>
                    <th>Kg</th>
                    <th>{{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</th>
                    <th>Pack</th>
                    <th>Kg</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $s)
                @if (($s->jml_debit_box - $s->jml_kredit_box) == 0 && ($s->jml_debit_pak - $s->jml_kredit_pak) == 0 && ($s->jml_debit_kg - $s->jml_kredit_kg) == 0 )
                    @php
                        continue;
                    @endphp
                @endif
                <tr>
                    <td>{{ $s->barang->nm_barang }}</td>
                    <td>{{ $s->barang->kode_barang }}</td>
                    <td>{{ date("d/m/Y", strtotime($s->tgl_exp)) }}</td>
                    <td style="text-align: center;">{{ preg_replace("/Block/","", $s->block->nm_block) }}</td>
                    <td style="text-align: center;">{{ preg_replace("/Cell/"," ", $s->cell->nm_cell) }}</td>
                    <td style="text-align: center;">{{ preg_replace("/Lantai/"," ", $s->rak->nm_rak) }}</td>
                    <td style="text-align: center;">{{ preg_replace("/Pallet/"," ", $s->pallet->nm_pallet) }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_box ? $s->jml_debit_box : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_pak ? $s->jml_debit_pak : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_kg ? $s->jml_debit_kg : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_kredit_box ? $s->jml_kredit_box : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_kredit_pak ? $s->jml_kredit_pak : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_kredit_kg ? $s->jml_kredit_kg : 0 }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_box - $s->jml_kredit_box }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_pak - $s->jml_kredit_pak }}</td>
                    <td style="text-align: center;">{{ $s->jml_debit_kg - $s->jml_kredit_kg }}</td>
                  </tr>
                @endforeach
            </tbody>
        </table>

        <table style="border: none !important; margin-top: 100px;" width="100%">
            <tbody>
                <tr>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Admin Warehouse</td>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Foreman</td>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Manager</td>
                    <td style="border: none !important; text-align: center; padding-bottom: 100px;">Admin Logistik</td>
                </tr>
                <tr>
                    <td style="border: none !important; text-align: center;">_________________</td>
                    <td style="border: none !important; text-align: center;">_________________</td>
                    <td style="border: none !important; text-align: center;">_________________</td>
                    <td style="border: none !important; text-align: center;">_________________</td>
                </tr>
            </tbody>
        </table>
    </div>


</div>
    
{{-- <center></center> --}}

</body>
</html>