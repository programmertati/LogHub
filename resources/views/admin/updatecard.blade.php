<script>
    $(document).ready(function() {
        const id = '{{ $dataKartu->id }}';

        $('#updateCardForm' + id).on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function(response) {
                    $('#editCard' + id).modal('hide');
                    $('#span-nama-' + id).text(response.name);
                    $('[data-kartu-id="' + id + '"]').text(response.name);
                    toastr.success('Berhasil memperbaharui kartu!');
                },
                error: function(response) {
                    toastr.error('Gagal memperbaharui kartu!');
                }
            });
        });
    });
</script>