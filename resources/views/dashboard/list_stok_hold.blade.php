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

                <div class="col-12 col-md-5">
                  <h5 class="float-start">List Stok Hold</h5>
                </div>
                <div class="col-5 col-md-3">
                    <div class="form-group">
                        <label for="">Dari</label>
                        <input type="date" class="form-control" value="{{ $tgl1 }}" name="tgl1" required>
                    </div>
                </div>
                <div class="col-5 col-md-3">
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="date" class="form-control" value="{{ $tgl2 }}" name="tgl2" required>
                    </div>
                </div>
  
                <div class="col-2 col-md-1">
                  <button class="btn btn-sm btn-primary mt-4" type="submit"><i class='bx bx-search-alt'></i></button>
                </div>
  
              </div>
            </form>              
              
          </div>
          <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table table-sm" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Batch</th>
                            <th>Mitra</th>
                            <th>Barang</th>
                            <th>Expired<br>Date</th>
                            <th>Lokasi</th>
                            <th>Qty</th>
                            <th>Shift</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($stok as $index => $s)
                          <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $s->kd_gabungan }}</td>
                            <td>{{ $s->mitra->nm_mitra }}</td>
                            <td>{{ $s->barang->nm_barang }}</td>
                            <td>{{ date("d/M/Y", strtotime($s->tgl_exp)) }}</td>
                            <td>{{ $s->block->nm_block }}<br>{{ $s->cell->nm_cell }}<br>{{ $s->rak->nm_rak }}<br>{{ $s->pallet->nm_pallet }}</td>
                            <td>{{ $s->debit_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $s->debit_pak }} Pack<br>{{ $s->debit_kg }} Kg</td>
                            <td>{{ $s->shift->nm_shift }}</td>
                            <td>{{ $s->user->name }}</td>
                            <td>
                              <button data-bs-toggle="modal" data-bs-target="#modal_edit_stok" stok_id="{{ $s->id }}" class="btn btn-primary btn-sm btn_edit_stok"><i class='bx bxs-edit'></i></button>
                              <a href="{{ route('deleteStokHold',$s->id) }}" class="btn btn-primary btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data?');"><i class='bx bxs-trash'></i></a>
                            </td>
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



  <form method="POST" action="{{ route('editStokMasuk') }}">
    @csrf
    <div class="modal fade" id="modal_edit_stok" tabindex="-1" aria-labelledby="modal_edit_stokLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_edit_stokLabel">Edit Stok Hold</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="table_edit_stok">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Edit</button>
            </div>
          </div>
        </div>
      </div>
  </form>




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

            $(document).on('click', '.btn_edit_stok', function() {
              var stok_id = $(this).attr('stok_id');

              $("#table_edit_stok").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('get-stok-masuk/'+stok_id, function (data) {        
                  $('#table_edit_stok').html(data);
                });

            });

            $(document).on('change', '#block', function() {
              var block_id = $(this).val();
              $('#cell').html('');
              $.get('get-cell/'+block_id, function (data) {        
                  $('#cell').html(data);
                });

            });

            $(document).on('change', '#cell', function() {
              var cell_id = $(this).val();
              $('#rak').html('');
              $.get('get-rak/'+cell_id, function (data) {        
                  $('#rak').html(data);
                });

            });

            $(document).on('change', '#rak', function() {
              var rak_id = $(this).val();
              $('#pallet').html('');
              $.get('get-pallet/'+rak_id, function (data) {        
                  $('#pallet').html(data);
                });

            });


        });

        

      </script>
  @endsection
@endsection

