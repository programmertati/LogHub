<script>
    $(document).ready(function(){
       // Form delete Checklist
       $(document).off('click', '.deletes');
       $(document).on('click', '.deletes', function(event) {
           event.preventDefault();
            var id = $(this).attr('id').split('-');
            var formData = $('#myFormChecklistDelete' + id[1]).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('hapusChecklist2') }}",
                data: formData,
                success: function(response){
                    // Menghilangkan Data Checklist //
                    $('#myFormChecklistUpdate' + id[1]).hide();
                    $('#myFormChecklistDelete' + id[1]).hide();
                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    toastr.success('Berhasil menghapus checklist!');
                },
                error: function(){
                    alert('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // End Section Checklist
        function progressBar(id,percentage ) {
            var progressBar = $('.progress-bar-' + id);
            progressBar.css('width', percentage + '%');
            progressBar.attr('aria-valuenow', percentage);
            progressBar.text(Math.round(percentage) + '%');
            progressBar.removeClass('bg-danger bg-warning bg-info bg-success');
            if (percentage <= 25) {
                progressBar.addClass('bg-danger');
            } else if (percentage > 25 && percentage < 50) {
                progressBar.addClass('bg-warning');
            } else if (percentage >= 50 && percentage <= 75) {
                progressBar.addClass('bg-info');
            } else if (percentage > 75) {
                progressBar.addClass('bg-success');
            }
        }
    });
</script>