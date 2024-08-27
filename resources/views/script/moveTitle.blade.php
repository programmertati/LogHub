<script>
    $(document).ready(function() {
        const titleContainer = document.getElementById('titleContainer');
        const sortable = new Sortable(titleContainer, {
            animation: 150,
            onEnd: function(evt) {
                updateTitlePositions();
            },
        });

        const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
        checklistsContainers.forEach(e => {
            new Sortable(e, {
                animation: 150,
                group: 'checklists',
                onEnd: function(evt) {
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

            fetch('{{ route('perbaharuiPosisiJudul') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        positions
                    })
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

            fetch('{{ route('perbaharuiPosisiCeklist') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        positions
                    })
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
    });
</script>
