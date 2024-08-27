<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Notification;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Tampilan Notifikasi //
    public function tampilanNotifikasi(Request $request)
    {
        return view('dashboard.notification_list');
    }
    // /Tampilan Notifikasi //

    // Hapus Notifikasi Per ID //
    public function hapusNotifikasi($id)
    {
        try {
            Notification::where('id', $id)->delete();

            Toastr::success('Notifikasi Berhasil Dihapus', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Notifikasi Gagal Dihapus', 'Error');
            return redirect()->back();
        }
    }
    // /Hapus Notifikasi Per ID //

    public function hapusSemua()
    {
        Notification::where('notifiable_id', Auth::user()->id)->delete();
        session()->flash('success', 'Notifikasi Dihapus');
        return response()->json([
            'redirect' =>  route('tampilan-semua-notifikasi'),
        ]);
    }

    // Baca Notifikasi Per ID //
    public function bacaNotifikasi($id)
    {
        if ($id) {
            $notification = auth()->user()->notifications->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['message' => 'Berhasil membaca notifikasi!'], 200);
            }
        }
        return response()->json(['message' => 'Gagal membaca notifikasi'], 500);
    }
    // /Baca Notifikasi Per ID //

    // Baca Semua Notifikasi //
    public function bacasemuaNotifikasi(Request $request)
    {
        $user = auth()->user();
        $user->notifications->markAsRead();

        if ($request->ajax()) {
            return response()->json(['success' => 'Berhasil membaca semua notifikasi!']);
        }

        return response()->json(['message' => 'Gagal membaca semua notifikasi!'], 500);
    }
    // /Baca Semua Notifikasi //
}
