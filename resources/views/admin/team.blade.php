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
                    <a href="{{ route('showTeams') }}"><i class="fa-solid fa-house fa-fade fa-2xl" style="position: relative; bottom: 10px;"></i></a>&nbsp;
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
                            <form action="{{ route('searchBoard', ['team_id' => $team->id]) }}" id="search-form" method="GET">
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
                                <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl w-72 hover:shadow-2xl bg-grad-{{ $board->pattern }}" style="margin-bottom: 15px;">
                                    <div class="flex-grow w-full p-4" style="padding: 3rem !important;"></div>
                                    <article class="flex flex-col w-full gap-1 px-4 py-2 bg-white border-t rounded-b-lg border-t-gray-200">
                                        <h4 class="overflow-hidden font-semibold truncate text-bold" style="font-size: 15px">{{ $board->name }}</h4>
                                    </article>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <!-- /Tampilan Papan -->

                    <div class="flex flex-wrap gap-x-8 gap-y-6">

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

                    </div>
                </div>

                {!! Toastr::message() !!}

                <!-- Tampilan Papan dan Anggota Tim -->
                <div class="flex flex-col max-h-96 gap-4 w-96">
                    <h2 class="ml-4 text-2xl font-bold">Anggota</h2>
                    <div class="isian-anggota  flex flex-col flex-grow w-full gap-2 p-4 border-2 border-gray-200 rounded-xl">
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

        <!-- Perbaharui Tim Modal -->
        <div id="updateTeam" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Perbaharui Tim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('doTeamDataUpdate', ['team_id' => $team->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                            <div class="form-group">
                                <label>Nama Tim</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('team_name') is-invalid @enderror" id="team_name" name="team_name" value="{{ $team->name }}" required>
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
                                <small class="text-danger">*Silahkan pilih kembali (Latar Belakang Tim) apabila melakukan pembaharuan.</small>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Perbaharui Tim Modal -->

        <!-- Anggota Tim Modal -->
        <div id="manageMember" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kelola Anggota Tim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="member-name">Anggota Tim</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="member-name" name="member-name" placeholder="Masukkan email anggota">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                            </div><br>
                            <select class="theSelect" id="member-name2" name="member-name" style="width: 100% !important">
                                <option selected disabled>-- Pilih Anggota Tim --</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->email }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="border border-dark rounded overflow-auto" style="max-height: 20rem;">
                            <div class="d-flex flex-wrap p-2">
                                @foreach ($members as $member)
                                    <div data-role="member-card" data-email="{{ $member->email }}" data-name="{{ $member->name }}" class="card m-2 p-2 text-center" style="width: 11.5rem; --tw-border-opacity: 1; border-color: rgb(209 213 219 / var(--tw-border-opacity)); cursor: pointer;">
                                        <img class="card-img-top rounded-circle mx-auto" src="{{ URL::to('/assets/images/' . $member->avatar) }}" style="width: 3rem;" loading="lazy">
                                        <div class="card-body p-1">
                                            <p class="card-text font-weight-bold">{{ $member->name }}</p>
                                            <p class="card-text">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="button" class="btn btn-primary submit-btn" id="save-btn">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Anggota Tim Modal -->

        <!-- Undangan Anggota Modal -->
        <div id="inviteMember" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Undang Anggota Tim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="input-text-inv-email" class="form-label">E-mail</label>
                            <div class="input-group gap-2">
                                <input type="email" class="form-control" id="inv-email" placeholder="Masukkan email anggota">
                                <button class="btn btn-primary" type="button" id="add-btn">
                                    <i class="fa-solid fa-user-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group gap-2" style="flex-wrap: nowrap;">
                                <select class="theSelect" id="inv-email2" style="width: 100% !important">
                                    <option selected disabled>-- Pilih Anggota Tim --</option>
                                    @foreach ($UserTeams as $result_team)
                                        <option value="{{ $result_team->email }}">{{ $result_team->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="button" id="add-btn2">
                                    <i class="fa-solid fa-user-plus"></i>
                                </button>
                            </div>
                        </div>
                        <form method="POST" id="invite-members-form" action="{{ route('doInviteMembers', ['team_id' => $team->id]) }}">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                            <div class="border border-2 border-dark p-2 rounded" style="max-height: 300px; overflow-y: auto;" id="invite-container">
                                
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn" id="save-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Undangan Anggota Modal -->

        <!-- Buat Papan Modal -->
        <div id="createBoard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Papan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('createBoard', ['team_id' => $team->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                            <div class="form-group">
                                <label>Nama Papan</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('board_name') is-invalid @enderror" id="board_name" name="board_name" required>
                                @error('board_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <label>Warna Papan</label>
                                <input type="hidden" id="background-field" name="board_pattern" value="{{ isset($backgrounds[0]) ? $backgrounds[0] : 'default_value' }}">
                                <div class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                    @isset($backgrounds)
                                        @foreach ($backgrounds as $background)
                                            <div onclick="selectPattern2('{{ $background }}')" class="{{ $background == $backgrounds[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $background }} hover:border-black" id="background-{{ $background }}">
                                                <div id="check-{{ $background }}" class="flex items-center justify-center w-full h-full {{ $background == $backgrounds[0] ? 'opacity-100' : 'opacity-0' }}">
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
        </div>
        <!-- /Buat Papan Modal -->
        
        <!-- Hapus Papan Modal -->
        <div id="deleteTeam" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Hapus Tim "{{ $team->name }}"?</h3>
                            <p>Apakah Anda yakin ingin menghapus tim ini?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('doDeleteTeam', ['team_id' => $team->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
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
        <!-- /Hapus Papan Modal -->

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
                .border-dark {border-color: white !important;}
            @endif
        @endforeach
    </style>

    @section('script')
        <script>
            function selectPattern(pattern) {
                var selectedPattern = document.querySelector('#pattern-field');
                selectedPattern.value = pattern;
        
                var allPatterns = document.querySelectorAll('.h-full');
                allPatterns.forEach(function(item) {
                    item.classList.remove('border-black');
                });
        
                var selectedPatternElement = document.getElementById('pattern-' + pattern);
                selectedPatternElement.classList.add('border-black');
        
                var allChecks = document.querySelectorAll('.fa-circle-check');
                allChecks.forEach(function(item) {
                    item.parentElement.style.opacity = '0';
                });
        
                var selectedCheck = document.getElementById('check-' + pattern);
                selectedCheck.style.opacity = '100';
            }

            function selectPattern2(background) {
                var selectedPattern = document.querySelector('#background-field');
                selectedPattern.value = background;
        
                var allPatterns = document.querySelectorAll('.h-full');
                allPatterns.forEach(function(item) {
                    item.classList.remove('border-black');
                });
        
                var selectedPatternElement = document.getElementById('background-' + background);
                selectedPatternElement.classList.add('border-black');
        
                var allChecks = document.querySelectorAll('.fa-circle-check');
                allChecks.forEach(function(item) {
                    item.parentElement.style.opacity = '0';
                });
        
                var selectedCheck = document.getElementById('check-' + background);
                selectedCheck.style.opacity = '100';
            }
        </script>

        <script>
            $(document).ready(function() {
                let selectedMembers = [];
                $('#manageMember').on('shown.bs.modal', function () {
                    $('#member-name').trigger('focus');
                    $('#member-name2').trigger('focus');
                });

                $('#save-btn').on('click', function() {
                    $('[data-role="member-card"].selected').each(function() {
                        selectedMembers.push($(this).data('email'));
                    });
            
                    if (selectedMembers.length > 0) {
                        $.ajax({
                            url: '{{ route('deleteTeamMember', ['team_id' => $team->id]) }}',
                            method: 'POST',
                            data: { emails: selectedMembers, _token:"{{ csrf_token() }}" },
                            success: function(response) {
                                $('#manageMember').modal('hide');
                                toastr.success('Berhasil menghapus anggota tim Anda!');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(response) {
                                toastr.error('Terjadi kesalahan saat menghapus anggota tim Anda!');
                            }
                        });
                    } else {
                        toastr.error('Tidak ada anggota tim yang Anda pilih!');
                    }
                });
            
                $('[data-role="member-card"]').on('click', function() {
                    $(this).toggleClass('selected border-info bg-red-200');
                });
            
                $('#member-name, #member-name2').on('change', function() {
                    let email = $(this).val().trim();
                    if (email !== "") {
                        let emailExists = false;
                        $('[data-role="member-card"]').each(function() {
                            if ($(this).data('email') === email) {
                                emailExists = true;
                                return false;
                            }
                        });
                        if (!emailExists) {
                            toastr.error('Email yang Anda masukkan tidak tersedia, silahkan memasukkannya kembali!');
                            return;
                        }
                        if (!selectedMembers.includes(email)) {
                            selectedMembers.push(email);
                            $(this).val('');
                        }
                    } else {
                        toastr.error('Email tidak boleh kosong!');
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const addBtn = document.getElementById('add-btn');
                const addBtn2 = document.getElementById('add-btn2');
                const emailInput = document.getElementById('inv-email');
                const emailInput2 = document.getElementById('inv-email2');
                const inviteContainer = document.getElementById('invite-container');
                const form = document.getElementById('invite-members-form');
                
                addBtn.addEventListener('click', () => {
                    const email = emailInput.value.trim();
                    if (email && email !== '-- Pilih Anggota Tim --') {
                        const emailDiv = document.createElement('div');
                        emailDiv.className = 'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-pill';
                        emailDiv.innerHTML = `
                        <span>${email}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fa-solid fa-trash"></i></button>
                        <input type="hidden" name="emails[]" value="${email}">
                    `;
                    inviteContainer.appendChild(emailDiv);
                    emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                        inviteContainer.removeChild(emailDiv);
                    });
                    emailInput.value = '';
                    }
                });

                addBtn2.addEventListener('click', () => {
                    const email = emailInput2.value.trim();
                    if (email && email !== '-- Pilih Anggota Tim --') {
                        const emailDiv = document.createElement('div');
                        emailDiv.className = 'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-pill';
                        emailDiv.innerHTML = `
                        <span>${email}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fa-solid fa-trash"></i></button>
                        <input type="hidden" name="emails[]" value="${email}">
                    `;
                    inviteContainer.appendChild(emailDiv);
                    emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                        inviteContainer.removeChild(emailDiv);
                    });
                    emailInput2.value = '';
                    }
                });

                form.addEventListener('submit', (e) => {
                    if (inviteContainer.children.length === 0) {
                    e.preventDefault();
                    toastr.error('Harap tambahkan email anggota tim Anda sebelum menyimpan!');
                    }
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var teamDescription = "{{ $team->description }}";
                var textarea = document.getElementById("team_description");
                textarea.value = teamDescription;
            });
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

        <script>
            history.pushState({}, "", '/admin/tim/lihat-papan/{{ $team->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Papan Tim - Admin | Trello - PT TATI';
        </script>
    @endsection
@endsection