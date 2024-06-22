@extends('layouts.master')
@section('content')

        <!-- Page Wrapper -->
        <div class="page-wrapper" style="height: 100vh">

            <!-- Tampilan Background Kolom & Card -->
            <div class="overflow-x-scroll overflow-y-auto bg-grad-{{ $board->pattern }}" style="height: 100%">

                <!-- Tampilan Kolom & Kartu -->
                <div class="tampilan-kolom gap-4 p-4" id="cardContainer">

                    <a href="#" data-toggle="modal" data-target="#addCol" class="flex-col flex-shrink-0 gap-2 px-4 py-2 transition shadow-lg cursor-pointer select-none h-4h w-72 rounded-xl bg-slate-100 hover:scale-105 hover:relative">
                        <p class="flex items-center justify-center gap-4 text-black"><i class="fa-solid fa-plus fa-lg"></i>Add list...</p>
                    </a>

                    <!-- Tampilan Kolom -->
                    @php
                    $sortedData = $dataColumnCard->count() > 0 ? $dataColumnCard->sortBy(function($item) {
                        return $item->position == 0 ? PHP_INT_MAX : $item->position;
                    }) : $dataColumnCard->sortBy('id');
                    @endphp
                    @foreach ($sortedData as $dataKolom)
                        <div class="kolom-card-{{ $dataKolom->id }}" id="kolom-card-{{ $dataKolom->id }}" data-id="{{ $dataKolom->id }}" onmouseenter="aksiKolomShow({{ $dataKolom->id }})" onmouseleave="aksiKolomHide({{ $dataKolom->id }})">

                            <!-- Tampilan Aksi Edit & Hapus -->
                            <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom{{ $dataKolom->id }}">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateColumn{{ $dataKolom->id }}">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteColumn{{ $dataKolom->id }}">
                                        <i class='fa fa-trash-o m-r-5'></i> Delete
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
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateColumn{{ $dataKolom->id }}">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                </div>
                            </div> --}}
                            <!-- /Tampilan Aksi Edit & Hapus Bersama Auth -->

                            <!-- Tampilan Nama Kolom -->
                            <h5 id="kolomNama{{ $dataKolom->id }}" class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">{{ $dataKolom->name }}</h5>
                            <!-- /Tampilan Nama Kolom -->

                            <ul class="card-container" id="containerCard{{ $dataKolom->id }}">

                                <!-- Tampilan Kartu -->
                                @php
                                $sortedData = $dataKolom->cards->count() > 0 ? $dataKolom->cards->sortBy(function($item) {
                                    return $item->position == 0 ? PHP_INT_MAX : $item->position;
                                }) : $dataKolom->cards->sortBy('id');
                                @endphp
                                @foreach ($sortedData as $dataKartu)
                                    <li class="kartu-loghub" data-id="{{ $dataKartu->id }}" onmouseenter="aksiKartuShow({{ $dataKartu->id }})" onmouseleave="aksiKartuHide({{ $dataKartu->id }})" style="position: relative;">
                                        
                                        <!-- Tampilan Aksi Edit -->
                                        {{-- @if($dataKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                            <div class="cover-card card-cover2-{{ $dataKartu->pattern }} {{ $dataKartu->pattern ? '' : 'hiddens' }}" id="cover-card-{{ $dataKartu->id }}"></div>
                                            <a href="#" data-toggle="modal" data-target="#editCard{{ $dataKartu->id }}">
                                                <div class="aksi-card" id="aksi-card{{ $dataKartu->id }}" style="position: absolute !important;">
                                                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                                                </div>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#editCard{{ $dataKartu->id }}">
                                                <div class="aksi-card" id="aksi-card{{ $dataKartu->id }}">
                                                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                                                </div>
                                            </a>
                                        {{-- @endif --}}
                                        <!-- /Tampilan Aksi Edit -->

                                        <!-- Tampilan Kartu Pengguna -->
                                        <a href="#" data-toggle="modal" data-target="#isianKartu{{ $dataKartu->id }}">
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
                                                    <div class="progress-checklist-light
                                                        @foreach($result_tema as $sql_mode => $mode_tema)
                                                            @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                progress-checklist-dark
                                                            @endif
                                                        @endforeach

                                                        @php
                                                            $totalPercentage = 0;
                                                            $totalCount = 0;
                                                        @endphp
                                                        @foreach ($dataKartu->titleChecklists as $titleChecklistss)
                                                            @php
                                                                $totalCount++;
                                                                $totalPercentage += $titleChecklistss->percentage;
                                                            @endphp
                                                        @endforeach

                                                        @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                            progress-checklist-100-light
                                                            @foreach($result_tema as $sql_mode => $mode_tema)
                                                                @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                    progress-checklist-100-dark
                                                                @endif
                                                            @endforeach
                                                        @endif">

                                                        @php
                                                            $perChecklist = 0;
                                                            $jumlahChecklist = 0;
                                                        @endphp
                                                        @foreach ($dataKartu->titleChecklists as $titleChecklists)
                                                            @foreach ($titleChecklists->checklists as $checklists)
                                                                @if(!empty($checklists->is_active))
                                                                    @php
                                                                        $perChecklist++;
                                                                    @endphp
                                                                @endif
                                                                @if(!empty($checklists->title_checklists_id))
                                                                    @php
                                                                        $jumlahChecklist++;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        @endforeach

                                                        @if($perChecklist > 0 || $jumlahChecklist > 0)
                                                            <div class="info-status9">
                                                                <i class="fa-regular fa-square-check icon-check-not-full-light
                                                                    @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                                        icon-check-full-light
                                                                    @endif
                                                                    @foreach($result_tema as $sql_mode => $mode_tema)
                                                                        @if ($mode_tema->tema_aplikasi == 'Gelap')
                                                                            icon-check-not-full-dark
                                                                            @if ($totalCount > 0 && $totalPercentage / $totalCount == 100)
                                                                                icon-check-full-dark
                                                                            @endif
                                                                        @endif
                                                                    @endforeach">
                                                                </i>
                                                                <span class="text-status9"><b>Checklist items</b></span>
                                                                <span class="total">{{ $perChecklist }}/{{ $jumlahChecklist }}</span>
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
                                    <form action="{{ route('addCard2', ['board_id' => $board->id, 'team_id' => $board->team_id, 'column_id' => $dataKolom->id ]) }}" method="POST">
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
                    @include('allrole.pindahkolom')
                    <!-- /Tampilan Kolom -->
                    
                </div>
                <!-- /Tampilan Kolom & Kartu -->

            </div>
            <!-- /Tampilan Background Kolom & Card -->

        </div>
        <!-- /Page Wrapper -->

        {!! Toastr::message() !!}

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
                        <form action="{{ route('addCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" id="addColForm" method="POST">
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
        @foreach ( $dataColumnCard as $dataKolom )
            <div id="updateColumn{{ $dataKolom->id }}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Columns</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="updateColumnForm{{ $dataKolom->id }}" action="{{ route('updateCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="column_id" id="column_id" value="{{ $dataKolom->id }}">
                                <div class="form-group">
                                    <label>Column's Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control @error('column_name') is-invalid @enderror" id="column_name" name="column_name" placeholder="Enter a column's name" value="{{ $dataKolom->name }}" required />
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
            @include('user.updatecolumn')
        @endforeach
        <!-- /Perbaharui Kolom Modal -->

        <!-- Hapus Kolom Modal Dicomment ketika menggunakan auth -->
        @foreach ( $dataColumnCard as $hapusKolom )
            <div id="deleteColumn{{ $hapusKolom->id }}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Columns "{{ $hapusKolom->name }}"?</h3>
                                <p>Are you sure you want to delete this column?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <form id="deleteColumnForm-{{ $hapusKolom->id }}" class="deleteColumnForm" data-column-id="{{ $hapusKolom->id }}" action="{{ route('deleteCol2', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="column_id" value="{{ $hapusKolom->id }}">
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
        @endforeach
        @include('user.deletecolumn')
        <!-- /Hapus Kolom Modal Dicomment ketika menggunakan auth -->

        <!-- Isian Kartu Modal -->
        @foreach ( $dataColumnCard as $dataKolom )
            @foreach ($dataKolom->cards as $isianKartu)
                <div id="isianKartu{{ $isianKartu->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="cover-card card-cover2-{{ $isianKartu->pattern }} {{ $isianKartu->pattern ? '' : 'hiddens' }}" id="cover-card2-{{ $isianKartu->id }}" style="height: 116px !important;"></div>
                            <div class="modal-header" style="justify-content: left;">
                                <div class="icon-card">
                                    <i class="fa-solid fa-credit-card fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="nama-kartu" data-kartu-id="{{ $isianKartu->id }}">{{ $isianKartu->name  }}</h5>
                                    {{-- @if($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}

                                        <!-- Untuk Pembaharuan Cover dan Hapus Kartu -->
                                        <div class="info-status4">
                                            <div class="dropdown dropdown-action opsi-hapus-cover" id="opsi-hapus-cover{{ $isianKartu->id }}">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a href="#" class="dropdown-item card-cover4-{{ $isianKartu->pattern }}" data-toggle="dropdown" data-auto-close="outside" id="formCover-{{ $isianKartu->id }}" aria-expanded="false"><i class="fa-solid fa-clapperboard"></i>  <span id="coverText">{{ $isianKartu->pattern ? 'Change Cover' : 'Add Cover' }}</span></a>
                                                    <form id="updateCoverForm" class="dropdown-menu p-4" style="min-width: 16rem !important; margin-top: -37px !important; margin-left: -19px !important;">
                                                        @csrf
                                                        <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                        <div class="topnav-dropdown-header">
                                                            <span class="move-card">Cover</span>
                                                        </div><br>
                                                        <div class="flex flex-col w-full gap-2">
                                                            <label style="font-size: 18px; @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: white; @endif @endforeach">Color's</label>
                                                            <small class="text-danger" style="font-weight: 700; font-size: 13px;">*Please select (Color's) again when updating.</small>
                                                            <input type="hidden" id="cover-field-{{ $isianKartu->id }}" name="pattern" value="{{ isset($covers[0]) ? $covers[0] : 'default_value' }}">
                                                            <div class="flex flex-wrap items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-auto border-2 border-gray-200 h-36 rounded-xl">
                                                                @isset($covers)
                                                                    @foreach ($covers as $cover)
                                                                        <div onclick="selectPattern2('{{ $cover }}', '{{ $isianKartu->id }}')" class="{{ $cover == $covers[0] ? 'order-first' : '' }} h-full flex flex-wrap border-4 rounded-lg w-36 card-cover-{{ $cover }} hover:border-black" id="cover-{{ $cover }}-{{ $isianKartu->id }}" style="height: 40% !important; width: 5rem !important; cursor: pointer">
                                                                            <div id="check-{{ $cover }}-{{ $isianKartu->id }}" class="flex flex-wrap items-center justify-center w-full h-full {{ $cover == $covers[0] ? 'opacity-100' : 'opacity-0' }}">
                                                                                <i class="fa-solid fa-circle-check"></i>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <a href="#" class="dropdown-item card-cover3-{{ $isianKartu->pattern }} {{ $isianKartu->pattern ? '' : 'hiddens' }}" id="cover-card3-{{ $isianKartu->id }}" data-toggle="modal">
                                                        <form onclick="hapusCoverCard2('{{ $isianKartu->id }}', event)">
                                                            @csrf
                                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                            <button type="submit" class="deleteCover" style="@foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: white; @endif @endforeach">
                                                                <i class='fa fa-trash-o m-r-5'></i> Delete Cover
                                                            </button>
                                                        </form>
                                                    </a>
                                                    @include('user.script4')
                                                    <a href="#" class="dropdown-item" data-toggle="modal">
                                                        <form id="deleteCardForm{{ $isianKartu->id }}" class="deleteCardForm" data-id="{{ $isianKartu->id }}" action="{{ route('hapusKartu2', ['card_id' => $isianKartu->id]) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $isianKartu->id }}">
                                                            <button type="submit" class="deleteCard" style="@foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: white; @endif @endforeach"><i class='fa fa-trash-o m-r-5'></i> Delete Card</button>
                                                        </form>
                                                    </a>
                                                    @include('user.deletecard')
                                                </div>
                                            </div>
                                            <span class="text-status4" style="line-height: 20px"><b>Delete / Cover</b></span>
                                        </div>
                                        <!-- /Untuk Pembaharuan Cover dan Hapus Kartu -->
                                        
                                    {{-- @endif --}}
                                    <div class="aksi-move-card">
                                        <p class="tag-list">in list</p>
                                        <div class="dropdown info-status10">
                                            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false" data-auto-close="outside" data-kolom-id="{{ $dataKolom->id }}" style="color: #489bdb; @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: #489bdb !important; @endif @endforeach margin-left: -12px; text-decoration: underline;">{{ $dataKolom->name }}</a>
                                            <span class="text-status10"><b>Move Card's</b></span>
                                            <form class="dropdown-menu p-4" style="min-width: 18rem !important; margin-left: 34px !important;">
                                                <div class="topnav-dropdown-header">
                                                    <span class="move-card">Move Card</span>
                                                </div><br>
                                                <div class="form-group">
                                                    <label for="select-card" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif @endforeach>List Card</label>
                                                    <select onclick="changeCard('{{ $isianKartu->id }}')" id="select-card{{ $isianKartu->id }}" class="theSelect" style="width: 100% !important; height: 36px; @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: white; background-color: #292D3E; @endif @endforeach cursor:pointer">
                                                        <option selected disabled>-- Select Card --</option>
                                                        @foreach ($dataKolom->cards->sortBy('column_id') as $dataKartu)
                                                            <option value="#isianKartu{{ $dataKartu->id }}">{{ $dataKartu->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                            {{-- @if($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                <!-- Tambah & Edit Keterangan -->
                                <div class="menu-keterangan">
                                    <div class="icon-align">
                                        <i class="fa-solid fa-align-left fa-lg"></i>
                                    </div>
                                    <div class="keterangan-tag">
                                        <p class="deskripsi-keterangan">Description</p>
                                        <form id="myForm{{ $isianKartu->id }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <textarea onclick="mentionTags2('keterangan{{ $isianKartu->id }}')" class="border border-1 border-dark w-40l p-2 rounded-xl" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white !important; background-color: #292D3E" @endif @endforeach rows="4" id="keterangan{{ $isianKartu->id }}" name="keterangan" placeholder="Enter a description">{{ $isianKartu->description }}</textarea><br>
                                            <div class="mention-tag-keterangan" id="mention-tag-keterangan{{ $isianKartu->id }}"></div>
                                            <div class="aksi-update-keterangan gap-2">
                                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButton{{ $isianKartu->id }}">Save</button>
                                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButton{{ $isianKartu->id }}">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    @include('user.script')
                                    @include('user.script6')
                                </div>
                                <!-- /Tambah & Edit Keterangan -->

                                <!-- Tambah Judul Checklist -->
                                <form id="myFormTitle{{ $isianKartu->id }}" method="POST">
                                    @csrf
                                    <div class="menu-tambah-checklist flex flex-col flex-wrap">
                                        <div class="header-checklist flex gap-5">
                                            <div class="icon-tambah-checklist hidden" id="iconCheck-{{ $isianKartu->id }}">
                                                <i class="fa-regular fa-square-check fa-xl" style="color: #489bdb;"></i>
                                            </div>
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <input type="text" class="border border-1 border-dark w-40l p-2 rounded-xl hidden" id="titleChecklist{{ $isianKartu->id }}" name="titleChecklist" placeholder="Enter a title" required>
                                        </div>
                                        <div class="aksi-tambah-checklist gap-2">
                                            <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitle{{ $isianKartu->id }}">Save</button>
                                            <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitle{{ $isianKartu->id }}">Cancel</button><br>
                                        </div>
                                    </div>
                                </form>
                                <div class="tambah-checklist">
                                    <button type="button" class="btn btn-outline-info icon-item" id="addTitle-{{ $isianKartu->id }}"><i class="fa-regular fa-square-check fa-lg"></i> Add Checklist</button>
                                </div>
                                <!-- /Tambah Judul Checklist -->
                            {{-- @else
                                <!-- Tampilan Keterangan Apabila Bukan Punyanya -->
                                <div class="menu-keterangan">
                                    <div class="icon-align">
                                        <i class="fa-solid fa-align-left fa-lg"></i>
                                    </div>
                                    <div class="keterangan-tag">
                                        <p class="deskripsi-keterangan">Description</p>
                                        <textarea class="border border-1 border-dark w-403 p-2 rounded-xl" rows="4" readonly @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white !important; background-color: #292D3E" @endif @endforeach>{{ $isianKartu->description }}</textarea><br>
                                    </div>
                                </div>
                                <!-- /Tampilan Keterangan Apabila Bukan Punyanya -->
                            @endif --}}
                                
                            {{-- @if($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                @foreach ($isianKartu->titleChecklists as $titleChecklists)
                                <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl">
                                    <!-- Perbaharui & Hapus Judul Checklist -->
                                    <div class="header-checklist flex justify-content">
                                        <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                                        <form id="myFormTitleUpdate{{ $titleChecklists->id }}" method="POST" class="update-title">
                                            @csrf
                                                <input type="hidden" id="title_id" name="title_id" value="{{ $titleChecklists->id }}">
                                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" style="font-size: 17px" id="titleChecklistUpdate{{ $titleChecklists->id }}" name="titleChecklistUpdate" placeholder="Enter a title" value="{{$titleChecklists->name}}">
                                                <div class="aksi-update-title gap-2">
                                                    <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate{{ $titleChecklists->id }}">Save</button>
                                                    <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitleUpdate{{ $titleChecklists->id }}">Cancel</button>
                                                </div>
                                        </form>
                                        <form id="myFormTitleDelete{{ $titleChecklists->id }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $titleChecklists->id }}">
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <div class="icon-hapus-title" id="hapus-title{{ $titleChecklists->id }}">
                                                <button type="submit" style="border: none; background: none; padding: 0;">
                                                    <div class="info-status5">
                                                        <i class="fa-solid fa-trash fa-lg icon-trash" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif @endforeach></i>
                                                        <span class="text-status5"><b>Delete Title's</b></span>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
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
                                    @include('user.script2')
                                    @foreach ($titleChecklists->checklists as $checklists)
                                    <div class="input-checklist">
                                        <!-- Tampilan Checklist -->
                                        <form id="myFormChecklistUpdate{{ $checklists->id }}" method="POST" class="form-checklist">
                                            @csrf
                                            <input class="dynamicCheckbox" type="checkbox" id="{{$checklists->id}}" name="{{$checklists->id}}" {{$checklists->is_active == '1' ? 'checked' : ''}}>
                                            <label class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl  {{$checklists->is_active == '1' ? 'strike-through' : ''}}" id="labelCheckbox-{{$checklists->id}}" for="labelCheckbox-{{$checklists->id}}">{{$checklists->name}}</label>
                                            <input type="hidden" id="checklist_id" name="checklist_id" value="{{ $checklists->id }}">
                                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                            <input onclick="mentionTags4('checkbox-{{ $checklists->id }}')" type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-{{$checklists->id}}" name="checkbox-{{$checklists->id}}" value="{{$checklists->name}}" placeholder="Enter a checklist">
                                            <div class="mention-tag" id="mention-tag-checkbox{{ $checklists->id }}"></div>
                                            @include('user.script8')
                                            
                                            <!-- Aksi Update Checklist -->
                                            <div class="aksi-update-checklist gap-2 margin-bottom-0" id="checklist-{{ $checklists->id }}">
                                                <button type="submit" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-{{ $checklists->id }}">Save</button>
                                                <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-{{ $checklists->id }}">Cancel</button>
                                            </div>
                                            <!-- /Aksi Update Checklist -->
                                        </form>
                                        <!-- Icon Hapus Checklist -->
                                        {{-- @if($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                            <form id="myFormChecklistDelete{{ $checklists->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                                <input type="hidden" id="title_checklists_id" name="title_checklists_id" value="{{ $titleChecklists->id }}">
                                                <input type="hidden" id="id" name="id" value="{{ $checklists->id }}">
                                                <div class="icon-hapus-checklist" id="hapus-checklist{{ $checklists->id }}">
                                                    <button type="submit" class="deletes" id="deleteButtonChecklist-{{ $checklists->id }}" style="border: none; background: none; padding: 0;">
                                                        <div class="info-status6">
                                                            <i class="fa-solid fa-trash fa-lg icon-trash" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif @endforeach></i>
                                                            <span class="text-status6"><b>Delete Checklist</b></span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </form>
                                        {{-- @endif --}}
                                        <!-- /Icon Hapus Checklist -->
                                    </div>
                                    <!-- /Tampilan Checklist -->

                                    @include('user.script3')
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
                                                <input onclick="mentionTags('checklist{{ $titleChecklists->id }}')" type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist{{ $titleChecklists->id }}" name="checklist" placeholder="Enter a checklist" required>
                                                <div class="mention-tag" id="mention-tag-checklist{{ $titleChecklists->id }}"></div>
                                                @include('user.script5')
                                            </div>
                                            <div class="aksi-update-checklist gap-2">
                                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist{{ $titleChecklists->id }}">Save</button>
                                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist{{ $titleChecklists->id }}">Cancel</button>
                                            </div>
                                    </form>
                                    <button type="button" class="btn btn-outline-info" id="AddChecklist{{ $titleChecklists->id }}">Add an item</button>
                                    <!-- Tambah baru checklist -->
                                    </div>
                                @endforeach
                            {{-- @else
                                @foreach ($isianKartu->titleChecklists as $titleChecklists)
                                    <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl">
                                        <!-- Perbaharui & Hapus Judul Checklist -->
                                        <div class="header-checklist flex gap-4">
                                            <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                                            <p class="isian-title border border-1 border-darks w-408 p-2 rounded-xl" style="font-size: 17px; margin-left: 20px; @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') color: white !important @endif @endforeach">{{$titleChecklists->name}}</p>
                                        </div>
                                        <!-- /Perbaharui & Hapus Judul Checklist -->

                                        <!-- Progress Bar Checklist -->
                                        <div class="progress2" data-checklist-id="{{ $titleChecklists->id }}">
                                            <div class="progress-bar progress-bar-{{ $titleChecklists->id }}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                0%
                                            </div>
                                        </div>
                                        <!-- Progress Bar Checklist -->
                                    
                                        <!-- Perbaharui & Hapus Checklist -->
                                        @include('user.script2')
                                        @foreach ($titleChecklists->checklists as $checklists)
                                            <div class="input-checklist">
                                                <!-- Tampilan Checklist -->
                                                <input class="dynamicCheckbox" type="checkbox" id="{{$checklists->id}}" name="{{$checklists->id}}" {{$checklists->is_active == '1' ? 'checked' : ''}} disabled>
                                                <label class="dynamicCheckboxLabel border border-1 border-darks w-408 p-2 rounded-xl  {{$checklists->is_active == '1' ? 'strike-through' : ''}}">{{$checklists->name}}</label>
                                            </div>
                                            <!-- /Tampilan Checklist -->
                                        @endforeach
                                        <!-- /Perbaharui & Hapus Checklist -->
                                    </div>
                                @endforeach
                            @endif --}}
                                <div class="menu-activity">
                                    <div class="header-activity flex">
                                        <i class="fa-solid fa-list-ul fa-lg"></i>
                                        <p class="activity-keterangan">Activity </p>
                                        <div onclick="showActivity('{{ $isianKartu->id }}')" class="icon-lihat">
                                            <div class="info-status7">
                                                <i class="fa-solid fa-eye fa-lg icon-comment" id="showActivityIcon{{ $isianKartu->id }}"></i>
                                                <span class="text-status7"><b>Seeing Comment's</b></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-komentar flex gap-4">
                                        <img class="avatar-comment" src="{{ URL::to('/assets/images/' . Auth::user()->avatar) }}" loading="lazy">
                                        <form id="commentForm{{ $isianKartu->id }}" action="{{ route('komentarKartu2', ['card_id' => $isianKartu->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                            <input type="hidden" name="card_id" value="{{ $isianKartu->id }}">
                                            <textarea onclick="saveComment('{{ $isianKartu->id }}'); mentionTags3('contentarea{{ $isianKartu->id }}')" class="form-control border border-1 border-dark rounded-xl" rows="1" cols="77" id="contentarea{{ $isianKartu->id }}" name="content" placeholder="Write a comment..."></textarea>
                                            <div class="mention-tag-comment" id="mention-tag-comment{{ $isianKartu->id }}"></div>
                                            <button type="submit" class="btn btn-outline-info hidden" style="margin-top: 1%;" id="simpanButton{{ $isianKartu->id }}">Save</button>
                                        </form>
                                        @include('user.comment')
                                        @include('user.script7')
                                    </div>
                                    <div class="activity-tag hiddens" id="showActivity{{ $isianKartu->id }}">
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
                                                        <div class="isian-tag" id="isianTag{{ $isianKartu->id }}">
                                                            @if ($history->type === 'event')
                                                                <div class="isian-history">
                                                                    <img class="avatar-activity" src="{{ URL::to('/assets/images/' . $history->avatar) }}" loading="lazy">
                                                                    <div class="title-activity">
                                                                        @if (strpos($history->content, 'Membuat Kartu') !== false)
                                                                            <!-- Berdasarkan kolom masing-masing -->
                                                                                <p class="isian-activity">{{ $history->name }}, added {{ $card->name }} to this column</p>
                                                                            @elseif (strpos($history->content, 'Memperbaharui Kartu') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has updated {{ $card->name }} to this column</p>
                                                                            @elseif (strpos($history->content, 'Memperbaharui Keterangan Kartu') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has updated card description {{ $card->name }} to this column</p>
                                                                            <!-- /Berdasarkan kolom masing-masing -->

                                                                            <!-- Berdasarkan kartu masing-masing -->
                                                                            @elseif (strpos($history->content, 'Membuat Judul Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, added title {{ $titleChecklists->name }} to this card</p>
                                                                            @elseif (strpos($history->content, 'Memperbaharui Judul Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has updated title {{ $titleChecklists->name }} to this card</p>
                                                                            @elseif (strpos($history->content, 'Menghapus Judul Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has deleted title {{ $titleChecklists->name }} to this card</p>
                                                                            <!-- /Berdasarkan kartu masing-masing -->

                                                                            <!-- Berdasarkan judul masing-masing -->
                                                                            @elseif (strpos($history->content, 'Membuat Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, added checklist to title {{ $titleChecklists->name }}</p>
                                                                            @elseif (strpos($history->content, 'Memperbaharui Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has updated checklist to title {{ $titleChecklists->name }}</p>
                                                                            @elseif (strpos($history->content, 'Menghapus Checklist') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has deleted checklist to title {{ $titleChecklists->name }}</p>
                                                                            <!-- /Berdasarkan judul masing-masing -->

                                                                            <!-- Berdasarkan cover masing-masing -->
                                                                            @elseif (strpos($history->content, 'Memperbaharui Cover Kartu') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has updated cover to this card</p>
                                                                            @elseif (strpos($history->content, 'Menghapus Cover Kartu') !== false)
                                                                                <p class="isian-activity">{{ $history->name }}, has deleted cover to this card</p>
                                                                            <!-- /Berdasarkan cover masing-masing -->
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="waktu-history">
                                                                    <p class="isian-activity"><i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F \p\u\k\u\l h:i A') }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($history->type === 'comment')
                                                                <div class="isian-history{{ $isianKartu->id }}">
                                                                    <img class="avatar-activity" src="{{ URL::to('/assets/images/' . $history->avatar) }}" loading="lazy">
                                                                    <div class="title-activity">
                                                                        <p>{{ $history->name }}<br><span>{{ $history->content }}</span></p>
                                                                    </div>
                                                                </div>
                                                                <div class="waktu-history">
                                                                    <p class="isian-activity"><i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F \p\u\k\u\l h:i A') }}</p>
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

        <!-- Perbaharui Kartu Modal -->
        @foreach ( $dataColumnCard as $dataKolom )
            @foreach ($dataKolom->cards as $dataKartu)
                <div id="editCard{{ $dataKartu->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Cards</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="updateCardForm{{ $dataKartu->id }}" action="{{ route('perbaharuiKartu2', ['card_id' => $dataKartu->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $dataKartu->id  }}">
                                    <div class="form-group">
                                        <label>Card's Name</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $dataKartu->name  }}" placeholder="Enter a card's name" required />
                                        @error('name')
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
                @include('user.updatecard')
            @endforeach
        @endforeach
        <!-- /Perbaharui Kartu Modal -->

        <style>
            .deleteCard:active {
                color: #ffffff
            }
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
            @foreach ( $dataColumnCard as $dataKolom )
                .kolom-card-{{ $dataKolom->id }} {
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
                    @foreach ( $dataColumnCard as $dataKolom )
                        .kolom-card-{{ $dataKolom->id }} {background-color: {{ $mode_tema->warna_sistem }} !important; border-color: {{ $mode_tema->warna_sistem_tulisan }} !important; color: {{ $mode_tema->warna_sistem_tulisan }} !important}
                    @endforeach
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
                @endif
            @endforeach
        </style>

    @section('script')
        <script src="{{ asset('assets/js/memuat-data-kolom-board.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-onclick-board.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>

        <script>
            history.pushState({}, "", '/user/tim/papan/{{ $team->id }}/{{ $board->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Team Card - User | Loghub - PT TATI ';
        </script>
    @endsection
@endsection