<div class="row">
    <input type="hidden" name="id" value="{{ $stok->id }}">

    <input type="hidden" name="barang_id_dulu" value="{{ $stok->barang_id }}">
    <input type="hidden" name="tgl_exp_dulu" value="{{ $stok->tgl_exp }}">
    <input type="hidden" name="block_id_dulu" value="{{ $stok->block_id }}">
    <input type="hidden" name="cell_id_dulu" value="{{ $stok->cell_id }}">
    <input type="hidden" name="rak_id_dulu" value="{{ $stok->rak_id }}">
    <input type="hidden" name="pallet_id_dulu" value="{{ $stok->pallet_id }}">
    
        <div class="col-6">
            <div class="form-group">
              <label for="">Barang</label>
              <input type="text" class="form-control" value="{{ $stok->barang->nm_barang }}"  disabled>
            </div>
          </div>
    
          <div class="col-6">
            <div class="form-group">
              <label for="">Exprired Date</label>
              <input type="date" class="form-control" value="{{ $stok->tgl_exp }}"  disabled>
            </div>
          </div>
    
          <div class="mt-2 col-6 col-md-3">
            <div class="form-group">
              <label for="">Block</label>
              <select name="block_id" class="form-control" id="block" onchange="getCell(this);" required>
                @foreach ($block as $b)
                    <option value="{{ $b->id }}" {{ $stok->block_id == $b->id ? 'selected' : '' }}>{{ $b->nm_block }}</option>
                @endforeach
              </select>
            </div>
          </div>
    
          <div class="mt-2 col-6 col-md-3">
            <div class="form-group">
              <label for="">Cell</label>
              <select name="cell_id" class="form-control" id="cell" onchange="getRak(this);" required>
                @foreach ($cell as $b)
                    <option value="{{ $b->id }}" {{ $stok->cell_id == $b->id ? 'selected' : '' }}>{{ $b->nm_cell }}</option>
                @endforeach
              </select>
            </div>
          </div>
    
          <div class="mt-2 col-6 col-md-3">
            <div class="form-group">
              <label for="">Lantai</label>
              <select name="rak_id" class="form-control" id="rak" onchange="getPallet(this);" required>
                @foreach ($rak as $b)
                    <option value="{{ $b->id }}" {{ $stok->rak_id == $b->id ? 'selected' : '' }}>{{ $b->nm_rak }}</option>
                @endforeach
              </select>
            </div>
          </div>
    
          <div class="mt-2 col-6 col-md-3">
            <div class="form-group">
              <label for="">Pallet</label>
              <select name="pallet_id" class="form-control" id="pallet" required>
                @foreach ($pallet as $b)
                    <option value="{{ $b->id }}" {{ $stok->pallet_id == $b->id ? 'selected' : '' }}>{{ $b->nm_pallet }}</option>
                @endforeach
              </select>
            </div>
          </div>
    
          <div class="col-4 mt-2">
            <div class="form-group">
              <label for="">Jumlah {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</label>
              <input type="number" name="debit_box" class="form-control" value="{{ $stok->sisa_box }}" max="{{ $stok->debit_box }}" required>
            </div>
          </div>
    
          <div class="mt-2 col-4">
            <div class="form-group">
              <label for="">Jumlah PAK</label>
              <input type="number" name="debit_pak" class="form-control" value="{{ $stok->sisa_pak }}" max="{{ $stok->debit_pak }}" required>
            </div>
          </div>
    
          <div class="mt-2 col-4">
            <div class="form-group">
              <label for="">Jumlah KG</label>
              <input type="number" name="debit_kg" class="form-control" value="{{ $stok->sisa_kg }}" max="{{ $stok->debit_kg }}" required>
            </div>
          </div>
    
    </div>