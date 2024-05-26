<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use DB;

class EmployeeController extends Controller
{
    // Tampilan Pengguna Menggunakan ID User //
    public function profileEmployee(Request $request, $user_id)
    {
        $user = DB::table('users')
            ->select(
                'users.user_id',
                'users.name',
                'users.email',
                'users.username',
                'users.employee_id',
                'users.tgl_lahir',
                'users.avatar'
            )
            ->where('user_id', $user_id)->get();

        $users = DB::table('users')
            ->select(
                'users.user_id',
                'users.name',
                'users.email',
                'users.username',
                'users.employee_id',
                'users.tgl_lahir',
                'users.avatar'
            )
            ->where('user_id', $user_id)->first();

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

        return view('employees.employeeprofile', compact('user', 'users', 'result_tema',
            'unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'
        ));
    }
    // /Tampilan Pengguna Menggunakan ID User //
}