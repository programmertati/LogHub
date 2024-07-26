@extends('layouts.master')
@section('content')

        <!-- Page Wrapper -->
        <div class="page-wrapper" style="height: 100vh">

            <!-- Tampilan Background Kolom & Card -->
            <div class="overflow-x-scroll overflow-y-auto bg-grad-{{ $board->pattern }}" id="bgGrad" style="height: 100%">
            @include('user.bg-sistem')

                <!-- Tampilan Kolom & Kartu -->
                <div class="tampilan-kolom gap-4 p-4" id="cardContainer">

                    <div class="info-status12">
                        <a href="#" data-toggle="modal" data-target="#addCol" class="add-column hover:scale-105 hover:relative">
                            <p class="flex items-center justify-center gap-4 text-black"><i class="fa-solid fa-plus fa-lg"></i>Add another list</p>
                            <span class="text-status12"><b>CTRL + B (Add New List)</b></span>
                        </a>
                    </div>

                    <!-- Tampilan Kolom -->
                    @php
                    $sortedDataKolom = $dataColumnCard->count() > 0 ? $dataColumnCard->sortBy(function($item) {
                        return $item->position == 0 ? $item->id : $item->position;
                    }) : $dataColumnCard->sortBy('id');
                    @endphp
                    @foreach ($sortedDataKolom as $dataKolom)
                        <div class="kolom-card hover:scale-105 hover:relative" id="kolom-card-{{ $dataKolom->id }}" data-id="{{ $dataKolom->id }}" onmouseenter="aksiKolomShow({{ $dataKolom->id }})" onmouseleave="aksiKolomHide({{ $dataKolom->id }})">

                            <!-- Tampilan Aksi Edit & Hapus -->
                            <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom{{ $dataKolom->id }}">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" onclick="updateColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('updateCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');" id="edit-column-{{ $dataKolom->id }}">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                    <a href="#" class="dropdown-item" onclick="deleteColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('deleteCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');">
                                        <i class='fa fa-trash-o m-r-5'></i> Delete
                                    </a>
                                    @php
                                        $softDeletedCards = $dataKolom->cards()->onlyTrashed()->count();
                                        $displayStyle = $softDeletedCards > 0 ? '' : 'display: none;';
                                    @endphp
                                    <a href="#" class="dropdown-item recover-kartu-link" id="recover-kartu-link-{{ $dataKolom->id }}" data-toggle="modal" data-target="#pulihkanKartuModal" data-column-id="{{ $dataKolom->id }}" style="{{ $displayStyle }}">
                                        <i class="fa-solid fa-recycle m-r-5"></i> Recover Card
                                    </a>
                                </div>
                            </div>
                            <!-- /Tampilan Aksi Edit & Hapus -->

                            <!-- Tampilan Aksi Edit & Hapus Bersama Auth -->
                            {{-- <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom{{ $dataKolom->id }}">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" onclick="updateColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('updateCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');" id="edit-column-{{ $dataKolom->id }}">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                    @php
                                        $softDeletedCards = $dataKolom->cards()->onlyTrashed()->count();
                                        $displayStyle = $softDeletedCards > 0 ? '' : 'display: none;';
                                    @endphp
                                    <a href="#" class="dropdown-item recover-kartu-link" id="recover-kartu-link-{{ $dataKolom->id }}" data-toggle="modal" data-target="#pulihkanKartuModal" data-column-id="{{ $dataKolom->id }}" style="{{ $displayStyle }}">
                                        <i class="fa-solid fa-recycle m-r-5"></i> Recover Card
                                    </a>
                                </div>
                            </div> --}}
                            <!-- /Tampilan Aksi Edit & Hapus Bersama Auth -->

                            <!-- Tampilan Nama Kolom -->
                            <h5 id="kolomNama{{ $dataKolom->id }}" class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">{{ $dataKolom->name }}</h5>
                            <!-- /Tampilan Nama Kolom -->

                            <ul class="card-container" id="containerCard{{ $dataKolom->id }}" data-id="{{ $dataKolom->id }}">

                                <!-- Tampilan Kartu -->
                                @php
                                $sortedDataKartu = $dataKolom->cards->count() > 0 ? $dataKolom->cards->sortBy(function($item) {
                                    return $item->position == 0 ? $item->id : $item->position;
                                }) : $dataKolom->cards->sortBy('id');
                                @endphp
                                @foreach ($sortedDataKartu as $dataKartu)
                                    <li class="kartu-loghub" data-id="{{ $dataKartu->id }}" onmouseenter="aksiKartuShow({{ $dataKartu->id }})" onmouseleave="aksiKartuHide({{ $dataKartu->id }})" style="position: relative;">
                                        
                                        <!-- Tampilan Aksi Edit -->
                                        <div class="cover-card card-cover2-{{ $dataKartu->pattern }} {{ $dataKartu->pattern ? '' : 'hiddens' }}" id="cover-card-{{ $dataKartu->id }}"></div>
                                        {{-- @if($dataKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                            <div class="dropdown dropdown-action aksi-card" id="aksi-card{{ $dataKartu->id }}" style="position: absolute !important;">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item" onclick="updateCardModal({{ $dataKartu->id }}, '{{ $dataKartu->name }}', '{{ route('perbaharuiKartu2', ['card_id' => $dataKartu->id]) }}');" id="edit-card-{{ $dataKartu->id }}">
                                                        <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                                                    </a>
                                                    <a href="#" class="dropdown-item" onclick="deleteCardModal2('{{ $dataKartu->id }}', '{{ $dataKartu->name }}', '{{ $dataKolom->name }}', '{{ route('hapusKartu2', ['card_id' => $dataKartu->id]) }}');">
                                                        <i class='fa fa-trash-o m-r-5'></i> Delete
                                                    </a>
                                                    <a href="#" class="dropdown-item" onclick="copyCardModal('{{ $dataKartu->id }}', '{{ $dataKartu->name }}', '{{ route('copyCard', ['column_id' => $dataKolom->id, 'id' => $dataKartu->id]) }}');" id="copy-card-{{ $dataKartu->id }}">
                                                        <i class="fa-regular fa-copy m-r-5"></i> Copy Card
                                                    </a>
                                                </div>
                                            </div>
                                        {{-- @endif --}}
                                        <!-- /Tampilan Aksi Edit -->

                                        <!-- Tampilan Kartu Pengguna -->
                                        <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val({{ $dataKartu->id }}); $('#form_kartu').submit();">
                                            <div class="card-nama" @if(!empty($dataKartu->pattern)) style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;" @endif>
                                                <span class="flex ms-3" id="span-nama-{{ $dataKartu->id }}" style="width: 150px; @if(!empty($dataKartu->description)) margin-bottom: 10px; @endif">{{ $dataKartu->name }}</span>
                                                <div class="tampilan-info gap-2">

                                                    <!-- Muncul apabila terdapat deskripsi pada kartu -->
                                                    @if(!empty($dataKartu->description))
                                                        <div class="info-status8" id="descriptionStatus{{ $dataKartu->id }}">
                                                            <i class="fa-solid fa-align-left icon-deskripsi-light
                                                                @foreach($result_tema as $sql_mode => $mode_tema)
                                                                    @if($mode_tema->tema_aplikasi == 'Gelap')
                                                                        icon-deskripsi-dark
                                                                    @endif
                                                                @endforeach">
                                                            </i>
                                                            <span class="text-status8"><b>This card has a description.</b></span>
                                                        </div>
                                                    @else
                                                        <div class="info-status8 hidden" id="descriptionStatus{{ $dataKartu->id }}">
                                                            <i class="fa-solid fa-align-left icon-deskripsi-light
                                                                @foreach($result_tema as $sql_mode => $mode_tema)
                                                                    @if($mode_tema->tema_aplikasi == 'Gelap')
                                                                        icon-deskripsi-dark
                                                                    @endif
                                                                @endforeach">
                                                            </i>
                                                            <span class="text-status8"><b>This card has a description.</b></span>
                                                        </div>
                                                    @endif
                                                    <!-- /Muncul apabila terdapat deskripsi pada kartu -->

                                                    <!-- Muncul apabila terdapat checklist pada kartu -->
                                                    <div id="iconChecklist-{{ $dataKartu->id }}" class="progress-checklist-light
                                                        @foreach($result_tema as $mode_tema)
                                                            @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                progress-checklist-dark
                                                            @endif
                                                        @endforeach
                                                        @php
                                                            $totalPercentage = 0;
                                                            $totalCount = 0;
                                                            foreach ($dataKartu->titleChecklists as $titleChecklists) {
                                                                $totalCount++;
                                                                $totalPercentage += $titleChecklists->percentage;
                                                            }
                                                        @endphp
                                                        @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                            progress-checklist-100-light
                                                            @foreach($result_tema as $mode_tema)
                                                                @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                    progress-checklist-100-dark
                                                                @endif
                                                            @endforeach
                                                        @endif">

                                                        @php
                                                            $perChecklist = 0;
                                                            $jumlahChecklist = 0;
                                                            foreach ($dataKartu->titleChecklists as $titleChecklists) {
                                                                foreach ($titleChecklists->checklists as $checklists) {
                                                                    if(!empty($checklists->is_active)) {
                                                                        $perChecklist++;
                                                                    }
                                                                    if(!empty($checklists->title_checklists_id)) {
                                                                        $jumlahChecklist++;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @if($perChecklist > 0 || $jumlahChecklist > 0)
                                                            <div class="info-status9">
                                                                <i class="fa-regular fa-square-check icon-check-not-full-light
                                                                    @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                                        icon-check-full-light
                                                                    @endif
                                                                    @foreach($result_tema as $mode_tema)
                                                                        @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                            icon-check-not-full-dark
                                                                            @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                                                icon-check-full-dark
                                                                            @endif
                                                                        @endif
                                                                    @endforeach">
                                                                </i>
                                                                @if(!empty($dataKartu->description))
                                                                    <span class="text-status9"><b>Checklist items</b></span>
                                                                @else
                                                                    <span class="text-status9a"><b>Checklist items</b></span>
                                                                @endif
                                                                <span id="perhitunganChecklist-{{ $dataKartu->id }}" class="total">{{ $perChecklist }}/{{ $jumlahChecklist }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- /Muncul apabila terdapat checklist pada kartu -->

                                                </div>
                                            </div>
                                        </a>
                                        <!-- /Tampilan Kartu Pengguna -->
                                    </li>
                                @endforeach
                                <!-- /Tampilan Kartu -->

                            </ul>
                            <div class="card-loghub hidden" id="cardLoghub{{ $dataKolom->id }}">
                                <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                    <form id="addCardForm{{ $dataKolom->id }}" action="{{ route('addCard', ['board_id' => $board->id, 'team_id' => $board->team_id, 'column_id' => $dataKolom->id ]) }}" method="POST" onsubmit="addCardScript(event, '{{ $dataKolom->id }}')">
                                        @csrf
                                        <input type="hidden" class="form-control" name="board_id" value="{{ $board->id }}">
                                        <input type="hidden" class="form-control" name="team_id" value="{{ $team->id }}">
                                        <input type="hidden" class="form-control" name="column_id" value="{{ $dataKolom->id }}">
                                        <input type="text" class="form-control" name="name" id="cardName" style="width: 130%; border-radius: 10px; background-color: #f5fffa;" placeholder="Enter card's name..." required>
                                        <button type="submit" class="btn btn-outline-info btn-add">Add card</button>
                                    </form>
                                </div>
                            </div>
                            <button onclick="openAdd('{{ $dataKolom->id }}')" class="btn btn-outline-info" id="btn-add{{ $dataKolom->id }}">
                                <i class="fa-solid fa-plus"></i> Add a card...
                            </button>
                        </div>
                    @endforeach
                    @include('allrole.pindah-kolom-kartu')
                    @include('allrole.tambah-kolom-kartu')
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
                        <h5 class="modal-title">Update Boards</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('updateBoard2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="board_id" value="{{ $board->id }}">
                            <div class="form-group">
                                <label>Board's Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('board_name') is-invalid @enderror" id="board_name" name="board_name" placeholder="Enter a board's name" value="{{ $board->name }}" required />
                                @error('board_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <label>Board's Color</label>
                                <input type="hidden" id="pattern-field" name="board_pattern" value="{{ isset($patterns[0]) ? $patterns[0] : 'default_value' }}">
                                <div class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                    @isset($patterns)
                                        @foreach ($patterns as $pattern)
                                            <div onclick="selectPattern('{{ $pattern }}')" class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black" id="pattern-{{ $pattern }}" style="cursor: pointer">
                                                <div id="check-{{ $pattern }}" class="flex items-center justify-center w-full h-full {{ $pattern == $patterns[0] ? 'opacity-100' : 'opacity-0' }}">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <small class="text-danger">*Please select (Board's Color) again when updating.</small>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
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
                            <h3>Delete Board's "{{ $board->name }}"?</h3>
                            <p>Are you sure you want to delete this board?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('deleteBoard2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="board_id" value="{{ $board->id }}">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
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
                        <h5 class="modal-title">Create Columns</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addColForm" action="{{ route('addCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST" onsubmit="addColumnScript(event)">
                            @csrf
                            <input type="hidden" class="form-control" name="board_id" value="{{ $board->id }}">
                            <input type="hidden" class="form-control" name="team_id" value="{{ $team->id }}">
                            <div class="form-group">
                                <label>Column's Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('column_name') is-invalid @enderror" id="buat-kolom" name="column_name" placeholder="Enter a column's name" required>
                                @error('column_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Buat Kolom Modal -->

        <!-- Perbaharui Kolom Modal -->
        <div id="updateColumn" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Columns</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateColumnForm" method="POST">
                            @csrf
                            <input type="hidden" name="column_id" id="update-column-id">
                            <div class="form-group">
                                <label>Column's Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" id="update-column-name" name="column_name" placeholder="Enter a column's name" required />
                                <span class="invalid-feedback" role="alert" id="column-name-error"></span>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Perbaharui Kolom Modal -->

        <!-- Hapus Kolom Modal Dicomment ketika menggunakan auth -->
        <div id="deleteColumn" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Columns "<span id="columnName"></span>"?</h3>
                            <p>Are you sure you want to delete this column?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form id="deleteColumnForm" method="POST">
                                @csrf
                                <input type="hidden" name="column_id" id="column-id">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Hapus Kolom Modal Dicomment ketika menggunakan auth -->

        <!-- Isian Kartu Modal -->
        <div id="isianKartu" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" id="form_kartu" class="form-horizontal" enctype="multipart/form-data" action="{{ route('getDataKartu2') }}">
                        @csrf
                        <input type="hidden" name="card_id" id="card_id">
                    </form>
                    <div id="div_hasil">
                    </div>
                </div>
            </div>
        </div>
        @include('user.script-modal-card')
        <!-- /Isian Kartu Modal -->

        <!-- Perbaharui Kartu Modal -->
        <div id="updateCard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Cards</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateCardForm" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="update-card-id">
                            <div class="form-group">
                                <label>Card's Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" id="update-card-name" name="name" placeholder="Enter a card's name" required />
                                <span class="invalid-feedback" role="alert" id="card-name-error"></span>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Perbaharui Kartu Modal -->

        <!-- Salin Kartu Modal -->
        <div id="copyCard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Copy Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="copyCardForm" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="copy-card-id">
                            <div class="form-group">
                                <label for="copy-card-name">Card's Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" id="copy-card-name" name="name" placeholder="Enter a card's name" required>
                                <span class="invalid-feedback" role="alert" id="copy-card-name-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="keep-checklists">Keep…</label><br>
                                <input type="checkbox" id="keep-checklists" name="keep_checklists" checked>
                                <label for="total-checklists"></label>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-outline-info submit-btn">Create card</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Salin Kartu Modal -->

        <!-- Pulihkan Kartu Modal -->
        <div id="pulihkanKartuModal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pulihkan-kartu-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">Recover Cards</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-pulihkan">Card</p>
                        <ul class="pulihkan-container" id="listPulihkanKartu"></ul>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('allrole.memuat-modal-pulihkan-kartu')
        <!-- /Pulihkan Kartu Modal -->

        <!-- Pulihkan Title & Checklist Modal -->
        <div id="pulihkanTitleChecklistModal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pulihkan-kartu-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">Recover Title & Checklist</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-pulihkan">Title Checklists</p>
                        <ul class="pulihkan-container2" id="listPulihkanTitleChecklist"></ul>
                        
                        <p class="text-pulihkan">Checklist</p>
                        <ul class="pulihkan-container3" id="listPulihkanChecklist"></ul>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger titlechecklist-btn" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Pulihkan Title & Checklist Modal -->

        <!-- Hapus Kartu Modal -->
        <div id="deleteCard" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Cards "<span id="cardName2"></span>"?</h3>
                            <p>Are you sure you want to delete this card in the <span id="columnName2"></span> column?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form id="deleteCardForm" class="deleteCardForm" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="card-id">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Hapus Kartu Modal -->

        <!-- Hapus Kartu Modal Depan -->
        <div id="deleteCard2" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Cards "<span id="cardName3"></span>"?</h3>
                            <p>Are you sure you want to delete this card in the <span id="columnName3"></span> column?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form id="deleteCardForm2" class="deleteCardForm" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="card-id">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Hapus Kartu Modal Depan -->

        <style>
            .deleteCover:active {
                color: #ffffff
            }
            .fa-pencil:hover{
                color: #212529;
            }
            .fa-pencil:active{
                color: #fff;
            }
            .fa-trash:active{
                color: #fff;
            }
            .margin-bottom-10 {
                margin-bottom: 10px;
                margin-top: 5px;
            }
            .margin-bottom-0 {
                margin-bottom: 0px;
            }
            .icon-trash {
                color: #626F86;
                transition: color 0.3s;
            }

            .icon-trash:hover {
                color: #dc3546e1;
            }

            .icon-trash:active {
                color: #e62034;
            }

            .icon-comment {
                color: #626F86;
                transition: color 0.3s;
            }

            .icon-comment:hover {
                color: #157347;
            }

            .icon-comment:active {
                color: #146c43;
            }

            .fa-eye {
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
                margin-bottom: -5px;
            }
            input[type="checkbox"]:checked {
                background-color: #6cd274;
                border-color: #6cd274;
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
                margin-bottom: 25px;
                margin-top:-4px;
                width:84.8%;
                margin-left:5.5%
            }
            .progress2 {
                display:-ms-flexbox;
                display:flex;
                height:1rem;
                overflow:hidden;
                line-height:0;
                font-size:.75rem;
                background-color:#e9ecef;
                border-radius:1rem;
                margin-bottom: 25px;
                margin-top:-4px;
                width:90%;
                margin-left:5.5%
            }
            .card-cover-green:hover {
                background-color: #216E4E;
            }
            .card-cover-yellow:hover {
                background-color: #7F5F01;
            }
            .card-cover-orange:hover {
                background-color: #A54800;
            }
            .card-cover-red:hover {
                background-color: #AE2E24;
            }
            .card-cover-purple:hover {
                background-color: #5E4DB2;
            }
            .card-cover-blue:hover {
                background-color: #0055CC;
            }
            .card-cover-sky:hover {
                background-color: #206A83;
            }
            .card-cover-lime:hover {
                background-color: #4C6B1F;
            }
            .card-cover-pink:hover {
                background-color: #943D73;
            }
            .card-cover-black:hover {
                background-color: #596773;
            }
            .kolom-card {
                --loghub-border-opacity: 1;
                color: #44546F !important;
                min-width: 325px !important;
                padding: 1rem !important;
                border-radius: 12px;
                box-shadow: 0px 1px 1px #091e4240 !important;
                background-color: #f1f2f4 !important;
                border-color: rgb(229 231 235 / var(--loghub-border-opacity)) !important;
                height: 1%;
                cursor: pointer;
            }
            .border-darks {border: 2px solid transparent !important; cursor: pointer;}
            .border-darkss {border-color: #d1d1d1 !important;}
            .border-darks:focus {border-color: #343a40 !important; cursor: pointer;}
            .border-dark:hover {background-color: #091E420F; !important; cursor: pointer;}
            .border-darks:hover {background-color: #091E420F; !important; cursor: pointer;}

            .bg-grad-system {
                position: relative;
                background: #FFFFFF;
                border: 1px solid #dee2e6;
            }

            .bg-grad-system::after {
                content: "System";
                position: absolute;
                font-size: 16px;
                top: 60%;
                left: 45px;
                color: #87919a;
                font-weight: bold;
            }

            .bg-grad-system.system {
                background: #FFFFFF !important;
                border: 0;
            }

            .bg-grad-system.no-after::after {
                display: none;
            }

            @foreach ( $dataColumnCard as $dataKolom )
                @foreach ($dataKolom->cards as $isianKartu)
                    .isian-history{{ $isianKartu->id }} {
                        display: flex;
                        width: 100%;
                        gap: 0.25rem !important;
                        --loghub-border-opacity: 1;
                        background-color: #f1f2f4;
                        box-shadow: 0px 1px 1px #091e4240;
                        border-color: rgb(229 231 235 / var(--loghub-border-opacity));
                        border-radius: 12px;
                    }
                @endforeach
            @endforeach
            
            @foreach($result_tema as $sql_mode => $mode_tema)
                @if ($mode_tema->tema_aplikasi == 'Gelap')
                    .fa-eye { color: white}
                    p {color: {{ $mode_tema->warna_mode }} !important}
                    .custom-modal .tag-list {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .deskripsi-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .checklist-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .activity-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .border-dark {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important;}
                    .border-darks {border: 2px solid transparent !important; cursor: pointer;}
                    .border-darkss {border-color: #d1d1d175 !important;}
                    .border-darks:focus {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important; cursor: pointer;}
                    .border-dark:hover {background-color: {{ $mode_tema->warna_sistem }} !important; cursor: pointer;}
                    .border-darks:hover {background-color: {{ $mode_tema->warna_sistem }} !important; cursor: pointer;}
                    .isian-keterangan {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .isian-checklist {color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    input[type=text] {color: white;   background-color: {{ $mode_tema->warna_mode }} !important}
                    .dynamicCheckboxLabel {background-color: {{ $mode_tema->warna_mode }} !important}
                    input[type="checkbox"] {background-color: {{ $mode_tema->warna_mode }} !important; border: 2px solid white !important}
                    input[type="checkbox"]:checked {border-color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .kolom-card {background-color: {{ $mode_tema->warna_sistem }} !important; border-color: {{ $mode_tema->warna_sistem_tulisan }} !important; color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    .progress{background-color: {{ $mode_tema->warna_sistem }} !important;}
                    .progress2{background-color: {{ $mode_tema->warna_sistem }} !important;}
                    .move-card {color: {{ $mode_tema->warna_sistem_tulisan }} !important;}
                    .container-footer .border {background-color: #4BCE97 !important;}
                    .fa-clock {color: #808080 !important;}
                    .card-cover-green {background-color: #216E4E;}
                    .card-cover-yellow {background-color: #7F5F01;}
                    .card-cover-orange {background-color: #A54800;}
                    .card-cover-red {background-color: #AE2E24;}
                    .card-cover-purple {background-color: #5E4DB2;}
                    .card-cover-blue {background-color: #0055CC;}
                    .card-cover-sky {background-color: #206A83;}
                    .card-cover-lime {background-color: #4C6B1F;}
                    .card-cover-pink {background-color: #943D73;}
                    .card-cover-black {background-color: #596773;}
                    .card-cover2-green {background-color: #216E4E;}
                    .card-cover2-yellow {background-color: #7F5F01;}
                    .card-cover2-orange {background-color: #A54800;}
                    .card-cover2-red {background-color: #AE2E24;}
                    .card-cover2-purple {background-color: #5E4DB2;}
                    .card-cover2-blue {background-color: #0055CC;}
                    .card-cover2-sky {background-color: #206A83;}
                    .card-cover2-lime {background-color: #4C6B1F;}
                    .card-cover2-pink {background-color: #943D73;}
                    .card-cover2-black {background-color: #596773;}
                    .sidebar-menu li a:hover {color: #ffffff !important}
                    .bg-grad-system.system {background: #292D3E !important}
                    .bg-grad-system {background: #737788; border: 0}
                    .bg-grad-system::after{color: #3e3f43;}

                    @foreach ( $dataColumnCard as $dataKolom )
                        @foreach ($dataKolom->cards as $isianKartu)
                            .isian-history{{ $isianKartu->id }} {background: rgb(31 28 54) !important}
                        @endforeach
                    @endforeach
                    
                @endif
            @endforeach
        </style>

    @section('script')
        <script src="{{ asset('assets/js/memuat-pattern-board.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-onclick-board.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-ulang.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-modal.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-shortcut.js?v='.time()) }}"></script>

        <script>
            history.pushState({}, "", '/user/tim/papan/{{ $team->id }}/{{ $board->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Team Card - User | Loghub - PT TATI ';
        </script>
    @endsection
@endsection