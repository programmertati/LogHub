<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\CompanySettings;
use App\Notifications\UlangTahunNotification;
use App\Models\Notification;
use App\Models\ModeAplikasi;
use App\Models\User;
use DB;

class HomeController extends Controller
{
    /**
     * Buat instance pengontrol baru.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan dasbor aplikasi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Halaman Utama //
    public function index(Request $request)
    {
        // Mendapatkan peran pengguna saat ini //
        $user = auth()->user();

        // Memeriksa peran pengguna dan mengarahkannya ke halaman yang sesuai //
        if ($user->role_name === 'Admin')
        {
            $dataPengguna = User::where('role_name', 'User')->count();
            $dataOnline = User::where('status_online', 'Online')->count();
            $dataOffline = User::where('status_online', 'Offline')->count();

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
           
            return view('dashboard.Halaman-admin', compact('dataPengguna', 'dataOnline', 'dataOffline',
                'result_tema', 'unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'));
        }

        elseif ($user->role_name === 'User')
        {
            $tampilanPerusahaan = CompanySettings::where('id',1)->first();

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
            
            return view('dashboard.Halaman-user', compact('tampilanPerusahaan', 'result_tema', 'unreadNotifications',
                'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'));
        }
    }

    // Baca Notifikasi Per ID //
    public function bacaNotifikasi($id)
    {
        if($id)
        {
            auth()->user()->notifications->where('id',$id)->markAsRead();
            Toastr::success('Notifikasi Telah Dibaca','Success');
        }
        return back();
    }
    // /Baca Notifikasi Per ID //

    // Baca Semua Notifikasi //
    public function bacasemuaNotifikasi()
    {
        $user = auth()->user();
        $user->notifications->markAsRead();
        Toastr::success('Semua Notifikasi Telah Dibaca','Success');
        return redirect()->back();
    }
    // /Baca Semua Notifikasi //

    // Manual Function Notifikasi Ultah //
    public function ulangtahun()
    {
        if (auth()->user())
        {
            $user = User::first();
            $notification = auth()->user()->notifications->where('data.user_id', $user->id)->first();
        
                if (!$notification) {
                    $notification = new UlangTahunNotification($user);
                    $notification->data['user_id'] = $user->id;
                    auth()->user()->notify($notification);
                }
        }
        return back();
    }
    // /Manual Function Notifikasi Ultah //

    // Mode Tema Aplikasi //
    public function updateTemaAplikasi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
        $user = ModeAplikasi::findOrFail($id);

        $tema_aplikasi = $request->input('tema_aplikasi');
        if ($tema_aplikasi == 'Terang') {
            $user->tema_aplikasi = 'Terang';
            $user->warna_sistem = null;
            $user->warna_sistem_tulisan = null;
            $user->warna_mode = null;
            $user->tabel_warna = null;
            $user->tabel_tulisan_tersembunyi = null;
            $user->warna_dropdown_menu = null;
            $user->ikon_plugin = null;
            $user->bayangan_kotak_header = null;
            $user->warna_mode_2 = null;

        } elseif ($tema_aplikasi == 'Gelap') {
            $user->tema_aplikasi = 'Gelap';
            $user->warna_sistem = '#171527';
            $user->warna_sistem_tulisan = 'white';
            $user->warna_mode = '#292D3E';
            $user->tabel_warna = 'rgba(0,0,0,.05)';
            $user->tabel_tulisan_tersembunyi = '#a3a3a3';
            $user->warna_dropdown_menu = 'rgb(31 28 54 / 1)';
            $user->ikon_plugin = 'rgba(244, 59, 72, 0.6)';
            $user->bayangan_kotak_header = '0 0.4px 5px rgb(255 255 255)';
            $user->warna_mode_2 = '#2b2e3c';
        }
        $user->save();

        $user2 = User::findOrFail($id);

        $tema_aplikasi = $request->input('tema_aplikasi');
        if ($tema_aplikasi == 'Terang') {
            $user2->tema_aplikasi = 'Terang';

        } elseif ($tema_aplikasi == 'Gelap') {
            $user2->tema_aplikasi = 'Gelap';
        }
        $user2->save();

        DB::commit();
        Toastr::success('Tema aplikasi berhasil diperbarui', 'Success');
        return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Tema aplikasi gagal diperbarui', 'Error');
            return redirect()->back();
        }
    }
    // /Mode Tema Aplikasi //
}