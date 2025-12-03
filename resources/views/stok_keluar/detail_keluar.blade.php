@extends('template.master')

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">

        <form action="{{ route('addCheckerKeluar') }}" method="post">
          @csrf

          <div class="card">
            <div class="card-header">
                <h5 class="float-start">Deatil Checker {{ $checker ? $checker[0]->kd_gabungan : '' }}</h5>
                <button type="submit" class="btn btn-primary float-end"><i class="bx bxs-save"></i> Save</button>
                <a href="{{ route('pdfDetailKeluar',$checker[0]->kd_gabungan) }}" target="_blank" class="btn btn-primary float-end mr-3 ml-3"><i class='bx bxs-file-pdf'></i> Print</a>
                <input type="hidden" name="kd_gabungan" value="{{ $checker ? $checker[0]->kd_gabungan : '' }}">
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" width="100%">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Lokasi</th>
                        <th>QTY</th>                                       
                        <th>Terima</th>
                        <th width="20%">Ket</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($checker as $index => $c)
                    <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $c->barang->nm_barang }}</td>
                    <td>{{ $c->block->nm_block }}<br>{{ $c->cell->nm_cell }}<br>{{ $c->rak->nm_rak }}<br>{{ $c->pallet->nm_pallet }}</td>
                    <td>{{ $c->kredit_box }} {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}<br>{{ $c->kredit_pak }} Pak<br>{{ $c->kredit_kg }} KG</td>           
                    <td><div class="form-check form-switch mb-2">
                        <input class="form-check-input terima" name="terima[]" checker_id="{{  $c->id }}" value="{{ $c->id }}" type="checkbox">
                      </div>
                    </td>
                    <td>
                        <textarea class="form-control" rows="3" cols="5" name="ket_checker[]" id="ket_{{ $c->id }}" required></textarea>
                        <input type="hidden" id="tolak_{{ $c->id }}" name="tolak[]" value="{{ $c->id }}">
                    </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div>
            
          </div>
        </form>
        
        


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


          $(document).on('change', '.terima', function() {
                if ($(this).is(':checked')) {

                  var checker_id = $(this).attr("checker_id");
                    $("#ket_"+checker_id).attr('disabled',true);
                    $("#tolak_"+checker_id).attr('disabled',true);

                } else {
                  
                  var checker_id = $(this).attr("checker_id");
                    $("#ket_"+checker_id).removeAttr("disabled");
                    $("#tolak_"+checker_id).removeAttr("disabled");

                }
              

            });


            
        });

        

      </script>
  @endsection
@endsection

