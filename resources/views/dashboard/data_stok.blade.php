@extends('template.master')

@section('chart')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js" integrity="sha512-tMabqarPtykgDtdtSqCL3uLVM0gS1ZkUAVhRFu1vSEFgvB73niFQWJuvviDyBGBH22Lcau4rHB5p2K2T0Xvr6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 col-md-4 mb-3">
        <div class="card">
          <div class="card-header">
            <h5>Penerimaan</h5>
          </div>
          <div class="card-body">
            <canvas id="penerimaan"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 mb-3">
        <div class="card">
          <div class="card-header">
            <h5>Pendistribusian</h5>
          </div>
          <div class="card-body">
            <canvas id="pendistribusian"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 mb-3">
        <div class="card">
          <div class="card-header">
            <h5>Barang Hold</h5>
          </div>
          <div class="card-body">
            <canvas id="hold"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Data Stok</h5>
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" id="table">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>                        
                        <th>Qty {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</th>
                        <th>Qty Pak</th>
                        <th>Qty Kg</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($dt_stok as $index => $d)
                    <tr>
                    <td>{{ $index+1 }}</td>
                      <td>{{ $d->barang->nm_barang }}</td>                      
                      <td>{{ $d->sisa_box }}</td>
                      <td>{{ $d->sisa_pak }}</td>
                      <td>{{ $d->sisa_kg }}</td>
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
    var penerimaan = JSON.parse(`<?php echo $penerimaan; ?>`);
    var periode = JSON.parse(`<?php echo $dt_pr; ?>`);
    const ctx_penerimaan = document.getElementById('penerimaan');
    const myChart_penerimaan = new Chart(ctx_penerimaan, {
        type: 'line',
        data: {
            labels: periode,
            datasets : penerimaan
        }
    });

    var pendistribusian = JSON.parse(`<?php echo $pendistribusian; ?>`);
    var periode = JSON.parse(`<?php echo $dt_pr; ?>`);
    const ctx_pendistribusian = document.getElementById('pendistribusian');
    const myChart_pendistribusian = new Chart(ctx_pendistribusian, {
        type: 'line',
        data: {
            labels: periode,
            datasets : pendistribusian
        }
    });

    var hold = JSON.parse(`<?php echo $hold; ?>`);
    var periode = JSON.parse(`<?php echo $dt_pr; ?>`);
    const ctx_hold = document.getElementById('hold');
    const myChart_hold = new Chart(ctx_hold, {
        type: 'line',
        data: {
            labels: periode,
            datasets : hold
        }
    });
    </script>

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

