@extends('layouts.master')
@section('content')

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Tampilan Background Kolom & Card -->
            <div class="overflow-x-scroll overflow-y-auto bg-grad-{{ $board->pattern }}" style="height: 93vh !important">

                <!-- Tampilan Kolom & Kartu -->
                <div class="tampilan-kolom gap-4 p-4">

                    <a href="#" data-toggle="modal" data-target="#addCol" class="flex-col flex-shrink-0 gap-2 px-4 py-2 transition shadow-lg cursor-pointer select-none h-4h w-72 rounded-xl bg-slate-100 hover:scale-105 hover:relative">
                        <p class="flex items-center justify-center gap-4 text-black"><i class="fa-solid fa-plus fa-lg"></i>Tambah Kolom...</p>
                    </a>

                    <!-- Tampilan Kolom -->
                    @foreach ( $dataColumnCard as $dataKolom )
                        <div class="kolom-card" onmouseenter="aksiKolomShow({{ $dataKolom->id }})" onmouseleave="aksiKolomHide({{ $dataKolom->id }})">

                            <!-- Tampilan Aksi Edit & Hapus -->
                            <a href="#" data-toggle="modal" data-target="#updateColumn{{ $dataKolom->id }}">
                                <div class="aksi-kolom" id="aksi-kolom{{ $dataKolom->id }}">
                                    <i class="fa-solid fa-pencil fa-sm"></i>
                                </div>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteColumn{{ $dataKolom->id }}">
                                <div class="aksi-kolom2" id="aksi-kolom2{{ $dataKolom->id }}">
                                    <i class="fa-solid fa-trash fa-sm"></i>
                                </div>
                            </a>
                            <!-- /Tampilan Aksi Edit & Hapus -->

                            <!-- Tampilan Nama Kolom -->
                            <h5 class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">{{ $dataKolom->name }}</h5>
                            <!-- /Tampilan Nama Kolom -->

                            <ul class="my-4 space-y-3">

                                <!-- Tampilan Kartu -->
                                    @foreach ($dataKolom->cards as $dataKartu)
                                        <li class="kartu-trello" id="kartu-trello" onmouseenter="aksiKartuShow({{ $dataKartu->id }})" onmouseleave="aksiKartuHide({{ $dataKartu->id }})">
                                            
                                            <!-- Tampilan Aksi Edit -->
                                            <a href="#" data-toggle="modal" data-target="#editCard{{ $dataKartu->id }}">
                                                <div class="aksi-card" id="aksi-card{{ $dataKartu->id }}">
                                                    <i class="fa-solid fa-pencil fa-sm"></i>
                                                </div>
                                            </a>
                                            <!-- /Tampilan Aksi Edit -->

                                            <!-- Tampilan Kartu Pengguna -->
                                            <a href="#" data-toggle="modal" data-target="#isianKartu{{ $dataKartu->id }}">
                                                <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                                    <span class="flex ms-3">{{ $dataKartu->name }}</span>
                                                </div>
                                            </a>
                                            <!-- /Tampilan Kartu Pengguna -->
                                        </li>
                                    @endforeach
                                        <li class="card-trello hidden" id="cardTrello{{ $dataKolom->id }}">
                                            <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                                <form action="{{ route('addCard', ['board_id' => $board->id, 'team_id' => $board->team_id, 'column_id' => $dataKolom->id ]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" class="form-control" name="board_id" value="{{ $board->id }}">
                                                    <input type="hidden" class="form-control" name="team_id" value="{{ $team->id }}">
                                                    <input type="hidden" class="form-control" name="column_id" value="{{ $dataKolom->id }}">
                                                    <input type="text" class="form-control" name="name" id="cardName" style="border-radius: 15px; background-color: #f5fffa;" placeholder="Masukkan nama kartu.." required>
                                                    <button type="submit" class="btn btn-outline-info btn-add">Tambah Kartu</button>
                                                </form>
                                            </div>
                                        </li>
                                        <button onclick="openAdd('{{ $dataKolom->id }}')" class="btn btn-outline-info" id="btn-add{{ $dataKolom->id }}">
                                            <i class="fa-solid fa-plus"></i> Tambah Kartu...
                                        </button>
                                <!-- /Tampilan Kartu -->

                            </ul>
                        </div>
                    @endforeach
                    <!-- /Tampilan Kolom -->
                    
                </div>
                <!-- /Tampilan Kolom & Kartu -->

            </div>
            <!-- /Tampilan Background Kolom & Card -->

        </div>
        <!-- /Page Wrapper -->

        {!! Toastr::message() !!}

        <!-- Perbaharui Papan Modal -->
        <div id="updateBoard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Perbaharui Papan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('updateBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="board_id" value="{{ $board->id }}">
                            <div class="form-group">
                                <label>Nama Papan</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('board_name') is-invalid @enderror" id="board_name" name="board_name" value="{{ $board->name }}" required />
                                @error('board_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <label>Warna Papan</label>
                                <input type="hidden" id="pattern-field" name="board_pattern" value="{{ isset($patterns[0]) ? $patterns[0] : 'default_value' }}">
                                <div class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                    @isset($patterns)
                                        @foreach ($patterns as $pattern)
                                            <div onclick="selectPattern('{{ $pattern }}')" class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black" id="pattern-{{ $pattern }}">
                                                <div id="check-{{ $pattern }}" class="flex items-center justify-center w-full h-full {{ $pattern == $patterns[0] ? 'opacity-100' : 'opacity-0' }}">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <small class="text-danger">*Silahkan pilih kembali (Warna Papan) apabila melakukan pembaharuan.</small>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Perbaharui Papan Modal -->
        
        <!-- Hapus Papan Modal -->
        <div id="deleteBoard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Hapus Papan "{{ $board->name }}"?</h3>
                            <p>Apakah Anda yakin ingin menghapus papan ini?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('deleteBoard', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="board_id" value="{{ $board->id }}">
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

        <!-- Buat Kolom Modal -->
        <div id="addCol" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Kolom</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- @isset($dataKartu)
                            <form action="{{ route('addCol', ['board_id' => $board->id, 'team_id' => $board->team_id, 'card_id' => $dataKartu->id]) }}" id="addColForm" method="POST">
                        @endif --}}
                        <form action="{{ route('addCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" id="addColForm" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" name="board_id" value="{{ $board->id }}">
                            <input type="hidden" class="form-control" name="team_id" value="{{ $team->id }}">
                            <div class="form-group">
                                <label>Nama Kolom</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('column_name') is-invalid @enderror" id="buat-kolom" name="column_name" required>
                                @error('column_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Buat Kolom Modal -->

        <!-- Perbaharui Kolom Modal -->
        @foreach ( $dataColumnCard as $perbaharuiKolom )
            <div id="updateColumn{{ $perbaharuiKolom->id }}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Perbaharui Kolom</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('updateCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="column_id" id="column_id" value="{{ $perbaharuiKolom->id  }}">
                                <div class="form-group">
                                    <label>Nama Kolom</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control @error('column_name') is-invalid @enderror" id="column_name" name="column_name" value="{{ $perbaharuiKolom->name  }}" required />
                                    @error('column_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- /Perbaharui Kolom Modal -->

        <!-- Hapus Kolom Modal -->
        @foreach ( $dataColumnCard as $hapusKolom )
            <div id="deleteColumn{{ $hapusKolom->id }}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Hapus Kolom "{{ $hapusKolom->name }}"?</h3>
                                <p>Apakah Anda yakin ingin menghapus kolom ini?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <form action="{{ route('deleteCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="column_id" id="column_id" value="{{ $hapusKolom->id  }}">
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
        @endforeach
        <!-- /Hapus Kolom Modal -->

        <!-- Isian Kartu Modal -->
        @foreach ( $dataColumnCard as $dataKolom )
            @foreach ($dataKolom->cards as $isianKartu)
                <div id="isianKartu{{ $isianKartu->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="justify-content: left;">
                                <div class="icon-card">
                                    <i class="fa-solid fa-credit-card fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="nama-kartu">{{ $isianKartu->name  }}</h5>
                                    {{-- @if($isianKartu->history->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                        <form action="{{ route('hapusKartu', ['card_id' => $isianKartu->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $isianKartu->id  }}">
                                            <div class="hapus-kartu">
                                                <button type="submit" style="border: none; background: none; padding: 0;">
                                                    <i class="fa-solid fa-trash fa-lg"></i>
                                                </button>
                                            </div>
                                        </form>
                                    {{-- @endif --}}
                                    <p class="tag-list">dalam daftar <a class="tag-name">{{ $dataKolom->name  }}</a></p>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <!-- Tambah & Edit Keterangan -->
                                <div class="menu-keterangan">
                                    <div class="icon-align">
                                        <i class="fa-solid fa-align-left fa-lg"></i>
                                    </div>
                                    <div class="keterangan-tag">
                                        <p class="deskripsi-keterangan">Keterangan</p>
                                        <form id="myForm{{ $isianKartu->id }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <input type="text" class="border border-1 border-dark w-40l p-2 rounded-xl" id="keterangan{{ $isianKartu->id }}" name="keterangan" placeholder="Masukkan Keterangan" value="{{ $isianKartu->description }}"><br>
                                            <div class="aksi-update-keterangan gap-2">
                                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButton{{ $isianKartu->id }}">Simpan</button>
                                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButton{{ $isianKartu->id }}">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                    @include('admin.script')
                                </div>
                                <!-- /Tambah & Edit Keterangan -->

                                <!-- Tambah Judul Checklist -->
                                <form id="myFormTitle{{ $isianKartu->id }}" method="POST">
                                    @csrf
                                    <div class="menu-tambah-checklist flex flex-col flex-wrap">
                                        <div class="header-checklist flex gap-5">
                                            <div class="icon-tambah-checklist hidden" id="iconCheck-{{ $isianKartu->id }}">
                                                <i class="fa-regular fa-square-check fa-xl"></i>
                                            </div>
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <input type="text" class="border border-1 border-dark w-40l p-2 rounded-xl hidden" id="titleChecklist{{ $isianKartu->id }}" name="titleChecklist" placeholder="Masukkan Judul" required>
                                        </div>
                                        <div class="aksi-tambah-checklist gap-2">
                                            <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitle{{ $isianKartu->id }}">Simpan</button>
                                            <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitle{{ $isianKartu->id }}">Batal</button><br>
                                        </div>
                                    </div>
                                </form>
                                <div class="tambah-checklist">
                                    <button type="button" class="btn btn-outline-info icon-item" id="addTitle-{{ $isianKartu->id }}"><i class="fa-regular fa-square-check fa-lg"></i> Tambah Checklist</button>
                                </div>
                                <!-- /Tambah Judul Checklist -->
                                
                                @foreach ($isianKartu->titleChecklists as $titleChecklists)
                                <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl">
                                    <!-- Perbaharui & Hapus Judul Checklist -->
                                    <div class="header-checklist flex justify-content">
                                        <i class="fa-regular fa-square-check fa-xl"></i>
                                        <form id="myFormTitleUpdate{{ $titleChecklists->id }}" method="POST" class="update-title">
                                            @csrf
                                                <input type="hidden" id="title_id" name="title_id" value="{{ $titleChecklists->id }}">
                                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" id="titleChecklistUpdate{{ $titleChecklists->id }}" name="titleChecklistUpdate" placeholder="Masukkan Judul" value="{{$titleChecklists->name}}">
                                                <div class="aksi-update-title gap-2">
                                                    <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate{{ $titleChecklists->id }}">Simpan</button>
                                                    <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitleUpdate{{ $titleChecklists->id }}">Batal</button>
                                                </div>
                                        </form>
                                        {{-- @if($isianKartu->history->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                            <form id="myFormTitleDelete{{ $titleChecklists->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" id="id" name="id" value="{{ $titleChecklists->id }}">
                                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                <div class="icon-hapus-title" id="hapus-title{{ $titleChecklists->id }}">
                                                    <button type="submit" style="border: none; background: none; padding: 0;">
                                                        <i class="fa-solid fa-trash fa-lg"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        {{-- @endif --}}
                                    </div>
                                    <!-- /Perbaharui & Hapus Judul Checklist -->

                                    <!-- Progress Bar Checklist -->
                                    <div class="progress" data-checklist-id="{{ $titleChecklists->id }}">
                                        <div class="progress-bar progress-bar-{{ $titleChecklists->id }}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                            0%
                                        </div>
                                    </div>
                                    <!-- Progress Bar Checklist -->
                                   
                                    <!-- Perbaharui & Hapus Checklist -->
                                    @include('admin.script2')
                                    @foreach ($titleChecklists->checklists as $checklists)
                                    <div class="input-checklist">
                                        <!-- Tampilan Checklist -->
                                        <form id="myFormChecklistUpdate{{ $checklists->id }}" method="POST" class="form-checklist flex gap-5">
                                            @csrf
                                            <input class="dynamicCheckbox" type="checkbox" id="{{$checklists->id}}" name="{{$checklists->id}}" {{$checklists->is_active == '1' ? 'checked' : ''}}>
                                            <label class="dynamicCheckboxLabel border border-1 border-darks w-407 p-2 rounded-xl  {{$checklists->is_active == '1' ? 'strike-through' : ''}}" id="labelCheckbox-{{$checklists->id}}" for="labelCheckbox-{{$checklists->id}}">{{$checklists->name}}</label>

                                            <input type="hidden" id="checklist_id" name="checklist_id" value="{{ $checklists->id }}">
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <input type="text" class="dynamicCheckboxValue border border-1 border-darks w-407 p-2 rounded-xl hidden" id="checkbox-{{$checklists->id}}" name="checkbox-{{$checklists->id}}" value="{{$checklists->name}}" placeholder="Masukkan checklist">
                                        </form>
                                        <!-- Icon Hapus Checklist -->
                                        {{-- @if($isianKartu->history->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                            <form id="myFormChecklistDelete{{ $checklists->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" id="id" name="id" value="{{ $checklists->id }}">
                                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                <div class="icon-hapus-checklist" id="hapus-checklist{{ $checklists->id }}">
                                                    <button type="submit" class="deletes" id="deleteButtonChecklist-{{ $checklists->id }}" style="border: none; background: none; padding: 0;">
                                                        <i class="fa-solid fa-trash fa-lg"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        {{-- @endif --}}
                                        <!-- /Icon Hapus Checklist -->
                                    </div>
                                    <!-- /Tampilan Checklist -->

                                    <!-- Aksi Update Checklist -->
                                    <div class="aksi-update-checklist gap-2">
                                        <button type="submit" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-{{ $checklists->id }}">Simpan</button>
                                        <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-{{ $checklists->id }}">Batal</button>
                                    </div>
                                    <!-- /Aksi Update Checklist -->

                                    @include('admin.script3')
                                    @endforeach
                                    <!-- /Perbaharui & Hapus Checklist -->

                                    <!-- Tambah baru checklist -->
                                    <div id="checkbox-container-{{ $titleChecklists->id }}"></div>
                                    <form id="myFormChecklist{{ $titleChecklists->id }}" method="POST">
                                        @csrf
                                            <input type="hidden" id="title_id" name="title_id" value="{{ $titleChecklists->id }}">
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <div class="header-tambah-checklist flex gap-4">
                                                <i class="fa-xl"></i>
                                                <input type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist{{ $titleChecklists->id }}" name="checklist" placeholder="Masukkan Checklist" required>
                                            </div>
                                            <div class="aksi-update-checklist gap-2">
                                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist{{ $titleChecklists->id }}">Simpan</button>
                                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist{{ $titleChecklists->id }}">Batal</button>
                                            </div>
                                    </form>
                                    <button type="button" class="btn btn-outline-info" id="AddChecklist{{ $titleChecklists->id }}">Tambah Item</button>
                                    <!-- Tambah baru checklist -->
                                    </div>
                                @endforeach
                                <div class="menu-activity">
                                    <div class="header-activity flex">
                                        <i class="fa-solid fa-list-ul fa-lg"></i>
                                        <p class="activity-keterangan">Activity </p>
                                        <div onclick="showActivity('{{ $dataKolom->id }}')" class="icon-lihat">
                                            <i class="fa-solid fa-eye fa-lg" id="showActivityIcon{{ $dataKolom->id }}"></i>
                                        </div>
                                    </div>
                                    <div class="input-komentar flex gap-4">
                                        <img class="avatar-comment" src="{{ URL::to('/assets/images/' . Auth::user()->avatar) }}" loading="lazy">
                                        <form action="{{ route('komentarKartu', ['card_id' => $isianKartu->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id  }}">
                                            <input type="hidden" name="card_id" value="{{ $isianKartu->id }}">
                                            <textarea onclick="saveComment('{{ $dataKolom->id }}')" class="form-control border border-1 border-dark rounded-xl" rows="1" cols="77" id="contentarea{{ $dataKolom->id }}" name="content" placeholder="Tulis komentar..."></textarea>
                                            <button type="submit" class="btn btn-outline-info icon-comment hidden" id="simpanButton{{ $dataKolom->id }}">Kirim</button>
                                        </form>
                                    </div>
                                    <div class="activity-tag flex flex-col hiddens" id="showActivity{{ $dataKolom->id }}">
                                        @php
                                            $columns = DB::table('columns')->where('id', $dataKolom->id)->get();
                                            $cards = DB::table('cards')->where('column_id', $dataKolom->id)->where('id', $isianKartu->id)->get();
                                        @endphp
                                        @foreach($columns as $column)
                                            @foreach($cards as $card)
                                                @php
                                                    $isianHistory = DB::table('card_histories')->leftjoin('users', 'card_histories.user_id', 'users.id')->where('card_id', $card->id)->select('card_histories.*','users.id','users.name','users.avatar')->orderBy('card_histories.created_at', 'desc')->get();
                                                @endphp
                                                @foreach($isianHistory as $history)
                                                    @foreach ($isianKartu->titleChecklists as $titleChecklists)
                                                        <div class="isian-tag w-403">
                                                            @if ($history->type === 'event')
                                                                <div class="isian-history flex gap-1" style="height: 45px">
                                                                    <img class="avatar-activity" src="{{ URL::to('/assets/images/' . $history->avatar) }}" loading="lazy">
                                                                    <div class="title-activity flex gap-1">
                                                                    @if (strpos($history->content, 'Membuat Kartu') !== false)
                                                                        <!-- Berdasarkan kolom masing-masing -->
                                                                            <p>{{ $history->name }}, telah menambahkan {{ $card->name }} ke kolom ini</p>
                                                                        @elseif (strpos($history->content, 'Memperbaharui Kartu') !== false)
                                                                            <p>{{ $history->name }}, telah memperbaharui {{ $card->name }} ke kolom ini</p>
                                                                        @elseif (strpos($history->content, 'Memperbaharui Keterangan Kartu') !== false)
                                                                            <p>{{ $history->name }}, telah memperbaharui keterangan kartu {{ $card->name }} ke kolom ini</p>
                                                                        <!-- /Berdasarkan kolom masing-masing -->

                                                                        <!-- Berdasarkan kartu masing-masing -->
                                                                        @elseif (strpos($history->content, 'Membuat Judul Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah menambahkan judul {{ $titleChecklists->name }} ke kartu ini</p>
                                                                        @elseif (strpos($history->content, 'Memperbaharui Judul Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah memperbaharui judul {{ $titleChecklists->name }} ke kartu ini</p>
                                                                        @elseif (strpos($history->content, 'Menghapus Judul Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah menghapus judul {{ $titleChecklists->name }} ke kartu ini</p>
                                                                        <!-- /Berdasarkan kartu masing-masing -->

                                                                        <!-- Berdasarkan judul masing-masing -->
                                                                        @elseif (strpos($history->content, 'Membuat Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah menambahkan checklist ke judul {{ $titleChecklists->name }}</p>
                                                                        @elseif (strpos($history->content, 'Memperbaharui Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah memperbaharui checklist ke judul {{ $titleChecklists->name }}</p>
                                                                        @elseif (strpos($history->content, 'Menghapus Checklist') !== false)
                                                                            <p>{{ $history->name }}, telah menghapus checklist ke judul {{ $titleChecklists->name }}</p>
                                                                        <!-- /Berdasarkan judul masing-masing -->
                                                                    @endif
                                                                    </div>
                                                                </div>
                                                                <div class="waktu-history">
                                                                    <p><i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F \p\u\k\u\l h:i A') }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($history->type === 'comment')
                                                                <div class="isian-history flex gap-1">
                                                                    <img class="avatar-activity" src="{{ URL::to('/assets/images/' . $history->avatar) }}" loading="lazy">
                                                                    <div class="title-activity flex gap-1">
                                                                        <p>{{ $history->name }}<br><span>{{ $history->content }}</span></p>
                                                                    </div>
                                                                </div>
                                                                <div class="waktu-history">
                                                                    <p><i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F \p\u\k\u\l h:i A') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        <!-- /Isian Kartu Modal -->

        <!-- Perbaharui Kartu Modal Belum Solving -->
        @foreach ( $dataColumnCard as $dataKolom )
            @foreach ($dataKolom->cards as $perbaharuiKartu)
                <div id="editCard{{ $perbaharuiKartu->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Perbaharui Kartu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('perbaharuiKartu', ['card_id' => $perbaharuiKartu->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $perbaharuiKartu->id  }}">
                                    <div class="form-group">
                                        <label>Nama Kartu</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $perbaharuiKartu->name  }}" required />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        <!-- /Perbaharui Kartu Modal -->

        <style>
            .fa-eye {
                color: black;
                cursor: pointer;
            }
            .fa-eye-slash {
                cursor: pointer;
            }
            input[type="checkbox"] {    
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                width: 20px;
                height: 20px;
                border: 2px solid black;
                border-radius: 4px;
                cursor: pointer;
            }
            input[type="checkbox"]:checked {
                background-color: #539dec;
                border-color: #539dec;
            }
            input[type="checkbox"]:checked::after {
                content: '✔';
                display: block;
                color: white;
                font-size: 16px;
                font-weight: 700;
                text-align: center;
                line-height: 20px;
            }
            .strike-through {
                text-decoration: line-through;
                font-weight: 600;
                /* color: #dc3545; */
            }
            .progress {
                display:-ms-flexbox;
                display:flex;
                height:1rem;
                overflow:hidden;
                line-height:0;
                font-size:.75rem;
                background-color:#e9ecef;
                border-radius:1rem;
                margin-bottom:10px;
                margin-top:-4px;
                width:84.8%;
                margin-left:5.5%
            }
            .kolom-card {
                --tw-bg-opacity: 1;
                --tw-border-opacity: 1;
                min-width: 290px !important;
                padding: 1rem !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
                background-color: rgb(241 245 249 / var(--tw-bg-opacity)) !important;
                border-color: rgb(229 231 235 / var(--tw-border-opacity)) !important;
            }
            .border-darks {border: 2px solid transparent !important; cursor: pointer;}
            .border-darkss {border-color: #343a40 !important;}
            .border-darks:focus {border-color: #343a40 !important; cursor: pointer;}
            .border-dark:hover {background-color: #091E420F; !important; cursor: pointer;}
            .border-darks:hover {background-color: #091E420F; !important; cursor: pointer;}
            
            @foreach($result_tema as $sql_mode => $mode_tema)
                @if ($mode_tema->tema_aplikasi == 'Gelap')
                    .fa-eye { color: white}
                    .fa-trash { color: white}
                    p {color: {{ $mode_tema->warna_mode }} !important}
                    .custom-modal .tag-list {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .deskripsi-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .checklist-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .activity-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .border-dark {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important;}
                    .border-darks {border: 2px solid transparent !important; cursor: pointer;}
                    .border-darkss {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important;}  
                    .border-darks:focus {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important; cursor: pointer;}
                    .border-dark:hover {background-color: {{ $mode_tema->warna_sistem }} !important; cursor: pointer;}
                    .border-darks:hover {background-color: {{ $mode_tema->warna_sistem }} !important; cursor: pointer;}
                    .isian-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .isian-checklist {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    input[type=text] {color: white;   background-color: {{ $mode_tema->warna_mode }} !important}
                    .dynamicCheckboxLabel {background-color: {{ $mode_tema->warna_mode }} !important}
                    input[type="checkbox"] {background-color: {{ $mode_tema->warna_mode }} !important; border: 2px solid white !important}
                    input[type="checkbox"]:checked {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .kolom-card {background-color: {{ $mode_tema->warna_sistem }} !important; border-color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .progress{background-color: {{ $mode_tema->warna_sistem }} !important;}
                @endif
            @endforeach
        </style>

    @section('script')
        <script src="{{ asset('assets/js/memuat-pattern-board.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-data-kolom-board.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-onclick-board.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

        <script>
            history.pushState({}, "", '/admin/tim/papan/{{ $team->id }}/{{ $board->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Kartu Tim - Admin | Trello - PT TATI';
        </script>
    @endsection
@endsection