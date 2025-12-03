@extends('template.master')

@section('content')


<!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <div class="col-12 mb-4 order-0">
      
      <div class="card">
          <div class="card-header">
              <h5 class="float-start">Input Stok Masuk</h5>
              
          </div>
          
          <div class="card-body">

            <form id="form_input_stok_masuk">
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label for="">Tanggal</label>
                    <input type="date" name="tgl" class="form-control" required>
                  </div>
                </div>
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Barang</label>
                    <select name="barang_id" class="form-control select2bs4" id="barang_id" required>
                      <option value="">Pilih Barang</option>
                      @foreach ($barang as $b)
                          <option value="{{ $b->id }}|{{ $b->nm_barang }}|{{ $b->kali_pak }}|{{ $b->kali_kg }}">{{ $b->nm_barang }}</option>
                      @endforeach
                      <input type="hidden" id="kali_pak">
                      <input type="hidden" id="kali_kg">
                    </select>
                  </div>
                </div>
  
                <div class="col-4">
                  <div class="form-group">
                    <label for="">Exprired Date</label>
                    <input type="date" name="tgl_exp" class="form-control" id="tgl_exp"  required>
                  </div>
                </div>

                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Block</label>
                    <select name="block_id" class="form-control" id="block" onchange="getCell(this);" required>
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
                    <select name="cell_id" class="form-control" id="cell" onchange="getRak(this);" required>
                      <option value="">Pilih Cell</option>
                    </select>
                  </div>
                </div>
  
                <div class="mt-2 col-6 col-md-3">
                  <div class="form-group">
                    <label for="">Lantai</label>
                    <select name="rak_id" class="form-control" id="rak" onchange="getPallet(this);" required>
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
  
                <div class="col-4 mt-2">
                  <div class="form-group">
                    <label for="">Jumlah {{ (Session::get('gudang_id') == 3 || Session::get('gudang_id') == 4) ? 'Karung' : 'Box' }}</label>
                    <input type="number" name="debit_box" class="form-control" id="debit_box" required>
                  </div>
                </div>
  
                <div class="mt-2 col-4">
                  <div class="form-group">
                    <label for="">Jumlah PAK</label>
                    <input type="number" name="debit_pak" class="form-control" id="debit_pak" required>
                  </div>
                </div>
  
                <div class="mt-2 col-4">
                  <div class="form-group">
                    <label for="">Jumlah KG</label>
                    <input type="number" name="debit_kg" class="form-control" id="debit_kg" required>
                  </div>
                </div>

                <div class="mt-2 col-4">
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

                <div class="mt-2 col-8">
                  <a href="{{ route('downloadFormat') }}" class="btn btn-sm btn-primary mt-4"><i class='bx bx-export' ></i> Format</a>
                  <button type="button" class="btn btn-sm btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modal_import_stok"><i class='bx bx-import' ></i> Import</button>
                  <button type="button" class="btn btn-sm btn-primary mt-4" id="btn_scan_qrcode" data-bs-toggle="modal" data-bs-target="#modal_scan_qr"><i class='bx bx-qr' ></i> Scan</button>
                </div>

                <div class="mt-2 col-6 mb-2 mt-2"></div>
  
                <div class="mt-2 col-6 mb-2 mt-2">
                  <button type="submit" id="btn_add_cart" class="btn btn-sm btn-primary float-end">
                    <i class='bx bxs-right-arrow-circle'></i> Lanjut
                  </button>
                </div>
                
              </div>
            </form>

          </div>
          
        </div>

        <div class="card">
          <div class="card-header">
              <h5 class="float-start">Batch Stok Masuk</h5>
              
          </div>
          <div class="card-body" id="cart">

          </div>
          <div class="card-footer">
            <button type="button" id="btn_input_data" class="btn btn-sm btn-primary float-end"><i class="bx bxs-save"></i> Input Stok</button>
          </div>
        </div>


    </div>

    <!-- Total Revenue -->

    <!--/ Total Revenue -->
    
  </div>

</div>
<!-- / Content -->

    

  <!-- Modal -->


