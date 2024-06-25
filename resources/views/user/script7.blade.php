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

        window.mentionTags3 = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(`mention-tag-comment${inputId.replace('contentarea', '')}`);
            let selectedUsers = [];

            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atPosition = value.lastIndexOf('@');
                if (atPosition !== -1) {
                    const query = value.substring(atPosition + 1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase().startsWith(query));
                    showMention3(filteredUsers, atPosition, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention3(users, atPosition, currentValue, inputTag, mentionTag) {
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
                    item.className = 'mention-tag-comment-item';
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
            const saveButtonId = `simpanButton${inputId.replace('contentarea', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            saveButton.addEventListener('click', function() {
                const contentTextarea = document.getElementById(inputId);
                const content = contentTextarea.value;

                if (selectedUsers.length > 0) {
                    const promises = selectedUsers.map(user => {
                        return fetch('/mention-tag-comment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                username: user.username,
                                content: content
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
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                };
            });
            // /Kirimkan data mention ke notifikasi //

        }
    });
</script>