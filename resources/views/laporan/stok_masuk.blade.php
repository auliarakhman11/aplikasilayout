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
                  <h5 class="float-start">Laporan Stok Masuk</h5>
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
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Batch</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Admin</th>
                            <th>Checker</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($stok as $index => $s)
                          <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $s->kd_gabungan }}</td>
                            <td>{{ date("d/M/Y", strtotime($s->tgl)) }}</td>
                            <td>{{ $s->shift->nm_shift }}</td>
                            <td>{{ $s->user->name }}</td>
                            <td>{{ $s->userChecker->name }}</td>
                            <td><a href="{{ route('pdfStokMasuk',$s->kd_gabungan) }}" target="_blank" class="btn btn-primary btn-sm"><i class='bx bxs-file-pdf'></i></a></td>
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