<form method="POST" action="{{ route('importStokMasuk') }}" enctype="multipart/form-data">
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
          
  
          {{-- <div class="card overflow-hidden mb-4" style="height: 300px">
            <h5 class="card-header">Vertical Scrollbar</h5>
            <div class="card-body" id="vertical-example">
  
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Vestibulum at eros</li>
              </ul>
  
            </div>
          </div> --}}
          
          
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_simpan_penilaian">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>

  <div class="modal fade" id="modal_scan_qr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_scan_qrLabel">Scan QR</h5>
          <button type="button" class="btn-close btn_tutup_scan" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
          <div id="reader" width="600px"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn_tutup_scan" data-bs-dismiss="modal">Close</button>
          {{-- <button type="submit" class="btn btn-primary" id="btn_simpan_penilaian">Simpan</button> --}}
        </div>
      </div>
    </div>
  </div>





  @section('script')

  <script src="{{ asset('js') }}/qrcode.js" type="text/javascript"></script>

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

            const html5QrCode = new Html5Qrcode('reader');

            function getCart() {
              $.get('get-cart-masuk', function (data) {        
                  $('#cart').html(data);
                });
            }

            getCart();

            

            // $(document).on('change', '#block', function() {
            //   var block_id = $(this).val();
            //   $('#cell').html('');
            //   $.get('get-cell/'+block_id, function (data) {        
            //       $('#cell').html(data);
            //     });

            // });

            // $(document).on('change', '#cell', function() {
            //   var cell_id = $(this).val();
            //   $('#rak').html('');
            //   $.get('get-rak/'+cell_id, function (data) {        
            //       $('#rak').html(data);
            //     });

            // });

            // $(document).on('change', '#rak', function() {
            //   var rak_id = $(this).val();
            //   $('#pallet').html('');
            //   $.get('get-pallet/'+rak_id, function (data) {        
            //       $('#pallet').html(data);
            //     });

            // });

            $(document).on('click', '.btn_hapus_cart', function() {
              var id = $(this).attr('cart_id');

              $.get('delete-cart-masuk/'+id, function (data) {        
                  getCart();
                });

            });

            $(document).on('click', '#btn_input_data', function() {

              $.get('save-stok-masuk', function (data) {        
                  getCart();

                  Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Stok Masuk Berhasil Diinput'
                                });

                });

            });

            

            

            $(document).on('submit', '#form_input_stok_masuk', function(event) {
                event.preventDefault();

                    $('#btn_add_cart').attr('disabled',true);
                    $('#btn_add_cart').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('addCartMasuk') }}",
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

                            if (data) {
                              Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Data Berhasil Diinput'
                                });
                            } else {
                              Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'error',
                                title: 'Data Barang Tidak Ada'
                                });
                            }
                                                        
                        },
                        error: function (data) { //jika error tampilkan error pada console
                                    alert('Error:', data);
                                    $("#btn_add_cart").removeAttr("disabled");
                            $('#btn_add_cart').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                                }
                    });

                });

                function closeScanQr(){
                  $('#modal_scan_qr').modal('hide');
                  html5QrCode.stop().then((ignore) => {
                  // QR Code scanning is stopped.
                  }).catch((err) => {
                  // Stop failed, handle it.
                  });
                }


                $(document).on('click', '#btn_scan_qrcode', function() {
                let audio = new Audio("{{ asset('audio/beep.mp3') }}");
                    function onScanSuccess(decodedText, decodedResult) {
                    // handle the scanned code as you like, for example:
                    console.log(`Code matched = ${decodedText}`, decodedResult);
                    }

                    function onScanFailure(error) {
                    // handle scan failure, usually better to ignore and keep scanning.
                    // for example:
                    console.warn(`Code scan error = ${error}`);
                    }

                    // let html5QrcodeScanner = new Html5QrcodeScanner(
                    // "reader",
                    // { fps: 10, qrbox: {width: 250, height: 250} },
                    // /* verbose= */ false);
                    // html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                    
                    const qrCodeSuccessCallback = message => {
                        // console.log(message);

                        $.get('checkBlockWh/'+message, function (cdata) {
                          if(!cdata){
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'error',
                                title: 'Data Warehouse tidak sesuai!'
                                });
                          }else{

                            $.get('get-data-pallet/'+message, function (data) {        
                              console.log(data.block_id);
                              $('#block').val(data.block_id+'|'+data.nm_block);

                              $.get('get-cell/'+data.block_id, function (data_cell) {        
                                $('#cell').html(data_cell);
                                $('#cell').val(data.cell_id+'|'+data.nm_cell);
                              });

                              $.get('get-rak/'+data.cell_id, function (data_rak) {        
                                $('#rak').html(data_rak);
                                $('#rak').val(data.rak_id+'|'+data.nm_rak);
                              });

                              $.get('get-pallet/'+data.rak_id, function (data_pallet) {        
                                $('#pallet').html(data_pallet);
                                $('#pallet').val(data.pallet_id+'|'+data.nm_pallet);
                              });

                              
                            });
                            
                          }
                        });

                        
                        // console.log(2);
                        audio.play();
                        closeScanQr();

                        
                        // $('#check'+message).prop('checked', true);
                    };
                    const config = {fps: 10, qrbox: 250, aspectRatio: 1};
                    html5QrCode.start({facingMode: 'environment'}, config, qrCodeSuccessCallback);
              });

              $(document).on('click', '.btn_tutup_scan', function() {
                html5QrCode.stop().then((ignore) => {
                // QR Code scanning is stopped.
                }).catch((err) => {
                // Stop failed, handle it.
                });
              });


              $(document).on('change', '#barang_id', function() {
                var dt_barang = $(this).val();

                if (dt_barang == '') {
                  $('#kali_pak').val(0);
                  $('#kali_kg').val(0);
                } else {
                  var data_barang = dt_barang.split("|");
                  $('#kali_pak').val(data_barang[2]);
                  $('#kali_kg').val(data_barang[3]);
                }

                
              });


              $(document).on('keyup', '#debit_box', function() {
                var qty = parseFloat($(this).val());

                const kali_pak = parseFloat($('#kali_pak').val());
                const kali_kg = parseFloat($('#kali_kg').val());

                const qty_pak = qty * kali_pak;
                const qty_kg = qty * kali_kg;

                $('#debit_pak').val(qty_pak ? qty_pak : 0);
                $('#debit_kg').val(qty_kg ? qty_kg : 0);
                
              });


        });

        

      </script>
  @endsection
@endsection

