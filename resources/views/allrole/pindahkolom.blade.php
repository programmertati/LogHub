<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const sortable = new Sortable(document.getElementById('cardContainer'), {
            animation: 150,
            onEnd: function (evt) {
                const columns = document.querySelectorAll('[id^="kolom-card"]');
                const positions = {};

                columns.forEach((column, index) => {
                    positions[column.dataset.id] = index + 1;
                });

                fetch('/perbaharui/posisi/kolom', {
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
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>