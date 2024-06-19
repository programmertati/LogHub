document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addColForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Respons jaringan tidak baik.');
            })
            .then((data) => {
                $('#cardContainer').append(`<div class="kolom-card-${data.id}" id="kolom-card-${data.id}" data-id="${data.id}" onmouseenter="aksiKolomShow(${data.id})" onmouseleave="aksiKolomHide(${data.id})">
                                                <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom${data.id}">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateColumn${data.id}">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>
                                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteColumn${data.id}">
                                                            <i class='fa fa-trash-o m-r-5'></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                                <h5 class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">${data.name}</h5>
                                                <ul class="card-container">
                                                    <li class="card-loghub hidden" id="cardLoghub${data.id}">
                                                        <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                                            <form action="{{ route('addCard', ['board_id' => $board->id, 'team_id' => $board->team_id, 'column_id' => $dataKolom->id ]) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" class="form-control" name="board_id" value="{{ $board->id }}">
                                                                <input type="hidden" class="form-control" name="team_id" value="{{ $team->id }}">
                                                                <input type="hidden" class="form-control" name="column_id" value="${data.id}">
                                                                <input type="text" class="form-control" name="name" id="cardName" style="width: 130%; border-radius: 10px; background-color: #f5fffa;" placeholder="Enter card's name..." required>
                                                                <button type="submit" class="btn btn-outline-info btn-add">Add card</button>
                                                            </form>
                                                        </div>
                                                    </li>
                                                    <button onclick="openAdd('${data.id}')" class="btn btn-outline-info" id="btn-add${data.id}">
                                                        <i class="fa-solid fa-plus"></i> Add a card...
                                                    </button>
                                                </ul>
                                            </div>`)
                console.log(`Respon dari server: ${data.id} ${data.name}`);
                $('#buat-kolom').val('');
            })
            .then((e) => {
                toastr.success('Berhasil membuat kolom!');
                $('#addCol').modal('hide');
            })
            .catch((error) => {
                console.error('Error:', error);
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
    });
});