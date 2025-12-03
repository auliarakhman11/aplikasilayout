<table class="table table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Barang</th>
            <th>Sisa Stok</th>
            <th>Expired Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dt_stok as $index => $d)
        @php
            if ($d->sisa_box == 0 && $d->sisa_pak == 0 && $d->sisa_kg == 0 ) {
                continue;
            }
        @endphp
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $d->barang->nm_barang }}</td>
            <td>{{ $d->sisa_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $d->sisa_pak }} Pak<br>{{ $d->sisa_kg }} Kg</td>
            <td>{{ date("d/M/Y", strtotime($d->tgl_exp)) }}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>