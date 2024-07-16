<script>
    $(document).ready(function(){
        const id = '{{ $isianKartu->id }}';
        // Section Keterangan
        
        // Set height Keterangan 
        const keterangan = $('#keterangan'+id).val();
        const lineCount = keterangan.split('\n').length;
        const desiredRows = lineCount + 2;
        $('#keterangan' + id).attr('rows', Math.max(desiredRows, 4));
        
        // Input keterangan
        $('#keterangan'+id).on('click', function(){
            $('#saveButton'+id).removeClass('hidden');
            $('#cancelButton'+id).removeClass('hidden');
        });
        // Button cancel form keterangan
        $('#cancelButton'+id).on('click', function(){
            $('#saveButton'+id).addClass('hidden');
            $('#cancelButton'+id).addClass('hidden');
            $('#myForm'+id)[0].reset();
        });
        // Form keterangan
        $('#myForm'+id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addDescription2') }}",
                data: formData,
                success: function(response){
                    $('#saveButton'+id).addClass('hidden');
                    $('#cancelButton'+id).addClass('hidden');
                    toastr.success('Berhasil memperbaharui keterangan!');
                    // Perbaharui Status Keterangan
                    if(response.status_keterangan == 'hide'){
                        $('#descriptionStatus' + id).addClass('hidden');
                    } else {
                        $('#descriptionStatus' + id).removeClass('hidden');
                    }
                },
                error: function(error){
                    toastr.error('Gagal memperbaharui keterangan!');
                }
            });
        });
        // End Section Keterangan

        // Section Title
        // Button add form title
         $('#addTitle-'+id).on('click', function(){
            $('#titleChecklist'+id).removeClass('hidden');
            $('#saveButtonTitle'+id).removeClass('hidden');
            $('#cancelButtonTitle'+id).removeClass('hidden');
            $('#iconCheck-'+id).removeClass('hidden');
            $('#addTitle-'+id).addClass('hidden');
            $('#myFormTitle'+id)[0].reset();
        });
        // Button cancel form title
        $('#cancelButtonTitle'+id).on('click', function(){
            $('#titleChecklist'+id).addClass('hidden');
            $('#saveButtonTitle'+id).addClass('hidden');
            $('#cancelButtonTitle'+id).addClass('hidden');
            $('#iconCheck-'+id).addClass('hidden');
            $('#addTitle-'+id).removeClass('hidden');
            $('#myFormTitle'+id)[0].reset();
        });
        // Form title
        $('#myFormTitle'+id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addTitle2') }}",
                data: formData,
                success: function(response){
                    // Mendapatkan ID dan Percentage dari Controller //
                    const title_id = `${response.titlechecklist.id}`;
                    const percentage = `${response.titlechecklist.percentage}`;

                    $('#titleChecklist'+id).addClass('hidden');
                    $('#saveButtonTitle'+id).addClass('hidden');
                    $('#cancelButtonTitle'+id).addClass('hidden');
                    $('#iconCheck-'+id).addClass('hidden');
                    $('#addTitle-'+id).removeClass('hidden');
                    toastr.success('Berhasil menambahkan judul!');
                     var newForm = `<div class="menu-checklist border border-1 border-darkss p-2 rounded-xl" data-id="${response.titlechecklist.id}">

                                        <!-- Perbaharui & Hapus Judul Checklist -->
                                        <div class="header-checklist flex justify-content">
                                            <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                                            <form id="myFormTitleUpdate${response.titlechecklist.id}" method="POST" class="update-title">
                                                @csrf
                                                    <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                                                    <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                    <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" style="font-size: 17px" id="titleChecklistUpdate${response.titlechecklist.id}" name="titleChecklistUpdate" placeholder="Enter a title" value="${response.titlechecklist.name}">
                                                    <div class="aksi-update-title gap-2">
                                                        <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate${response.titlechecklist.id}">Save</button>
                                                        <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitleUpdate${response.titlechecklist.id}">Cancel</button>
                                                    </div>
                                            </form>

                                            {{-- @if($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                                <form id="myFormTitleDelete${response.titlechecklist.id}" method="POST">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="${response.titlechecklist.id}">
                                                    <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                    <div class="icon-hapus-title" id="hapus-title${response.titlechecklist.id}">
                                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                                            <div class="info-status5">
                                                                <i class="fa-solid fa-trash fa-lg icon-trash" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif @endforeach></i>
                                                                <span class="text-status5"><b>Delete Title's</b></span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            {{-- @endif --}}
                                        </div>
                                        <!-- /Perbaharui & Hapus Judul Checklist -->

                                        <!-- Progress Bar Checklist -->
                                        <div class="progress" data-checklist-id="${response.titlechecklist.id}">
                                            <div class="progress-bar progress-bar-${response.titlechecklist.id}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                0%
                                            </div>
                                        </div>
                                        <!-- Progress Bar Checklist -->

                                        <!-- Perbaharui & Hapus Checklist -->
                                        <div class="checklist-container" id="checklist-container-${response.titlechecklist.id}"></div>
                                        <!-- /Perbaharui & Hapus Checklist -->

                                        <!-- Tambah baru checklist -->
                                        <form id="myFormChecklist${response.titlechecklist.id}" method="POST">
                                            @csrf
                                                <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                <div class="header-tambah-checklist flex gap-4">
                                                    <i class="fa-xl"></i>
                                                    <input onclick="mentionTags('checklist${response.titlechecklist.id}')" type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist${response.titlechecklist.id}" name="checklist" placeholder="Enter a checklist" required>
                                                    <div class="mention-tag" id="mention-tag-checklist${response.titlechecklist.id}"></div>
                                                </div>
                                                <div class="aksi-update-checklist gap-2">
                                                    <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist${response.titlechecklist.id}">Save</button>
                                                    <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist${response.titlechecklist.id}">Cancel</button>
                                                </div>
                                        </form>

                                        <button type="button" class="btn btn-outline-info" id="AddChecklist${response.titlechecklist.id}">Add an item</button>
                                        <!-- Tambah baru checklist -->

                                    </div>`;
                    $('#titleContainer').append(newForm);

                    // Form Pindah Title & Checklist
                    const titleContainer = document.getElementById('titleContainer');
                    const sortable = new Sortable(titleContainer, {
                        animation: 150,
                        onEnd: function (evt) {
                            updateTitlePositions();
                        },
                    });

                    const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
                    checklistsContainers.forEach(e => {
                        new Sortable(e, {
                            animation: 150,
                            group: 'checklists',
                            onEnd: function (evt) {
                                updateChecklistPositions();
                            },
                        });
                    });

                    function updateTitlePositions() {
                        const positions = {};
                        const titleIds = titleContainer.children;
                        for (let i = 0; i < titleIds.length; i++) {
                            const title = titleIds[i];
                            const id = title.dataset.id;
                            if (id !== undefined) {
                                positions[id] = i + 1;
                            }
                        }

                        fetch('{{ route("perbaharuiPosisiJudul") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ positions })
                        })

                        .then(response => response.json())

                        .then(data => {
                            if (data.success) {
                                toastr.success('Berhasil perbaharui posisi judul!');
                            } else {
                                toastr.error('Gagal perbaharui posisi judul!');
                            }
                        })

                        .catch(error => {
                            console.error('Terjadi kesalahan saat perbaharui posisi judul:', error);
                        });
                        
                    }

                    function updateChecklistPositions() {
                        const positions = {};
                        checklistsContainers.forEach(container => {
                            const titleId = container.closest('.menu-checklist').dataset.id;
                            const checklists = container.children;
                            for (let i = 0; i < checklists.length; i++) {
                                const checklist = checklists[i];
                                const id = checklist.dataset.id;
                                if (id !== undefined) {
                                    positions[id] = {
                                        position: i + 1,
                                        title_id: titleId
                                    };
                                }
                            }
                        });

                        fetch('{{ route("perbaharuiPosisiCeklist") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ positions })
                        })

                        .then(response => response.json())

                        .then(data => {
                            if (data.success) {
                                toastr.success('Berhasil perbaharui posisi checklist!');
                            } else {
                                toastr.error('Gagal perbaharui posisi checklist!');
                            }

                            // Perbaharui Progress Bar pada UI
                            if (data.titlechecklist) {
                                data.titlechecklist.forEach(tc => {
                                    progressBar(tc.id, tc.percentage);
                                });
                            }
                        })

                        .catch(error => {
                            console.error('Terjadi kesalahan saat perbaharui posisi checklist:', error);
                        });
                        
                    }

                    // Checklist Container
                    progressBar(title_id, percentage);
                    $('#titleChecklistUpdate'+title_id).on('click', function() {
                        $('#saveButtonTitleUpdate'+title_id).removeClass('hidden');
                        $('#cancelButtonTitleUpdate'+title_id).removeClass('hidden');
                    });

                    // Tombol Batal Perbaharui Judul
                    $('#cancelButtonTitleUpdate'+title_id).on('click', function() {
                        $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
                        $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
                        $('#myFormTitleUpdate'+title_id)[0].reset();
                    });

                    // Form Perbaharui Judul
                    $('#myFormTitleUpdate'+title_id).on('submit', function(event) {
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('updateTitle2') }}",
                            data: formData,
                            success: function(response){
                                $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
                                $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
                                toastr.success('Berhasil memperbaharui judul!');
                                localStorage.clear();
                            },
                            error: function(error){
                                toastr.error('Gagal memperbaharui judul!');
                            }
                        });
                    });

                    // Form Hapus Judul
                    $(document).ready(function() {
                        $(document).off('submit', '[id^="myFormTitleDelete"]');
                        $(document).on('submit', '[id^="myFormTitleDelete"]', function(event) {
                            event.preventDefault();
                            var formData = $(this).serialize();
                            var formId = $(this).attr('id');
                            var titleId = formId.split('myFormTitleDelete')[1];
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('hapusTitle2') }}",
                                data: formData,
                                success: function(response) {
                                    localStorage.setItem('modal_id', response.card_id);
                                    // location.reload();
                                    // Menghilangkan Title Checklist //
                                    $('#' + formId).closest('.menu-checklist').hide();
                                    toastr.success('Berhasil menghapus judul!');

                                    // Show modal after create title
                                    var modal_id = localStorage.getItem('modal_id');
                                    // $('#isianKartu'+modal_id).modal('show');
                                    $('#isianKartu' + modal_id).on('click', function() {
                                        localStorage.clear();
                                    });
                                },
                                error: function(error){
                                    toastr.error('Gagal menghapus judul!');
                                }
                            });
                        });
                    });

                    // Tombol tambah checklist
                    $('#AddChecklist'+title_id).on('click', function(){
                        $('#AddChecklist'+title_id).addClass('hidden');
                        $('#checklist'+title_id).removeClass('hidden');
                        $('#saveButtonChecklist'+title_id).removeClass('hidden');
                        $('#cancelButtonChecklist'+title_id).removeClass('hidden');
                    });

                    // Tombol batal tambah checklist
                    $('#cancelButtonChecklist'+title_id).on('click', function(){
                        $('#AddChecklist'+title_id).removeClass('hidden');
                        $('#checklist'+title_id).addClass('hidden');
                        $('#saveButtonChecklist'+title_id).addClass('hidden');
                        $('#cancelButtonChecklist'+title_id).addClass('hidden');
                        $('#myFormChecklist'+title_id)[0].reset();
                    });
                    
                    // Form tambah checklist
                    $('#myFormChecklist'+title_id).on('submit', function(event) {
                        event.preventDefault();
                        var formData = $(this).serialize();

                        // Periksa masukan yang kosong
                        var isEmpty = false;
                        $(this).find('input, textarea').each(function() {
                            if ($(this).val().trim() === '') {
                                isEmpty = true;
                            }
                        });
                        if (isEmpty) {
                            toastr.error('Gagal membuat checklist!');
                            return;
                        }
                        
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('addChecklist2') }}",
                            data: formData,
                            success: function(response){
                                $('#AddChecklist'+title_id).removeClass('hidden');
                                $('#checklist'+title_id).addClass('hidden');
                                $('#checklist'+title_id).val('');
                                $('#saveButtonChecklist'+title_id).addClass('hidden');
                                $('#cancelButtonChecklist'+title_id).addClass('hidden');
                                progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                                toastr.success('Berhasil membuat checklist!');
                                var newForm = `<div id="section-checklist-${response.checklist.id}" class="input-checklist" data-id="${response.checklist.id}">
                                                    <form id="myFormChecklistUpdate${response.checklist.id}" method="POST" class="form-checklist">
                                                        @csrf
                                                        <input class="dynamicCheckbox" type="checkbox" id="${response.checklist.id}" name="${response.checklist.id}" ${response.checklist.is_active ? 'checked' : ''}>
                                                        <label class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl ${response.checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${response.checklist.id}" for="labelCheckbox-${response.checklist.id}">${response.checklist.name}</label>
                                                        <input type="hidden" id="checklist_id" name="checklist_id" value="${response.checklist.id}">
                                                        <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                        <input onclick="mentionTags4('checkbox-${response.checklist.id}')" type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-${response.checklist.id}" name="checkbox-${response.checklist.id}" value="${response.checklist.name}" placeholder="Enter a checklist">
                                                        <div class="mention-tag" id="mention-tag-checkbox${response.checklist.id}"></div>

                                                        <div class="aksi-update-checklist gap-2 margin-bottom-0">
                                                            <button type="button" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-${response.checklist.id}">Save</button>
                                                            <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-${response.checklist.id}">Cancel</button>
                                                        </div>
                                                    </form>
                                                    <form id="myFormChecklistDelete${response.checklist.id}" method="POST">
                                                        @csrf
                                                        <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                        <input type="hidden" id="title_checklists_id" name="title_checklists_id" value="${response.titlechecklist.id}">
                                                        <input type="hidden" id="id" name="id" value="${response.checklist.id}">
                                                        <div class="icon-hapus-checklist" id="hapus-checklist${response.checklist.id}">
                                                            <button type="button" class="deletes" id="deleteButtonChecklist-${response.checklist.id}" style="border: none; background: none; padding: 0;">
                                                                <div class="info-status6">
                                                                    <i class="fa-solid fa-trash fa-lg icon-trash" @foreach($result_tema as $sql_mode => $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif @endforeach></i>
                                                                    <span class="text-status6"><b>Delete Checklist</b></span>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>`;
                                $('#checklist-container-'+title_id).append(newForm);
                            },
                            error: function(error){
                                toastr.error('Gagal membuat checklist!');
                            }
                        });
                    });

                    // Form checkbox checklist
                    $(document).off('change', '.dynamicCheckbox');
                    $(document).on('change', '.dynamicCheckbox', function() {
                        var checkbox = $(this);
                        var isChecked = checkbox.is(':checked');
                        var label = $('label[for="labelCheckbox-' + checkbox.attr('id') + '"]');
                        if (isChecked) {
                            label.addClass('strike-through');
                            label.removeClass('hidden');
                            $('#checkbox-'+checkbox.attr('id')).addClass('hidden');
                            $('#saveButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                            $('#cancelButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                            formChecklist(checkbox.attr('id'));
                        } else {
                            label.removeClass('strike-through');
                            label.removeClass('hidden');
                            $('#checkbox-'+checkbox.attr('id')).addClass('hidden');
                            $('#saveButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                            $('#cancelButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                            formChecklist(checkbox.attr('id'));
                        }
                    });

                    // Isian dari form checklist
                    $(document).off('click', 'label[for]');
                    $(document).on('click', 'label[for]', function() {
                        var label = $(this).attr('for');
                        var checkboxId = label.split('-');
                        $('label[for="labelCheckbox-' + checkboxId[1] + '"]').addClass('hidden');
                    $('#checkbox-'+checkboxId[1]).removeClass('hidden');
                    $('#saveButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
                    $('#cancelButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
                    });
                    
                    // Tombol batal form checklist
                    $(document).off('click', '.cancels');
                    $(document).on('click', '.cancels', function() {
                        var id = $(this).attr('id').split('-');
                        $('#checkbox-'+id[1]).addClass('hidden');
                        $('#saveButtonChecklistUpdate-'+id[1]).addClass('hidden');
                        $('#cancelButtonChecklistUpdate-'+id[1]).addClass('hidden');
                        $('label[for="labelCheckbox-' + id[1] + '"]').removeClass('hidden');
                    });

                    // Tombol simpan form checklist
                    $(document).off('click', '.saves');
                    $(document).on('click', '.saves', function(event) {
                        var id = $(this).attr('id').split('-');
                        event.preventDefault(); 
                        var formData = $('#myFormChecklistUpdate' + id[1]).serialize();

                        // Periksa masukan yang kosong
                        var isEmpty = false;
                        $('#myFormChecklistUpdate' + id).find('input, textarea').each(function() {
                            if ($(this).val().trim() === '') {
                                isEmpty = true;
                            }
                        });
                        if (isEmpty) {
                            toastr.error('Gagal memperbaharui checklist!');
                            return;
                        }
                        
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('updateChecklist2') }}",
                            data: formData,
                            success: function(response){
                                $('label[for="labelCheckbox-' + response.checklist.id+ '"]').removeClass('hidden');
                                $('label[for="labelCheckbox-' + response.checklist.id+ '"]').html(response.checklist.name);
                                $('#checkbox-'+response.checklist.id).addClass('hidden');
                                $('#saveButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                                $('#cancelButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                                toastr.success('Berhasil memperbaharui checklist!');
                                localStorage.clear();
                            },
                            error: function(error){
                                toastr.error('Gagal memperbaharui checklist!');
                            }
                        });
                    });

                    // Form Perbaharui Checklist
                    function formChecklist(id) {
                        var formData = $('#myFormChecklistUpdate' + id).serialize();
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('updateChecklist2') }}",
                            data: formData,
                            success: function(response){
                                $('label[for="labelCheckbox-' + response.checklist.id+ '"]').removeClass('hidden');
                                $('label[for="labelCheckbox-' + response.checklist.id+ '"]').html(response.checklist.name);
                                $('#checkbox-'+response.checklist.id).addClass('hidden');
                                $('#saveButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                                $('#cancelButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                                toastr.success('Berhasil memperbaharui checklist!');
                                progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                                localStorage.clear();
                            },
                            error: function(error){
                                toastr.error('Gagal memperbaharui checklist!');
                            }
                        });
                    }

                    // Form Hapus Checklist
                    $(document).off('click', '.deletes');
                    $(document).on('click', '.deletes', function(event) {
                        event.preventDefault();
                        var id = $(this).attr('id').split('-');
                        var formData = $('#myFormChecklistDelete' + id[1]).serialize();
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('hapusChecklist2') }}",
                            data: formData,
                            success: function(response){
                                // Hapus Section Checklist
                                $('#section-checklist-' + id[1]).remove();
                                progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                                toastr.success('Berhasil menghapus checklist!');
                            },
                            error: function(error){
                                toastr.error('Gagal menghapus checklist!');
                            }
                        });
                    });

                    // Tambahkan key Enter untuk validasi input dan menyimpan checklist
                    $(document).on('keypress', function(e) {
                        if(e.which == 13) {
                            var activeElement = $(document.activeElement);
                            if (activeElement.is('input') || activeElement.is('textarea')) {
                                var form = activeElement.closest('form');
                                var isEmpty = false;

                                form.find('input, textarea').each(function() {
                                    if ($(this).val().trim() === '') {
                                        isEmpty = true;
                                    }
                                });

                                if (isEmpty) {
                                    toastr.error('Gagal memperbaharui checklist!');
                                    return false;
                                }

                                // Picu klik tombol simpan jika dalam bentuk pembaruan
                                if (form.attr('id').startsWith('myFormChecklistUpdate')) {
                                    form.find('.saves').click();
                                    return false;
                                }
                            }
                        }
                    });

                    // Proses dari Progress Bar
                    function progressBar(id,percentage ) {
                        var progressBar = $('.progress-bar-' + id);
                        progressBar.css('width', percentage + '%');
                        progressBar.attr('aria-valuenow', percentage);
                        progressBar.text(Math.round(percentage) + '%');
                        progressBar.removeClass('bg-danger bg-warning bg-info bg-success');
                        if (percentage <= 25) {
                            progressBar.addClass('bg-danger');
                        } else if (percentage > 25 && percentage < 50) {
                            progressBar.addClass('bg-warning');
                        } else if (percentage >= 50 && percentage <= 75) {
                            progressBar.addClass('bg-info');
                        } else if (percentage > 75) {
                            progressBar.addClass('bg-success');
                        }
                    }
                },
                error: function(error) {
                    toastr.error('Gagal menambahkan judul!');
                }
            });
        });
        // End Section Title
    });
