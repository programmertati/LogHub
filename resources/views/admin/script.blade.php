<script>
    $(document).ready(function(){
        const id = '{{ $isianKartu->id }}';
        // Section Keterangan
        // Input keterangan
        $('#keterangan'+id).on('click', function(){
            $('#saveButton'+id).removeClass('hidden');
            $('#cancelButton'+id).removeClass('hidden');
        });
        // Button cancel form keterangan
        $('#cancelButton'+id).on('click', function(){
            $('#saveButton'+id).addClass('hidden');
            $('#cancelButton'+id).addClass('hidden');
            $('#myForm'+id)[0].reset();
        });
        // Form keterangan
        $('#myForm'+id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addDescription') }}",
                data: formData,
                success: function(response){
                    console.log(response);
                    $('#saveButton'+id).addClass('hidden');
                    $('#cancelButton'+id).addClass('hidden');
                    toastr.success('Anda berhasil memperbaharui keterangan!');
                    localStorage.clear();
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // End Section Keterangan

        // Section Title
        // Button add form title
         $('#addTitle-'+id).on('click', function(){
            $('#titleChecklist'+id).removeClass('hidden');
            $('#saveButtonTitle'+id).removeClass('hidden');
            $('#cancelButtonTitle'+id).removeClass('hidden');
            $('#iconCheck-'+id).removeClass('hidden');
            $('#addTitle-'+id).addClass('hidden');
        });
        // Button cancel form title
        $('#cancelButtonTitle'+id).on('click', function(){
            $('#titleChecklist'+id).addClass('hidden');
            $('#saveButtonTitle'+id).addClass('hidden');
            $('#cancelButtonTitle'+id).addClass('hidden');
            $('#iconCheck-'+id).addClass('hidden');
            $('#addTitle-'+id).removeClass('hidden');
            $('#myFormTitle'+id)[0].reset();
        });
        // Form title
        $('#myFormTitle'+id).on('submit', function(event){
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addTitle') }}",
                data: formData,
                success: function(response){
                    console.log(response.card_id);
                    $('#titleChecklist'+id).addClass('hidden');
                    $('#saveButtonTitle'+id).addClass('hidden');
                    $('#cancelButtonTitle'+id).addClass('hidden');
                    $('#iconCheck-'+id).addClass('hidden');
                    $('#addTitle-'+id).addClass('hidden');
                    localStorage.setItem('modal_id', response.card_id);
                    window.location.reload();
                    toastr.success('Anda berhasil menambahkan judul!');
                },
                error: function(){
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            });
        });
        // Show modal after create title
        var modal_id = localStorage.getItem('modal_id');
        $('#isianKartu'+modal_id).modal('show');
        $('#isianKartu'+id).on('click', function(){
            localStorage.clear();
        });
        // End Section Title
    });
</script>