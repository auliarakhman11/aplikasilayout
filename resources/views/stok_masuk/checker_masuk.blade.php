@extends('template.master')

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Checker Masuk</h5>
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" id="table">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>No Batch</th>
                        <th>Tanggal Masuk</th>
                        <th>Jam</th>
                        <th>Shift</th>                                        
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($checker as $index => $c)
                    <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $c->kd_gabungan }}</td>
                      <td>{{ date("d/M/Y", strtotime($c->tgl)) }}</td>
                      <td>{{ date("H:i", strtotime($c->created_at)) }}</td>  
                      <td>{{ $c->shift->nm_shift }}</td>                                          
                      <td><a class="btn btn-sm btn-primary" href="{{ route('detailMasuk',$c->kd_gabungan) }}"><i class='bx bx-task'></i></a></td>
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

