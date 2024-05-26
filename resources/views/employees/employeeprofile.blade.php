@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Profil</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="card mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        <a href="{{ URL::to('/assets/images/' . $users->avatar) }}" data-fancybox="foto-profil">
                                            <img alt="{{ $users->name }}" src="{{ URL::to('/assets/images/' . $users->avatar) }}" loading="lazy">
                                        </a>
                                    </div>
                                </div>
                                <div class="profile-basic pro-overview tab-pane fade show active">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="pro-edit">
                                                <a data-target="#foto_profile" data-toggle="modal" class="edit-icon-avatar" href="#">
                                                    <i class="fa-solid fa-camera-retro fa-lg"></i>
                                                </a>
                                            </div>
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0">{{ $users->name }}</h3>
                                                <div class="staff-id">ID Akun : {{ $users->user_id }}</div>
                                                <div class="small doj text-muted">Tanggal Bergabung : {{ \Carbon\Carbon::parse(Session::get('join_date'))->translatedFormat('l, j F Y || h:i A') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Nama Lengkap</div>
                                                    <div class="text">
                                                        @if (!empty($users->name))
                                                            <a>{{ $users->name }}</a>
                                                        @else
                                                            <a>N/A</a>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">E-mail</div>
                                                    <div class="text">
                                                        @if (!empty($users->email))
                                                            <a href="mailto:{{ $users->email }}" class="text">{{ $users->email }}</a>
                                                        @else
                                                            <a>N/A</a>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Username</div>
                                                    <div class="text">
                                                        @if (!empty($users->username))
                                                            <a>{{ $users->username }}</a>
                                                        @else
                                                            <a>N/A</a>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">ID Employee</div>
                                                    <div class="text">
                                                        @if (!empty($users->employee_id))
                                                            <a>{{ $users->employee_id }}</a>
                                                        @else
                                                            <a>N/A</a>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Tanggal Lahir</div>
                                                    <div class="text">
                                                        @if (!empty($users->tgl_lahir))<a>{{ date('d F Y', strtotime($users->tgl_lahir)) }}</a>
                                                        @else
                                                            <a>N/A</a>
                                                        @endif
                                                    </div>
                                                </li><br>
                                                <a href='#' class='btn btn-outline-danger' data-toggle='modal' data-target='#hapus_pengguna'><i class="fa-solid fa-trash fa-lg"></i> Hapus Akun</a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="pro-edit">
                                    <a data-target="#data_pengguna" data-toggle="modal" class="edit-icon" href="#">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /Page Content -->
    
        <!-- Update Data Pengguna Modal -->
        <div id="data_pengguna" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Data Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile/perbaharui/data-pengguna2') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $users->user_id }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $users->name }}" placeholder="Masukkan nama">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $users->email }}" placeholder="Masukkan email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="{{ $users->username }}" placeholder="Masukkan username">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ID Employee</label>
                                                <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ $users->employee_id }}" placeholder="Masukkan ID Employee">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Lahir</label>
                                                <div class="cal-icon">
                                                    @if (!empty($users))
                                                        <input class="form-control datetimepicker" type="text" id="birthDate" name="birthDate" value="{{ $users->tgl_lahir }}">
                                                        <small class="text-danger">Example : 10-10-2013</small>
                                                    @else
                                                        <input class="form-control datetimepicker" type="text" id="birthDate" name="birthDate">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                @if (!empty($users))
                                                    <input type="hidden" class="form-control" id="avatar" name="avatar" value="{{ $users->avatar }}">
                                                @else
                                                    <input type="hidden" class="form-control" id="avatar" name="avatar">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
        <!-- /Update Data Pengguna Modal -->

        <!-- Hapus Akun Modal -->
        <div id="hapus_pengguna" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Hapus Akun</h3>
                            <p>Apakah anda yakin ingin menghapus akun ini?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('data/pengguna/hapus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Hapus</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Hapus Akun Modal -->

        <!-- Update Foto Profil Modal -->
        <div id="foto_profile" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Foto Profil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile/perbaharui/foto') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="profile-img-wrap edit-img">
                                        <img class="inline-block" id="imagePreview" src="{{ URL::to('/assets/images/' . $users->avatar) }}" alt="{{ $users->name }}" loading="lazy">
                                        <div class="fileupload btn">
                                            <span class="btn-text">Unggah</span>
                                            <input class="upload" type="file" id="image" name="images" onchange="previewImage(event)">
                                            @if (!empty($users))
                                                <input type="hidden" name="hidden_image" id="e_image" value="{{ $users->avatar }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" id="name" name="name" value="{{ $users->name }}">
                                                <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $users->user_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ $users->email }}">
                                            </div>
                                        </div>
                                    </div>
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
        <!-- /Update Foto Profil Modal -->

    </div>
    <!-- /Page Wrapper -->

    @section('script')
        <script>
            function previewImage(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('imagePreview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>

        <!-- FancyBox Foto Profil -->
        <script>
            $(document).ready(function() {
                $('[data-fancybox="foto-profil"]').fancybox({
                });
            });
        </script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
        <!-- /FancyBox Foto Profil -->

        <script>
		    $(".theSelect").select2();
	    </script>

        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

    @endsection
@endsection