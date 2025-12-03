@extends('template.master')

@section('content')
    <!-- Content -->



<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

      <div class="col-12 mb-4 order-0">
        
        <div class="card">
            <div class="card-header">
                <h5 class="float-start">Data User</h5>
                <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modal_add_user"><i class='bx bxs-plus-circle'></i> Tambah Data</button>
            </div>
            
            <div class="card-body">

              <div class="table-responsive text-nowrap">
                <table class="table" id="table">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Role</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @foreach ($user as $u)
                    <tr>
                      <td>{{ $u->name }}</td>
                      <td>{{ $u->username }}</td>
                      <td>{{ $u->role->nm_role }}</td>
                      <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_edit_user{{ $u->id}}"><i class='bx bxs-message-square-edit'></i></button></td>
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

  <form method="POST" action="{{ route('addUser') }}">
    @csrf
    <div class="modal fade" id="modal_add_user" tabindex="-1" aria-labelledby="modal_add_userLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_add_userLabel">Tambah User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Username</label>
                      <input type="text" name="username" class="form-control" required>
                  </div>
                </div> 
                
                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Role</label>
                      <select name="role_id" class="form-control" required>
                        <option value="">Pilih Role</option>
                        @foreach ($role as $r)
                        <option value="{{ $r->id }}">{{ $r->nm_role }}</option>
                        @endforeach
                      </select>
                  </div>
                </div> 

                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Password</label>
                      <input type="password" name="password" class="form-control" required>
                  </div>
                </div> 

                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Confirmasi Password</label>
                      <input type="password" name="password_confirmation" class="form-control" required>
                  </div>
                </div>


                <div class="col-12 mt-3"><h4><b>Akses Gudang</b></h4></div>

                @foreach ($gudang as $g)
                          
                    <div class="col-3">
                      <label for="{{ $g->nm_gudang.$g->id }}"><input id="{{ $g->nm_gudang.$g->id }}" type="checkbox" value="{{ $g->id }}" name="gudang_id[]" > {{ $g->nm_gudang }}</label>
                    </div>

                @endforeach


                <div class="col-12 mt-3"><h4><b>Akses Menu</b></h4></div>

                @foreach ($data_menu as $m)
                <div class="col-12 text-center mt-2"><label for=""><dt><u>{{ $m->nm_menu }}</u></dt></label></div>
                
                
                @foreach ($m->submenu as $s)
                    <div class="col-4">
                      <label for="{{ $s->nm_submenu.$s->id }}"><input id="{{ $s->nm_submenu.$s->id }}" type="checkbox" value="{{ $s->id }}" name="submenu_id[]" > {{ $s->nm_submenu }}</label>
                    </div>
                @endforeach
                <hr>
                @endforeach
                
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="btn_edit_user">Save</button>
            </div>
          </div>
        </div>
      </div>
  </form>

  @foreach ($user as $u)
  <form method="POST" action="{{ route('editUser') }}">
    @csrf
    <div class="modal fade" id="modal_edit_user{{ $u->id}}" tabindex="-1" aria-labelledby="modal_add_userLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_add_userLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">

                <input type="hidden" name="id" value="{{ $u->id }}">
                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                    </div>
                </div>

                
                <div class="col-12 mb-2">
                  <div class="form-group">
                      <label for="">Role</label>
                      <select name="role_id" class="form-control" required>
                        @foreach ($role as $r)
                        <option value="{{ $r->id }}" {{ $r->id == $u->role_id ? 'selected' : '' }}>{{ $r->nm_role }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>

                <div class="col-12 mt-3"><h4><b>Akses Gudang</b></h4></div>

                @php
                    $dtGudang = [];
                @endphp
                @foreach ($u->aksesGudang as $ag)
                  @php
                      $dtGudang [] = $ag->gudang_id
                  @endphp
                @endforeach


                
                @foreach ($gudang as $g)
                    <div class="col-4">
                      <label for="{{ $g->nm_gudang.$g->id.$u->id }}"><input id="{{ $g->nm_gudang.$g->id.$u->id }}" type="checkbox" value="{{ $g->id }}" name="gudang_id[]" {{ in_array($g->id, $dtGudang) ? 'checked' : '' }} > {{ $g->nm_gudang }}</label>
                    </div>
                @endforeach


                <div class="col-12 mt-3"><h4><b>Akses Menu</b></h4></div>

                @foreach ($data_menu as $m)
                <div class="col-12 text-center mt-2"><label for=""><dt><u>{{ $m->nm_menu }}</u></dt></label></div>
                @php
                    $dtSubmenu = [];
                @endphp
                @if ($u->aksesMenu)
                  @foreach ($u->aksesMenu as $a)
                    @php
                        $dtSubmenu [] = $a->submenu_id
                    @endphp
                  @endforeach
                @endif

                
                @foreach ($m->submenu as $s)
                    <div class="col-4">
                      <label for="{{ $s->nm_submenu.$s->id.$u->id }}"><input id="{{ $s->nm_submenu.$s->id.$u->id }}" type="checkbox" value="{{ $s->id }}" name="submenu_id[]" {{ in_array($s->id, $dtSubmenu) ? 'checked' : '' }} > {{ $s->nm_submenu }}</label>
                    </div>
                @endforeach
                <hr>
                @endforeach

                
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="btn_edit_user">Save</button>
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

