@extends('template.master')

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Data Mitra</h5>
                <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modal_tambah_mitra"><i class='bx bxs-plus-circle'></i> Tambah Data</button>
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" id="table">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mitra</th>                        
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($mitra as $index => $m)
                    <tr>
                    <td>{{ $index+1 }}</td>
                      <td>{{ $m->nm_mitra }}</td>                      
                      <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_edit_mitra{{ $m->id}}"><i class='bx bxs-message-square-edit'></i></button></td>
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

  <form method="POST" action="{{ route('addMitra') }}">
    @csrf
    <div class="modal fade" id="modal_tambah_mitra" tabindex="-1" aria-labelledby="modal_tambah_mitraLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_tambah_mitraLabel">Tambah Mitra</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama mitra</label>
                        <input type="text" name="nm_mitra" class="form-control" required>
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

  @foreach ($mitra as $m)
  <form method="POST" action="{{ route('editMitra') }}">
    @csrf
    <div class="modal fade" id="modal_edit_mitra{{ $m->id}}" tabindex="-1" aria-labelledby="modal_tambah_mitraLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_tambah_mitraLabel">Edit Mitra</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <input type="hidden" name="id" value="{{ $m->id }}">
                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama Mitra</label>
                        <input type="text" name="nm_mitra" value="{{ $m->nm_mitra }}" class="form-control" required>
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

