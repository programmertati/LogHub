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
                        <div class="kolom-card" id="kolom-card-{{ $dataKolom->id }}" data-id="{{ $dataKolom->id }}" onmouseenter="aksiKolomShow({{ $dataKolom->id }})" onmouseleave="aksiKolomHide({{ $dataKolom->id }})">

                            <!-- Tampilan Aksi Edit & Hapus -->
                            <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom{{ $dataKolom->id }}">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" onclick="updateColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('updateCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');" id="edit-column-{{ $dataKolom->id }}">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                    <a href="#" class="dropdown-item" onclick="deleteColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('deleteCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');">
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
                                    <a href="#" class="dropdown-item" onclick="updateColumnModal({{ $dataKolom->id }}, '{{ $dataKolom->name }}', '{{ route('updateCol', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}');" id="edit-column-{{ $dataKolom->id }}">
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
                                            <a href="#" onclick="updateCardModal({{ $dataKartu->id }}, '{{ $dataKartu->name }}', '{{ route('perbaharuiKartu', ['card_id' => $dataKartu->id]) }}');" id="edit-card-{{ $dataKartu->id }}">
                                                <div class="aksi-card" id="aksi-card{{ $dataKartu->id }}" style="position: absolute !important;">
                                                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                                                </div>
                                            </a>
                                            <a href="#" onclick="updateCardModal({{ $dataKartu->id }}, '{{ $dataKartu->name }}', '{{ route('perbaharuiKartu', ['card_id' => $dataKartu->id]) }}');" id="edit-card-{{ $dataKartu->id }}">
                                                <div class="aksi-card" id="aksi-card{{ $dataKartu->id }}">
                                                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                                                </div>
                                            </a>
                                        {{-- @endif --}}
                                        <!-- /Tampilan Aksi Edit -->

                                        <!-- Tampilan Kartu Pengguna -->
                                        {{-- <a href="#" data-toggle="modal" data-target="#isianKartu{{ $dataKartu->id }}"> --}}
                                            <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="
                                                $('#card_id').val({{ $dataKartu->id }});
                                                $('#form_kartu').submit();
                                            ">
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

      <!-- Isian Kartu Modal bayu -->
      <div id="isianKartu" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" id="form_kartu" class="form-horizontal" enctype="multipart/form-data" action="{{ route('getDataKartu') }}">
                    @csrf
                    <input type="hidden" name="card_id" id="card_id">
                </form>
                <div id="div_hasil">
                </div>
            </div>
        </div>
    </div>
    <script>
          $(document).ready(function() { 
            var loading = `<div id="loader-wrapper">
                            <div id="loader">
                                <div class="loader-ellips">
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                </div>
                            </div>
                        </div>`;
            $('#form_kartu').on('submit', function(e) {
                e.preventDefault(); 
                $("#div_hasil").append(loading);
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $("#div_hasil").html(data);
                    },
                    error: function(jXHR, textStatus, errorThrown) {
                        $("#div_hasil").html(textStatus);
                        alert(textStatus);
                    }
                });
            });
        });
    </script>
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

                    @foreach ( $dataColumnCard as $dataKolom )
                        @foreach ($dataKolom->cards as $isianKartu)
                            .isian-history{{ $isianKartu->id }} {background: rgb(31 28 54) !important}
                        @endforeach
                    @endforeach
                    
                @endif
            @endforeach
        </style>

    @section('script')
        <script src="{{ asset('assets/js/memuat-data-kolom-board.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-onclick-board.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-ulang.js?v='.time()) }}"></script>
        <script src="{{ asset('assets/js/memuat-modal.js?v='.time()) }}"></script>

        <script>
            history.pushState({}, "", '/user/tim/papan/{{ $team->id }}/{{ $board->id }}');
        </script>
        
        <script>
            document.getElementById('pageTitle').innerHTML = 'Team Card - User | Loghub - PT TATI ';
        </script>
    @endsection
@endsection