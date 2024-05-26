@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">

                <!-- Pencaharian Nama Tim -->
                <form id="search-form" action="{{ route('searchTeam2') }}" method="GET">
                    @csrf
                    <div class="row filter-row">
                        <div class="col-sm-6 col-md-9">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="team_name" value="{{ session('__old_team_name') }}" style="--tw-border-opacity: 1; border-color: rgb(0 0 0 / var(--tw-border-opacity)); border-radius: 30px" />
                                <label class="focus-label"><i class="fa-solid fa-cube"></i> Nama Tim</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn-success btn-block btn_search" style="width: 45%; border-radius: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                        </div>
                    </div>
                </form>
                <!-- /Pencaharian Nama Tim -->

                <!-- Fitur Undangan Pengguna -->
                @if (!$invites->isEmpty())
                    <div class="flex flex-col gap-6"><br>
                        <div>
                            <h2 class="ml-6 text-3xl font-bold">Undangan Tertunda</h2><hr>
                        </div>
                        <div class="flex flex-wrap gap-x-8 gap-y-6">
                            @foreach ($invites as $team)
                                <a href="#" data-toggle="modal" data-target="#acceptInvite_{{ $team->id }}">
                                    <div class="flex flex-col transition bg-white border border-gray-200 shadow-sm cursor-pointer select-none w-72 rounded-xl hover:shadow-2xl duration-300">
                                        <div class="h-4p bg-gray-200 rounded-tl-xl rounded-tr-xl"></div>
                                        <div class="flex flex-col gap-1 px-4 py-2">
                                            <h4 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h4>
                                            <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8">
                                                {{ $team->description }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- /Fitur Undangan Pengguna -->
                
                <!-- Tampilan Tim Pengguna -->
                <div class="row align-items-center">
                    <div class="col"><br>
                        <h2 class="ml-6 text-3xl font-bold">Tim Saya</h2><hr>
                    </div>
                </div>
                <!-- /Tampilan Tim Pengguna -->

            </div>

            <!-- /Page Header -->
            {!! Toastr::message() !!}

            
            <div class="flex flex-wrap gap-x-8 gap-y-6">
                <!-- Fitur Buat Tim -->
                {{-- @if ($teams->isEmpty())
                    <a href="#" data-toggle="modal" data-target="#createTeam">
                        <div class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">
                            <i class="fa-solid fa-plus fa-2xl"></i><br>
                            <h4>Buat Tim</h4>
                        </div>
                    </a>
                @endif --}}
                <!-- /Fitur Buat Tim -->

                <!-- Tampilan Tim -->
                @foreach ($teams as $team)
                    <a href="{{ route('viewTeam2', ['team_id' => $team->id]) }}" class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-52 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">
                        <div class="flex-grow w-full p-4">
                            @php $user = $team->users->first(); @endphp
                            @if($user)
                                <img src="{{ asset('assets/images/' . $user->avatar) }}" alt="{{ $user->name }}" name="{{ $user->name }}" loading="lazy" class="avatar-tim">
                            @endif
                        </div>
                        <article class="flex flex-col w-full h-20 gap-1 px-4 py-2 bg-white border-t border-t-gray-200">
                            <h4 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h4>
                            <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">
                                {{ $team->description }} </p>
                        </article>
                    </a>
                @endforeach
                <!-- /Tampilan Tim -->
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Buat Tim Modal -->
        <!-- <div id="createTeam" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Tim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('doCreateTeam') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Nama Tim</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('team_name') is-invalid @enderror" id="team_name" name="team_name" required>
                                @error('team_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Tim</label><span class="text-danger">*</span>
                                <textarea class="form-control @error('team_description') is-invalid @enderror" id="team_description" name="team_description" required></textarea>
                                @error('team_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <label>Latar Belakang Tim</label>
                                {{-- <input type="hidden" id="pattern-field" name="team_pattern" value="{{ $patterns[0] }}"> --}}
                                <input type="hidden" id="pattern-field" name="team_pattern" value="{{ isset($patterns[0]) ? $patterns[0] : 'default_value' }}">
                                <div class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                    @isset($patterns)
                                        @foreach ($patterns as $pattern)
                                            <div onclick="selectPattern('{{ $pattern }}')" class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black" id="pattern-{{ $pattern }}">
                                                <div id="check-{{ $pattern }}" class="flex items-center justify-center w-full h-full {{ $pattern == $patterns[0] ? 'opacity-100' : 'opacity-0' }}">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- /Buat Tim Modal -->

        <!-- Terima Undangan Modal -->
        @if (isset($UserTeams))
            @foreach ($UserTeams as $result_team)
            <div id="acceptInvite_{{ $result_team->team->id }}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Undangan Tim</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('acceptTeamInvite', ['user_id' => Auth::user()->id, 'team_id' => $result_team->team_id]) }}" method="GET">
                                @csrf
                                <div class="flex flex-col gap-4">
                                    <div class="w-full p-4 h-28 bg-pattern-{{ $result_team->team->pattern }}" id="header-overlay">
                                        <div class="relative flex items-center justify-center w-20 overflow-hidden bg-black border-4 border-white rounded-full aspect-square">
                                            <img class="absolute top-0 left-0 z-40 object-fill w-full h-full" src="{{ URL::to('/assets/images/' . $result_team->user->avatar) }}" loading="lazy">
                                        </div><hr><hr>
                                    </div><br>
                                    <div class="flex flex-col">
                                        <p>Anda diundang untuk bergabung dengan Tim <span class="font-bold">{{ $result_team->team->name }}</span></p>
                                        <p><span class="font-bold">Keterangan: </span><span>{{ $result_team->team->description }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <p>Hormat Kami, <span class="font-bold">{{ $result_team->user->name }}</span></p>
                                        <img class="avatar-undangan" src="{{ URL::to('/assets/images/' . $result_team->user->avatar) }}" loading="lazy">
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Terima</button>
                                    </div>
                            </form>
                            <form action="{{ route('rejectTeamInvite', ['user_id' => Auth::user()->id, 'team_id' => $result_team->team_id]) }}" method="GET">
                                @csrf
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Tolak</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        <!-- /Terima Undangan Modal -->
        
    </div>
    <!-- /Page Wrapper -->

    @section('script')
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

        <script>
            history.pushState({}, "", '/user/tim');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Tim - User | Trello - PT TATI';
        </script>
    @endsection
@endsection