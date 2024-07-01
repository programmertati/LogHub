<script>
    $(document).ready(function() { 
        var loading = `<div id="loader-wrapper">
                            <div id="loader">
                                <div class="loader-ellips">
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                </div>
                            </div>
                        </div>`;
        $('#form_kartu').on('submit', function(e) {
            e.preventDefault(); 
            $("#div_hasil").append(loading);
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    $("#div_hasil").html(data);
                },
                error: function(jXHR, textStatus, errorThrown) {
                    $("#div_hasil").html(textStatus);
                    toastr.error('Gagal memuat data!');
                }
            });
        });
    });
</script>