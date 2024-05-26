<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\CardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Card;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class CardController extends Controller
{
    public function __construct(protected TeamLogic $teamLogic, protected CardLogic $cardLogic)
    {
    }

    // Tampilan Kartu //
    public function showCard(Request $request, $team_id, $board_id, $card_id)
    {
        $board_id = intval($board_id);
        $team_id = intval($team_id);

        $card = Card::find($card_id);
        $board = Board::find($board_id);
        $team = Team::find($team_id);
        $owner = $this->teamLogic->getTeamOwner($team_id);
        $workers = $this->cardLogic->getWorkers($card_id);
        $hist = $this->cardLogic->getHistories($card_id);

        return view("card")
            ->with("card", $card)
            ->with("board", $board)
            ->with("team", $team)
            ->with("workers", $workers)
            ->with("histories", $hist)
            ->with("owner", $owner);
    }
    // /Tampilan Kartu //

    public function assignCard(Request $request, $team_id, $board_id, $card_id)
    {
        return redirect()->back();
    }

    // Masuk ke dalam Kartu //
    public function assignSelf(Request $request, $team_id, $board_id, $card_id)
    {
        $user_id = Auth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->addUser($card_id, $user_id);
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Joined card.");
        
        Toastr::success('Menambahkan diri Anda ke kartu telah berhasil!', 'Success');
        return redirect()->back();
    }
    // /Masuk ke dalam Kartu //

    // Keluar dari Kartu //
    public function leaveCard(Request $request, $team_id, $board_id, $card_id)
    {
        $user_id = Auth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->removeUser($card_id, $user_id);
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Left card.");

        Toastr::success('Kamu berhasil keluar dari kartu!', 'Success');
        return redirect()->route("board", ["team_id" => $team_id, "board_id" => $board_id]);
    }
    // /Keluar dari Kartu //

    // Hapus Kartu //
    public function deleteCard(Request $request, $team_id, $board_id, $card_id)
    {
        $this->cardLogic->deleteCard(intval($card_id));

        Toastr::success('Kartu berhasil dihapus!', 'Success');
        return redirect()->route("board", ["team_id" => $team_id, "board_id" => $board_id]);
    }
    // /Hapus Kartu //

    // Perbaharui Kartu //
    public function updateCard(Request $request, $team_id, $board_id, $card_id)
    {
        $request->validate([
            "card_name" => "required|max:95"
        ]);

        $user_id = AUth::user()->id;
        $card_id = intval($card_id);
        $card = Card::find($card_id);
        $card->name = $request->card_name;
        $card->description = $request->card_description;
        $card->save();
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Updated card informations.");

        Toastr::success('Kartu berhasil diperbarui!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Kartu //

    // Menambahkan Komen //
    public function addComment(Request $request, $team_id, $board_id, $card_id)
    {
        $request->validate(["content" => "required|max:200"]);
        $user_id = AUth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->cardComment($card_id, $user_id, $request->content);

        return redirect()->back();
    }
    // /Menambahkan Komen //
}