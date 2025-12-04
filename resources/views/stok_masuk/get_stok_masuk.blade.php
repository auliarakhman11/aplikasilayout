<div class="row">
<input type="hidden" name="id" value="{{ $stok->id }}">
    <div class="col-4">
        <div class="form-group">
          <label for="">Barang</label>
          <select name="barang_id" class="form-control select2bs4" required>
            @foreach ($barang as $b)
                <option value="{{ $b->id }}" {{ $stok->barang_id == $b->id ? 'selected' : '' }}>{{ $b->nm_barang }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-4">
        <div class="form-group">
          <label for="">Exprired Date</label>
          <input type="date" name="tgl_exp" class="form-control" value="{{ $stok->tgl_exp }}"  required>
        </div>
      </div>

      <div class="col-4"></div>

      <div class="mt-2 col-6 col-md-3">
        <div class="form-group">
          <label for="">Block</label>
          <select name="block_id" class="form-control" id="block" required>
            @foreach ($block as $b)
                <option value="{{ $b->id }}" {{ $stok->block_id == $b->id ? 'selected' : '' }}>{{ $b->nm_block }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mt-2 col-6 col-md-3">
        <div class="form-group">
          <label for="">Cell</label>
          <select name="cell_id" class="form-control" id="cell" required>
            @foreach ($cell as $b)
                <option value="{{ $b->id }}" {{ $stok->cell_id == $b->id ? 'selected' : '' }}>{{ $b->nm_cell }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mt-2 col-6 col-md-3">
        <div class="form-group">
          <label for="">Lantai</label>
          <select name="rak_id" class="form-control" id="rak" required>
            @foreach ($rak as $b)
                <option value="{{ $b->id }}" {{ $stok->rak_id == $b->id ? 'selected' : '' }}>{{ $b->nm_rak }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mt-2 col-6 col-md-3">
        <div class="form-group">
          <label for="">Pallet</label>
          <input type="number" class="form-control" name="pallet_id" value="{{ $stok->pallet_id }}" required>
        </div>
      </div>

      <div class="col-4 mt-2">
        <div class="form-group">
          <label for="">Jumlah {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</label>
          <input type="number" name="debit_box" class="form-control" value="{{ $stok->debit_box }}" required>
        </div>
      </div>

      <div class="mt-2 col-4">
        <div class="form-group">
          <label for="">Jumlah PAK</label>
          <input type="number" name="debit_pak" class="form-control" value="{{ $stok->debit_pak }}" required>
        </div>
      </div>

      <div class="mt-2 col-4">
        <div class="form-group">
          <label for="">Jumlah KG</label>
          <input type="number" name="debit_kg" class="form-control" value="{{ $stok->debit_kg }}" required>
        </div>
      </div>

</div>