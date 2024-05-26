@extends('layouts.master')
@section('content')

    <?php
    $hour = date('G');
    $minute = date('i');
    $second = date('s');
    $msg = ' Today is ' . date('l, M. d, Y.');
    
    if ($hour >= 0 && $hour <= 11 && $minute <= 59 && $second <= 59) {
        $greet = 'Selamat Pagi,';
    } elseif ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
        $greet = 'Selamat Siang,';
    } elseif ($hour >= 16 && $hour <= 17 && $minute <= 59 && $second <= 59) {
        $greet = 'Selamat Sore,';
    } elseif ($hour >= 18 && $hour <= 23 && $minute <= 59 && $second <= 59) {
        $greet = 'Selamat Malam,';
    }
    ?>

    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">{{ $greet }} {{ Session::get('name') }} &#128522;</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard <b>Administrator</b></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="container">
                <div class="action-buttons">
                    <button onclick="slideContent('left')" id="moveLeftButton" class="action-button action-button-primary"><span class="arrow-kiri"></span></button>
                    <button onclick="slideContent('right')" id="moveRightButton" class="action-button action-button-primary"><span class="arrow-kanan"></span></button>
                </div>
                <div id="slide-card-atas">
                    <a href="#" class="slide-card-atass">
                        <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                        <div>
                            <h2 role="presentation">{{ $dataPengguna }}</h2><br>
                            <div class="currency">
                                Jumlah Seluruh Pengguna
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="slide-card-atass">
                        <span class="dash-widget-icon"><i class="fa fa-user-circle"></i></span>
                        <div>
                            <h2 role="presentation">{{ $dataOnline }}</h2><br>
                            <div class="currency">
                                Pengguna Online
                            </div>
                        </div>
                    </a>

                    <a href="#" class="slide-card-atass">
                        <span class="dash-widget-icon"><i class="fa fa-user-circle-o"></i></span>
                        <div>
                            <h2 role="presentation">{{ $dataOffline }}</h2><br>
                            <div class="currency">
                                Pengguna Offline
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}
        </div>
    </div>
    <!-- /Page Content -->
    </div>
    @section('script')
        <script src="{{ asset('assets/js/dashboard.js') }}"></script>
        <script src="{{ asset('assets/js/slide-card.js') }}"></script>
        
    @endsection
@endsection