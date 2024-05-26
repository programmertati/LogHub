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
                $('#cardContainer').append(`<div class="w-full max-w-72 p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700" id="kolom-trello">
                                                <a href="#" data-toggle="modal" data-target="#diisi id modal">
                                                    <div class="aksi-kolom">
                                                        <i class="fa-solid fa-pencil fa-sm"></i>
                                                    </div>
                                                </a>
                                                <a href="#" data-toggle="modal" data-target="#diisi id modal">
                                                    <div class="aksi-kolom2">
                                                        <i class="fa-solid fa-trash fa-sm"></i>
                                                    </div>
                                                </a>
                                                <h5 class="kolom-nama mb-3 font-semibold text-lg dark:text-white">${data.name}</h5>
                                                <ul class="my-4 space-y-3">
                                                    <li class="card-trello hidden" id="cardTrello">
                                                        <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                                            <textarea class="form-control" name="#" style="border-radius: 15px; background-color: #f5fffa;" placeholder="Masukkan judul ini.."></textarea>
                                                        </div>
                                                    </li>
                                                    <a href="#" class="btn btn-outline-info" style="border-radius: 30px" id="addCardButton">
                                                        <i class="fa-solid fa-plus"></i> Tambah Kartu
                                                    </a>
                                                </ul>
                                            </div>`)
                console.log(`Respon dari server: ${data.id} ${data.name}`);
                $('#buat-kolom').val('');
            })
            .then((e) => {
                toastr.success('Anda berhasil membuat kolom!');
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