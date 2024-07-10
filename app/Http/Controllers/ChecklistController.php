<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TitleChecklists;
use App\Models\Checklists;
use Illuminate\Support\Facades\Auth;
use App\Logic\CardLogic;

class ChecklistController extends Controller
{
    public function __construct(
        protected CardLogic $cardLogic
    ) {
    }

    // Tambahkan Title Kartu Admin //
    public function addTitle(Request $request)
    {
        $titlechecklist = TitleChecklists::create([
            'cards_id' => $request->card_id,
            'name' => $request->titleChecklist
        ]);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Judul Checklist");

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titlechecklist
        ]);
    }
    // /Tambahkan Title Kartu Admin //

    // Tambahkan Title Kartu User //
    public function addTitle2(Request $request)
    {
        $titlechecklist = TitleChecklists::create([
            'cards_id' => $request->card_id,
            'name' => $request->titleChecklist
        ]);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Judul Checklist");

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titlechecklist
        ]);
    }
    // /Tambahkan Title Kartu User //

    // Perbaharui Title Kartu Admin //
    public function updateTitle(Request $request)
    {
        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Judul Checklist");

        TitleChecklists::where('id', $request->title_id)->update([
            'name' => $request->titleChecklistUpdate
        ]);

        return response()->json(['message' => 'Data berhasil disimpan!', 'card_id' => $request->card_id]);
    }
    // /Perbaharui Title Kartu Admin //

    // Perbaharui Title Kartu User //
    public function updateTitle2(Request $request)
    {
        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Judul Checklist");
        
        TitleChecklists::where('id', $request->title_id)->update([
            'name' => $request->titleChecklistUpdate
        ]);

        return response()->json(['message' => 'Data berhasil disimpan!', 'card_id' => $request->card_id]);
    }
    // /Perbaharui Title Kartu User //

    // Hapus Judul Kartu Admin //
    public function hapusTitle(Request $request)
    {
        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Judul Checklist");

        TitleChecklists::destroy($request->id);

        return response()->json(['message' => 'Data berhasil dihapus!', 'card_id' => $request->card_id]);
    }
    // /Hapus Judul Kartu Admin //

    // Hapus Judul Kartu User //
    public function hapusTitle2(Request $request)
    {
        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Judul Checklist");

        TitleChecklists::destroy($request->id);

        return response()->json(['message' => 'Data berhasil dihapus!', 'card_id' => $request->card_id]);
    }
    // /Hapus Judul Kartu User //

    // Tambahkan Checklist Admin //
    public function addChecklist(Request $request)
    {
        $data = Checklists::create([
            'title_checklists_id' => $request->title_id,
            'name' => $request->checklist
        ]);

        //Get Checklists
        $checklist = Checklists::where('title_checklists_id', $request->title_id)->where('id', $data->id)->first();
        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Checklist");

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Tambahkan Checklist Admin //

    // Tambahkan Checklist User //
    public function addChecklist2(Request $request)
    {
        $data = Checklists::create([
            'title_checklists_id' => $request->title_id,
            'name' => $request->checklist
        ]);

        //Get Checklists
        $checklist = Checklists::where('title_checklists_id', $request->title_id)->where('id', $data->id)->first();
        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Checklist");

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Tambahkan Checklist User //

    // Perbaharui Checklist Admin //
    public function updateChecklist(Request $request)
    {
        $is_active = $request->{$request->checklist_id} == 'on' ? 1 : 0;
        //Update Checklists
        Checklists::where('id', $request->checklist_id)->update([
            'name' => $request->{'checkbox-'.$request->checklist_id},
            'is_active' => $is_active,
        ]);

        //Get Checklists
        $checklist = Checklists::find($request->checklist_id);
        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Checklist");

        return response()->json([
            'message' => 'Data berhasil diperbaharui!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Perbaharui Checklist Admin //

    // Perbaharui Checklist Admin //
    public function updateChecklist2(Request $request)
    {
        $is_active = $request->{$request->checklist_id} == 'on' ? 1 : 0;
        //Update Checklists
        Checklists::where('id', $request->checklist_id)->update([
            'name' => $request->{'checkbox-'.$request->checklist_id},
            'is_active' => $is_active,
        ]);

        //Get Checklists
        $checklist = Checklists::find($request->checklist_id);
        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Checklist");

        return response()->json([
            'message' => 'Data berhasil diperbaharui!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Perbaharui Checklist Admin //

    // Hapus Checklist Kartu Admin //
    public function hapusChecklist(Request $request) 
    {
        //Destroy Checklist
        Checklists::destroy($request->id);
        //Get Progress Bar
        $titleChecklist = $this->progressBar($request->title_checklists_id);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Checklist");

        return response()->json([
            'message' => 'Data berhasil dihapus!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Hapus Checklist Kartu Admin //

    // Hapus Checklist Kartu Admin //
    public function hapusChecklist2(Request $request) 
    {
        //Destroy Checklist
        Checklists::destroy($request->id);
        //Get Progress Bar
        $titleChecklist = $this->progressBar($request->title_checklists_id);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Checklist");

        return response()->json([
            'message' => 'Data berhasil dihapus!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titleChecklist,
        ]);
    }
    // /Hapus Checklist Kartu Admin //

    public static function progressBar($title_checklists_id)
    {
        $totData = Checklists::where('title_checklists_id', $title_checklists_id)->count();
        $countActive = Checklists::where('title_checklists_id', $title_checklists_id)->where('is_active', 1)->count();
        $percentage = !empty($countActive) ? round(($countActive / $totData) * 100) : 0; 
        TitleChecklists::where('id', $title_checklists_id)->update([
            'percentage' => $percentage
        ]);
        $titleChecklist = TitleChecklists::find($title_checklists_id);

        return $titleChecklist;
    }
}