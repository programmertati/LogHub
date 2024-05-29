<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <div class="sidebar-left">
                    <a href="{{ route('home') }}" class="logo" style="font-size: 24px; color: black; font-weight: bold;">
                        <i class="fa-solid fa-list-check fa-2xl"></i>
                        <span class="logo-text" style="display: inline-block;">LogHub</span>
                    </a>
                </div>
                <li class="{{ set_active(['home']) }}">
                    <a href="{{ route('home') }}" class="{{ set_active(['home']) ? 'noti-dot' : '' }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Home</span>
                    </a>
                </li>

                @if (Auth::user()->role_name == 'Admin')
                    <li class="menu-title"> <span>System Management</span> </li>
                    <li class="{{ set_active(['manajemen/pengguna']) }}">
                        <a href="{{ route('manajemen-pengguna') }}" class="{{ set_active(['manajemen/pengguna']) ? 'noti-dot' : '' }}">
                            <i class="fa-solid fa-person"></i>
                            <span>List User</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/aktivitas']) }}">
                        <a href="{{ route('riwayat-aktivitas') }}" class="{{ set_active(['riwayat/aktivitas']) ? 'noti-dot' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span>History Activity</span>
                        </a>
                    </li>
                    <li class="menu-title"> <span>Task Management</span> </li>
                    <li class="{{ set_active(['admin/tim']) }}">
                        <a href="{{ route('showTeams') }}" class="{{ set_active(['admin/tim']) ? 'noti-dot' : '' }}">
                            <i class="fa-solid fa-cube"></i>
                            <span>Team</span>
                        </a>
                    </li>
                    @if (Route::is('showTeams'))
                    <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 0; top: 10px;">
                        <a href="#" data-toggle="modal" data-target="#createTeam">
                            <i class="fa-solid fa-cubes" style="font-size: 20px"></i>
                            <span>Add Teams</span>
                        </a>
                    </li><br><br>
                    @endif
                    @if (Route::is('searchTeam'))
                    <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 0">
                        <a href="#" data-toggle="modal" data-target="#createTeam">
                            <i class="fa-solid fa-cubes" style="font-size: 20px"></i>
                            <span>Add Teams</span>
                        </a>
                    </li>
                    @endif
                    @if (Route::is('viewTeam'))
                        <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 10px">
                            <a href="#" data-toggle="modal" data-target="#updateTeam">
                                <i class="fa-solid fa-pencil" style="font-size: 20px"></i>
                                <span>Edit</span>
                            </a>
                            @if ($statusTeams->contains('Member'))
                                <a href="#" data-toggle="modal" data-target="#manageMember">
                                    <i class="fa-solid fa-user-gear" style="font-size: 20px"></i>
                                    <span>Members</span>
                                </a>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#inviteMember">
                                <i class="fa-solid fa-user-plus" style="font-size: 20px"></i>
                                <span>Invite</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#createBoard">
                                <i class="fa-solid fa-table-columns" style="font-size: 20px"></i>
                                <span>Add Board</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteTeam">
                                <i class="fa-solid fa-trash" style="font-size: 20px"></i>
                                <span>Delete</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('searchBoard'))
                        <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 10px">
                            <a href="#" data-toggle="modal" data-target="#updateTeam">
                                <i class="fa-solid fa-pencil" style="font-size: 20px"></i>
                                <span>Edit</span>
                            </a>
                            @if ($statusTeams->contains('Member'))
                                <a href="#" data-toggle="modal" data-target="#manageMember">
                                    <i class="fa-solid fa-user-gear" style="font-size: 20px"></i>
                                    <span>Members</span>
                                </a>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#inviteMember">
                                <i class="fa-solid fa-user-plus" style="font-size: 20px"></i>
                                <span>Invite</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#createBoard">
                                <i class="fa-solid fa-table-columns" style="font-size: 20px"></i>
                                <span>Add Board</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteTeam">
                                <i class="fa-solid fa-trash" style="font-size: 20px"></i>
                                <span>Delete</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board'))
                        <a href="{{ route('viewTeam', ['team_id' => $team->id]) }}">
                            <div class="btn btn-outline-warning" style="border-radius: 15px; width: 70%; margin-left: 32px;">
                                <span><h4>Board:</h4></span>
                                <span><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                        <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 10px; top: 10px">
                            <a href="#" data-toggle="modal" data-target="#updateBoard">
                                <i class="fa-solid fa-pencil" style="font-size: 20px"></i>
                                <span>Edit</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteBoard">
                                <i class="fa-solid fa-trash" style="font-size: 20px"></i>
                                <span>Delete</span>
                            </a>
                        </li><br><br>
                    @endif
                    <li class="menu-title"> <span>Setting</span> </li>
                    <li class="{{ set_active(['admin/profile']) }}">
                        <a href="{{ route('admin-profile') }}" class="{{ set_active(['admin/profile']) ? 'noti-dot' : '' }}">
                            <i class="la la-user"></i>
                            <span> Profile</span>
                        </a>
                    </li>
                    {{-- <li class="{{ set_active(['admin/kata-sandi']) }}">
                        <a href="{{ route('admin-kata-sandi') }}" class="{{ set_active(['admin/kata-sandi']) ? 'noti-dot' : '' }}">
                            <i class="la la-key"></i>
                            <span> Change Password</span>
                        </a>
                    </li> --}}
                @endif

                @if (Auth::user()->role_name == 'User')
                    <li class="menu-title"> <span>Task Management</span> </li>
                    <li class="{{ set_active(['user/tim']) }}">
                        <a href="{{ route('showTeams2') }}" class="{{ set_active(['user/tim']) ? 'noti-dot' : '' }}">
                            <i class="fa-solid fa-cube"></i>
                            <span>Team</span>
                        </a>
                    </li>
                    @if (Route::is('viewTeam2'))
                        <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 0">
                            <a href="#" data-toggle="modal" data-target="#leaveTeam">
                                <i class="fa-solid fa-right-from-bracket" style="font-size: 20px"></i>
                                <span>Leave Team</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('searchBoard2'))
                        <li class="btn btn-outline-warning" style="left: 10%; border-radius: 15px; padding: 0">
                            <a href="#" data-toggle="modal" data-target="#leaveTeam">
                                <i class="fa-solid fa-right-from-bracket" style="font-size: 20px"></i>
                                <span>Leave Team</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board2'))
                        <a href="{{ route('viewTeam2', ['team_id' => $team->id]) }}">
                            <div class="btn btn-outline-warning" style="border-radius: 15px; width: 70%; margin-left: 32px;">
                                <span><h4>Board:</h4></span>
                                <span><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                    @endif
                    <li class="menu-title"> <span>Setting</span> </li>
                    <li class="{{ set_active(['user/profile']) }}">
                        <a href="{{ route('user-profile') }}" class="{{ set_active(['user/profile']) ? 'noti-dot' : '' }}">
                            <i class="la la-user"></i>
                            <span> Profile</span>
                        </a>
                    </li>
                    {{-- <li class="{{ set_active(['user/kata-sandi']) }}">
                        <a href="{{ route('user-kata-sandi') }}" class="{{ set_active(['user/kata-sandi']) ? 'noti-dot' : '' }}">
                            <i class="la la-key"></i>
                            <span> Change Password</span>
                        </a>
                    </li> --}}
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->