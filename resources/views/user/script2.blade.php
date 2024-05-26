<script>
    $(document).ready(function(){
        const title_id = '{{ $titleChecklists->id }}';
        const percentage = '{{ $titleChecklists->percentage }}';
        progressBar(title_id, percentage);
        // Section Update Title
        // Input update title
        $('#titleChecklistUpdate'+title_id).on('click', function(){
            $('#saveButtonTitleUpdate'+title_id).removeClass('hidden');
            $('#cancelButtonTitleUpdate'+title_id).removeClass('hidden');
        });
        // Button cancel form update title
        $('#cancelButtonTitleUpdate'+title_id).on('click', function(){
            $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
            $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
            $('#myFormTitleUpdate'+title_id)[0].reset();
        });
        // Form update title
        $('#myFormTitleUpdate'+title_id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateTitle2') }}",
                data: formData,
                success: function(response){
                    console.log(response);
                    $('#saveButtonTitleUpdate'+title_id).addClass('hidden');
                    $('#cancelButtonTitleUpdate'+title_id).addClass('hidden');
                    toastr.success('Anda berhasil memperbaharui judul!');
                    localStorage.clear();
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // Form delete title
        $('#myFormTitleDelete'+title_id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            console.log(title_id);
            $.ajax({
                type: 'POST',
                url: "{{ route('hapusTitle2') }}",
                data: formData,
                success: function(response){
                    console.log(response);
                    localStorage.setItem('modal_id', response.card_id);
                    location.reload();
                    toastr.success('Anda berhasil menghapus judul!');

                    // Show modal after create title
                    var modal_id = localStorage.getItem('modal_id');
                    $('#isianKartu'+modal_id).modal('show');
                    $('#isianKartu'+id).on('click', function(){
                        localStorage.clear();
                    });
                },
                error: function(){
                    alert('An error occurred. Please try again.');
                }
            });
        });
        // End Section Update Title

        // Section Checklist
        // Button add form checklist
        $('#AddChecklist'+title_id).on('click', function(){
            $('#AddChecklist'+title_id).addClass('hidden');
            $('#checklist'+title_id).removeClass('hidden');
            $('#saveButtonChecklist'+title_id).removeClass('hidden');
            $('#cancelButtonChecklist'+title_id).removeClass('hidden');
        });
        // Button cancel form checklist
        $('#cancelButtonChecklist'+title_id).on('click', function(){
            $('#AddChecklist'+title_id).removeClass('hidden');
            $('#checklist'+title_id).addClass('hidden');
            $('#saveButtonChecklist'+title_id).addClass('hidden');
            $('#cancelButtonChecklist'+title_id).addClass('hidden');
            $('#myFormChecklist'+title_id)[0].reset();
        });
        // Form Add Checklist
        $('#myFormChecklist'+title_id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addChecklist2') }}",
                data: formData,
                success: function(response){
                    $('#AddChecklist'+title_id).removeClass('hidden');
                    $('#checklist'+title_id).addClass('hidden');
                    $('#checklist'+title_id).val('');
                    $('#saveButtonChecklist'+title_id).addClass('hidden');
                    $('#cancelButtonChecklist'+title_id).addClass('hidden');
                    toastr.success('Anda berhasil membuat checklist!');
                    console.log(response);
                    var newForm = `<div class="input-checklist2">
                                        <form id="myFormChecklistUpdate${response.checklist.id}" method="POST" class="form-checklist flex gap-5">
                                            @csrf
                                            <input class="dynamicCheckbox" type="checkbox" id="${response.checklist.id}" name="${response.checklist.id}" ${response.checklist.is_active ? 'checked' : ''}>
                                            <label class="dynamicCheckboxLabel border border-1 border-darks w-407s p-2 rounded-xl ${response.checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${response.checklist.id}" for="labelCheckbox-${response.checklist.id}">${response.checklist.name}</label>
                                            <input type="hidden" id="checklist_id" name="checklist_id" value="${response.checklist.id}">
                                            <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                            <input type="text" class="dynamicCheckboxValue border border-1 border-darks w-407s p-2 rounded-xl hidden" id="checkbox-${response.checklist.id}" name="checkbox-${response.checklist.id}" value="${response.checklist.name}">
                                        </form>
                                        <form id="myFormChecklistDelete${response.checklist.id}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="${response.checklist.id}">
                                            <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                            <div class="icon-hapus-checklist" id="hapus-checklist${response.checklist.id}">
                                                <button type="button" class="deletes" id="deleteButtonChecklist-${response.checklist.id}" style="border: none; background: none; padding: 0;">
                                                    <i class="fa-solid fa-trash fa-lg"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <div class="aksi-update-checklist2 gap-2">
                                            <button type="button" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-${response.checklist.id}">Simpan</button>
                                            <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-${response.checklist.id}">Batal</button>
                                        </div>
                                    </div>`;
                    $('#checkbox-container-'+title_id).append(newForm);
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
         // Checkbox form checklist
        $(document).off('change', '.dynamicCheckbox');
        $(document).on('change', '.dynamicCheckbox', function() {
            var checkbox = $(this);
            var isChecked = checkbox.is(':checked');
            var label = $('label[for="labelCheckbox-' + checkbox.attr('id') + '"]');
            if (isChecked) {
                label.addClass('strike-through');
                label.removeClass('hidden');
                $('#checkbox-'+checkbox.attr('id')).addClass('hidden');
                $('#saveButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                $('#cancelButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                formChecklist(checkbox.attr('id'));
            } else {
                label.removeClass('strike-through');
                label.removeClass('hidden');
                $('#checkbox-'+checkbox.attr('id')).addClass('hidden');
                $('#saveButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                $('#cancelButtonChecklistUpdate-'+checkbox.attr('id')).addClass('hidden');
                formChecklist(checkbox.attr('id'));
            }
        });
        // Label form checklist
        $(document).off('click', 'label[for]');
        $(document).on('click', 'label[for]', function() {
            var label = $(this).attr('for');
            var checkboxId = label.split('-');
            console.log(checkboxId);
            $('label[for="labelCheckbox-' + checkboxId[1] + '"]').addClass('hidden');
            console.log(checkboxId[1]);
           $('#checkbox-'+checkboxId[1]).removeClass('hidden');
           $('#saveButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
           $('#cancelButtonChecklistUpdate-'+checkboxId[1]).removeClass('hidden');
        });
        // Button cancels form checklist
        $(document).off('click', '.cancels');
        $(document).on('click', '.cancels', function() {
            var id = $(this).attr('id').split('-');
            $('#checkbox-'+id[1]).addClass('hidden');
            $('#saveButtonChecklistUpdate-'+id[1]).addClass('hidden');
            $('#cancelButtonChecklistUpdate-'+id[1]).addClass('hidden');
            $('label[for="labelCheckbox-' + id[1] + '"]').removeClass('hidden');
        });
        // Button saves form checklist
        $(document).off('click', '.saves');
        $(document).on('click', '.saves', function() {
            var id = $(this).attr('id').split('-');
            console.log(id);
            event.preventDefault(); 
            var formData = $('#myFormChecklistUpdate' + id[1]).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateChecklist2') }}",
                data: formData,
                success: function(response){
                    $('label[for="labelCheckbox-' + response.checklist.id+ '"]').removeClass('hidden');
                    $('label[for="labelCheckbox-' + response.checklist.id+ '"]').html(response.checklist.name);
                    $('#checkbox-'+response.checklist.id).addClass('hidden');
                    $('#saveButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                    $('#cancelButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                    toastr.success('Anda berhasil memperbaharui checklist!');
                    localStorage.clear();
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // Form Update Checklist
        function formChecklist(id){
            var formData = $('#myFormChecklistUpdate' + id).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('updateChecklist2') }}",
                data: formData,
                success: function(response){
                    $('label[for="labelCheckbox-' + response.checklist.id+ '"]').removeClass('hidden');
                    $('label[for="labelCheckbox-' + response.checklist.id+ '"]').html(response.checklist.name);
                    $('#checkbox-'+response.checklist.id).addClass('hidden');
                    $('#saveButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                    $('#cancelButtonChecklistUpdate-'+response.checklist.id).addClass('hidden');
                    console.log(response);
                    toastr.success('Anda berhasil memperbaharui checklist!');
                    progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                    localStorage.clear();
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        }
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