</script>

<script>
    $(document).ready(function() {
        const users = [
            @foreach ($UserTeams as $result_team)
                {
                    username: "{{ $result_team->username }}",
                    name: "{{ $result_team->name }}",
                    email: "{{ $result_team->email }}",
                    avatar: "{{ URL::to('/assets/images/' . $result_team->avatar) }}"
                },
            @endforeach
        ];

        window.mentionTags = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(`mention-tag-${inputId}`);
            let selectedUsers = [];
            let isSubmitting = false;

            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atPosition = value.lastIndexOf('@');
                if (atPosition !== -1) {
                    const query = value.substring(atPosition + 1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase().startsWith(query));
                    showMention(filteredUsers, atPosition, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention(users, atPosition, currentValue, inputTag, mentionTag) {
                mentionTag.innerHTML = '';
                if (users.length === 0) {
                    mentionTag.style.display = 'none';
                    return;
                }
                mentionTag.style.display = 'block';
                users.forEach(user =>
                {
                    // Untuk div avatar, username, name/email //
                    const item = document.createElement('div');
                    item.className = 'mention-tag-item';
                    item.style.display = 'flex';
                    item.style.alignItems = 'flex-end';
                    // /Untuk div avatar, username, name/email //

                    // Untuk img avatar //
                    const avatarImg = document.createElement('img');
                    avatarImg.className = 'avatar-mention';
                    avatarImg.src = user.avatar;
                    avatarImg.loading = 'lazy';
                    // /Untuk img avatar //

                    // Untuk inputan yang dikeluarkan //
                    const userInfo = document.createElement('div');
                    const username = document.createElement('div');
                    username.innerText = user.username;
                    // /Untuk inputan yang dikeluarkan //

                    // Untuk email //
                    // const email = document.createElement('div');
                    // email.innerText = user.email;
                    // userInfo.appendChild(username);
                    // userInfo.appendChild(email);
                    // /Untuk email //

                    // Untuk nama lengkap //
                    const name = document.createElement('div');
                    name.innerText = user.name;
                    userInfo.appendChild(username);
                    userInfo.appendChild(name);
                    // /Untuk nama lengkap //

                    item.appendChild(avatarImg);
                    item.appendChild(userInfo);
                    item.addEventListener('click', function() {

                        // Untuk inputan yang dikeluarkan //
                        const newValue = currentValue.substring(0, atPosition + 1) + user.username.toLowerCase() + ' ';
                        // /Untuk inputan yang dikeluarkan //

                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih //
                        inputTag.focus();

                        // Setel ke pengguna baru yang dipilih
                        selectedUsers = [user];

                        // /Kembali fokus ke input setelah memilih //
                        
                    });
                    mentionTag.appendChild(item);
                });
            }

            // Kalau tidak ada @ maka akan hidden container //
            document.addEventListener('click', function(event) {
                if (!mentionTag.contains(event.target) && event.target !== inputTag) {
                    mentionTag.style.display = 'none';
                }
            });
            // /Kalau tidak ada @ maka akan hidden container //

            // Kirimkan data mention ke notifikasi //
            const saveButtonId = `saveButtonChecklist${inputId.replace('checklist', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    if (!isSubmitting) {
                        sendData();
                    }
                });

                // Menambahkan event listener untuk tombol "Enter"
                inputTag.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && !isSubmitting) {
                        sendData();
                    }
                });

                function sendData() {
                    // Tetapkan tanda untuk mencegah pengiriman duplikat
                    isSubmitting = true;
                    
                    const name = inputTag.value;

                    if (selectedUsers.length > 0) {
                        const promises = selectedUsers.map(user => {
                            return fetch('/mention-tag-checklist', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    username: user.username,
                                    name: name
                                })
                            });
                        });

                        Promise.all(promises)
                            .then(responses => {
                                const allSuccessful = responses.every(response => response.ok);
                                if (allSuccessful) {
                                    toastr.success('Berhasil mengirimkan mention tag!');
                                } else {
                                    toastr.error('Gagal mengirimkan mention tag!');
                                }

                                // Reset pengguna yang dipilih setelah mengirim data
                                selectedUsers = [];

                                // Setel ulang penanda
                                isSubmitting = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);

                                // Reset pengguna yang dipilih meskipun ada kesalahan
                                selectedUsers = [];

                                // Setel ulang penanda
                                isSubmitting = false;
                            });
                    } else {
                        // Reset penanda jika tidak ada pengguna yang dipilih
                        isSubmitting = false;
                    }
                }
            }
            // /Kirimkan data mention ke notifikasi //

        }
    });
</script>

<script>
    $(document).ready(function() {
        const users = [
            @foreach ($UserTeams as $result_team)
                {
                    username: "{{ $result_team->username }}",
                    name: "{{ $result_team->name }}",
                    email: "{{ $result_team->email }}",
                    avatar: "{{ URL::to('/assets/images/' . $result_team->avatar) }}"
                },
            @endforeach
        ];

        window.mentionTags4 = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(`mention-tag-checkbox${inputId.replace('checkbox-', '')}`);
            let selectedUsers = [];
            let isSubmitting = false;

            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atPosition = value.lastIndexOf('@');
                if (atPosition !== -1) {
                    const query = value.substring(atPosition + 1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase().startsWith(query));
                    showMention4(filteredUsers, atPosition, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention4(users, atPosition, currentValue, inputTag, mentionTag) {
                mentionTag.innerHTML = '';
                if (users.length === 0) {
                    mentionTag.style.display = 'none';
                    return;
                }
                mentionTag.style.display = 'block';
                users.forEach(user =>
                {
                    // Untuk div avatar, username, name/email //
                    const item = document.createElement('div');
                    item.className = 'mention-tag-item';
                    item.style.display = 'flex';
                    item.style.alignItems = 'flex-end';
                    // /Untuk div avatar, username, name/email //

                    // Untuk img avatar //
                    const avatarImg = document.createElement('img');
                    avatarImg.className = 'avatar-mention';
                    avatarImg.src = user.avatar;
                    avatarImg.loading = 'lazy';
                    // /Untuk img avatar //

                    // Untuk inputan yang dikeluarkan //
                    const userInfo = document.createElement('div');
                    const username = document.createElement('div');
                    username.innerText = user.username;
                    // /Untuk inputan yang dikeluarkan //

                    // Untuk email //
                    // const email = document.createElement('div');
                    // email.innerText = user.email;
                    // userInfo.appendChild(username);
                    // userInfo.appendChild(email);
                    // /Untuk email //

                    // Untuk nama lengkap //
                    const name = document.createElement('div');
                    name.innerText = user.name;
                    userInfo.appendChild(username);
                    userInfo.appendChild(name);
                    // /Untuk nama lengkap //

                    item.appendChild(avatarImg);
                    item.appendChild(userInfo);
                    item.addEventListener('click', function() {

                        // Untuk inputan yang dikeluarkan //
                        const newValue = currentValue.substring(0, atPosition + 1) + user.username.toLowerCase() + ' ';
                        // /Untuk inputan yang dikeluarkan //

                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih //
                        inputTag.focus();
                        
                        // Setel ke pengguna baru yang dipilih
                        selectedUsers = [user];

                        // /Kembali fokus ke input setelah memilih //
                        
                    });
                    mentionTag.appendChild(item);
                });
            }

            // Kalau tidak ada @ maka akan hidden container //
            document.addEventListener('click', function(event) {
                if (!mentionTag.contains(event.target) && event.target !== inputTag) {
                    mentionTag.style.display = 'none';
                }
            });
            // /Kalau tidak ada @ maka akan hidden container //

            // Kirimkan data mention ke notifikasi //
            const saveButtonId = `saveButtonChecklistUpdate${inputId.replace('checkbox', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    if (!isSubmitting) {
                        sendData();
                    }
                });

                // Menambahkan event listener untuk tombol "Enter"
                inputTag.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && !isSubmitting) {
                        sendData();
                    }
                });

                function sendData() {
                    // Tetapkan tanda untuk mencegah pengiriman duplikat
                    isSubmitting = true;

                    const name = inputTag.value;

                    if (selectedUsers.length > 0) {
                        const promises = selectedUsers.map(user => {
                            return fetch('/mention-tag-checklist', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    username: user.username,
                                    name: name
                                })
                            });
                        });

                        Promise.all(promises)
                            .then(responses => {
                                const allSuccessful = responses.every(response => response.ok);
                                if (allSuccessful) {
                                    toastr.success('Berhasil mengirimkan mention tag!');
                                } else {
                                    toastr.error('Gagal mengirimkan mention tag!');
                                }

                                // Reset pengguna yang dipilih setelah mengirim data
                                selectedUsers = [];

                                // Setel ulang penanda
                                isSubmitting = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);

                                // Reset pengguna yang dipilih meskipun ada kesalahan
                                selectedUsers = [];

                                // Setel ulang penanda
                                isSubmitting = false;
                            });
                    } else {
                        // Reset penanda jika tidak ada pengguna yang dipilih
                        isSubmitting = false;
                    }
                }
            }
            // /Kirimkan data mention ke notifikasi //
            
        }
    });
</script>