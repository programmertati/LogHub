@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Manajemen Pengguna</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pengguna</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i
                                class="fa fa-plus"></i> Tambah Pengguna</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" id="user_name" name="user_name">
                        <label class="focus-label">User Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="type_role">
                            <option selected disabled>-- Pilih Peran --</option>
                            @foreach ($role_name as $name)
                                <option value="{{ $name->role_type }}">{{ $name->role_type }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Peran</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="type_status">
                            <option selected disabled> -- Pilih Status --</option>
                            @foreach ($status_user as $status)
                                <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <button type="submit" class="btn btn-success btn-block btn_search"> Cari </button>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table" id="userDataList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengguna</th>
                                    <th>ID Pengguna</th>
                                    <th>E-mail</th>
                                    <th>Username</th>
                                    <th>ID Employee</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Peran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Daftar Pengguna Modal -->
        <div id="add_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('data/pengguna/tambah-data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Alamat E-mail </label>
                                    <input class="form-control" type="email" id="" name="email" value="{{ old('email') }}" placeholder="Masukkan E-mail">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" type="text" id="" name="username" value="{{ old('username') }}" placeholder="Masukkan Username" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>ID Employee</label>
                                        <input class="form-control" type="text" id="" name="employee_id" value="{{ old('employee_id') }}" placeholder="Masukkan ID Employee">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="image" name="image" value="photo_defaults.jpg">
                                <input type="hidden" class="form-control" id="" name="tema_aplikasi" value="Terang">
                                <input type="hidden" class="form-control" id="" name="status_online" value="Offline">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Peran</label><br>
                                    <select class="select" name="role_name" id="role_name">
                                        <option selected disabled>-- Pilih Peran --</option>
                                        @foreach ($role_name as $role )
                                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status" id="status">
                                        <option selected disabled>-- Pilih Status --</option>
                                        @foreach ($status_user as $status)
                                        <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="info-status">
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; margin-top: 4px;">
                                            <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                        </svg>
                                        <span class="text-status"><b>Indikator Kata Sandi :</b><br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Lemah : [a-z]<br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Sedang : [a-z] + [angka]<br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Kuat : [a-z] + [angka] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Kata Sandi</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password"  id="passwordInput1" placeholder="Masukkan Kata Sandi">
                                            <div class="input-group-append" style="position: sticky">
                                                <button type="button" id="tampilkanPassword1" class="btn btn-outline-secondary">
                                                    <i id="icon1" class="fa fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="kekuatan-indicator">
                                            <div class="kata-sandi-lemah-after-1"></div>
                                            <div class="kata-sandi-sedang-after-1"></div>
                                            <div id="indicator-kata-sandi-1"></div>
                                            <div class="kata-sandi-lemah-before-1"></div>
                                            <div class="kata-sandi-sedang-before-1"></div>
                                        </div>
                                        <div id="indicator-kata-sandi-tulisan-1"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-status">
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; margin-top: 4px;">
                                            <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                        </svg>
                                        <span class="text-status"><b>Indikator Kata Sandi :</b><br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Lemah : [a-z]<br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Sedang : [a-z] + [angka]<br>
                                            <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Kuat : [a-z] + [angka] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Konfirmasi Kata Sandi</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation" id="passwordInput2" placeholder="Masukkan Konfirmasi Kata Sandi">
                                            <div class="input-group-append">
                                                <button type="button" id="tampilkanPassword2" class="btn btn-outline-secondary">
                                                    <i id="icon2" class="fa fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="kekuatan-indicator">
                                            <div class="kata-sandi-lemah-after-2"></div>
                                            <div class="kata-sandi-sedang-after-2"></div>
                                            <div id="indicator-kata-sandi-2"></div>
                                            <div class="kata-sandi-lemah-before-2"></div>
                                            <div class="kata-sandi-sedang-before-2"></div>
                                        </div>
                                        <div id="indicator-kata-sandi-tulisan-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Daftar Pengguna Modal -->

        <!-- Edit Daftar Pengguna Modal -->
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('data/pengguna/perbaharui') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input class="form-control" type="text" name="name" id="e_name" value="" placeholder="Masukkan Nama Lengkap" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Alamat E-mail</label>
                                    <input class="form-control" type="email" name="email" id="e_email" value="" placeholder="Masukkan E-mail" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" type="text" id="e_username" name="username" value="" placeholder="Masukkan Username" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>ID Employee</label>
                                        <input class="form-control" type="text" id="e_employee_id" name="employee_id" value="" placeholder="Masukkan ID Employee" />
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="image" name="images" value="photo_defaults.jpg">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Peran </label>
                                    <select class="select" name="role_name" id="e_role_name">
                                        @foreach ($role_name as $role )
                                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status" id="e_status">
                                        @foreach ($status_user as $status)
                                        <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Perbaharui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Daftar Pengguna Modal -->

    </div>
    <!-- /Page Wrapper -->

    <style>
        .status_online{
            position: relative;
            width: 10px;
            height: 10px;
            display: inline-block !important;
            border-radius: 50%;
            top: 10px;
            right: 18px;
            background-color: #55ce63;
            border: 1px solid #fff;
        }
        
        .status_offline{
            position: relative;
            width: 10px;
            height: 10px;
            display: inline-block !important;
            border-radius: 50%;
            top: 10px;
            right: 18px;
            background-color: #a7a7a7;
            border: 1px solid #fff;
        }
    </style>

    @section('script')
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                table = $('#userDataList').DataTable({

                    lengthMenu: [
                        [10, 25, 50, 100, 150],
                        [10, 25, 50, 100, 150]
                    ],
                    buttons: [
                        'pageLength',
                    ],
                    "pageLength": 10,
                    order: [
                        [5, 'desc']
                    ],
                    processing: true,
                    serverSide: true,
                    ordering: true,
                    searching: true,
                    ajax: {
                        url: "{{ route('get-users-data') }}",
                        data: function(data) {
                            // read valus for search
                            var user_name = $('#user_name').val();
                            var type_role = $('#type_role').val();
                            var type_status = $('#type_status').val();
                            data.user_name = user_name;
                            data.type_role = type_role;
                            data.type_status = type_status;
                        }
                    },

                    columns: [{
                            data: 'no',
                            name: 'no',
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'user_id',
                            name: 'user_id'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'employee_id',
                            name: 'employee_id'
                        },
                        {
                            data: 'join_date',
                            name: 'join_date',
                        },
                        {
                            data: 'role_name',
                            name: 'role_name',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'action',
                            name: 'action',
                        },
                    ],
                    "language": {
                        "lengthMenu": "Show _MENU_ entries",
                        "zeroRecords": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "search": "Cari:",
                        "searchPlaceholder": "Nama, Peran, Status ",
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next",
                            "first": "<i class='fa-solid fa-backward-fast'></i>",
                            "last": "<i class='fa-solid fa-forward-fast'></i>",
                        }
                    }
                });
                $('.btn_search').on('click', function() {
                    table.draw();
                });
            });
        </script>

        <script>
            $(".theSelect").select2();
        </script>

        <script src="{{ asset('assets/js/lihatkatasandi.js') }}"></script>

        <script src="{{ asset('assets/js/indicatorkatasandi.js') }}"></script>

        <script src="{{ asset('assets/js/usercontrol.js') }}"></script>
        
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

        <script>
            document.getElementById('pageTitle').innerHTML = 'Manajemen Daftar Pengguna - Admin | Trello - PT TATI';
        </script>

    @endsection
@endsection