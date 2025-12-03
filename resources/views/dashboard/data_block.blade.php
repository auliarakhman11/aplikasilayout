@extends('template.master')

@section('content')
    <!-- Content -->

<style>
    div.scroll_horizontal table {
        display: inline-block;
        
    }

    div.scroll_horizontal {
        overflow-y: scroll;
        overflow-x: auto;
        white-space: nowrap;
        height:500px;
    }

</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Data Block</h5>
            </div>
            
            <div class="card-body">

              <div class="scroll_horizontal">
                
                @foreach ($block as $b)
                <table border="1px solid" style="font-size: 20px;">
                    <thead>
                        <tr>
                            <th style="background-color: tomato"><b><a href="#modal_detail_block" data-bs-toggle="modal" style="text-decoration: none; color: black;" class="btn_detail_block" block_id="{{ $b->id }}">{{ $b->nm_block }}</a></b></th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($b->cell as $c)
                        <tr>
                            <td style="background-color: skyblue"><b><a href="#modal_detail_cell" data-bs-toggle="modal" style="text-decoration: none; color: black;" class="btn_detail_cell" cell_id="{{ $c->id }}">{{ $c->nm_cell }}</a></b>  <a target="_black" href="{{ route('generateQr',$c->id) }}" class="btn btn-xs btn-primary"><i class='bx bx-qr'></i></a></td>
                        </tr>
                            @foreach ($c->rak as $r)
                                <tr>
                                    <td><a href="#modal_detail_rak" data-bs-toggle="modal" style="text-decoration: none; color: black;" class="btn_detail_rak" rak_id="{{ $r->id }}">{{ $r->nm_rak }}</a></td>
                                </tr>
                            @endforeach
                        
                        @endforeach
                        
                    </tbody>
                </table>
                @endforeach

                

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

  <div class="modal fade" id="modal_detail_block" tabindex="-1" aria-labelledby="modal_detail_blockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_detail_blockLabel">Stok Block</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="table_detail_block">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_detail_cell" tabindex="-1" aria-labelledby="modal_detail_cellLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_detail_cellLabel">Stok Cell</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="table_detail_cell">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modal_detail_rak" tabindex="-1" aria-labelledby="modal_detail_rakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_detail_rakLabel">Stok Lantai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="table_detail_rak">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>


  <form id="form_pindah_barang">
    <div class="modal fade" id="modal_pindah_barang" tabindex="-1" aria-labelledby="modal_pindah_barangLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_pindah_barangLabel">Pindah Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="table_pindah_barang">
            
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="btn_pindah_barang">Pindah</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            
          </div>
        </div>
      </div>
    </div>
  </form>

  
  

  @section('script')

  <script>
    function getCell(block){
              $('#cell').html('');
              $.get('get-cell/'+block.value, function (data) {        
                  $('#cell').html(data);
                });
            }

    function getRak(cell){
      $('#rak').html('');
      $.get('get-rak/'+cell.value, function (data) {        
          $('#rak').html(data);
      });
    }

    function getPallet(rak){
      $('#pallet').html('');
      $.get('get-pallet/'+rak.value, function (data) {        
          $('#pallet').html(data);
      });
    }

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


          $(document).on('click', '.btn_detail_block', function() {
              var block_id = $(this).attr('block_id');

              $("#table_detail_block").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('detail-block/'+block_id, function (data) {        
                  $('#table_detail_block').html(data);
                });

            });

            $(document).on('click', '.btn_detail_cell', function() {
              var cell_id = $(this).attr('cell_id');

              $("#table_detail_cell").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('detail-cell/'+cell_id, function (data) {        
                  $('#table_detail_cell').html(data);
                });

            });

            $(document).on('click', '.btn_detail_rak', function() {
              var rak_id = $(this).attr('rak_id');

              $("#table_detail_rak").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('detail-rak/'+rak_id, function (data) {        
                  $('#table_detail_rak').html(data);
                });

            });

            $(document).on('click', '.btn_get_pindah_barang', function() {
              var pallet_id = $(this).attr('pallet_id');
              var block_id = $(this).attr('block_id');
              var cell_id = $(this).attr('cell_id');
              var rak_id = $(this).attr('rak_id');
              var tgl_exp = $(this).attr('tgl_exp');
              var barang_id = $(this).attr('barang_id');


              $("#table_pindah_barang").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('getPindahBarang/'+pallet_id+'/'+tgl_exp+'/'+barang_id+'/'+block_id+'/'+cell_id+'/'+rak_id, function (data) {        
                  $('#table_pindah_barang').html(data);
                });

            });


            $(document).on('submit', '#form_pindah_barang', function(event) {
                event.preventDefault();

                    $('#btn_pindah_barang').attr('disabled',true);
                    $('#btn_pindah_barang').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('pindahBarang') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Barang Berhasil Dipindah'
                                });

                                $('#modal_pindah_barang').modal('hide');

                                $("#btn_pindah_barang").removeAttr("disabled");
                            $('#btn_pindah_barang').html('Pindah'); //tombol
                                                        
                        },
                        error: function (data) { //jika error tampilkan error pada console
                                    alert('Error:', data);
                                    console.log('Error:', data);
                                    $("#btn_pindah_barang").removeAttr("disabled");
                            $('#btn_pindah_barang').html('Pindah'); //tombol
                                }
                    });

                });


            


            
        });

        

      </script>
  @endsection
@endsection

