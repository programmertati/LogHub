<script>
    $(document).ready(function() {
        const title_id = '{{ $titleChecklists->id }}';

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;

        $('#checklistform-all-' + title_id).on('change', function() {
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var checklistId = $(this).closest('form').find('input[name="title_checklists_id"]').val();
            var isChecked = $(this).is(':checked');
            var toastBerhasil = isChecked ? 'Berhasil centang semua checklist!' : 'Berhasil tidak centang semua checklist!';

            $.ajax({
                url: '/perbaharui/semua/checklist',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title_checklists_id: checklistId,
                    is_active: isChecked ? 1 : 0
                },
                success: function(response) {
                    toastr.success(toastBerhasil);
                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    updateCheckboxes(response.checklist);

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(xhr) {
                    toastr.error('Gagal memperbarui checklist!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });

        function progressBar(id, percentage) {
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

        function updateCheckboxes(checklists) {
            checklists.forEach(function(checklist) {
                var checkbox = $('#'+ checklist.id);
                checkbox.prop('checked', checklist.is_active == 1);
                var label = $('#labelCheckbox-' + checklist.id);
                if (checklist.is_active == 1) {
                    label.addClass('strike-through');
                } else {
                    label.removeClass('strike-through');
                }
            });
        }
    });
</script>