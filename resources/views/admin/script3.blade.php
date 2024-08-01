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
                url: "{{ route('hapusChecklist') }}",
                data: formData,
                success: function(response){
                    // Hapus Section Checklist
                    $('#section-checklist-' + id[1]).remove();

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 0) {
                        checklistAllCheckbox.addClass('hidden');
                    }

                    // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                    var cardId = response.cardId;
                    var softDeletedTitle = response.softDeletedTitle;
                    var softDeletedChecklist = response.softDeletedChecklist;
                    var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                    if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                        recoverTitleChecklist.show();
                    } else {
                        recoverTitleChecklist.hide();
                    }

                    // Pengecekan pada checkbox
                    var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist.id);
                    if (response.titlechecklist.percentage === 100) {
                        checklistAllCheckbox.prop('checked', true);
                    } else {
                        checklistAllCheckbox.prop('checked', false);
                    }

                    // Untuk Mengatur Icon Checklist //
                    if (response.jumlahChecklist === 0) {
                        $('#iconChecklist-' + response.cardId).addClass('hidden');
                    }
                    $('#perhitunganChecklist-' + response.cardId).html(response.perChecklist + '/' + response.jumlahChecklist);

                    if (response.perChecklist < response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.cardId;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.removeClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                            iconChecklistCheck.addClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.removeClass('icon-check-full-light').removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.removeClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                            iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                            iconChecklistCheck.removeClass('icon-check-full-dark').removeClass('icon-check-full-light');
                        }
                    } else if (response.perChecklist == response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.cardId;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.addClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                            iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.addClass('icon-check-full-light').removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.addClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                            iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                            iconChecklistCheck.addClass('icon-check-full-dark').removeClass('icon-check-full-light');
                        }
                    }
                    // /Untuk Mengatur Icon Checklist //

                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    toastr.success('Berhasil menghapus checklist!');
                },
                error: function(error){
                    toastr.error('Gagal menghapus checklist!');
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