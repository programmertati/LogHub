<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\BoardLogic;
use App\Logic\TeamLogic;
use App\Logic\CardLogic;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Models\TitleChecklists;
use App\Models\Checklists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Notification;
use DB;
use Carbon\Carbon;

class BoardController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected BoardLogic $boardLogic,
        protected CardLogic $cardLogic
    ) {
    }

    // Membuat Papan Khusus Admin //
    public function createBoard(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required",
            "board_name" => "required",
            "board_pattern" => "required"
        ]);
        $team_id = intval($request->team_id);

        $createdBoard = $this->boardLogic->createBoard(
            $team_id,
            $request->board_name,
            $request->board_pattern,
        );

        if ($createdBoard == null)
            Toastr::error('Gagal membuat papan, silahkan coba lagi!', 'Error');
            return redirect()->back();

        Toastr::success('Papan berhasil dibuat!', 'Success');
        return redirect()->back();
    }
    // /Membuat Papan Khusus Admin //

    // Membuat Papan Khusus User //
    public function createBoard2(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required",
            "board_name" => "required",
            "board_pattern" => "required"
        ]);
        $team_id = intval($request->team_id);

        $createdBoard = $this->boardLogic->createBoard2(
            $team_id,
            $request->board_name,
            $request->board_pattern,
        );

        if ($createdBoard == null)
            Toastr::error('Gagal membuat papan, silahkan coba lagi!', 'Error');
            return redirect()->back();

        Toastr::success('Papan berhasil dibuat!', 'Success');
        return redirect()->back();
    }
    // /Membuat Papan Khusus User //

    // Tampilan Papan Admin //
    public function showBoard($team_id, $board_id)
    {
        $userID = Auth::id();
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $dataColumnCard = Column::with('cards')
            ->where('board_id', '=', $board_id )
            ->get();
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        $actionTeams = DB::table('user_team')->where('team_id', '=', $team_id)->where('status', '=', 'Owner')->where('user_id', '=', $userID)->get();

        $result_tema = DB::table('mode_aplikasi')
            ->select(
                'mode_aplikasi.id',
                'mode_aplikasi.tema_aplikasi',
                'mode_aplikasi.warna_sistem',
                'mode_aplikasi.warna_sistem_tulisan',
                'mode_aplikasi.warna_mode',
                'mode_aplikasi.tabel_warna',
                'mode_aplikasi.tabel_tulisan_tersembunyi',
                'mode_aplikasi.warna_dropdown_menu',
                'mode_aplikasi.ikon_plugin',
                'mode_aplikasi.bayangan_kotak_header',
                'mode_aplikasi.warna_mode_2',
                )
            ->where('user_id', auth()->user()->user_id)
            ->get();

        $user = auth()->user();
        $role = $user->role_name;
        $unreadNotifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->get();

        $readNotifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNotNull('read_at')
            ->get();

        $semua_notifikasi = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->get();

        $belum_dibaca = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->whereNull('read_at')
            ->get();

        $dibaca = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->whereNotNull('read_at')
            ->get();

        return view("admin.board")
            ->with("UserTeams", $UserTeams)
            ->with("actionTeams", $actionTeams)
            ->with("dataColumnCard", $dataColumnCard)
            ->with("result_tema", $result_tema)
            ->with("unreadNotifications", $unreadNotifications)
            ->with("readNotifications", $readNotifications)
            ->with("semua_notifikasi", $semua_notifikasi)
            ->with("belum_dibaca", $belum_dibaca)
            ->with("dibaca", $dibaca)
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Tampilan Papan Admin //

    // Ambil Data Modal Kartu Admin //
    public function getDataKartu(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $isianKartu = Card::find($request->card_id);
        $dataKolom = Column::find($isianKartu->column_id);
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        $result_tema = DB::table('mode_aplikasi')
            ->select(
                'mode_aplikasi.id',
                'mode_aplikasi.tema_aplikasi',
                'mode_aplikasi.warna_sistem',
                'mode_aplikasi.warna_sistem_tulisan',
                'mode_aplikasi.warna_mode',
                'mode_aplikasi.tabel_warna',
                'mode_aplikasi.tabel_tulisan_tersembunyi',
                'mode_aplikasi.warna_dropdown_menu',
                'mode_aplikasi.ikon_plugin',
                'mode_aplikasi.bayangan_kotak_header',
                'mode_aplikasi.warna_mode_2',
                )
            ->where('user_id', auth()->user()->user_id)
            ->get();

        return view('admin.isi-kartu')
            ->with("isianKartu", $isianKartu)
            ->with("dataKolom", $dataKolom)
            ->with("result_tema", $result_tema)
            ->with("UserTeams", $UserTeams)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Ambil Data Modal Kartu Admin //

    // Tampilan Papan User //
    public function showBoard2($team_id, $board_id)
    {
        $userID = Auth::id();
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData2($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $dataColumnCard = Column::with('cards')
            ->where('board_id', '=', $board_id )
            ->get();
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        $actionTeams = DB::table('user_team')->where('team_id', '=', $team_id)->where('status', '=', 'Owner')->where('user_id', '=', $userID)->get();

        $result_tema = DB::table('mode_aplikasi')
            ->select(
                'mode_aplikasi.id',
                'mode_aplikasi.tema_aplikasi',
                'mode_aplikasi.warna_sistem',
                'mode_aplikasi.warna_sistem_tulisan',
                'mode_aplikasi.warna_mode',
                'mode_aplikasi.tabel_warna',
                'mode_aplikasi.tabel_tulisan_tersembunyi',
                'mode_aplikasi.warna_dropdown_menu',
                'mode_aplikasi.ikon_plugin',
                'mode_aplikasi.bayangan_kotak_header',
                'mode_aplikasi.warna_mode_2',
                )
            ->where('user_id', auth()->user()->user_id)
            ->get();

        $user = auth()->user();
        $role = $user->role_name;
        $unreadNotifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->get();

        $readNotifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNotNull('read_at')
            ->get();

        $semua_notifikasi = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->get();

        $belum_dibaca = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->whereNull('read_at')
            ->get();

        $dibaca = DB::table('notifications')
            ->leftjoin('users', 'notifications.notifiable_id', 'users.id')
            ->select(
                'notifications.*',
                'notifications.id',
                'users.name',
                'users.avatar'
            )
            ->whereNotNull('read_at')
            ->get();

        return view("user.board")
            ->with("UserTeams", $UserTeams)
            ->with("actionTeams", $actionTeams)
            ->with("dataColumnCard", $dataColumnCard)
            ->with("result_tema", $result_tema)
            ->with("unreadNotifications", $unreadNotifications)
            ->with("readNotifications", $readNotifications)
            ->with("semua_notifikasi", $semua_notifikasi)
            ->with("belum_dibaca", $belum_dibaca)
            ->with("dibaca", $dibaca)
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Tampilan Papan User //

    // Ambil Data Modal Kartu User //
    public function getDataKartu2(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $isianKartu = Card::find($request->card_id);
        $dataKolom = Column::find($isianKartu->column_id);
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        $result_tema = DB::table('mode_aplikasi')
            ->select(
                'mode_aplikasi.id',
                'mode_aplikasi.tema_aplikasi',
                'mode_aplikasi.warna_sistem',
                'mode_aplikasi.warna_sistem_tulisan',
                'mode_aplikasi.warna_mode',
                'mode_aplikasi.tabel_warna',
                'mode_aplikasi.tabel_tulisan_tersembunyi',
                'mode_aplikasi.warna_dropdown_menu',
                'mode_aplikasi.ikon_plugin',
                'mode_aplikasi.bayangan_kotak_header',
                'mode_aplikasi.warna_mode_2',
                )
            ->where('user_id', auth()->user()->user_id)
            ->get();

        return view('user.isi-kartu')
            ->with("isianKartu", $isianKartu)
            ->with("dataKolom", $dataKolom)
            ->with("result_tema", $result_tema)
            ->with("UserTeams", $UserTeams)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Ambil Data Modal Kartu User //

    // Perbaharui Papan Khusus Admin //
    public function updateBoard(Request $request)
    {
        $request->validate([
            "board_id"      => "required",
            "board_name"    => "required",
            "board_pattern" => "required",
        ]);

        $board = Board::find(intval($request->board_id));
        $board->name = $request->board_name;
        $board->pattern = $request->board_pattern;
        $board->save();

        Toastr::success('Papan berhasil diperbaharui!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Papan Khusus Admin //

    // Perbaharui Papan Khusus User //
    public function updateBoard2(Request $request)
    {
        $request->validate([
            "board_id"      => "required",
            "board_name"    => "required",
            "board_pattern" => "required",
        ]);

        $board = Board::find(intval($request->board_id));
        $board->name = $request->board_name;
        $board->pattern = $request->board_pattern;
        $board->save();

        Toastr::success('Papan berhasil diperbaharui!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Papan Khusus User //

    // Hapus Papan Khusus Admin //
    public function deleteBoard($team_id, $board_id)
    {
        Board::where("id", intval($board_id))->delete();
        Toastr::success('Papan berhasil dihapus!', 'Success');
        return redirect()->route("viewTeam", ["team_id" => intval($team_id)]);
    }
    // /Hapus Papan Khusus Admin //

    // Hapus Papan Khusus Admin //
    public function deleteBoard2($team_id, $board_id)
    {
        Board::where("id", intval($board_id))->delete();
        Toastr::success('Papan berhasil dihapus!', 'Success');
        return redirect()->route("viewTeam2", ["team_id" => intval($team_id)]);
    }
    // /Hapus Papan Khusus Admin //

    // Membuat Kolom Admin //
    public function addColumn(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);

        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null) {
            return response()->json(['error' => 'Gagal membuat kolom, silahkan coba lagi!'], 500);
        }

        return response()->json([
            'id' => $createdColumn->id,
            'name' => $createdColumn->name,
            'updateUrl' => route('updateCol', ['board_id' => $board_id, 'team_id' => $team_id]),
            'deleteUrl' => route('deleteCol', ['board_id' => $board_id, 'team_id' => $team_id]),
            'addCardUrl' => route('addCard', ['board_id' => $board_id, 'team_id' => $team_id, 'column_id' => $createdColumn->id]),
            'board_id' => $board_id,
            'team_id' => $team_id
        ]);
    }
    // /Membuat Kolom Admin //

    // Membuat Kolom User //
    public function addColumn2(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);

        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        $createdColumn = $this->boardLogic->addColumn2($board_id, $request->column_name);

        if ($createdColumn == null) {
            return response()->json(['error' => 'Gagal membuat kolom, silahkan coba lagi!'], 500);
        }

        return response()->json([
            'id' => $createdColumn->id,
            'name' => $createdColumn->name,
            'updateUrl' => route('updateCol', ['board_id' => $board_id, 'team_id' => $team_id]),
            'deleteUrl' => route('deleteCol', ['board_id' => $board_id, 'team_id' => $team_id]),
            'addCardUrl' => route('addCard', ['board_id' => $board_id, 'team_id' => $team_id, 'column_id' => $createdColumn->id]),
            'board_id' => $board_id,
            'team_id' => $team_id
        ]);
    }
    // /Membuat Kolom User //

    // Perbaharui Kolom Admin //
    public function updateCol(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "column_name" => "required|max:200",
            "column_id" => "required",
        ]);

        $col_id = intval($request->column_id);
        $column = Column::find($col_id);
        if (!$column) {
            return response()->json(['error' => 'Kolom tidak ditemukan atau terhapus harap menghubungi pemiliknya'], 404);
        }
        $column->name = $request->column_name;
        $column->save();
        return response()->json(['name' => $column->name], 200);
    }
    // /Perbaharui Kolom Admin //

    // Perbaharui Kolom User //
    public function updateCol2(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "column_name" => "required|max:200",
            "column_id" => "required",
        ]);

        $col_id = intval($request->column_id);
        $column = Column::find($col_id);
        if (!$column) {
            return response()->json(['error' => 'Kolom tidak ditemukan atau terhapus harap menghubungi pemiliknya'], 404);
        }
        $column->name = $request->column_name;
        $column->save();
        return response()->json(['name' => $column->name], 200);
    }
    // /Perbaharui Kolom User //

    // Menghapus Kolom Admin //
    public function deleteCol(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol($col_id);

        return response()->json(['message' => 'Berhasil menghapus kolom!']);
    }
    // /Menghapus Kolom Admin //

    // Menghapus Kolom User //
    public function deleteCol2(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol($col_id);

        return response()->json(['message' => 'Berhasil menghapus kolom!']);
    }
    // /Menghapus Kolom User //

    // Membuat Kartu Admin //
    public function addCard(Request $request, $team_id, $board_id, $column_id)
    {
        // Mendapatkan data board->id dan column->id
        $board_id = intval($board_id);
        $column_id = intval($column_id);

        // Dapatkan nama kartu dari permintaan
        $card_name = $request->name;

        // Tambahkan kartu baru menggunakan boardLogic
        $newCard = $this->boardLogic->addCard($column_id, $card_name);

        // Membuat histori pembuatan kartu
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Membuat Kartu");

        // Temukan column->id tempat kartu ditambahkan
        $newColumn = Column::where('id', $column_id)->first();

        // Periksa apakah newCard dan newColumn berhasil dibuat dan diambil
        if ($newCard && $newColumn) {
            return response()->json([
                'success' => true,

                'column' => [
                    'id' => $newColumn->id,
                    'name' => $newColumn->name,
                ],

                'card' => [
                    'id' => $newCard->id,
                    'name' => $newCard->name,
                    'pattern' => $newCard->pattern,
                    'description' => $newCard->description,
                    'updateUrl' => route('perbaharuiKartu', ['card_id' => $newCard->id]),
                    'deleteUrl' => route('hapusKartu', ['card_id' => $newCard->id])
                ]
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    // /Membuat Kartu Admin //

    // Membuat Kartu User //
    public function addCard2(Request $request, $team_id, $board_id, $column_id)
    {
        // Mendapatkan data board->id dan column->id
        $board_id = intval($board_id);
        $column_id = intval($column_id);

        // Dapatkan nama kartu dari permintaan
        $card_name = $request->name;

        // Tambahkan kartu baru menggunakan boardLogic
        $newCard = $this->boardLogic->addCard2($column_id, $card_name);

        // Membuat histori pembuatan kartu
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Membuat Kartu");

        // Temukan column->id tempat kartu ditambahkan
        $newColumn = Column::where('id', $column_id)->first();

        // Periksa apakah newCard dan newColumn berhasil dibuat dan diambil
        if ($newCard && $newColumn) {
            return response()->json([
                'success' => true,

                'column' => [
                    'id' => $newColumn->id,
                    'name' => $newColumn->name,
                ],

                'card' => [
                    'id' => $newCard->id,
                    'name' => $newCard->name,
                    'pattern' => $newCard->pattern,
                    'description' => $newCard->description,
                    'updateUrl' => route('perbaharuiKartu', ['card_id' => $newCard->id]),
                    'deleteUrl' => route('hapusKartu', ['card_id' => $newCard->id])
                ]
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    // /Membuat Kartu User //

    // Perbaharui Kartu Admin //
    public function perbaharuiKartu(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                "name" => "required|max:200"
            ]);

            $user_id = Auth::user()->id;
            $card_id = intval($card_id);
            $card = Card::find($card_id);

            $updateKartu = [
                'id'            => $request->id,
                'name'          => $request->name,
            ];

            $card->update($updateKartu);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Kartu");

            DB::commit();
            return response()->json(['name' => $request->name], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbaharui kartu!'], 500);
        }
    }
    // Perbaharui Kartu Admin //

    // Perbaharui Kartu User //
    public function perbaharuiKartu2(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                "name" => "required|max:200"
            ]);

            $user_id = Auth::user()->id;
            $card_id = intval($card_id);
            $card = Card::find($card_id);

            $updateKartu = [
                'id'            => $request->id,
                'name'          => $request->name,
            ];

            $card->update($updateKartu);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Kartu");

            DB::commit();
            return response()->json(['name' => $request->name], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbaharui kartu!'], 500);
        }
    }
    // Perbaharui Kartu User //

    // Perbaharui Kartu Admin //
    public function hapusKartu(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $this->cardLogic->deleteCard(intval($card_id));

            DB::commit();
            return response()->json(['message' => 'Berhasil menghapus kartu!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menghapus kartu!']);
        }
    }
    // Perbaharui Kartu Admin //

    // Perbaharui Kartu Admin //
    public function hapusKartu2(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $this->cardLogic->deleteCard(intval($card_id));

            DB::commit();
            return response()->json(['message' => 'Berhasil menghapus kartu!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menghapus kartu!']);
        }
    }
    // Perbaharui Kartu Admin //

    // Perbaharui Deskripsi Kartu Admin //
    public function addDescription(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'description' => $request->keterangan
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $status_keterangan = empty($request->keterangan) ? 'hide' : 'show';
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Keterangan Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!', 'status_keterangan' => $status_keterangan]);
    }
    // Perbaharui Deskripsi Kartu Admin //

    // Perbaharui Cover Kartu Admin //
    public function perbaharuiCover(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => $request->pattern
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Perbaharui Cover Kartu Admin //

    // Hapus Cover Kartu Admin //
    public function hapusCover(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => NULL
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Hapus Cover Kartu Admin //

    // Perbaharui Deskripsi Kartu User //
    public function addDescription2(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'description' => $request->keterangan
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $status_keterangan = empty($request->keterangan) ? 'hide' : 'show';
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Keterangan Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!', 'status_keterangan' => $status_keterangan]);
    }
    // Perbaharui Deskripsi Kartu User //

    // Perbaharui Cover Kartu User //
    public function perbaharuiCover2(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => $request->pattern
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Perbaharui Cover Kartu User //

    // Hapus Cover Kartu User //
    public function hapusCover2(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => NULL
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Hapus Cover Kartu User //

    // Menambahkan Komen Admin //
    public function komentarKartu(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                ["content" => "required|max:255"
            ]);

            $user_id = $request->user_id;
            $card_id = $request->card_id;

            $createComment = [
                "user_id"   => $user_id,
                "card_id"   => $card_id,
                "type"      => "comment",
                "content"   => $request->content,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            DB::table('card_histories')->insert($createComment);

            DB::commit();
            $response = [
                'name' => Auth::user()->name,
                'content' => $request->content,
                'created_at' => Carbon::now()->translatedFormat('j F \p\u\k\u\l h:i A')
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memberikan komentar!'], 500);
        }
    }
    // /Menambahkan Komen Admin //

    // Menambahkan Komen User //
    public function komentarKartu2(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                ["content" => "required|max:255"
            ]);

            $user_id = $request->user_id;
            $card_id = $request->card_id;

            $createComment = [
                "user_id"   => $user_id,
                "card_id"   => $card_id,
                "type"      => "comment",
                "content"   => $request->content,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            DB::table('card_histories')->insert($createComment);

            DB::commit();
            $response = [
                'name' => Auth::user()->name,
                'content' => $request->content,
                'created_at' => Carbon::now()->translatedFormat('j F \p\u\k\u\l h:i A')
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memberikan komentar!'], 500);
        }
    }
    // /Menambahkan Komen User //

    // Untuk Pindah Posisi Kolom //
    public function perbaharuiPosisiKolom(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            Column::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Kolom //

    // Untuk Pindah Posisi Kartu //
    public function perbaharuiPosisiKartu(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            Card::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Kartu //

    // Untuk Pindah Posisi Judul //
    public function perbaharuiPosisiJudul(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            TitleChecklists::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Judul //

    // Untuk Pindah Posisi Checklist //
    public function perbaharuiPosisiCeklist(Request $request)
    {
        $positions = $request->input('positions');

        dd($positions);

        foreach ($positions as $id => $position) {
            Checklists::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Checklist //

    // Memindahkan Kartu Admin & User //
    public function reorderCard(Request $request, $team_id, $board_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($request->column_id);
        $middle_id = intval($request->middle_id);
        $bottom_id = intval($request->bottom_id);
        $top_id = intval($request->top_id);

        $updatedCard = $this->boardLogic->moveCard($middle_id, $column_id, $bottom_id, $top_id);

        return response()->json($updatedCard);
    }
    // /Memindahkan Kartu Admin & User //

    // Memindahkan Kolom Admin //
    public function reorderCol(Request $request, $team_id, $board_id)
    {
        $user_id = Auth::user()->id;
        $board_id = intval($board_id);
        $middle_id = intval($request->middle_id);
        $right_id = intval($request->right_id);
        $left_id = intval($request->left_id);

        if (!$this->boardLogic->hasAccess($user_id, $board_id)) {
            return response()->json(["url" => route("showTeams")], HttpResponse::HTTP_BAD_REQUEST);
        }

        $updatedCol = $this->boardLogic->moveCol($middle_id, $right_id, $left_id);
        return response()->json($updatedCol);
    }
    // /Memindahkan Kolom Admin //

    // Memindahkan Kolom User //
    public function reorderCol2(Request $request, $team_id, $board_id)
    {
        $user_id = Auth::user()->id;
        $board_id = intval($board_id);
        $middle_id = intval($request->middle_id);
        $right_id = intval($request->right_id);
        $left_id = intval($request->left_id);

        if (!$this->boardLogic->hasAccess($user_id, $board_id)) {
            return response()->json(["url" => route("showTeams2")], HttpResponse::HTTP_BAD_REQUEST);
        }

        $updatedCol = $this->boardLogic->moveCol($middle_id, $right_id, $left_id);
        return response()->json($updatedCol);
    }
    // /Memindahkan Kolom User //
}