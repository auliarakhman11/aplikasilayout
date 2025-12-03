@if ($count != 0)

<form id="form_save_return_barang">

    <div class="card">
        <div class="card-header">
            <h5 class="float-start">Batch Return Barang</h5>
            
        </div>
        <div class="card-body">
    
            <div class="row mb-3">
    
                <div class="col-4">
                    <div class="form-group">
                      <label for="">Tanggal</label>
                      <input type="date" name="tgl" class="form-control" required>
                    </div>
                </div>
            
                <div class="col-4">
                    <div class="form-group">
                      <label for="">Shift</label>
                      <select name="shift_id" class="form-control" required>
                        <option value="">Pilih Shift</option>
                        @foreach ($shift as $s)
                            <option value="{{ $s->id }}">{{ $s->nm_shift }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                      <label for="">Mitra</label>
                      <select name="mitra_id" class="form-control" required>
                        <option value="">Pilih mitra</option>
                        @foreach ($mitra as $m)
                            <option value="{{ $m->id }}">{{ $m->nm_mitra }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
            
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
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
    
        </div>
        <div class="card-footer">
          <button type="submit" id="btn_input_data" class="btn btn-sm btn-primary float-end"><i class="bx bxs-save"></i> Input Stok</button>
        </div>
      </div>

</form>




@endif

