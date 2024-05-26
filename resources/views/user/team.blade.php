@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">

                <!-- Tampilan Foto & Nama Tim -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('showTeams2') }}"><i class="fa-solid fa-house fa-fade fa-2xl" style="position: relative; bottom: 10px;"></i></a>&nbsp;
                    <p class="text-xl font-bold">Tim:</p>
                    <p class="text-xl">{{ $team->name }}</p>
                    <p class="text-xl font-bold">Keterangan:</p>
                    <p class="text-xl">{{ $team->description }}</p>
                </div>

                <div class="w-full h-24 flex items-center p-6 bg-pattern-{{ $team->pattern }} border-b border-gray-200">
                    <div class="w-20 h-20">
                        @if (Auth::user()->id == $owner->id)
                            <img class="avatar-papan" src="{{ URL::to('/assets/images/' . $owner->avatar) }}" loading="lazy">
                        @endif
                    </div>
                </div>
                <!-- /Tampilan Foto & Nama Tim -->

            </div>
            <!-- /Page Header -->

            <div class="flex gap-8">
                <div class="flex flex-col gap-8 flex-1 w-full">

                    <!-- /Tampilan Papan dan Pencaharian Nama Papan -->
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-2 pl-1">
                                <h2 class="text-2xl font-bold">Papan</h2>
                            </div>
                            <form action="{{ route('searchBoard2', ['team_id' => $team->id]) }}" id="search-form" method="GET">
                                @csrf
                                <div class="row filter-row">
                                    <div class="col-sm-6 col-md-9">
                                        <div class="form-group form-focus">
                                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                            <input type="text" class="form-control floating" name="board_name" value="{{ session('__old_board_name') }}" style="--tw-border-opacity: 1; border-color: rgb(0 0 0 / var(--tw-border-opacity)); border-radius: 30px">
                                            <label class="focus-label"><i class="fa-solid fa-table-columns"></i> Nama Papan</label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <button type="submit" class="btn btn-success btn-block btn_search" style="width: 45%; border-radius: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Tampilan Papan dan Pencaharian Nama Papan -->
                    
                    <!-- Tampilan Papan -->
                    <div class="tampilan-papan">
                        @isset($boards)
                            @foreach ($boards as $board)
                                <a href="{{ route('board2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl w-72 hover:shadow-2xl bg-grad-{{ $board->pattern }}" style="margin-bottom: 15px;">
                                    <div class="flex-grow w-full p-4" style="padding: 3rem !important;"></div>
                                    <article class="flex flex-col w-full gap-1 px-4 py-2 bg-white border-t rounded-b-lg border-t-gray-200">
                                        <h4 class="overflow-hidden font-semibold truncate text-bold" style="font-size: 15px">{{ $board->name }}</h4>
                                    </article>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <!-- /Tampilan Papan -->

                    {{-- <div class="flex flex-wrap gap-x-8 gap-y-6">

                        <!-- Fitur Buat Papan -->
                        @isset($boards)
                            @if ($boards->isEmpty() && Auth::user()->id == $owner->id)
                                <a href="#" data-toggle="modal" data-target="#createBoard">
                                    <div class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl" style="background-color: rgb(243 244 246 / 1) !important; @foreach($result_tema as $sql_mode => $mode_tema)@if($mode_tema->tema_aplikasi == 'Gelap')background-color: #292D3E !important; @endif @endforeach">
                                        <i class="fa-solid fa-plus fa-2xl"></i><br>
                                        <h4>Buat Papan</h4>
                                    </div>
                                </a>
                            @endif
                        @endif
                        <!-- /Fitur Buat Papan -->

                    </div> --}}
                </div>

                {!! Toastr::message() !!}

                <!-- Tampilan Papan dan Anggota Tim -->
                <div class="flex flex-col max-h-96 gap-4 w-96">
                    <h2 class="ml-4 text-2xl font-bold">Anggota</h2>
                    <div class="flex flex-col flex-grow w-full gap-2 p-4 overflow-x-hidden overflow-y-auto border-2 border-gray-200 rounded-xl">
                        <div class="flex items-center gap-4">
                            <a href="{{ URL::to('/assets/images/' . $owner->avatar) }}" data-fancybox="foto-profil">
                                <img src="{{ URL::to('/assets/images/' . $owner->avatar) }}" loading="lazy" class="!flex-shrink-0 !flex-grow-0 w-12 avatar-undangan">
                            </a>
                            <p class="flex-grow truncate">{{ $owner->name }}</p>
                            <i class="fa-solid fa-crown fa-lg w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0"></i>
                        </div>
                        @foreach ($members as $member)
                            <div class="flex items-center gap-4">
                                <a href="{{ URL::to('/assets/images/' . $member->avatar) }}" data-fancybox="foto-profil">
                                    <img src="{{ URL::to('/assets/images/' . $member->avatar) }}" loading="lazy" class="!flex-shrink-0 !flex-grow-0 w-12 avatar-undangan">
                                </a>
                                <p class="w-40 truncate">{{ $member->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /Tampilan Papan dan Anggota Tim -->

            </div>
            
        </div>
        <!-- /Page Content -->

        <!-- Keluar dari Tim Modal -->
        <div id="leaveTeam" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Keluar dari Tim "{{ $team->name }}"?</h3>
                            <p>Apakah Anda yakin ingin keluar dari tim ini?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('doLeaveTeam', ['team_id' => $team->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Keluar dari Tim</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Keluar dari Tim Modal -->

    </div>
    <!-- /Page Wrapper -->

    <style> 
        .p-4 {
            padding: 1rem !important;
        }
        .rounded-xl {
            --tw-border-opacity: 1;
            background-color: rgb(229 231 235 / var(--tw-border-opacity)) !important;
        }
        .text-xl {
            font-size: 1.5rem !important;
            line-height: 0.5rem !important;
        }

        @foreach($result_tema as $sql_mode => $mode_tema)
            @if ($mode_tema->tema_aplikasi == 'Gelap')
                .rounded-xl {background-color: {{ $mode_tema->warna_mode }} !important;}
            @endif
        @endforeach
    </style>

    @section('script')
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

        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

        <script>
            history.pushState({}, "", '/user/tim/lihat-papan/{{ $team->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Papan Tim - User | Trello - PT TATI';
        </script>
    @endsection
@endsection