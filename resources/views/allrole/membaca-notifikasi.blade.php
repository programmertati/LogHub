<script>
    $(document).ready(function() {
        $('.close-notification').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('bacaNotifikasi', ['id' => ':id']) }}".replace(':id', id),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    toastr.success('Berhasil membaca notifikasi!');

                    var unreadCount = parseInt($('.badge-pill').text());
                    if (unreadCount > 0) {
                        $('.badge-pill').text(unreadCount - 1);
                    }

                    $('#open-popup_' + id).closest('.notification-message').remove();
                    if (unreadCount > 1) {
                        $('#noNewNotifications').addClass('hidden');
                        $('#mark-all').show();
                    } else {
                        $('#noNewNotifications').removeClass('hidden');
                        $('#mark-all').hide();
                    }
                    // var notificationElement = $('#open-popup_' + id).closest('.notification-message');
                    // notificationElement.removeClass('noti-unread').addClass('noti-read');
                    // notificationElement.find('a').attr('id', 'open-popup_' + id);
                },
                error: function(error) {
                    toastr.error('Gagal membaca notifikasi!');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#mark-all').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success(response.success);
                    
                    var unreadCount = parseInt($('.badge-pill').text());
                    $('.badge-pill').text(0);
                    
                    $('.notification-message').remove();
                },
                error: function(error) {
                    toastr.error('Gagal membaca semua notifikasi!');
                }
            });
        });
    });
</script>