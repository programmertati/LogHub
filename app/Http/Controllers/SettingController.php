<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\CompanySettings;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Notification;

class SettingController extends Controller
{
    // Tampilan Pengaturan Perusahaan //
    public function pengaturanPerusahaan()
    {
        $result_pengaturan_perusahaan = CompanySettings::where('id',1)->first();

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
        
        return view('settings.companysettings',compact('result_pengaturan_perusahaan', 'result_tema', 'unreadNotifications',
            'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'));
    }
    // /Tampilan Pengaturan Perusahaan //

    // Tambah Pengaturan Perusahaan //
    public function tambahPengaturan(Request $request)
    {
        $request->validate([
            'company_name'      =>'required',
            'contact_person'    =>'required',
            'address'           =>'required',
            'country'           =>'required',
            'city'              =>'required',
            'state_province'    =>'required',
            'postal_code'       =>'required',
            'email'             =>'required',
            'phone_number'      =>'required',
            'mobile_number'     =>'required',
            'fax'               =>'required',
            'website_url'       =>'required',
        ]);

        try {
            $saveRecord                 = CompanySettings::updateOrCreate(['id' => $request->id]);
            $saveRecord->company_name   = $request->company_name;
            $saveRecord->contact_person = $request->contact_person;
            $saveRecord->address        = $request->address;
            $saveRecord->country        = $request->country;
            $saveRecord->city           = $request->city;
            $saveRecord->state_province = $request->state_province;
            $saveRecord->postal_code    = $request->postal_code;
            $saveRecord->email          = $request->email;
            $saveRecord->phone_number   = $request->phone_number;
            $saveRecord->mobile_number  = $request->mobile_number;
            $saveRecord->fax            = $request->fax;
            $saveRecord->website_url    = $request->website_url;
            $saveRecord->save();
            
            DB::commit();
            Toastr::success('Detail perusahaan berhasil diperbaharui','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Detail perusahaan gagal diperbaharui','Error');
            return redirect()->back();
        }
    }
    // /Tambah Pengaturan Perusahaan //
}