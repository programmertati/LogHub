<script>
    $(document).ready(function () {
        const id = '{{ $dataKolom->id }}';

        $('#updateColumnForm' + id).on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var columnId = form.find('#column_id').val();
            var columnName = form.find('#column_name').val();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function (response) {
                    $('#updateColumn' + columnId).modal('hide');
                    $('#kolomNama' + columnId).text(response.name);
                    $('[data-kolom-id="' + id + '"]').text(response.name);
                    toastr.success('Berhasil memperbaharui kolom!');
                },
                error: function (response) {
                    toastr.error('Gagal memperbaharui kolom!');
                }
            });
        });
    });
</script>