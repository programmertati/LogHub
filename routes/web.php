<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LockScreen;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChecklistController;

// ----------------------------- Menu Sidebar Aktif ----------------------------- //
function set_active($route) {
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

// ----------------------------- Autentikfikasi Login ----------------------------- //
Route::get('/', function () {
    return view('auth.login');
});

// ----------------------------- Autentikfikasi MultiLevel ----------------------------- //
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
    Route::get('home',function() {
        return view('home');
    });
});
Auth::routes();

// ----------------------------- Halaman Utama ----------------------------- //
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::patch('/update-tema/{id}', 'updateTemaAplikasi')->name('updateTemaAplikasi');
    Route::get('/notifikasi/dibaca/{id}', 'bacaNotifikasi')->name('notifikasi.dibaca');
    Route::post('/notifikasi/dibaca/semua', 'bacasemuaNotifikasi')->name('notifikasi.dibaca-semua');
    Route::get('/ulangtahun', 'ulangtahun')->name('ulangtahun');
});

// ----------------------------- Pengaturan Perusahaan ----------------------------- //
Route::controller(SettingController::class)->group(function () {
    Route::get('pengaturan/perusahaan', 'pengaturanPerusahaan')->middleware('auth')->name('pengaturan-perusahaan');
    Route::post('pengaturan/perusahaan/save', 'tambahPengaturan')->middleware('auth')->name('pengaturan-perusahaan-save');
});

// ----------------------------- Masuk Aplikasi ----------------------------- //
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// ----------------------------- Kunci Layar ----------------------------- //
Route::controller(LockScreen::class)->group(function () {
    Route::get('lock_screen', 'lockScreen')->middleware('auth')->name('lock_screen');
    Route::post('unlock', 'unlock')->name('unlock');
});

// ----------------------------- Daftar Akun ----------------------------- //
Route::controller(RegisterController::class)->group(function () {
    Route::get('/daftar', 'tampilanDaftar')->name('daftar');
    Route::post('/daftar', 'daftarAplikasi')->name('daftar');
});

// ----------------------------- Lupa Kata Sandi ----------------------------- //
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('lupa-kata-sandi', 'getEmail')->name('lupa-kata-sandi');
    Route::post('lupa-kata-sandi', 'postEmail')->name('lupa-kata-sandi');    
});

// ----------------------------- Atur Ulang Kata Sandi ----------------------------- //
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('ubah-kata-sandi/{token}', 'getPassword')->name('ubah-kata-sandi');
    Route::post('ubah-kata-sandi', 'updatePassword')->name('ubah-kata-sandi');
});

// ----------------------------- Pengelola Pengguna ----------------------------- //
Route::controller(UserManagementController::class)->group(function () {
    Route::get('manajemen/pengguna', 'index')->middleware('auth')->name('manajemen-pengguna');
    Route::get('get-users-data', 'getPenggunaData')->name('get-users-data');
    Route::get('riwayat/aktivitas', 'tampilanUserLogAktivitas')->middleware('auth')->name('riwayat-aktivitas');
    Route::get('riwayat/aktivitas/otentikasi', 'tampilanLogAktivitas')->middleware('auth')->name('riwayat-aktivitas-otentikasi');
    Route::get('admin/profile', 'profileAdmin')->middleware('auth')->name('admin-profile');
    Route::get('user/profile', 'profileUser')->middleware('auth')->name('user-profile');
    Route::post('profile/perbaharui/data-pengguna', 'perbaharuiDataPengguna')->name('profile/perbaharui/data-pengguna');
    Route::post('profile/perbaharui/data-pengguna2', 'perbaharuiDataPengguna2')->name('profile/perbaharui/data-pengguna2');
    Route::post('profile/perbaharui/foto', 'perbaharuiFotoProfile')->name('profile/perbaharui/foto');
    Route::post('data/pengguna/tambah-data', 'tambahAkunPengguna')->name('data/pengguna/tambah-data');
    Route::post('data/pengguna/perbaharui', 'perbaharuiAkunPengguna')->name('data/pengguna/perbaharui');
    Route::post('data/pengguna/hapus', 'hapusAkunPengguna')->name('data/pengguna/hapus');
    Route::get('admin/kata-sandi', 'tampilanPerbaharuiKataSandi')->middleware('auth')->name('admin-kata-sandi');
    Route::get('user/kata-sandi', 'tampilanPerbaharuiKataSandi')->middleware('auth')->name('user-kata-sandi');
    Route::post('change/password/db', 'perbaharuiKataSandi')->name('change/password/db');
    Route::get('get-riwayat-aktivitas', 'getRiwayatAktivitas')->name('get-riwayat-aktivitas');
    Route::get('get-aktivitas-pengguna', 'getAktivitasPengguna')->name('get-aktivitas-pengguna');
 
});

