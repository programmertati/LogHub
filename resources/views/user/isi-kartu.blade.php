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
                        <a href="#" class="dropdown-item card-cover4-{{ $isianKartu->pattern }}" data-toggle="dropdown" data-auto-close="outside" id="formCover-{{ $isianKartu->id }}" aria-expanded="false"><i class="fa-solid fa-clapperboard m-r-5"></i><span id="coverText">{{ $isianKartu->pattern ? 'Change Cover' : 'Add Cover' }}</span></a>
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
                        <a href="#" class="dropdown-item" onclick="deleteCardModal('{{ $isianKartu->id }}', '{{ $isianKartu->name }}', '{{ $dataKolom->name }}', '{{ route('hapusKartu', ['card_id' => $isianKartu->id]) }}');">
                            <i class='fa fa-trash-o m-r-5'></i> Delete Card
                        </a>
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
        <div class="info-status13">
            <span class="text-status13"><b>ESC (Close Modal)</b></span>
            <span aria-hidden="true">&times;</span>
        </div>
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
                <textarea onclick="mentionTags2('keterangan{{ $isianKartu->id }}')" class="isian-deskripsi" rows="4" id="keterangan{{ $isianKartu->id }}" name="keterangan" placeholder="Enter a description">{{ $isianKartu->description }}</textarea><br>
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
                                            <p class="isian-activity">{{ $history->name }}<br><span>{{ $history->content }}</span></p>
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