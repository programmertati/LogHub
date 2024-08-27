<script>
    $(document).ready(function() {
        const columnContainer = document.getElementById('cardContainer');
        new Sortable(columnContainer, {
            animation: 150,
            onEnd: function(evt) {
                updateColumnPositions();
            },
        });

        const cardContainers = document.getElementsByClassName('card-container');
        Array.from(cardContainers).forEach(e => {
            new Sortable(e, {
                group: 'shared',
                animation: 150,
                onAdd: function(evt) {
                    updateCardColumn(evt);
                },
                onEnd: function(evt) {
                    updateCardPositions(evt.to);
                },
            });
        });

        function updateColumnPositions() {
            const positions = {};
            const columnIds = columnContainer.children;
            for (let i = 0; i < columnIds.length; i++) {
                const column = columnIds[i];
                const id = column.dataset.id;
                if (id !== undefined) {
                    positions[id] = i + 1;
                }
            }

            fetch('{{ route('perbaharuiPosisiKolom') }}', {
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
                        toastr.success('Berhasil perbaharui posisi kolom!');
                    } else {
                        toastr.error('Gagal perbaharui posisi kolom!');
                    }
                })

                .catch(error => {
                    console.error('Terjadi kesalahan saat perbaharui posisi kolom:', error);
                });

        }

        function updateCardPositions(container) {
            const positions = {};
            const cards = container.children;
            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const id = card.dataset.id;
                if (id !== undefined) {
                    positions[id] = i + 1;
                }
            }

            fetch('{{ route('perbaharuiPosisiKartu') }}', {
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
                        toastr.success('Berhasil perbaharui posisi kartu!');
                    } else {
                        toastr.error('Gagal perbaharui posisi kartu!');
                    }
                })

                .catch(error => {
                    console.error('Terjadi kesalahan saat perbaharui posisi kartu:', error);
                });

        }

        function updateCardColumn(evt) {
            const cardId = evt.item.dataset.id;
            const newColumnId = evt.to.dataset.id;

            fetch('{{ route('perbaharuiPosisiKartuKeKolom') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        card_id: cardId,
                        new_column_id: newColumnId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {} else {}
                })
                .catch(error => {
                    console.error('Terjadi kesalahan saat memindah kartu ke kolom:', error);
                });
        }
    });
</script>