// ----------------------------- Profil Pengguna By ID Akun ----------------------------- //
Route::controller(EmployeeController::class)->group(function () {
    Route::get('user/profile/{user_id}', 'profileEmployee')->middleware('auth');
});

// ----------------------------- Notifikasi ----------------------------- //
Route::controller(NotificationController::class)->group(function () {
    Route::get('tampilan/semua/notifikasi', 'tampilanNotifikasi')->name('tampilan-semua-notifikasi');
    Route::get('/tampilan/semua/notifikasi/hapus/data/{id}', 'hapusNotifikasi')->name('tampilan-semua-notifikasi-hapus-data');
});

// ----------------------------- Team ----------------------------- //
Route::controller(TeamController::class)->group(function () {
    // ----------------------------- Admin ----------------------------- //
    Route::post("admin/tim", "createTeam")->middleware(["auth", "auth.session"])->name("doCreateTeam");
    Route::get("admin/tim", "showTeams")->middleware(["auth", "auth.session"])->name("showTeams");
    Route::get("admin/tim/cari", "search")->middleware(["auth", "auth.session"])->name("searchTeam");
    Route::get("admin/tim/lihat-papan/{team_id}", "showTeam")->middleware(["auth", "auth.session", "userInTeam"])->name("viewTeam");
    Route::get("admin/tim/lihat-papan/cari/papan/{team_id}", "searchBoard")->middleware(["auth", "auth.session", "userInTeam"])->name("searchBoard");
    Route::post("admin/tim/perbaharui/tim/{team_id}", "updateData")->middleware(["auth", "auth.session", "userInTeam"])->name("doTeamDataUpdate");
    Route::post("admin/tim/hapus/tim/{team_id}", "deleteTeam")->middleware(["auth", "auth.session", "userInTeam"])->name("doDeleteTeam");
    Route::post("admin/tim/hapus/pengguna/{team_id}", "deleteMembers")->middleware(["auth", "auth.session", "userInTeam"])->name("deleteTeamMember");
    Route::post("admin/tim/undangan/{team_id}", "inviteMembers")->middleware(["auth", "auth.session", "userInTeam"])->name("doInviteMembers");
    Route::post("admin/tim/perbaharui/foto/{team_id}", "updateImage")->middleware(["auth", "auth.session", "userInTeam"])->name("doChangeTeamImage");
    Route::get("admin/tim/undangan/{team_id}/{user_id}", "getInvite")->middleware(["auth", "auth.session", ])->name("getInvite");

    // ----------------------------- User ----------------------------- //
    Route::get("user/tim", "showTeams2")->middleware(["auth", "auth.session"])->name("showTeams2");
    Route::get("user/tim/cari", "search2")->middleware(["auth", "auth.session"])->name("searchTeam2");
    Route::get("user/tim/lihat-papan/{team_id}", "showTeam2")->middleware(["auth", "auth.session", "userInTeam"])->name("viewTeam2");
    Route::get("user/tim/undangan/diterima/{team_id}/{user_id}", "acceptInvite")->middleware(["auth", "auth.session", ])->name("acceptTeamInvite");
    Route::get("user/tim/undangan/ditolak/{team_id}/{user_id}", "rejectInvite")->middleware(["auth", "auth.session", ])->name("rejectTeamInvite");
    Route::get("user/tim/lihat-papan/cari/papan/{team_id}", "searchBoard2")->middleware(["auth", "auth.session", "userInTeam"])->name("searchBoard2");
    Route::post("user/tim/tinggalkan/{team_id}", "leaveTeam")->middleware(["auth", "auth.session", "userInTeam"])->name("doLeaveTeam");
});

