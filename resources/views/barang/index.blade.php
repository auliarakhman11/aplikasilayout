@extends('template.master')

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Data Barang</h5>
                <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modal_tambah_barang"><i class='bx bxs-plus-circle'></i> Tambah Data</button>
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" id="table">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Perkalian Pack</th>
                        <th>Perkalian Kg</th>                       
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($barang as $index => $b)
                    <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $b->kode_barang }}</td>
                      <td>{{ $b->nm_barang }}</td>  
                      <td>{{ $b->kali_pak }}</td>
                      <td>{{ $b->kali_kg }}</td>                    
                      <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_edit_barang{{ $b->id}}"><i class='bx bxs-message-square-edit'></i></button></td>
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

  <form method="POST" action="{{ route('addBarang') }}">
    @csrf
    <div class="modal fade" id="modal_tambah_barang" tabindex="-1" aria-labelledby="modal_tambah_barangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_tambah_barangLabel">Tambah Barang</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama Barang</label>
                        <input type="text" name="nm_barang" class="form-control" required>
                    </div>
                </div>

                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Kode Barang</label>
                      <input type="text" name="kode_barang" class="form-control" required>
                  </div>
                </div> 

                <div class="col-6 mb-2">
                  <div class="form-group">
                      <label for="">Perkalian Pack</label>
                      <input type="number" name="kali_pak" class="form-control" required>
                  </div>
                </div>

                <div class="col-6 mb-2">
                  <div class="form-group">
                      <label for="">Perkalian Kg</label>
                      <input type="number" name="kali_kg" class="form-control" required>
                  </div>
                </div> 

                
                
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
  </form>

  @foreach ($barang as $b)
  <form method="POST" action="{{ route('editBarang') }}">
    @csrf
    <div class="modal fade" id="modal_edit_barang{{ $b->id}}" tabindex="-1" aria-labelledby="modal_tambah_barangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_tambah_barangLabel">Edit Barang</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <input type="hidden" name="id" value="{{ $b->id }}">
                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama Barang</label>
                        <input type="text" name="nm_barang" value="{{ $b->nm_barang }}" class="form-control" required>
                    </div>
                </div>

                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Kode Barang</label>
                      <input type="text" name="kode_barang" value="{{ $b->kode_barang }}" class="form-control" required>
                  </div>
                </div> 

                <div class="col-6 mb-2">
                  <div class="form-group">
                      <label for="">Perkalian Pack</label>
                      <input type="number" name="kali_pak" value="{{ $b->kali_pak }}" class="form-control" required>
                  </div>
                </div>

                <div class="col-6 mb-2">
                  <div class="form-group">
                      <label for="">Perkalian Kg</label>
                      <input type="number" name="kali_kg" value="{{ $b->kali_kg }}" class="form-control" required>
                  </div>
                </div> 
                
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
  </form>
  @endforeach
  

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

          <?php if(session('error_kota')): ?>
          Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'error',
                    title: "{{ session('error_kota') }}"
                  });            
          <?php endif; ?>

          <?php if($errors->any()): ?>
          Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'error',
                    title: ' Ada data yang tidak sesuai, periksa kembali'
                  });            
          <?php endif; ?>


            
        });

        

      </script>
  @endsection
@endsection

