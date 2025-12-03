@if ($count != 0)
<div class="table-responsive text-nowrap">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Expired Date</th>
                <th>Barang</th>
                <th>QTY</th>
                <th>Lokasi</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i=1;
            @endphp
            @foreach ($cart as $c)
            <tr>
                <td>{{ $i++ }}</td>
                <td class="text-center">
                    @if ($c->options->cek_lokasi)
                        <span class="btn btn-sm btn-success"><i class='bx bxs-check-circle'></i></span>
                    @else
                    <span class="btn btn-sm btn-danger"><i class='bx bxs-x-circle'></i></span>
                    @endif
                </td>
                <td>{{ $c->options->tgl_edit }}</td>
                <td>{{ $c->options->tgl_exp_edit }}</td>
                <td>{{ $c->options->barang }}</td>
                <td>{{ $c->options->debit_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $c->options->debit_pak }} Pak<br>{{ $c->options->debit_kg }}</td>
                <td>{{ $c->options->block }}<br>{{ $c->options->cell }}<br>{{ $c->options->rak }}<br>{{ $c->options->pallet }}</td>
                <td><button class="btn btn-sm btn-primary btn_hapus_cart" type="button" cart_id="{{ $c->rowId }}"><i class='bx bxs-trash-alt'></i></button></td>
            </tr>
            @endforeach            
        </tbody>
    </table>
</div>
@endif

