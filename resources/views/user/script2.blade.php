<script>
    $(document).ready(function(){
        const title_id = '{{ $titleChecklists->id }}';
        const percentage = '{{ $titleChecklists->percentage }}';
        progressBar(title_id, percentage);

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;
        
        // Section Update Title
        // Input update title
        $('#titleChecklistUpdate'+title_id).on('click', function(){
            $('#saveButtonTitleUpdate'+title_id).removeClass('hidden');
            $('#cancelButtonTitleUpdate'+title_id).removeClass('hidden');
        });
        // Button cancel form update title
        $('#cancelButtonTitleUpdate'+title_id).on('click', function(){
            $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
            $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
            $('#myFormTitleUpdate'+title_id)[0].reset();
        });
        // Form update title
        $('#myFormTitleUpdate'+title_id).on('submit', function(event){
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
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

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error){
                    toastr.error('Gagal memperbaharui judul!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });
        // Form delete title
        $(document).off('submit', '[id^="myFormTitleDelete"]');
        $(document).on('submit', '[id^="myFormTitleDelete"]', function(event) {
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var formData = $(this).serialize();
            var formId = $(this).attr('id');
            var titleId = formId.split('myFormTitleDelete')[1];
            $.ajax({
                type: 'POST',
                url: "{{ route('hapusTitle2') }}",
                data: formData,
                success: function(response) {
                    localStorage.setItem('modal_id', response.card_id);
                    // Menghilangkan Title Checklist //
                    $('#' + formId).closest('.menu-checklist').remove();

                    // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                    var cardId = response.cardId;
                    var softDeletedTitle = response.softDeletedTitle;
                    var softDeletedChecklist = response.softDeletedChecklist;
                    var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                    if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                        recoverTitleChecklist.show();
                    } else {
                        recoverTitleChecklist.hide();
                    }

                    toastr.success('Berhasil menghapus judul!');

                    // Show modal after create title
                    var modal_id = localStorage.getItem('modal_id');
                    $('#isianKartu' + modal_id).on('click', function() {
                        localStorage.clear();
                    });

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error){
                    toastr.error('Gagal menghapus judul!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });
        // End Section Update Title

        // Section Checklist
        // Button add form checklist
        $('#AddChecklist'+title_id).on('click', function(){
            $('#AddChecklist'+title_id).addClass('hidden');
            $('#checklist'+title_id).removeClass('hidden');
            $('#saveButtonChecklist'+title_id).removeClass('hidden');
            $('#cancelButtonChecklist'+title_id).removeClass('hidden');
        });
        // Button cancel form checklist
        $('#cancelButtonChecklist'+title_id).on('click', function(){
            $('#AddChecklist'+title_id).removeClass('hidden');
            $('#checklist'+title_id).addClass('hidden');
            $('#saveButtonChecklist'+title_id).addClass('hidden');
            $('#cancelButtonChecklist'+title_id).addClass('hidden');
            $('#myFormChecklist'+title_id)[0].reset();
        });
        // Form Add Checklist
        $('#myFormChecklist'+title_id).on('submit', function(event){
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var formData = $(this).serialize();

            // Periksa masukan yang kosong
            var isEmpty = false;
            $('#myFormChecklist' + title_id).find('input').each(function() {
                if ($(this).val().trim() === '') {
                    isEmpty = true;
                }
            });
            if (isEmpty) {
                toastr.error('Bidang checklist tidak boleh kosong!');

                // Setel ulang tanda
                isSubmitting = false;
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

                    // Memunculkan checkbox ketika tambah data checklist
                    $('#checklistform-all-' + title_id).removeClass('hidden');

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + title_id);
                    if (response.titlechecklist.percentage === 100) {
                        checklistAllCheckbox.prop('checked', true);
                    } else {
                        checklistAllCheckbox.prop('checked', false);
                    }

                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    toastr.success('Berhasil membuat checklist!');
                    var newForm = `
                        <div id="section-checklist-${response.checklist.id}" class="input-checklist" data-id="${response.checklist.id}">
                            <form id="myFormChecklistUpdate${response.checklist.id}" method="POST" class="form-checklist">
                                @csrf
                                <input class="dynamicCheckbox" type="checkbox" id="${response.checklist.id}" name="${response.checklist.id}" ${response.checklist.is_active ? 'checked' : ''}>
                                <label class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl ${response.checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${response.checklist.id}" for="labelCheckbox-${response.checklist.id}">${response.checklist.name}</label>
                                <input type="hidden" id="checklist_id" name="checklist_id" value="${response.checklist.id}">
                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                <input onclick="mentionTags4('checkbox-${response.checklist.id}')" type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-${response.checklist.id}" name="checkbox-${response.checklist.id}" value="${response.checklist.name}" placeholder="Enter a checklist">
                                <div class="mention-tag" id="mention-tag-checkbox${response.checklist.id}"></div>

                                <div onclick="checklistUpdate(${response.checklist.id})" class="aksi-update-checklist2 gap-2 margin-bottom-0" id="checklist-${response.checklist.id}">
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

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error){
                    toastr.error('Gagal membuat checklist!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });
         // Checkbox form checklist
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
        // Label form checklist
        $(document).off('click', 'label[for]');
        $(document).on('click', 'label[for]', function() {
            var label = $(this).attr('for');
            var checkboxId = label.split('-');
            $('label[for="labelCheckbox-' + checkboxId[1] + '"]').addClass('hidden');
           $('#checkbox-'+checkboxId[1]).removeClass('hidden');
           $('#saveButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
           $('#cancelButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
        });
        // Button cancels form checklist
        $(document).off('click', '.cancels');
        $(document).on('click', '.cancels', function() {
            var id = $(this).attr('id').split('-');
            $('#checkbox-'+id[1]).addClass('hidden');
            $('#saveButtonChecklistUpdate-'+id[1]).addClass('hidden');
            $('#cancelButtonChecklistUpdate-'+id[1]).addClass('hidden');
            $('label[for="labelCheckbox-' + id[1] + '"]').removeClass('hidden');
        });
        // Button saves form checklist
        $(document).off('click', '.saves');
        $(document).on('click', '.saves', function(event) {
            var id = $(this).attr('id').split('-');
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var formData = $('#myFormChecklistUpdate' + id[1]).serialize();

            // Periksa masukan yang kosong
            var isEmpty = false;
            $('#myFormChecklistUpdate' + id).find('input').each(function() {
                if ($(this).val().trim() === '') {
                    isEmpty = true;
                }
            });
            if (isEmpty) {
                toastr.error('Bidang checklist tidak boleh kosong!');

                // Setel ulang tanda
                isSubmitting = false;
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

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 100) {
                        checklistAllCheckbox.prop('checked', true);
                    } else {
                        checklistAllCheckbox.prop('checked', false);
                    }

                    localStorage.clear();

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error){
                    toastr.error('Gagal memperbaharui checklist!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });
        // Form Update Checklist
        function formChecklist(id){
            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
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

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 100) {
                        checklistAllCheckbox.prop('checked', true);
                    } else {
                        checklistAllCheckbox.prop('checked', false);
                    }
                    
                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    localStorage.clear();

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error){
                    toastr.error('Gagal memperbaharui checklist!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        }

        // Tambahkan key Enter untuk validasi input dan menyimpan checklist
        $(document).ready(function() {
            $(document).on('keypress', function(e) {
                if (e.which == 13) {
                    var activeElement = $(document.activeElement);
                    if (activeElement.is('input') || activeElement.is('textarea')) {
                        var form = activeElement.closest('form');

                        // Cegah pengiriman form secara default
                        e.preventDefault();

                        if (form.attr('id').startsWith('myFormChecklistUpdate')) {
                            form.find('.saves').click();
                        } else {
                            form.submit();
                        }
                    }
                }
            });

            // Tandai untuk mencegah pengiriman berulang kali
            let isSubmitting = false;

            $('form').on('submit', function(event) {
                const form = $(this);
                if (form.attr('id').startsWith('myFormChecklistUpdate')) {
                    form.find('.saves').click();
                    toastr.success('Berhasil memperbaharui checklist!');
                    event.preventDefault();
                }
            });

            $('.saves').on('click', function() {

                // Setel ulang tanda
                isSubmitting = false;
            });
        });
        
        // End Section Checklist
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
                const atMatches = value.match(/@\w+/g);
                if (atMatches) {
                    const lastMatch = atMatches[atMatches.length - 1];
                    const query = lastMatch.substring(1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase().startsWith(query));
                    showMention4(filteredUsers, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention4(users, currentValue, inputTag, mentionTag) {
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
                        const atMatches = currentValue.match(/@\w+/g);
                        const lastAtMatch = atMatches[atMatches.length - 1];
                        const newValue = currentValue.replace(lastAtMatch, '@' + user.username.toLowerCase() + ' ');
                        // /Untuk inputan yang dikeluarkan //

                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih //
                        inputTag.focus();

                        // Setel ke pengguna baru yang dipilih
                        selectedUsers.push(user);

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

                    const uniqueSelectedUsers = Array.from(new Set(selectedUsers.map(user => user.username)))
                        .map(username => selectedUsers.find(user => user.username === username));

                    if (uniqueSelectedUsers.length > 0) {
                        const promises = uniqueSelectedUsers.map(user => {
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
    function checklistUpdate(id) {
        $(document).ready(function() {
            const dynamicCheckboxValue = document.getElementById(`checkbox-${id}`);
            const aksiUpdateChecklist = document.getElementById(`checklist-${id}`);
            const saveButton = document.getElementById(`saveButtonChecklistUpdate-${id}`);
            const cancelButton = document.getElementById(`cancelButtonChecklistUpdate-${id}`);
            

            dynamicCheckboxValue.addEventListener('click', function () {
                aksiUpdateChecklist.style.marginBottom = '10px';
                aksiUpdateChecklist.style.marginTop = '5px';
            });

            saveButton.addEventListener('click', function () {
                aksiUpdateChecklist.style.marginBottom = '0';
                aksiUpdateChecklist.style.marginTop = '0';
            });

            cancelButton.addEventListener('click', function () {
                aksiUpdateChecklist.style.marginBottom = '0';
                aksiUpdateChecklist.style.marginTop = '0';
            });
        });
    }
</script>