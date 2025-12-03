@if (empty($dtBarang))
    <h5>Stok kosong!!</h5>
@else
<div class="scrollme">
  <table class="table table-sm" width="100%">
    <thead>
      <tr>
          <th>#</th>
          <th>Barang</th>
          <th>Tanggal Exprired</th>
          <th>QTY</th>
          <th>Lokasi</th>
          <th>Pilih</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">
      @foreach ($dtBarang as $index => $d)
      <tr>
      <td>{{ $index+1 }}</td>
      <td>{{ $d->barang->nm_barang }}</td>
        <td>{{ date("d/M/Y", strtotime($d->tgl_exp)) }}</td>
        <td  width="30%">
            <div class="input-group">
                <input type="number" class="form-control form-control-sm input_barang_{{ $d->id }} input_box" urutan="{{ $d->id }}" kali_pak="{{ $d->barang->kali_pak }}" kali_kg="{{ $d->barang->kali_kg }}" name="kredit_box[]" value="{{ $d->sisa_box }}" max="{{ $d->sisa_box }}" disabled>
                <span class="input-group-text" style="font-size: 10px;">{{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</span>
            </div>
            <div class="input-group">
                <input type="number" class="form-control form-control-sm input_barang_{{ $d->id }}" id="kredit_pak{{ $d->id }}" name="kredit_pak[]" value="{{ $d->sisa_pak }}" max="{{ $d->sisa_pak }}" disabled>
                <span class="input-group-text" style="font-size: 10px;">Pack</span>
            </div>
            <div class="input-group">
                <input type="number" class="form-control form-control-sm input_barang_{{ $d->id }}"id="kredit_kg{{ $d->id }}" name="kredit_kg[]" value="{{ $d->sisa_kg }}" max="{{ $d->sisa_kg }}" disabled>
                <span class="input-group-text" style="font-size: 10px;">KG</span>
            </div>
            
            <input type="hidden" class="input_barang_{{ $d->id }}" name="barang_id[]" value="{{ $d->barang_id }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="block_id[]" value="{{ $d->block_id }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="cell_id[]" value="{{ $d->cell_id }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="rak_id[]" value="{{ $d->rak_id }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="pallet_id[]" value="{{ $d->pallet_id }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="barang[]" value="{{ $d->barang->nm_barang }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="block[]" value="{{ $d->block->nm_block }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="cell[]" value="{{ $d->cell->nm_cell }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="rak[]" value="{{ $d->rak->nm_rak }}" disabled>
            <input type="hidden" class="input_barang_{{ $d->id }}" name="pallet[]" value="{{ $d->pallet->nm_pallet }}" disabled>

            <input type="hidden" class="input_barang_{{ $d->id }}" name="tgl_exp[]" value="{{ $d->tgl_exp }}" disabled>
            
        </td>              
        <td>{{ $d->block->nm_block }}<br>{{ $d->cell->nm_cell }}<br>{{ $d->rak->nm_rak }}<br>{{ $d->pallet->nm_pallet }}</td>

        <td>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input terima" checker_id="{{  $d->id }}" value="{{ $d->id }}" type="checkbox">
              </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endif