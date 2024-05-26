function pembaharuanTanggalWaktu()
{
    var now = new Date();
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var tanggal = now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
    var jam = now.getHours();
    var menit = now.getMinutes();
    var detik = now.getSeconds();

    jam = (jam < 10) ? '0' + jam : jam;
    menit = (menit < 10) ? '0' + menit : menit;
    detik = (detik < 10) ? '0' + detik : detik;

    document.getElementById('tanggal-master').innerHTML = tanggal;
    document.getElementById('waktu-master').innerHTML = jam + ':' + menit;

    document.getElementById('tanggal-semua-notifikasi').innerHTML = tanggal;
    document.getElementById('waktu-semua-notifikasi').innerHTML = jam + ':' + menit;

    setTimeout(pembaharuanTanggalWaktu, 1000);
}

window.onload = pembaharuanTanggalWaktu;