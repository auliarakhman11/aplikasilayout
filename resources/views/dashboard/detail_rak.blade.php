<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Barang</th>
                <th>Pallet</th>
                <th>Sisa Stok</th>
                <th>Expired Date</th>
                <th>Pindah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dt_stok as $index => $d)
            @php
                if ($d->sisa_box == 0 && $d->sisa_pak == 0 && $d->sisa_kg == 0 ) {
                    continue;
                }
    
                $no = 1;
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $d->barang->nm_barang }}</td>
                <td>{{ $d->pallet->nm_pallet }}</td>
                <td>{{ $d->sisa_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $d->sisa_pak }} Pak<br>{{ $d->sisa_kg }} Kg</td>
                <td>{{ date("d/M/Y", strtotime($d->tgl_exp)) }}</td>
                <td><a data-bs-toggle="modal" href="#modal_pindah_barang" class="btn btn-sm btn-primary btn_get_pindah_barang" pallet_id="{{ $d->pallet_id }}" block_id="{{ $d->block_id }}" cell_id="{{ $d->cell_id }}" rak_id="{{ $d->rak_id }}" tgl_exp="{{ $d->tgl_exp }}" barang_id="{{ $d->barang_id }}"><i class='bx bx-transfer-alt'></i></a></td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>