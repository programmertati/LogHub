<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Team;
use App\Models\User;
use App\Models\Board;
use App\Models\Column;
use App\Models\UserTeam;
use App\Models\ModeAplikasi;
use Illuminate\Http\Request;
use App\Models\DaftarPegawai;
use App\Models\TitleChecklists;

class CreateUserController extends Controller
{
    public function create(Request $request)
    {
        User::create([
            'name'                         => $request->name,
            'user_id'                      => $request->user_id,
            'email'                        => $request->email,
            'username'                     => $request->username,
            'employee_id'                  => $request->employee_id,
            'join_date'                    => $request->join_date,
            'status'                       => $request->status,
            'role_name'                    => $request->role_name,
            'avatar'                       => $request->avatar,
            'password'                     => $request->password,
            'tema_aplikasi'                => $request->tema_aplikasi,
            'status_online'                => $request->status_online,
        ]);

        DaftarPegawai::create([
            'name'                         => $request->name,
            'user_id'                      => $request->user_id,
            'email'                        => $request->email,
            'username'                     => $request->username,
            'employee_id'                  => $request->employee_id,
            'role_name'                    => $request->role_name,
            'avatar'                       => $request->avatar,
        ]);

        ModeAplikasi::create([
            'name'                         => $request->name,
            'user_id'                      => $request->user_id,
            'email'                        => $request->email,
            'tema_aplikasi'                => 'Terang',
        ]);

        $team = Team::where('name', 'LOG HARIAN TATI 2024')->first();
        $board = Board::where('team_id', $team->id)->where('name', 'LOG HARIAN TATI 2024')->first();
        $user = User::where('user_id', $request->user_id)->first();
        UserTeam::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'status' => $request->role_name == 'Admin' ? 'Owner' : 'Member',
        ]);



        $tgl = ["9 - 13 September 2024", "16 - 20 September 2024", "23 - 27 September 2024", "30 September - 4 Oktober 2024"];

        $hari = ['Target Mingguan', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $column = Column::create([
            'board_id' => $board->id,
            'name' => $request->name,
        ]);
        foreach ($tgl as $date) {
            $card = Card::create([
                'column_id' => $column->id,
                'name' => $date,
            ]);
            foreach ($hari as $day) {
                TitleChecklists::create([
                    'cards_id' => $card->id,
                    'name' => $day,
                ]);
            }
        }

        return response()->json([
            'status' => 200,
            'msg' => 'success'
        ]);

        // $user = User::create([]);
    }
}
