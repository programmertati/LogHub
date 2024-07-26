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