<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use DB;
use Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Tempat mengarahkan pengguna setelah login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Buat instance pengontrol baru.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'locked',
            'unlock'
        ]);
    }

    // Tampilan Masuk Aplikasi //
    public function login(Request $request)
    {
        return view('auth.login');
    }
    // /Tampilan Masuk Aplikasi //

    // Untuk Cek Authentifikasi //
    public function authenticate(Request $request)
    {
        $request->validate([
            'username_employee_id_atau_email'   => 'required|string',
            'password'                          => 'required|string'
        ]);

        if ($request->username_employee_id_atau_email === '-') {
            Toastr::error('Gagal, Username / ID Employee / Email tidak valid. Silahkan masukkan kembali Username / ID Employee / Email valid.', 'Error');
            return redirect('login');
        }
        
        try {
            $username   = $request->username_employee_id_atau_email;
            $password   = $request->password;
            $dt         = Carbon::now();
            $todayDate  = $dt->toDayDateTimeString();
        
            $authUsername   = Auth::attempt(['username'     => $username, 'password' => $password, 'status' => 'Active']);
            $authEmployee   = Auth::attempt(['employee_id'  => $username, 'password' => $password, 'status' => 'Active']);
            $authEmail      = Auth::attempt(['email'        => $username, 'password' => $password, 'status' => 'Active']);

            if ($authUsername || $authEmployee || $authEmail) {
                $user = Auth::user();
                Session::put('name', $user->name);
                Session::put('email', $user->email);
                Session::put('username', $user->username);
                Session::put('employee_id', $user->employee_id);
                Session::put('user_id', $user->user_id);
                Session::put('join_date', $user->join_date);
                Session::put('status', $user->status);
                Session::put('role_name', $user->role_name);
                Session::put('avatar', $user->avatar);
                
                $activityLog = ['name' => Session::get('name'), 'username' => $user->username, 'employee_id' => $user->employee_id, 'email' => $user->email, 'description' => 'Berhasil Masuk Aplikasi Trello', 'date_time' => $todayDate];
                DB::table('activity_logs')->insert($activityLog);

                $result_user_id = Session::get('user_id');
                $updateStatus = [
                    'status_online' => 'Online'
                ];
                DB::table('users')->where('user_id', $result_user_id)->update($updateStatus);

                Toastr::success('Anda berhasil memasuki aplikasi Trello', 'Success');
                return redirect()->intended('home');

            } elseif (User::where('employee_id', $username)->orWhere('email', $username)->exists()) {
                Toastr::error('Gagal, kata sandi anda tidak sama. Silahkan masukkan kembali kata sandi valid', 'Error');
                return redirect('login');
                
            } else {
                Toastr::error('Gagal, Username / ID Employee / Email anda tidak terdaftar pada aplikasi ini', 'Error');
                return redirect('login');
            }
        } catch (\Exception $e) {
            \Log::error($e);
            DB::rollback();
            Toastr::error('Gagal, Username / ID Employee / Email anda tidak terdaftar pada aplikasi ini', 'Error');
            return redirect()->back();
        }
    }
    // /Untuk Cek Authentifikasi //

    // Untuk Keluar Aplikasi //
    public function logout(Request $request)
    {
        $dt         = Carbon::now();
        $todayDate  = $dt->toDayDateTimeString();

        $result_user_id = Session::get('user_id');
        $updateStatus = [
            'status_online' => 'Offline'
        ];
        DB::table('users')->where('user_id', $result_user_id)->update($updateStatus);

        $activityLog = ['name' => Session::get('name'), 'username'=> Session::get('username'), 'employee_id'=> Session::get('employee_id'), 'email'=> Session::get('email'), 'description' => 'Berhasil Keluar Aplikasi Trello', 'date_time' => $todayDate];
        DB::table('activity_logs')->insert($activityLog);
        
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('username');
        $request->session()->forget('employee_id');
        $request->session()->forget('user_id');
        $request->session()->forget('join_date');
        $request->session()->forget('status');
        $request->session()->forget('role_name');
        $request->session()->forget('avatar');
        $request->session()->flush();
        
        Auth::logout();
        Toastr::success('Anda berhasil keluar aplikasi Trello','Success');
        return redirect('login');
    }
    // Untuk Keluar Aplikasi //
}