@extends('template.master')

@section('content')


<!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <div class="col-12 mb-4 order-0">

        <div class="card">
          <div class="card-header">
            <form action="" method="get">
              <div class="row">

                <div class="col-12 col-md-3">
                  <h5 class="float-start">Laporan Penerimaan dan Pengiriman</h5>
                </div>

                <div class="col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Barang</label>
                    <select name="barang_id" class="form-control select2bs4" required>
                      <option value="All" {{ $barang_id == 'All' ? 'selected' : '' }}>All</option>
                      @foreach ($barang as $b)
                          <option {{ $barang_id == $b->id ? 'selected' : '' }} value="{{ $b->id }}" >{{ $b->nm_barang }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <label for="">Periode</label>
                        <input type="date" class="form-control" value="{{ $tgl }}" name="tgl" required>
                    </div>
                </div>

  
                <div class="col-2 col-md-1">
                  <button class="btn btn-sm btn-primary mt-4" type="submit"><i class='bx bx-search-alt'></i></button>
                </div>

                <div class="col-4 col-md-2">
                    <a href="{{ route('pdfPenerimaanPengiriman',['tgl' => $tgl, 'barang_id' => $barang_id]) }}" target="_blank" class="btn btn-sm btn-primary mt-4"><i class='bx bxs-file-pdf'></i> Print</a>
                  </div>
  
              </div>
            </form>              
              
          </div>
          <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table table-sm" id="table">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">#</th>
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
                      @foreach ($stok as $index => $s)
                          <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $s->barang->nm_barang }}</td>
                            <td>{{ $s->barang->kode_barang }}</td>
                            <td>{{ date("d/m/Y", strtotime($s->tgl_exp)) }}</td>
                            <td>{{ preg_replace("/Block/","", $s->block->nm_block) }}</td>
                            <td>{{ preg_replace("/Cell/"," ", $s->cell->nm_cell) }}</td>
                            <td>{{ preg_replace("/Lantai/"," ", $s->rak->nm_rak) }}</td>
                            <td>{{ preg_replace("/Pallet/"," ", $s->pallet->nm_pallet) }}</td>
                            <td>{{ $s->jml_debit_box ? $s->jml_debit_box : 0 }}</td>
                            <td>{{ $s->jml_debit_pak ? $s->jml_debit_pak : 0 }}</td>
                            <td>{{ $s->jml_debit_kg ? $s->jml_debit_kg : 0 }}</td>
                            <td>{{ $s->jml_kredit_box ? $s->jml_kredit_box : 0 }}</td>
                            <td>{{ $s->jml_kredit_pak ? $s->jml_kredit_pak : 0 }}</td>
                            <td>{{ $s->jml_kredit_kg ? $s->jml_kredit_kg : 0 }}</td>
                            <td>{{ $s->jml_debit_box - $s->jml_kredit_box }}</td>
                            <td>{{ $s->jml_debit_pak - $s->jml_kredit_pak }}</td>
                            <td>{{ $s->jml_debit_kg - $s->jml_kredit_kg }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>

          </div>
        </div>


    </div>

    <!-- Total Revenue -->

    <!--/ Total Revenue -->
    
  </div>

</div>
<!-- / Content -->

    

  <!-- Modal -->







  @section('script')
      <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $(document).ready(function () {

            <?php if(session('success')): ?>
            Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            icon: 'success',
            title: '<?= session('success'); ?>'
            });            
            <?php endif; ?>

            <?php if(session('error')): ?>
            Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            icon: 'error',
            title: '<?= session('error'); ?>'
            });            
            <?php endif; ?>

            


        });

        

      </script>
  @endsection
@endsection

