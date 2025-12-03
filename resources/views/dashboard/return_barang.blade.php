@extends('template.master')

@section('content')


<!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <div class="col-12 mb-4 order-0">
      
      <div class="card">
          <div class="card-header">
              <h5 class="float-start">Input Barang Hold</h5>
              
          </div>
          
          <div class="card-body">

            <form id="form_input_return_barang">
              <div class="row">

                
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Barang</label>
                    <select name="barang_id" class="form-control select2bs4" required>
                      <option value="">Pilih Barang</option>
                      @foreach ($barang as $b)
                          <option value="{{ $b->id }}|{{ $b->nm_barang }}">{{ $b->nm_barang }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Exprired Date</label>
                    <input type="date" name="tgl_exp" class="form-control" id="tgl_exp"  required>
                  </div>
                </div>
                <div class="col-4"></div>

                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Block</label>
                    <select name="block_id" class="form-control" id="block" required>
                      <option value="">Pilih Block</option>
                      @foreach ($block as $b)
                          <option value="{{ $b->id }}|{{ $b->nm_block }}">{{ $b->nm_block }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
  
                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Cell</label>
                    <select name="cell_id" class="form-control" id="cell" required>
                      <option value="">Pilih Cell</option>
                    </select>
                  </div>
                </div>
  
                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Lantai</label>
                    <select name="rak_id" class="form-control" id="rak" required>
                      <option value="">Pilih Lantai</option>
                    </select>
                  </div>
                </div>

                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Pallet</label>
                    <select name="pallet_id" class="form-control" id="pallet" required>
                      <option value="">Pilih Pallet</option>
                    </select>
                  </div>
                </div>

  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Jumlah {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</label>
                    <input type="number" name="debit_box" class="form-control" id="debit_box" required>
                  </div>
                </div>
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Jumlah PAK</label>
                    <input type="number" name="debit_pak" class="form-control" id="debit_pak" required>
                  </div>
                </div>
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Jumlah KG</label>
                    <input type="number" name="debit_kg" class="form-control" id="debit_kg" required>
                  </div>
                </div>
  
                

                {{-- <div class="col-8">
                  <a href="{{ route('downloadFormat') }}" class="btn btn-sm btn-primary mt-4"><i class='bx bx-export' ></i> Format</a>
                  <button type="button" class="btn btn-sm btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modal_import_stok"><i class='bx bx-import' ></i> Import</button>
                </div> --}}

                <div class="col-6 mb-2 mt-2"></div>
  
                <div class="col-6 mb-2 mt-2">
                  <button type="submit" id="btn_add_cart" class="btn btn-sm btn-primary float-end">
                    <i class='bx bxs-right-arrow-circle'></i> Lanjut
                  </button>
                </div>
                
              </div>
            </form>

          </div>
          
        </div>

        <div id="cart"></div>


    </div>

    <!-- Total Revenue -->

    <!--/ Total Revenue -->
    
  </div>

</div>
<!-- / Content -->

    

  <!-- Modal -->


{{-- <form method="POST" action="{{ route('importStokMasuk') }}" enctype="multipart/form-data">
  @csrf
  <div class="modal fade" id="modal_import_stok" tabindex="-1" aria-labelledby="modal_import_stokLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_import_stokLabel">Import Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
  
          
          <div class="row">

            <div class="col-6">
              <div class="form-group">
                <label for="">Tanggal</label>
                <input type="date" name="tgl" class="form-control" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="">Shift</label>
                <select name="shift_id" class="form-control" required>
                  <option value="">Pilih Shift</option>
                  @foreach ($shift as $s)
                      <option value="{{ $s->id }}">{{ $s->nm_shift }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label for="">File Excel</label>
                <input type="file" class="form-control" name="file_excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
              </div>
            </div>

          </div>
          
          
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_simpan_penilaian">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form> --}}





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

            function getCart() {
              $.get('get-cart-return-barang', function (data) {        
                  $('#cart').html(data);
                });
            }

            getCart();

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

            $(document).on('click', '.btn_hapus_cart', function() {
              var id = $(this).attr('cart_id');

              $.get('delete-cart-return-barang/'+id, function (data) {        
                  getCart();
                });

            });

            // $(document).on('click', '#btn_input_data', function() {

            //   $.get('save-stok-masuk', function (data) {        
            //       getCart();

            //       Swal.fire({
            //                     toast: true,
            //                     position: 'top-end',
            //                     showConfirmButton: false,
            //                     timer: 3000,
            //                     icon: 'success',
            //                     title: 'Stok Masuk Berhasil Diinput'
            //                     });

            //     });

            // });

            

            

            $(document).on('submit', '#form_input_return_barang', function(event) {
                event.preventDefault();

                    $('#btn_add_cart').attr('disabled',true);
                    $('#btn_add_cart').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('addCartReturnBarang') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                            
                          $("#btn_add_cart").removeAttr("disabled");
                            $('#btn_add_cart').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                            getCart();
 
                            
                            $('.select2bs4').val('');
                            $('.select2bs4').select2({theme: 'bootstrap4', tags: true,}).trigger('change');

                            $("#debit_box").val('');
                            $("#debit_pak").val('');
                            $("#debit_kg").val('');
                            $("#tgl_exp").val('');

                            $("#block").val('');

                            $("#cell").html('<option value="">Pilih Cell</option>');
                            $("#rak").html('<option value="">Pilih Lantai</option>');
                            $("#pallet").html('<option value="">Pilih Pallet</option>');

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Data Berhasil Diinput'
                                });
                                                        
                        },
                        error: function (data) { //jika error tampilkan error pada console
                                    alert('Error:', data);
                                    $("#btn_add_cart").removeAttr("disabled");
                            $('#btn_add_cart').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                                }
                    });

                });



                $(document).on('submit', '#form_save_return_barang', function(event) {
                event.preventDefault();

                    $('#btn_input_data').attr('disabled',true);
                    $('#btn_input_data').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('saveReturnBarang') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                            
                          $("#btn_input_data").removeAttr("disabled");
                            $('#btn_input_data').html('<i class="bx bxs-save"></i> Input Stok'); //tombol
                            getCart();

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Data Berhasil Diinput'
                                });
                                                        
                        },
                        error: function (data) { //jika error tampilkan error pada console
                                    alert('Error:', data);
                                    $("#btn_input_data").removeAttr("disabled");
                            $('#btn_input_data').html('<i class="bx bxs-save"></i> Input Stok'); //tombol
                                }
                    });

                });


        });

        

      </script>
  @endsection
@endsection