// ----------------------------- Board ----------------------------- //
Route::controller(BoardController::class)->group(function () {
    // ----------------------------- Admin ----------------------------- //
    Route::post("admin/tim/papan/{team_id}", "createBoard")->middleware("auth", "auth.session", "userInTeam")->name("createBoard");
    Route::get("admin/tim/papan/{team_id}/{board_id}", "showBoard")->middleware("auth", "auth.session", "boardAccess")->name("board");
    Route::post("admin/tim/papan/{team_id}/{board_id}", "updateBoard")->middleware("auth", "auth.session", "boardAccess")->name("updateBoard");
    Route::post("admin/tim/papan/hapus/{team_id}/{board_id}", "deleteBoard")->middleware("auth", "auth.session", "boardAccess")->name("deleteBoard");
    Route::post("admin/tim/{team_id}/papan/{board_id}/kolom", "addColumn")->middleware("auth", "auth.session", "boardAccess")->name("addCol");
    // Route::post("admin/tim/{team_id}/papan/{board_id}/kolom/{card_id}", "addColumn")->middleware("auth", "auth.session", "boardAccess")->name("addCol");
    Route::post("admin/tim/papan/kolom/perbaharui/{team_id}/{board_id}", "updateCol")->middleware("auth", "auth.session", "boardAccess")->name("updateCol");
    Route::post("admin/tim/papan/kolom/hapus/{team_id}/{board_id}", "deleteCol")->middleware("auth", "auth.session", "boardAccess")->name("deleteCol");
    Route::post("admin/tim/{team_id}/papan/{board_id}/kolom/{column_id}/kartu", "addCard")->middleware("auth", "auth.session", "boardAccess")->name("addCard");
    Route::post("admin/tim/papan/kolom/kartu/perbaharui/{card_id}", "perbaharuiKartu")->name("perbaharuiKartu");
    Route::post("admin/tim/papan/kolom/kartu/hapus/{card_id}", "hapusKartu")->name("hapusKartu");
    Route::post("admin/tim/papan/kolom/kartu/deskripsi/tambah", "addDescription")->name("addDescription");
    Route::post("admin/tim/papan/kolom/kartu/komentar/{card_id}", "komentarKartu")->name("komentarKartu");

    // ----------------------------- User ----------------------------- //
    Route::get("user/tim/papan/{team_id}/{board_id}", "showBoard2")->middleware("auth", "auth.session", "boardAccess")->name("board2");
    Route::post("user/tim/{team_id}/papan/{board_id}/kolom", "addColumn2")->middleware("auth", "auth.session", "boardAccess")->name("addCol2");
    // Route::post("user/tim/{team_id}/papan/{board_id}/kolom/{card_id}", "addColumn2")->middleware("auth", "auth.session", "boardAccess")->name("addCol2");
    Route::post("user/tim/papan/kolom/perbaharui/{team_id}/{board_id}", "updateCol2")->middleware("auth", "auth.session", "boardAccess")->name("updateCol2");
    Route::post("user/tim/papan/kolom/hapus/{team_id}/{board_id}", "deleteCol2")->middleware("auth", "auth.session", "boardAccess")->name("deleteCol2");
    Route::post("user/tim/{team_id}/papan/{board_id}/kolom/{column_id}/kartu", "addCard2")->middleware("auth", "auth.session", "boardAccess")->name("addCard2");
    Route::post("user/tim/papan/kolom/kartu/perbaharui/{card_id}", "perbaharuiKartu2")->name("perbaharuiKartu2");
    Route::post("user/tim/papan/kolom/kartu/hapus/{card_id}", "hapusKartu2")->name("hapusKartu2");
    Route::post("user/tim/papan/kolom/kartu/deskripsi/tambah", "addDescription2")->name("addDescription2");
    Route::post("user/tim/papan/kolom/kartu/komentar/{card_id}", "komentarKartu2")->name("komentarKartu2");
    


    Route::get("team/{team_id}/board/{board_id}/data", "getData")->middleware("auth", "auth.session", "boardAccess")->name("boardJson");
    Route::post("team/{team_id}/board/{board_id}/card/reorder", "reorderCard")->middleware("auth", "auth.session", "boardAccess")->name("reorderCard");
    Route::post("team/{team_id}/board/{board_id}/column/reorder", "reorderCol")->middleware("auth", "auth.session", "boardAccess")->name("reorderCol");
    Route::post("team/{team_id}/board/{board_id}/column/reorder", "reorderCol2")->middleware("auth", "auth.session", "boardAccess")->name("reorderCol2");
});

