<script>
    $(document).ready(function(){
        const title_id = '{{ $titleChecklists->id }}';
        const percentage = '{{ $titleChecklists->percentage }}';
        progressBar(title_id, percentage);
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
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateTitle') }}",
                data: formData,
                success: function(response){
                    $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
                    $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
                    toastr.success('Berhasil memperbaharui judul!');
                    localStorage.clear();
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // Form delete title
        $(document).ready(function() {
            $(document).off('submit', '[id^="myFormTitleDelete"]');
            $(document).on('submit', '[id^="myFormTitleDelete"]', function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                var formId = $(this).attr('id');
                var titleId = formId.split('myFormTitleDelete')[1];
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hapusTitle') }}",
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
                    error: function(){
                        toastr.error('Terjadi kesalahan, silakan coba lagi!');
                    }
                });
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
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addChecklist') }}",
                data: formData,
                success: function(response){
                    $('#AddChecklist'+title_id).removeClass('hidden');
                    $('#checklist'+title_id).addClass('hidden');
                    $('#checklist'+title_id).val('');
                    $('#saveButtonChecklist'+title_id).addClass('hidden');
                    $('#cancelButtonChecklist'+title_id).addClass('hidden');
                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    toastr.success('Berhasil membuat checklist!');
                    var newForm = `<div class="input-checklist flex justify-content">
                                        <form id="myFormChecklistUpdate${response.checklist.id}" method="POST" class="form-checklist">
                                            @csrf
                                            <input class="dynamicCheckbox" type="checkbox" id="${response.checklist.id}" name="${response.checklist.id}" ${response.checklist.is_active ? 'checked' : ''} style="margin-left: -20px;">
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
                    $('#checkbox-container-'+title_id).append(newForm);
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
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
        $(document).on('click', '.saves', function() {
            var id = $(this).attr('id').split('-');
            event.preventDefault(); 
            var formData = $('#myFormChecklistUpdate' + id[1]).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateChecklist') }}",
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
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // Form Update Checklist
        function formChecklist(id){
            var formData = $('#myFormChecklistUpdate' + id).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateChecklist') }}",
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
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        }
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
    document.addEventListener('DOMContentLoaded', function() {
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
                        const newValue = currentValue.substring(0, atPosition + 1) + (user.username).toLowerCase() + ' ';
                        // /Untuk inputan yang dikeluarkan //

                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih //
                        inputTag.focus();
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
            document.querySelectorAll('[id^="saveButtonChecklistUpdate"]').forEach(button => {
                button.addEventListener('click', function() {
                    const inputId = button.id.replace('saveButtonChecklistUpdate', 'checkbox');
                    const checklistInput = document.getElementById(inputId);
                    const name = checklistInput.value;

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
                                    console.log('Notifikasi dikirim.');
                                } else {
                                    toastr.error('Gagal mengirimkan mention tag!');
                                    console.error('Gagal mengirim notifikasi.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            });
            // /Kirimkan data mention ke notifikasi //
            
        }
    });
</script>