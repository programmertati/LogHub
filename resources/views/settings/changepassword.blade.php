@extends('layouts.master')
@section('content')
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Ubah Kata Sandi</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    {{-- message --}}
                    {!! Toastr::message() !!}
            
                    <form method="POST" action="{{ route('change/password/db') }}">
                        @csrf
                        <div class="form-group">
                            <div class="info-status1">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                </svg>
                                <span class="text-status1"><b>Indikator Kata Sandi :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Lemah : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Sedang : [a-z] + [angka]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Kuat : [a-z] + [angka] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>Kata Sandi Lama</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="passwordInput1" name="current_password" value="{{ old('current_password') }}" placeholder="Masukkan kata sandi lama">
                                <div class="input-group-append" style="position: sticky">
                                    <button type="button" id="tampilkanPassword1" class="btn btn-outline-secondary">
                                        <i id="icon1" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="info-status2">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                </svg>
                                <span class="text-status2"><b>Indikator Kata Sandi :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Lemah : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Sedang : [a-z] + [angka]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Kuat : [a-z] + [angka] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="passwordInput2" name="new_password" placeholder="Masukkan kata sandi baru">
                                <div class="input-group-append" style="position: sticky">
                                    <button type="button" id="tampilkanPassword2" class="btn btn-outline-secondary">
                                        <i id="icon2" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                        <div class="form-group">
                            <div class="info-status3">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                </svg>
                                <span class="text-status3"><b>Indikator Kata Sandi :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Lemah : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Sedang : [a-z] + [angka]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Sandi Kuat : [a-z] + [angka] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" id="passwordInput3" name="new_confirm_password" placeholder="Konfirmasi kata sandi baru">
                                <div class="input-group-append">
                                    <button type="button" id="tampilkanPassword3" class="btn btn-outline-secondary">
                                        <i id="icon3" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('new_confirm_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="kekuatan-indicator">
                                <div class="kata-sandi-lemah-after-3"></div>
                                <div class="kata-sandi-sedang-after-3"></div>
                                <div id="indicator-kata-sandi-3"></div>
                                <div class="kata-sandi-lemah-before-3"></div>
                                <div class="kata-sandi-sedang-before-3"></div>
                            </div>
                            <div id="indicator-kata-sandi-tulisan-3"></div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Ubah Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    @section('script')
        <script src="{{ asset('assets/js/lihatkatasandi.js') }}"></script>

        <script src="{{ asset('assets/js/indicatorkatasandi.js') }}"></script>

        <script>
            @if (Auth::user()->role_name == 'Admin') 
                document.getElementById('pageTitle').innerHTML = 'Pengaturan Ubah Kata Sandi - Admin | Trello - PT TATI';
            @endif
            @if (Auth::user()->role_name == 'User') 
                document.getElementById('pageTitle').innerHTML = 'Pengaturan Ubah Kata Sandi - User | Trello - PT TATI';
            @endif
        </script>
        
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

    @endsection
@endsection