Route::controller(ChecklistController::class)->group(function () {
    // ----------------------------- Admin ----------------------------- //
    Route::post("admin/tim/papan/kolom/kartu/judul/tambah", "addTitle")->name("addTitle");
    Route::post("admin/tim/papan/kolom/kartu/judul/perbaharui", "updateTitle")->name("updateTitle");
    Route::post("admin/tim/papan/kolom/kartu/judul/hapus", "hapusTitle")->name("hapusTitle");
    Route::post("admin/tim/papan/kolom/kartu/judul/checklist/hapus", "hapusChecklist")->name("hapusChecklist");
    Route::post("admin/tim/checklist/tambah", "addChecklist")->name("addChecklist");
    Route::post("admin/tim/checklist/perbaharui", "updateChecklist")->name("updateChecklist");
    Route::get('/admin/tim/checklist/perbaharui/{title_checklists_id}', 'getProgress');

    // ----------------------------- User ----------------------------- //
    Route::post("user/tim/papan/kolom/kartu/judul/tambah", "addTitle2")->name("addTitle2");
    Route::post("user/tim/papan/kolom/kartu/judul/perbaharui", "updateTitle2")->name("updateTitle2");
    Route::post("user/tim/papan/kolom/kartu/judul/hapus", "hapusTitle2")->name("hapusTitle2");
    Route::post("user/tim/papan/kolom/kartu/judul/checklist/hapus", "hapusChecklist2")->name("hapusChecklist2");
    Route::post("user/tim/checklist/tambah", "addChecklist2")->name("addChecklist2");
    Route::post("user/tim/checklist/perbaharui", "updateChecklist2")->name("updateChecklist2");
    Route::get('/user/tim/checklist/perbaharui/{title_checklists_id}', 'getProgress2');
});

// ----------------------------- Card ----------------------------- //
Route::controller(CardController::class)->group(function () {
    // ----------------------------- Admin ----------------------------- //
    Route::post("admin/tim/{team_id}/papan/{board_id}/kartu/{card_id}/perbaharui", "updateCard")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("updateCard");



    Route::get("team/{team_id}/board/{board_id}/card/{card_id}/view", "showCard")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("viewCard");
    Route::post("team/{team_id}/board/{board_id}/card/{card_id}/assign", "assignCard")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("assignCard");
    Route::post("team/{team_id}/board/{board_id}/card/{card_id}/assignself", "assignSelf")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("assignSelf");
    Route::post("team/{team_id}/board/{board_id}/card/{card_id}/leave", "leaveCard")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("leaveCard");
    Route::post("team/{team_id}/board/{board_id}/card/{card_id}/delete", "deleteCard")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("deleteCard");
    Route::post("team/{team_id}/board/{board_id}/card/{card_id}/comment", "addComment")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("commentCard");

    // ----------------------------- User ----------------------------- //
    Route::post("user/tim/{team_id}/papan/{board_id}/kartu/{card_id}/perbaharui", "updateCard2")->middleware("auth", "auth.session", "boardAccess","cardExist")->name("updateCard2");
});