<script>
    $(document).ready(function(){
        const titleContainer = document.getElementById('titleContainer');
        const sortable = new Sortable(titleContainer, {
            animation: 150,
            onEnd: function (evt) {
                updateTitlePositions();
            },
        });

        [...document.getElementsByClassName('checklist-container')].forEach(e => {
            new Sortable(e, {
                animation: 150,
                onEnd: function (evt) {
                    updateChecklistPositions(e);
                },
            });
        });

        function updateTitlePositions() {
            const positions = {};
            const titleIds = titleContainer.children;
            for (let i = 0; i < titleIds.length; i++) {
                const title = titleIds[i];
                const id = title.dataset.id;
                positions[id] = i + 1;
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

        function updateChecklistPositions(e) {
            const positions = {};
            const checklists = e.children;
            for (let i = 0; i < checklists.length; i++) {
                const checklist = checklists[i];
                const id = checklist.dataset.id;
                positions[id] = i + 1;
            }

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
            })

            .catch(error => {
                console.error('Terjadi kesalahan saat perbaharui posisi checklist:', error);
            });
            
        }
    });
</script>