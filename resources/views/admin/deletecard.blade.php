<script>
    $(document).ready(function () {
        const id = '{{ $isianKartu->id }}';
        $('#deleteCardForm'+id).on('submit', function(e){
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var cardId = form.data('id');
            
            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function (response) {
                    $('#isianKartu' + id).modal('hide');
                    $('li[data-id="' + cardId + '"]').addClass('hidden');
                    toastr.success('Berhasil menghapus kartu!');
                },
                error: function(response) {
                    toastr.error('Gagal menghapus kartu!');
                }
            });
        });
    });
</script>