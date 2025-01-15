<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UpdateStatusPegawaiController extends Controller
{
    //
    public function updateStatusPegawai(Request $request){
        try{
            $tokenLogHub = $request->token_key;
            if ($tokenLogHub == env('TOKEN_KEY')) {
                DB::beginTransaction();

                // Ambil data user berdasarkan employee_id
                $user = User::where('pegawai_id_mantai', $request->id)->get();

                // Jika user tidak ditemukan
                if ($user->isEmpty()) {
                    throw new Exception('User tidak ditemukan di Log Hub!');
                }else{
                    $user->first()->update([
                        'status' => $request->status,
                    ]);
                }
               
                DB::commit();
                return response()->json([
                    'message' => 'Status Pegawai berhasil diupdate pada Log Hub!',
                ]);
            }else{
                return response()->json([
                    'message' => 'Token Key is invalid pada Log Hub',
                ],400);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage() . ' pada Log Hub',
            ],400);
        }   
    }
}
