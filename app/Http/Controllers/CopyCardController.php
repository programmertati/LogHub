<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Column;
use App\Models\TitleChecklists;
use App\Models\Checklists;
use Carbon\Carbon;

class CopyCardController extends Controller
{
    // Untuk menyalin kartu, judul ceklist dan ceklist
    public function copyCard(Request $request, $column_id, $id)
    {
        // Validasi inputan
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Salin Kartu dan Temukan kartu asli atau tiruan
        $card = Card::findOrFail($id);
        $newCard = $card->replicate();
        $newCard->name = $request->input('name');
        $newCard->position = $card->position != 0 ? $card->position + 1 : 0;
        $newCard->created_at = Carbon::now();
        $newCard->save();

        // Salin Judul Ceklist
        $titleChecklists = TitleChecklists::where('cards_id', $id)->get();
        foreach ($titleChecklists as $titleChecklist) {
            $newTitleChecklist = $titleChecklist->replicate();
            $newTitleChecklist->cards_id = $newCard->id;
            $newTitleChecklist->created_at = Carbon::now();

            // Jika checkbox tidak tercentang, set nilai percentage
            if (!$request->input('keep_checklists')) {
                $newTitleChecklist->percentage = NULL;
            }

            $newTitleChecklist->save();

            // Salin Ceklist
            $checklists = Checklists::where('title_checklists_id', $titleChecklist->id)->get();
            foreach ($checklists as $checklist) {
                $newChecklist = $checklist->replicate();
                $newChecklist->title_checklists_id = $newTitleChecklist->id;
                $newChecklist->created_at = Carbon::now();

                // Jika checkbox tidak tercentang, set nilai is_active
                if (!$request->input('keep_checklists')) {
                    $newChecklist->is_active = 0;
                }
                
                $newChecklist->save();
            }
        }

        // Mendapatkan Data Kolom
        $newColumn = Column::findOrFail($column_id);

        // Siapkan respons
        $responseData = [
            'message' => 'Berhasil menyalin kartu!',
            'new_card_id' => $newCard->id,

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
                'deleteUrl' => route('hapusKartu', ['card_id' => $newCard->id]),
                'copyCardUrl' => route('copyCard', ['column_id' => $newColumn->id, 'id' => $newCard->id])
            ],
        ];

        return response()->json($responseData, 200);
    }

    // Untuk mendapatkan total ceklist
    public function getTotalActiveChecklists($id)
    {
        $totalActiveChecklists = Checklists::whereHas('titleChecklist', function ($query) use ($id) {
            $query->where('cards_id', $id);
        })->where('is_active', 1)->count();

        return response()->json(['totalActiveChecklists' => $totalActiveChecklists]);
    }
}