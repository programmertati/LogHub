<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\BoardLogic;
use App\Logic\FileLogic;
use App\Logic\TeamLogic;
use App\Logic\UserLogic;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Notification;
use DB;

class TeamController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected FileLogic $fileLogic,
        protected UserLogic $userLogic,
    ) {
    }

    // Membuat Tim Khusus Admin //
    public function createTeam(Request $request)
    {
        $request->validate([
            "team_name"         => "required|min:5|max:20",
            "team_description"  => "required|min:5|max:90",
            "team_pattern"      => 'required',
        ]);

        $createdTeam = $this->teamLogic->createTeam(
            Auth::user()->id,
            $request->team_name,
            $request->team_description,
            $request->team_pattern,
        );

        return redirect()->route("viewTeam", ['team_id' => $createdTeam->id]);
    }
    // /Membuat Tim Khusus Admin //

    // Menampilkan Tim Admin //
    public function showTeams()
    {
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);
        $UserTeams = UserTeam::with(['user','team'])->get();

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

        return view("admin.teams", compact('UserTeams','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("teams", $teams)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("invites", $invites);
    }
    // /Menampilkan Tim Admin //

    // Menampilkan Tim User //
    public function showTeams2()
    {
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);
        $UserTeams = UserTeam::with(['user','team'])->get();

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

        return view("user.teams", compact('UserTeams','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("teams", $teams)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("invites", $invites);
    }
    // /Menampilkan Tim User //

    // Fitur Pencarian Admin //
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if ($validator->fails()) {
            return redirect()->route("showTeams");
        }

        $request->session()->flash("__old_team_name", $request->team_name);
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"], $request->team_name);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"], $request->team_name);
        $patterns = ['zig-zag', 'isometric', 'wavy', 'triangle', 'paper', 'moon', 'rect', 'triangle-2', 'polka', 'polka-2',
                    'line-bold-horizontal', 'line-bold-vertical', 'line-thin-diagonal', 'box', 'zig-zag-flat', 'circle'];

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

        return view("admin.teams", compact('patterns','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("teams", $teams)
            ->with("invites", $invites);
    }
    // /Fitur Pencarian Admin //

    // Fitur Pencarian User //
    public function search2(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if ($validator->fails()) {
            return redirect()->route("showTeams2");
        }

        $request->session()->flash("__old_team_name", $request->team_name);
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"], $request->team_name);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"], $request->team_name);
        $patterns = ['zig-zag', 'isometric', 'wavy', 'triangle', 'paper', 'moon', 'rect', 'triangle-2', 'polka', 'polka-2',
                    'line-bold-horizontal', 'line-bold-vertical', 'line-thin-diagonal', 'box', 'zig-zag-flat', 'circle'];

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

        return view("user.teams", compact('patterns','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("teams", $teams)
            ->with("invites", $invites);
    }
    // /Fitur Pencarian User //

    // Menampilkan Tim Admin //
    public function showTeam($team_id)
    {
        $team_id = intval($team_id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);
        $UserTeams = DB::table('users')->select('name', 'email')->get();
        $statusTeams = DB::table('user_team')->pluck('status');

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

        return view("admin.team", compact('statusTeams', 'UserTeams','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }
    // /Menampilkan Tim Admin //

    // Menampilkan Tim User //
    public function showTeam2($team_id)
    {
        $team_id = intval($team_id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);

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

        return view("user.team", compact('result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }
    // /Menampilkan Tim User //

    // Fitur Terima Undangan Khusus User //
    public function acceptInvite($team_id, $user_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $userInvite = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->first();

        if ($userInvite == null) {

            Toastr::error('Undangan tidak ditemukan, mungkin dibatalkan atau masa berlakunya sudah habis hubungi pemilik tim.', 'Error');
            return redirect()->back();
        }

        $userInvite->status = "Member";
        $userInvite->save();

        Toastr::success('Undangan telah diterima!', 'Success');
        return redirect()->back();
    }
    // /Fitur Terima Undangan Khusus User //

    // Fitur Menolak Undangan Khusus User //
    public function rejectInvite($team_id, $user_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $userInvite = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->first();

        if ($userInvite == null) {
            return redirect()->back();
        }

        $userInvite->delete();

        Toastr::success('Undangan telah ditolak!', 'Success');
        return redirect()->back();
    }
    // /Fitur Menolak Undangan Khusus User //

    // Fitur Pencarian Papan Admin //
    public function searchBoard(Request $request, $team_id)
    {
        $request->validate([
            "team_id"       => "required|integer",
            "user_id"       => "required|integer",
            "board_name"    => "required",
        ]);

        $team_id = intval($request->team_id);

        $request->session()->flash("__old_board_name", $request->board_name);
        $team_id = intval($request->team_id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id, $request->board_name);
        $UserTeams = DB::table('users')->select('name', 'email')->get();
        $statusTeams = DB::table('user_team')->pluck('status');

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

        return view("admin.team", compact('statusTeams', 'UserTeams','result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }
    // /Fitur Pencarian Papan Admin //

    // Fitur Pencarian Papan User //
    public function searchBoard2(Request $request, $team_id)
    {
        $request->validate([
            "team_id"       => "required|integer",
            "user_id"       => "required|integer",
            "board_name"    => "required",
        ]);

        $team_id = intval($request->team_id);

        $request->session()->flash("__old_board_name", $request->board_name);
        $team_id = intval($request->team_id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id, $request->board_name);

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

        return view("user.team", compact('result_tema','unreadNotifications', 'readNotifications', 'semua_notifikasi', 'belum_dibaca', 'dibaca'))
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }
    // /Fitur Pencarian Papan User //

    // Fitur Keluar dari Tim Khusus User //
    public function leaveTeam(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required",
        ]);

        $user_email  = Auth::user()->email;
        $team_id = intval($request->team_id);
        $this->teamLogic->deleteMembers($team_id, [$user_email]);

        Toastr::success('Keluar dari tim telah berhasil!', 'Success');
        return redirect()->route("showTeams2");
    }
    // /Fitur Keluar dari Tim Khusus User //

    // Fitur Menghapus Tim Khusus Admin //
    public function deleteTeam(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required"
        ]);

        $team_id = intval($request->team_id);
        $this->teamLogic->deleteTeam($team_id);

        Toastr::success('Tim berhasil dihapus!', 'Success');
        return redirect()->route("showTeams");
    }
    // /Fitur Menghapus Tim Khusus Admin //

    // Perbaharui Data Tim Khusus Admin //
    public function updateData(Request $request)
    {
        $request->validate([
            "team_id"           => "required|integer",
            "team_name"         => "required|min:5|max:20",
            "team_description"  => 'required|min:8|max:90',
            "team_pattern"      => 'required',
        ]);

        $team_id = intval($request->team_id);
        $selectedTeam = Team::find($team_id);

        if ($selectedTeam == null) {
            Toastr::error('Tim ini sudah dihapus, silakan hubungi pemilik tim', 'Error');
            return redirect()->route("showTeams");
        }

        $selectedTeam->name = $request->team_name;
        $selectedTeam->description = $request->team_description;
        $selectedTeam->pattern = $request->team_pattern;
        $selectedTeam->save();

        Toastr::success('Data tim berhasil diperbaharui!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Data Tim Khusus Admin //

    // Fitur Menghapus Anggota Khusus Admin //
    public function deleteMembers(Request $request)
    {
        $team_id = intval($request->team_id);
        $this->teamLogic->deleteMembers($team_id, $request->emails);
        return response()->json(["message" => "Anggota berhasil dihapus"]);
    }
    // /Fitur Menghapus Anggota Khusus Admin //

    // Fitur Mengundang Anggota Khusus Admin //
    public function inviteMembers(Request $request, $team_id)
    {
        $emails = $request->emails;
        $team_id = intval($request->team_id);

        if ($emails == null)
            return redirect()->back();


        foreach ($emails as $email) {
            $user = User::where("email", $email)->first();
            if ($user == null) continue;
            $existingInvite = UserTeam::where('user_id', $user->id)
                ->where('team_id', $team_id)
                ->first();

            if ($existingInvite != null) continue;

            UserTeam::create([
                "user_id"   => $user->id,
                "team_id"   => $team_id,
                "status"    => "Pending"
            ]);
        }

        Toastr::success('Undangan terkirim, harap menunggu konfirmasi dari anggota tersebut!', 'Success');
        return redirect()->back();
    }
    // /Fitur Mengundang Anggota Khusus Admin //

    // Perbaharui Background Tim //
    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'     => "required|mimes:jpg,jpeg,png|max:10240",
            'team_id'   => "required"
        ]);

        $registeredTeam = Team::find(intval($request->team_id));

        if ($validator->fails() || $registeredTeam == null) {
            return response()->json($validator->messages(), HttpResponse::HTTP_BAD_REQUEST);
        }

        $this->fileLogic->storeTeamImage($registeredTeam->id, $request, "image");
        return response()->json(["message" => "Berhasil"]);
    }
    // /Perbaharui Background Tim //

    // Fitur Undangan //
    public function getInvite($team_id, $user_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $owner = $this->teamLogic->getTeamOwner($team_id);
        $team = Team::find($team_id);
        $owner_initials = $this->userLogic->getInitials($owner->name);
        $team_initials = $this->userLogic->getInitials($team->name);

        return response()->json([
            "owner_name"        => $owner->name,
            "owner_initial"     => $owner_initials,
            "owner_image"       => $owner->image_path,
            "team_name"         => $team->name,
            "team_initial"      => $team_initials,
            "team_description"  => $team->description,
            "team_image"        => $team->image_path,
            "team_pattern"      => $team->pattern,
            "accept_url"        => route('acceptTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),
            "reject_url"        => route('rejectTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),
        ]);
    }
    // /Fitur Undangan //
}