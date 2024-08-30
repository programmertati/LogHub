<script>
    $(document).ready(function() {

        let isInputFocused = false;
        const titleContainer = document.getElementById('titleContainer');
        const sortableInstances = [];
        let initialTitlePositions = getTitlePositions(); // Simpan posisi awal titles


        $(document).on('click', '.isian-title', function() {
            // e.preventDefault();
            // e.stopImmediatePropagation();
            isInputFocused = true;
            titleSortable.option("disabled", true);
        });
        $('.isian-title').blur(function(e) {
            e.preventDefault();
            isInputFocused = false;
            titleSortable.option("disabled", false);
        });

        // titleContainer.forEach(e => {
        const titleSortable = new Sortable(titleContainer, {
            animation: 150,
            onEnd: function(evt) {
                // if (!isInputFocused) {
                const newTitlePositions = getTitlePositions();
                if (JSON.stringify(initialTitlePositions) !== JSON.stringify(
                        newTitlePositions)) {
                    updateTitlePositions(newTitlePositions);
                    initialTitlePositions =
                        newTitlePositions; // Update posisi awal dengan posisi baru
                }
                // }
            },
        });
        // sortableInstances.push(sortableInstance);
        // });


        const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
        // const sortableInstances = [];
        let initialChecklistPositions = getChecklistPositions(); // Simpan posisi awal checklists

        $(document).on('click', 'label[for]', function() {
            isInputFocused = true;
            sortableInstances.forEach(sortableInstance => {
                sortableInstance.option("disabled", true);
            });
            titleSortable.option("disabled", true);
        });

        $('.dynamicCheckboxValue').blur(function(e) {
            e.preventDefault();
            isInputFocused = false;
            sortableInstances.forEach(sortableInstance => {
                sortableInstance.option("disabled", false);
            });
            titleSortable.option("disabled", false);
        });

        checklistsContainers.forEach(e => {
            const sortableInstance = new Sortable(e, {
                animation: 150,
                group: 'checklists',
                onEnd: function(evt) {
                    if (!isInputFocused) {
                        const newChecklistPositions = getChecklistPositions();

                        if (JSON.stringify(initialChecklistPositions) !== JSON.stringify(
                                newChecklistPositions)) {
                            updateChecklistPositions(newChecklistPositions);
                            initialChecklistPositions =
                                newChecklistPositions; // Update posisi awal dengan posisi baru
                        }
                    }
                },
            });
            sortableInstances.push(sortableInstance);
        });

        function getTitlePositions() {
            const positions = {};
            const titleIds = titleContainer.children;
            for (let i = 0; i < titleIds.length; i++) {
                const title = titleIds[i];
                const id = title.dataset.id;
                if (id !== undefined) {
                    positions[id] = i + 1;
                }
            }
            return positions;
        }

        function updateTitlePositions(positions) {
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

        function getChecklistPositions() {
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
            return positions;
        }

        function updateChecklistPositions(positions) {
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
