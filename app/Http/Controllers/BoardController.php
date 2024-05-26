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

    // Tampilan Papan Admin //
    public function showBoard($team_id, $board_id)
    {
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $dataColumnCard = Column::with('cards')
            ->where('board_id', '=', $board_id )
            ->get();

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

        return view("admin.board", compact('dataColumnCard', 'result_tema', 'unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("patterns", BoardLogic::PATTERN);
    }
    // /Tampilan Papan Admin //

    // Tampilan Papan User //
    public function showBoard2($team_id, $board_id)
    {
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $dataColumnCard = Column::with('cards')
            ->where('board_id', '=', $board_id )
            ->get();

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

        return view("user.board", compact('dataColumnCard', 'result_tema', 'unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("patterns", BoardLogic::PATTERN);
    }
    // /Tampilan Papan User //

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

    // Hapus Papan Khusus Admin //
    public function deleteBoard($team_id, $board_id)
    {
        Board::where("id", intval($board_id))->delete();
        Toastr::success('Papan berhasil dihapus!', 'Success');
        return redirect()->route("viewTeam", ["team_id" => intval($team_id)]);
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

        // $user_id = AUth::user()->id;
        // $user_id = AUth::user()->id;
        // $card_id = intval($card_id);
        // $card = Card::find($card_id);
        // $card->save();
        // $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Kolom");

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null) {
            Toastr::error('Gagal membuat kolom, silahkan coba lagi!', 'Error');
            return redirect()->back();
        }

        return response()->json($createdColumn);
    }

    // public function addColumn(Request $request, $team_id, $board_id, $card_id)
    // {
    //     $request->validate([
    //         "board_id" => "required",
    //         "column_name" => "required",
    //     ]);
    //     $team_id = intval($team_id);
    //     $board_id = intval($request->board_id);

    //     $user_id = AUth::user()->id;
    //     $user_id = AUth::user()->id;
    //     $card_id = intval($card_id);
    //     $card = Card::find($card_id);
    //     $card->save();
    //     $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Kolom");

    //     $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

    //     if ($createdColumn == null) {
    //         Toastr::error('Gagal membuat kolom, silahkan coba lagi!', 'Error');
    //         return redirect()->back();
    //     }

    //     return response()->json($createdColumn);
    // }
    // /Membuat Kolom Admin //

    // Membuat Kolom Admin //
    public function addColumn2(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);
        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        // $user_id = AUth::user()->id;
        // $user_id = AUth::user()->id;
        // $card_id = intval($card_id);
        // $card = Card::find($card_id);
        // $card->save();
        // $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Kolom");

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null) {
            Toastr::error('Gagal membuat kolom, silahkan coba lagi!', 'Error');
            return redirect()->back();
        }

        return response()->json($createdColumn);
    }

    // public function addColumn2(Request $request, $team_id, $board_id, $card_id)
    // {
    //     $request->validate([
    //         "board_id" => "required",
    //         "column_name" => "required",
    //     ]);
    //     $team_id = intval($team_id);
    //     $board_id = intval($request->board_id);

    //     $user_id = AUth::user()->id;
    //     $user_id = AUth::user()->id;
    //     $card_id = intval($card_id);
    //     $card = Card::find($card_id);
    //     $card->save();
    //     $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Kolom");

    //     $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

    //     if ($createdColumn == null) {
    //         Toastr::error('Gagal membuat kolom, silahkan coba lagi!', 'Error');
    //         return redirect()->back();
    //     }

    //     return response()->json($createdColumn);
    // }
    // /Membuat Kolom Admin //

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
            Toastr::error('Kolom tidak ditemukan atau terhapus harap menghubungi pemiliknya', 'Error');
            return redirect()->back();
        }
        $column->name = $request->column_name;
        $column->save();
        Toastr::success('Anda berhasil memperbaharui kolom!', 'Success');
        return redirect()->back();
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
            Toastr::error('Kolom tidak ditemukan atau terhapus harap menghubungi pemiliknya', 'Error');
            return redirect()->back();
        }
        $column->name = $request->column_name;
        $column->save();
        Toastr::success('Anda berhasil memperbaharui kolom!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Kolom User //

    // Menghapus Kolom Admin //
    public function deleteCol(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol($col_id);
        Toastr::success('Anda berhasil menghapus kolom!', 'Success');
        return redirect()->back();
    }
    // /Menghapus Kolom Admin //

    // Menghapus Kolom User //
    public function deleteCol2(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol2($col_id);
        Toastr::success('Anda berhasil menghapus kolom!', 'Success');
        return redirect()->back();
    }
    // /Menghapus Kolom User //

    // Membuat Kartu Admin //
    public function addCard(Request $request, $team_id, $board_id, $column_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($column_id);
        $card_name = $request->name;

        $newCard = $this->boardLogic->addCard($column_id, $card_name);
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Membuat Kartu");

        Toastr::success('Anda berhasil membuat kartu!', 'Success');
        return redirect()->back();
        // return response()->json($newCard);
    }
    // /Membuat Kartu Admin //

    // Membuat Kartu User //
    public function addCard2(Request $request, $team_id, $board_id, $column_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($column_id);
        $card_name = $request->name;

        $newCard = $this->boardLogic->addCard2($column_id, $card_name);
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Membuat Kartu");

        Toastr::success('Anda berhasil membuat kartu!', 'Success');
        return redirect()->back();
        // return response()->json($newCard);
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

            $user_id = AUth::user()->id;
            $card_id = intval($card_id);
            $card = Card::find($card_id);

            $updateKartu = [
                'id'            => $request->id,
                'name'          => $request->name,
            ];

            $card->save();
            Card::where('id', $request->id)->update($updateKartu);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Kartu");

            DB::commit();
            Toastr::success('Anda berhasil memperbaharui kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal memperbaharui kartu!', 'Error');
            return redirect()->back();
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

            $user_id = AUth::user()->id;
            $card_id = intval($card_id);
            $card = Card::find($card_id);

            $updateKartu = [
                'id'            => $request->id,
                'name'          => $request->name,
            ];

            $card->save();
            Card::where('id', $request->id)->update($updateKartu);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Kartu");

            DB::commit();
            Toastr::success('Anda berhasil memperbaharui kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal memperbaharui kartu!', 'Error');
            return redirect()->back();
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
            Toastr::success('Anda berhasil menghapus kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal menghapus kartu!', 'Error');
            return redirect()->back();
        }
    }
    // Perbaharui Kartu Admin //

    // Perbaharui Kartu Admin //
    public function hapusKartu2(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $this->cardLogic->deleteCard2(intval($card_id));

            DB::commit();
            Toastr::success('Anda berhasil menghapus kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal menghapus kartu!', 'Error');
            return redirect()->back();
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
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Keterangan Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // Perbaharui Deskripsi Kartu Admin //

    // Perbaharui Deskripsi Kartu User //
    public function addDescription2(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'description' => $request->keterangan
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Keterangan Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // Perbaharui Deskripsi Kartu User //

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
            Toastr::success('Anda berhasil memberikan komentar pada kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal memberikan komentar pada kartu!', 'Error');
            return redirect()->back();
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
            Toastr::success('Anda berhasil memberikan komentar pada kartu!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Anda gagal memberikan komentar pada kartu!', 'Error');
            return redirect()->back();
        }
    }
    // /Menambahkan Komen User //

    // Mendapatkan Data Admin & User //
    public function getData($team_id, $board_id)
    {
        $boardData = $this->boardLogic->getData(intval($board_id));
        return response()->json($boardData);
    }
    // /Mendapatkan Data Admin & User //

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