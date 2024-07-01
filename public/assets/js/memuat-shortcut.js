document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        $('.modal').modal('hide');
    }

    if (event.ctrlKey && event.key === 'b' || event.key === 'B') {
        $('#addCol').modal('toggle');
    }

    if (event.ctrlKey && event.key === 'm' || event.key === 'M') {
        var firstButton = document.querySelector('button[id^="btn-add"]');
        if (firstButton) {
            var id = firstButton.getAttribute('id').replace('btn-add', '');
            openAdd(id);
        }
    }
    
});