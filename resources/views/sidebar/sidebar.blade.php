<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <div class="sidebar-left">
                    <a href="{{ route('home') }}" class="logo" style="font-size: 24px; color: black; font-weight: bold;">
                        <i class="fa-solid fa-list-check fa-2xl"></i>
                        <span class="logo-text" style="display: inline-block; font-weight: 900 !important">LogHub</span>
                    </a>
                </div>
                <li class="{{ set_active(['home']) }}">
                    <a href="{{ route('home') }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span style="font-weight: 900">Home</span>
                    </a>
                </li>

                @if (Auth::user()->role_name == 'Admin')
                    <li class="menu-title"> <span style="font-weight: 900">System Management</span> </li>
                    <li class="{{ set_active(['manajemen/pengguna']) }}">
                        <a href="{{ route('manajemen-pengguna') }}">
                            <i class="fa-solid fa-person"></i>
                            <span style="font-weight: 900">List User</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/aktivitas']) }}">
                        <a href="{{ route('riwayat-aktivitas') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span style="font-weight: 900">History Activity</span>
                        </a>
                    </li>
                    <li class="menu-title"> <span style="font-weight: 900">Task Management</span> </li>
                    <li @if (Route::is('showTeams') || Route::is('searchTeam') || Route::is('viewTeam') || Route::is('searchBoard') || Route::is('board')) class="active" @endif>
                        <a href="{{ route('showTeams') }}">
                            <i class="fa-solid fa-cube"></i>
                            <span style="font-weight: 900">Team</span>
                        </a>
                    </li>
                    @if (Route::is('showTeams'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="#" class="link-add-teams" data-toggle="modal" data-target="#createTeam">
                            <i class="icon-add-teams fa-solid fa-cubes"></i>
                            <span style="font-weight: 900">Add Teams</span>
                        </a>
                    </li>
                    @endif
                    @if (Route::is('searchTeam'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="#" class="link-add-teams" data-toggle="modal" data-target="#createTeam">
                            <i class="icon-add-teams fa-solid fa-cubes"></i>
                            <span style="font-weight: 900">Add Teams</span>
                        </a>
                    </li>
                    @endif
                    @if (Route::is('viewTeam'))
                        <li class="link-view-team btn btn-outline-danger">
                            <a href="#" data-toggle="modal" data-target="#updateTeam">
                                <i class="icon-view-team fa-solid fa-pencil"></i>
                                <span style="font-weight: 900">Edit</span>
                            </a>
                            @if ($statusTeams->contains('Member'))
                                <a href="#" data-toggle="modal" data-target="#manageMember">
                                    <i class="icon-view-team fa-solid fa-user-gear"></i>
                                    <span style="font-weight: 900">Members</span>
                                </a>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#inviteMember">
                                <i class="icon-view-team fa-solid fa-user-plus"></i>
                                <span style="font-weight: 900">Invite</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#createBoard">
                                <i class="icon-view-team fa-solid fa-table-columns"></i>
                                <span style="font-weight: 900">Add Board</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteTeam">
                                <i class="icon-view-team fa-solid fa-trash"></i>
                                <span style="font-weight: 900">Delete</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('searchBoard'))
                        <li class="link-view-team btn btn-outline-danger">
                            <a href="#" data-toggle="modal" data-target="#updateTeam">
                                <i class="icon-view-team fa-solid fa-pencil"></i>
                                <span style="font-weight: 900">Edit</span>
                            </a>
                            @if ($statusTeams->contains('Member'))
                                <a href="#" data-toggle="modal" data-target="#manageMember">
                                    <i class="icon-view-team fa-solid fa-user-gear"></i>
                                    <span style="font-weight: 900">Members</span>
                                </a>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#inviteMember">
                                <i class="icon-view-team fa-solid fa-user-plus"></i>
                                <span style="font-weight: 900">Invite</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#createBoard">
                                <i class="icon-view-team fa-solid fa-table-columns"></i>
                                <span style="font-weight: 900">Add Board</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteTeam">
                                <i class="icon-view-team fa-solid fa-trash"></i>
                                <span style="font-weight: 900">Delete</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board'))
                        <a href="{{ route('viewTeam', ['team_id' => $team->id]) }}">
                            <div class="view-board btn btn-outline-danger">
                                <span style="font-weight: 900"><h4>Board:</h4></span>
                                <span style="font-weight: 900"><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                        <li class="link-view-board btn btn-outline-danger">
                            <a href="#" data-toggle="modal" data-target="#updateBoard">
                                <i class="icon-view-board fa-solid fa-pencil"></i>
                                <span style="font-weight: 900">Edit</span>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#deleteBoard">
                                <i class="icon-view-board fa-solid fa-trash"></i>
                                <span style="font-weight: 900">Delete</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                    <li class="{{ set_active(['admin/profile']) }}">
                        <a href="{{ route('admin-profile') }}">
                            <i class="la la-user"></i>
                            <span style="font-weight: 900"> Profile</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role_name == 'User')
                    <li class="menu-title"> <span style="font-weight: 900">Task Management</span> </li>
                    <li @if (Route::is('showTeams2') || Route::is('searchTeam2') || Route::is('viewTeam2') || Route::is('searchBoard2') || Route::is('board2')) class="active" @endif>
                        <a href="{{ route('showTeams2') }}">
                            <i class="fa-solid fa-cube"></i>
                            <span style="font-weight: 900">Team</span>
                        </a>
                    </li>
                    @if (Route::is('viewTeam2'))
                        <li class="link-view-team-user btn btn-outline-danger">
                            <a href="#" data-toggle="modal" data-target="#leaveTeam">
                                <i class="icon-view-team fa-solid fa-right-from-bracket"></i>
                                <span style="font-weight: 900">Leave Team</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('searchBoard2'))
                        <li class="link-view-team-user btn btn-outline-danger">
                            <a href="#" data-toggle="modal" data-target="#leaveTeam">
                                <i class="icon-view-team fa-solid fa-right-from-bracket"></i>
                                <span style="font-weight: 900">Leave Team</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board2'))
                        <a href="{{ route('viewTeam2', ['team_id' => $team->id]) }}">
                            <div class="view-board btn btn-outline-danger">
                                <span style="font-weight: 900"><h4>Board:</h4></span>
                                <span style="font-weight: 900"><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                    @endif
                    <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                    <li class="{{ set_active(['user/profile']) }}">
                        <a href="{{ route('user-profile') }}">
                            <i class="la la-user"></i>
                            <span style="font-weight: 900"> Profile</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->