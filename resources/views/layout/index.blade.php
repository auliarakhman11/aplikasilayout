@extends('template.master')

@section('content')
    <!-- Content -->



    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">

            <div class="col-8 mb-4 order-0">

                <div class="card">
                    <div class="card-header">
                        <h5 class="float-start">Data Layout</h5>
                        <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#modal_tambah"><i class='bx bxs-plus-circle'></i> Tambah Data</button>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive text-nowrap">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Block</th>
                                        {{-- <th>Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($block as $index => $m)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $m->nm_block }}</td>
                                            {{-- <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_edit_mitra{{ $m->id}}"><i class='bx bxs-message-square-edit'></i></button></td> --}}
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

    <form method="POST" action="{{ route('addLayout') }}">
        @csrf
        <div class="modal fade" id="modal_tambah" tabindex="-1" aria-labelledby="modal_tambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_tambahLabel">Tambah Layout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="">Block</label>
                                    <input type="text" name="nm_block" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="">Jumlah FG</label>
                                    <input type="number" name="jml_fg" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="">Jumlah Lantai</label>
                                    <input type="number" name="jml_lantai" class="form-control" required>
                                </div>
                            </div>

                            {{-- <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="">Jumlah Pallet</label>
                                    <input type="number" name="jml_pallet" class="form-control" required>
                                </div>
                            </div> --}}



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

    {{-- @foreach ($block as $m)
        <form method="POST" action="{{ route('editMitra') }}">
            @csrf
            <div class="modal fade" id="modal_edit_mitra{{ $m->id }}" tabindex="-1"
                aria-labelledby="modal_tambahLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_tambahLabel">Edit Mitra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <input type="hidden" name="id" value="{{ $m->id }}">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="">Nama Mitra</label>
                                        <input type="text" name="nm_mitra" value="{{ $m->nm_mitra }}"
                                            class="form-control" required>
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
    @endforeach --}}


@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $(document).ready(function() {


            <?php if(session('success')): ?>
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'success',
                title: '<?= session('success') ?>'
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
