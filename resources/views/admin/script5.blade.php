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
                const atMatches = value.match(/@\w+/g);
                if (atMatches) {
                    const lastMatch = atMatches[atMatches.length - 1];
                    const query = lastMatch.substring(1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase().startsWith(query));
                    showMention(filteredUsers, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention(users, currentValue, inputTag, mentionTag) {
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
    $(document).ready(function(){
       // Form delete Checklist
       $(document).off('click', '.deletes');
       $(document).on('click', '.deletes', function(event) {
           event.preventDefault();
            var id = $(this).attr('id').split('-');
            var formData = $('#myFormChecklistDelete' + id[1]).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('hapusChecklist') }}",
                data: formData,
                success: function(response){
                    // Hapus Section Checklist
                    $('#section-checklist-' + id[1]).remove();

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 0) {
                        checklistAllCheckbox.addClass('hidden');
                    }

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

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 100) {
                        checklistAllCheckbox.prop('checked', true);
                    } else {
                        checklistAllCheckbox.prop('checked', false);
                    }

                    // Untuk Mengatur Icon Checklist //
                    if (response.jumlahChecklist === 0) {
                        $('#iconChecklist-' + response.cardId).addClass('hidden');
                    }
                    $('#perhitunganChecklist-' + response.cardId).html(response.perChecklist + '/' + response.jumlahChecklist);

                    if (response.perChecklist < response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.cardId;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.removeClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                            iconChecklistCheck.addClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.removeClass('icon-check-full-light').removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.removeClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                            iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                            iconChecklistCheck.removeClass('icon-check-full-dark').removeClass('icon-check-full-light');
                        }
                    } else if (response.perChecklist == response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.cardId;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.addClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                            iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.addClass('icon-check-full-light').removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.addClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                            iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                            iconChecklistCheck.addClass('icon-check-full-dark').removeClass('icon-check-full-light');
                        }
                    }
                    // /Untuk Mengatur Icon Checklist //

                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    toastr.success('Berhasil menghapus checklist!');
                },
                error: function(error){
                    toastr.error('Gagal menghapus checklist!');
                }
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