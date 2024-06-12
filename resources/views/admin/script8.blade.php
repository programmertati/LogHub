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

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const saveButton = document.getElementById('saveButtonChecklistUpdate-{{ $checklists->id }}');
        const cancelButton = document.getElementById('cancelButtonChecklistUpdate-{{ $checklists->id }}');
        const container = document.getElementById('checklist-{{ $checklists->id }}');
        function updateMargin() {
            if (!saveButton.classList.contains('hidden') || !cancelButton.classList.contains('hidden')) {
                container.classList.remove('margin-bottom-0');
                container.classList.add('margin-bottom-10');
            } else {
                container.classList.remove('margin-bottom-10');
                container.classList.add('margin-bottom-0');
            }
        }
        updateMargin();
        const observer = new MutationObserver(updateMargin);
        observer.observe(saveButton, { attributes: true, attributeFilter: ['class'] });
        observer.observe(cancelButton, { attributes: true, attributeFilter: ['class'] });
    });
</script>