function updateColumnModal(columnId, columnName, formAction) {
    $('#update-column-id').val(columnId);
    $('#update-column-name').val(columnName);
    $('#updateColumnForm').attr('action', formAction);
    $('#column-name-error').text('');
    $('#update-column-name').removeClass('is-invalid');
    $('#updateColumn').modal('show');
}

function updateCardModal(cardId, cardName, formAction) {
    $('#update-card-id').val(cardId);
    $('#update-card-name').val(cardName);
    $('#updateCardForm').attr('action', formAction);
    $('#card-name-error').text('');
    $('#update-card-name').removeClass('is-invalid');
    $('#updateCard').modal('show');
}

function deleteColumnModal(columnId, columnName, formAction) {
    $('#columnName').text(columnName);
    $('#column-id').val(columnId);
    $('#deleteColumnForm').attr('action', formAction);
    $('#deleteColumn').modal('show');
}

function deleteCardModal(cardId, cardName, columnName, formAction) {
    $('#columnName2').text(columnName);
    $('#cardName2').text(cardName);
    $('#card-id').val(cardId);
    $('#deleteCardForm').attr('action', formAction);
    $('#isianKartu' + cardId).modal('hide').one('hidden.bs.modal', function () {
        $('#deleteCard').modal('show');
    });
}

$(document).ready(function() {
    $('#updateColumnForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var columnId = $('#update-column-id').val();
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                $('#updateColumn').modal('hide');
                $('#kolomNama' + columnId).text(response.name);
                $('[data-kolom-id="' + columnId + '"]').text(response.name);
                $('#edit-column-' + columnId).attr('onclick', `updateColumnModal(${columnId}, '${response.name}', '${url}')`);
                toastr.success('Berhasil memperbaharui kolom!');
            },
            error: function(response) {
                if (response.responseJSON && response.responseJSON.errors) {
                    var errors = response.responseJSON.errors;
                    if (errors.column_name) {
                        $('#update-column-name').addClass('is-invalid');
                        $('#column-name-error').text(errors.column_name[0]);
                    }
                } else {
                    toastr.error('Gagal memperbaharui kolom!');
                }
            }
        });
    });
});

$(document).ready(function() {
    $('#updateCardForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var cardId = $('#update-card-id').val();
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                $('#updateCard').modal('hide');
                $('#span-nama-' + cardId).text(response.name);
                $('[data-kartu-id="' + cardId + '"]').text(response.name);
                $('#edit-card-' + cardId).attr('onclick', `updateCardModal(${cardId}, '${response.name}', '${url}')`);
                toastr.success('Berhasil memperbaharui kartu!');
            },
            error: function(response) {
                if (response.responseJSON && response.responseJSON.errors) {
                    var errors = response.responseJSON.errors;
                    if (errors.column_name) {
                        $('#update-card-name').addClass('is-invalid');
                        $('#card-name-error').text(errors.column_name[0]);
                    }
                } else {
                    toastr.error('Gagal memperbaharui kartu!');
                }
            }
        });
    });
});

$(document).ready(function() {
    $('#deleteColumnForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var columnId = $('#column-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteColumn').modal('hide');
                $('#kolom-card-' + columnId).addClass('hidden');
                toastr.success('Berhasil menghapus kolom!');
            },
            error: function(response) {
                toastr.error('Gagal menghapus kolom!');
            }
        });
    });
});

$(document).ready(function() {
    $('#deleteCardForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var cardId = $('#card-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteCard').modal('hide');
                $('li[data-id="' + cardId + '"]').addClass('hidden');
                toastr.success('Berhasil menghapus kartu!');
            },
            error: function(response) {
                toastr.error('Gagal menghapus kartu!');
            }
        });
    });

    $(document).on('click', '.cancel-btn', function() {
        var cardId = $('#card-id').val();
        $('#deleteCard').modal('hide').one('hidden.bs.modal', function () {
            $('#isianKartu' + cardId).modal('show');
        });
    });
});