@extends('template.master')

@section('content')


<!-- Content -->

<style>
  .select2-container {
    z-index:100000;
}

/* .scrollme {
    overflow-x: auto;
    height:600px;
    overflow-y: scroll;
} */

</style>



<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <div class="col-12 mb-4 order-0">
      
      <div class="card">
          <div class="card-header">
              <h5 class="float-start">Input Stok keluar</h5>

              <button type="button" class="btn btn-sm btn-primary float-end ml-2" data-bs-toggle="modal" data-bs-target="#modal_cari_barang"><i class='bx bx-search-alt'></i> Cari Barang</button>
              <button type="button" class="btn btn-sm btn-primary float-end ml-2" id="btn_scan_qrcode" data-bs-toggle="modal" data-bs-target="#modal_scan_qr"><i class='bx bx-qr' ></i> Scan</button>
              
          </div>
          
          <div class="card-body">

            

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


<form id="form_add_cart_keluar">
  <div class="modal fade" id="modal_cari_barang" tabindex="-1" aria-labelledby="modal_cari_barangLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_cari_barangLabel">Cari Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
            <div class="row mb-3">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <select name="barang_id" class="form-control select2bs4" id="select_barang" required>
                          <option value="" >Pilih Barang</option>
                          @foreach ($barang as $b)
                              <option value="{{ $b->id }}" >{{ $b->nm_barang }}</option>
                          @endforeach
                        </select>
                      </div>
                </div>
            </div>

            <div id="table_barang"></div>
          
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_add_cart"><i class="bx bxs-right-arrow-circle"></i> Lanjut</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form id="form_add_cart_keluar_qr">
  <div class="modal fade" id="modal_cari_barang_qr" tabindex="-1" aria-labelledby="modal_cari_barang_qrLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_cari_barang_qrLabel">Cari Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        

            <div id="table_barang_qr"></div>
          
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_add_cart_qr"><i class="bx bxs-right-arrow-circle"></i> Lanjut</button>
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

            $(document).on('change', '.terima', function() {
                if ($(this).is(':checked')) {

                  var checker_id = $(this).attr("checker_id");
                    
                    $(".input_barang_"+checker_id).removeAttr("disabled");
                    

                } else {
                  
                  var checker_id = $(this).attr("checker_id");
                  $(".input_barang_"+checker_id).attr('disabled',true);

                }
              

            });

            function getCart() {
              $.get('get-cart-keluar', function (data) {        
                  $('#cart').html(data);
                });
            }

            getCart();

            $(document).on('change', '#select_barang', function() {
              var barang_id = $(this).val();

              $("#table_barang").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
              
              $.get('get-detail-barang/'+barang_id, function (data) {        
                  $('#table_barang').html(data);
                });

            });

            
            $(document).on('click', '.btn_hapus_cart', function() {
              var id = $(this).attr('cart_id');

              $.get('delete-cart-keluar/'+id, function (data) {        
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

            $(document).on('submit', '#form_add_cart_keluar', function(event) {
                event.preventDefault();

                    $('#btn_add_cart').attr('disabled',true);
                    $('#btn_add_cart').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('addCartKeluar') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                            
                          $("#btn_add_cart").removeAttr("disabled");
                            $('#btn_add_cart').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                            // getCart();
 
                            
                            $('.select2bs4').val('');
                            $('.select2bs4').select2({theme: 'bootstrap4', tags: true,}).trigger('change');
                            $('#form_add_cart_keluar').trigger("reset");

                            $("#table_barang").html('');

                            $('select:not(.normal)').each(function () {
                                $(this).select2({
                                    dropdownParent: $(this).parent()
                                });
                            });

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
                                    $("#btn_add_cart").removeAttr("disabled");
                            $('#btn_add_cart').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                                }
                    });

                });

                $(document).on('submit', '#form_add_cart_keluar_qr', function(event) {
                event.preventDefault();

                    $('#btn_add_cart_qr').attr('disabled',true);
                    $('#btn_add_cart_qr').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('addCartKeluar') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                            
                          $("#btn_add_cart_qr").removeAttr("disabled");
                            $('#btn_add_cart_qr').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                            // getCart();
 
                            
                            // $('.select2bs4').val('');
                            // $('.select2bs4').select2({theme: 'bootstrap4', tags: true,}).trigger('change');
                            $('#form_add_cart_keluar_qr').trigger("reset");

                            $("#table_barang").html('');

                            $("#modal_cari_barang_qr").modal('hide');

                            // $('select:not(.normal)').each(function () {
                            //     $(this).select2({
                            //         dropdownParent: $(this).parent()
                            //     });
                            // });

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
                                    $("#btn_add_cart_qr").removeAttr("disabled");
                            $('#btn_add_cart_qr').html('<i class="bx bxs-right-arrow-circle"></i> Lanjut'); //tombol
                                }
                    });

                });


              $(document).on('submit', '#form_save_stok_keluar', function(event) {
                event.preventDefault();

                    $('#btn_input_data').attr('disabled',true);
                    $('#btn_input_data').html('Loading..');

                    
                    $.ajax({
                        url:"{{ route('saveStokKeluar') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                            
                          $("#btn_input_data").removeAttr("disabled");
                            $('#btn_input_data').html('<i class="bx bxs-save"></i> Input Stok'); //tombol
                            // getCart();
 
                            
                            
                            $('#form_save_stok_keluar').trigger("reset");

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

                $('select:not(.normal)').each(function () {
                    $(this).select2({
                        dropdownParent: $(this).parent()
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
                        $("#table_barang_qr").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
                        $.get('getDetailBarangQR/'+message, function (cdata) {
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

                            $('#modal_cari_barang_qr').modal('show');
                            $("#table_barang_qr").html(cdata);
                            
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

              $(document).on('keyup', '.input_box', function() {
                const qty = parseFloat($(this).val());

                const urutan = $(this).attr('urutan');


                const kali_pak = parseFloat($(this).attr('kali_pak'));
                const kali_kg = parseFloat($(this).attr('kali_kg'));

                const qty_pak = qty * kali_pak;
                const qty_kg = qty * kali_kg;

                $('#kredit_pak'+urutan).val(qty_pak ? qty_pak : 0);
                $('#kredit_kg'+urutan).val(qty_kg ? qty_kg : 0);
                
              });


        });

        

      </script>
  @endsection
@endsection

