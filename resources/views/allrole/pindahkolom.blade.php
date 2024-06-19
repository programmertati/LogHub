<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columnContainer = document.getElementById('cardContainer');
        const sortable = new Sortable(columnContainer, {
            animation: 150,
            onEnd: function (evt) {
                updateColumnPositions();
            },
        });
        
        [...document.getElementsByClassName('card-container')].forEach(e => {
            new Sortable(e, {
                animation: 150,
                onEnd: function (evt) {
                    updateCardPositions(e);
                },
            })
        });

        function updateColumnPositions() {
            const positions = {};
            const columns = document.querySelectorAll('[id^="kolom-card"]');
            columns.forEach((column, index) => {
                const id = column.dataset.id;
                positions[id] = index + 1;
            });

            fetch('{{ route("perbaharuiPosisiKolom") }}', {
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
                    toastr.success('Berhasil perbaharui posisi kolom!');
                    console.log('Posisi kolom berhasil diperbaharui!');
                } else {
                    toastr.success('Gagal perbaharui posisi kolom!');
                    console.error('Posisi kolom gagal diperbaharui!');
                }
            })

            .catch(error => {
                console.error('Terjadi kesalahan saat perbaharui posisi kolom:', error);
            });
            
        }

        function updateCardPositions(e) {
            const positions = {};
            const cards = $(e).children().toArray();
            cards.forEach((card, index) => {
                const id = card.dataset.id;
                positions[id] = index + 1;
            });

            fetch('{{ route("perbaharuiPosisiKartu") }}', {
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
                    toastr.success('Berhasil perbaharui posisi kartu!');
                    console.log('Posisi kartu berhasil diperbaharui!');
                } else {
                    toastr.success('Gagal perbaharui posisi kartu!');
                    console.error('Posisi kartu gagal diperbaharui!');
                }
            })

            .catch(error => {
                console.error('Terjadi kesalahan saat perbaharui posisi kartu:', error);
            });
            
        }
    });
</script>