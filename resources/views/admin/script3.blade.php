<script>
    $(document).ready(function(){
       // Form delete Checklist
       $(document).off('click', '.deletes');
       $(document).on('click', '.deletes', function() {
           event.preventDefault();
            var id = $(this).attr('id').split('-');
            var formData = $('#myFormChecklistDelete' + id[1]).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('hapusChecklist') }}",
                data: formData,
                success: function(response){
                    console.log(response);
                    location.reload();
                    localStorage.setItem('modal_id', response.card_id);
                    toastr.success('Anda berhasil menghapus checklist!');

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
        // End Section Checklist
    });
</script>