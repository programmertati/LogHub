@extends('layouts.settings')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Pengaturan Perusahaan</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    {{-- message --}}
                    {!! Toastr::message() !!}

                    <form action="{{ route('pengaturan-perusahaan-save') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="id" value="1">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Perusahaan <span class="text-danger">*</span></label>
                                    @if (!empty($result_pengaturan_perusahaan->company_name))
                                    <input class="form-control" type="text" name="company_name" value="{{ $result_pengaturan_perusahaan->company_name }}">
                                    @else
                                    <input class="form-control" type="text" name="company_name" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Narahubung</label>
                                    @if (!empty($result_pengaturan_perusahaan->contact_person))
                                    <input type="text" class="form-control" name="contact_person" value="{{ $result_pengaturan_perusahaan->contact_person }}">
                                    @else
                                    <input type="text" class="form-control" name="contact_person" value="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    @if (!empty($result_pengaturan_perusahaan->address))
                                    <input type="text" class="form-control" name="address" value="{{ $result_pengaturan_perusahaan->address }}">
                                    @else
                                    <input type="text" class="form-control" name="address" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Negara</label>
                                    @if (!empty($result_pengaturan_perusahaan->country))
                                    <input type="text" class="form-control" name="country" value="{{ $result_pengaturan_perusahaan->country }}">
                                    @else
                                    <input type="text" class="form-control" name="country" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Kota/Kabupaten</label>
                                    @if (!empty($result_pengaturan_perusahaan->city))
                                        <input type="text" class="form-control" name="city" value="{{ $result_pengaturan_perusahaan->city }}">
                                    @else
                                        <input type="text" class="form-control" name="city">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    @if (!empty($result_pengaturan_perusahaan->state_province))
                                        <input type="text" class="form-control" name="state_province" value="{{ $result_pengaturan_perusahaan->state_province }}">
                                    @else
                                        <input type="text" class="form-control" name="state_province">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Kode Pos</label>
                                    @if (!empty($result_pengaturan_perusahaan->postal_code))
                                        <input type="text" class="form-control" name="postal_code" value="{{ $result_pengaturan_perusahaan->postal_code }}">
                                    @else
                                        <input type="text" class="form-control" name="postal_code">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    @if (!empty($result_pengaturan_perusahaan->email))
                                        <input type="email" class="form-control" name="email" value="{{ $result_pengaturan_perusahaan->email }}">
                                    @else
                                        <input type="email" class="form-control" name="email">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    @if (!empty($result_pengaturan_perusahaan->phone_number))
                                        <input type="tel" class="form-control" name="phone_number" value="{{ $result_pengaturan_perusahaan->phone_number }}">
                                    @else
                                        <input type="tel" class="form-control" name="phone_number">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nomor HP</label>
                                    @if (!empty($result_pengaturan_perusahaan->mobile_number))
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+62</span>
                                        </div>
                                        <input type="tel" class="form-control" id="c_mobile_number_value" name="mobile_number" value="{{ $result_pengaturan_perusahaan->mobile_number }}">
                                    </div>
                                    <small id="error_message-1" class="text-danger2"></small>
                                    @else
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+62</span>
                                        </div>
                                        <input type="tel" class="form-control" id="c_mobile_number_value" name="mobile_number" value="">
                                    </div>
                                    <small id="error_message-1" class="text-danger2"></small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fax</label>
                                    @if (!empty($result_pengaturan_perusahaan->fax))
                                    <input type="text" class="form-control" name="fax" value="{{ $result_pengaturan_perusahaan->fax }}">
                                    @else
                                    <input type="text" class="form-control" name="fax" value="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Alamat Website</label>
                                    @if (!empty($result_pengaturan_perusahaan->website_url))
                                    <input type="text" class="form-control" name="website_url" value="{{ $result_pengaturan_perusahaan->website_url }}">
                                    @else
                                    <input type="text" class="form-control" name="website_url" value="">
                                    @endif
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
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

    @section('script')
        <script>
            var toggleBtn = document.getElementById('toggle_btn');
            var logo = document.querySelector('.logo');
            var logoIcon = document.querySelector('.logo i');
            var logoText = document.querySelector('.logo .logo-text');

            toggleBtn.addEventListener('click', function() {
                if (logoIcon.style.display === 'none') {
                    logoIcon.style.display = 'inline-block';
                    logoText.style.display = 'none';
                } else {
                    logoIcon.style.display = 'inline-block';
                    logoText.style.display = 'none';
                }
            });
        </script>

        <script>
            document.getElementById('c_mobile_number_value').addEventListener('input', function(event) {
                var inputValue = event.target.value;
                
                // Jika angka pertama adalah 0
                if (inputValue.charAt(0) === '0') {
                    document.getElementById('error_message-1').innerHTML = 'Gunakan format yang benar. Contoh: 812345678';
                } else {
                    document.getElementById('error_message-1').innerHTML = '';
                }
            });
        </script>
    
    @endsection
@endsection