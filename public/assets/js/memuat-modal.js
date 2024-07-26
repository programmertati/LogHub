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
    $('#isianKartu').modal('hide').one('hidden.bs.modal', function () {
        $('#deleteCard').modal('show');
    });
}

function deleteCardModal2(cardId, cardName, columnName, formAction) {
    $('#columnName3').text(columnName);
    $('#cardName3').text(cardName);
    $('#card-id').val(cardId);
    $('#deleteCardForm2').attr('action', formAction);
    $('#deleteCard2').modal('show');
}

function copyCardModal(cardId, cardName, formAction) {
    $('#copy-card-id').val(cardId);
    $('#copy-card-name').val(cardName).attr('placeholder', cardName);
    $('#copyCardForm').attr('action', formAction);
    $('#copy-card-name-error').text('');
    $('#copy-card-name').removeClass('is-invalid');
    $('#keep-checklists').prop('checked', true);

    // Mengambil total is_active dari server
    $.ajax({
        url: '/cards/' + cardId + '/total-active-checklists',
        type: 'GET',
        success: function(response) {
            $('#keep-checklists').next('label').text('Checklists (' + response.totalActiveChecklists + ')');
            $('#copyCard').modal('show');
        },
        error: function(error) {
            toastr.error('Gagal mengambil total checklist!');
        }
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
                $('#kolom-card-' + columnId).remove();
                toastr.success('Berhasil menghapus kolom!');
            },
            error: function(error) {
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
                $('li[data-id="' + cardId + '"]').remove();
                toastr.success(response.message);

                // Perbarui visibilitas tautan Recover Card
                var columnId = response.columnId;
                var softDeletedCards = response.softDeletedCards;
                var recoverCards = $('#recover-kartu-link-' + columnId);

                if (softDeletedCards > 0) {
                    recoverCards.show();
                } else {
                    recoverCards.hide();
                    $('#pulihkanKartuModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Gagal menghapus kartu!');
            }
        });
    });

    // Tangani transisi kembali ke modal isianKartu
    $(document).on('click', '.cancel-btn', function() {
        $('#deleteCard').modal('hide').one('hidden.bs.modal', function () {
            $('#isianKartu').modal('show');
        });
    });

    // Tangani tombol tutup modal dan klik di luar modal
    $(document).on('click', '.submit-btn', function() {
        $('#deleteCard').modal('hide').one('hidden.bs.modal', function () {
            $('#isianKartu').modal('hide');
        });
    });
});

$(document).ready(function() {
    $('#deleteCardForm2').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var cardId = $('#card-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteCard2').modal('hide');
                $('li[data-id="' + cardId + '"]').remove();
                toastr.success(response.message);

                // Perbarui visibilitas tautan Recover Card
                var columnId = response.columnId;
                var softDeletedCards = response.softDeletedCards;
                var recoverCards = $('#recover-kartu-link-' + columnId);

                if (softDeletedCards > 0) {
                    recoverCards.show();
                } else {
                    recoverCards.hide();
                    $('#pulihkanKartuModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Gagal menghapus kartu!');
            }
        });
    });
});

$('#copyCardForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            let cardContainer = document.getElementById('containerCard' + response.column.id);
            let newCard = document.createElement('li');
            newCard.classList.add('kartu-loghub');
            newCard.setAttribute('data-id', response.new_card_id);
            newCard.setAttribute('onmouseenter', 'aksiKartuShow(' + response.new_card_id + ')');
            newCard.setAttribute('onmouseleave', 'aksiKartuHide(' + response.new_card_id + ')');
            newCard.style.position = 'relative';

            // Container Kartu ketika disalin
            newCard.innerHTML = `
                <!-- Tampilan Aksi Edit -->
                <div class="cover-card card-cover2-${response.card.pattern || ''} ${response.card.pattern == null ? 'hiddens' : ''}" id="cover-card-${response.card.id}"></div>
                <div class="dropdown dropdown-action aksi-card" id="aksi-card${response.card.id}" style="position: absolute !important;">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="updateCardModal(${response.card.id}, '${response.card.name}', '${response.card.updateUrl}');" id="edit-card-${response.card.id}">
                            <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                        </a>
                        <a href="#" class="dropdown-item" onclick="deleteCardModal2('${response.card.id}', '${response.card.name}', '${response.column.name}', '${response.card.deleteUrl}');">
                            <i class='fa fa-trash-o m-r-5'></i> Delete
                        </a>
                        <a href="#" class="dropdown-item" onclick="copyCardModal('${response.card.id}', '${response.card.name}', '${response.card.copyCardUrl}');" id="copy-card-${response.card.id}">
                            <i class="fa-regular fa-copy m-r-5"></i> Copy Card
                        </a>
                    </div>
                </div>
                <!-- /Tampilan Aksi Edit -->

                <!-- Tampilan Kartu Pengguna -->
                <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val(${response.card.id}); $('#form_kartu').submit();">
                    <div class="card-nama" ${response.card.pattern ? 'style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;"' : ''}>
                        <span class="flex ms-3" id="span-nama-${response.card.id}" style="width: 150px; ${response.card.description ? 'margin-bottom: 10px;' : ''}">${response.card.name}</span>
                        <div class="tampilan-info gap-2">

                            <!-- Muncul apabila terdapat deskripsi pada kartu -->
                            ${response.card.description ? `
                                <div class="info-status8" id="descriptionStatus${response.card.id}">
                                    <i class="fa-solid fa-align-left icon-deskripsi-light
                                        @foreach($result_tema as $sql_mode => $mode_tema)
                                            @if($mode_tema->tema_aplikasi == 'Gelap')
                                                icon-deskripsi-dark
                                            @endif
                                        @endforeach">
                                    </i>
                                    <span class="text-status8"><b>This card has a description.</b></span>
                                </div>` : `
                                <div class="info-status8 hidden" id="descriptionStatus${response.card.id}">
                                    <i class="fa-solid fa-align-left icon-deskripsi-light
                                        @foreach($result_tema as $sql_mode => $mode_tema)
                                            @if($mode_tema->tema_aplikasi == 'Gelap')
                                                icon-deskripsi-dark
                                            @endif
                                        @endforeach">
                                    </i>
                                    <span class="text-status8"><b>This card has a description.</b></span>
                                </div>`}
                            <!-- /Muncul apabila terdapat deskripsi pada kartu -->
                            
                        </div>
                    </div>
                </a>
                <!-- /Tampilan Kartu Pengguna -->
            `;
            cardContainer.appendChild(newCard);

            toastr.success('Berhasil menyalin kartu!');
            $('#copyCard').modal('hide');
        },
        error: function(xhr, status, error) {
            toastr.error('Gagal menyalin kartu!');
            $('#copy-card-name-error').text('Terjadi kesalahan saat menyalin kartu!');
            $('#copy-card-name').addClass('is-invalid');
        }
    });
});