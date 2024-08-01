<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <div class="sidebar-left">
                    <a href="{{ route('home') }}" class="logo" style="font-size: 24px; color: black; font-weight: bold;">
                        {{-- <i class="fa-solid fa-list-check fa-2xl"></i> --}}
                        {{-- <span class="logo-text" style="display: inline-block; font-weight: 900 !important">LogsHub</span> --}}
                        <img src="{{ URL::to('/assets/images/logo.png') }}" style="width: 70%;display: inline-block; font-weight: 900 !important" loading="lazy" class="logo-text">
                    </a>
                </div>
                <li class="{{ set_active(['home']) }} active-home">
                    <a href="{{ route('home') }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span style="font-weight: 900">Home</span>
                    </a>
                </li>

                @if (Auth::user()->role_name == 'Admin')
                    <li class="menu-title"> <span style="font-weight: 900">System Management</span> </li>
                    <li class="{{ set_active(['manajemen/pengguna']) }} active-manajemen-pengguna">
                        <a href="{{ route('manajemen-pengguna') }}">
                            <i class="fa-solid fa-person"></i>
                            <span style="font-weight: 900">List User</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/aktivitas']) }} active-riwayat-aktivitas">
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
                    @if (Route::is('showTeams') || Route::is('searchTeam'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="#" class="link-add-teams" data-toggle="modal" data-target="#createTeam">
                            <i class="icon-add-teams fa-solid fa-cubes"></i>
                            <span style="font-weight: 900">Add Teams</span>
                        </a>
                    </li>
                    @endif
                    @if (Route::is('viewTeam') || Route::is('searchBoard'))
                        @foreach ($actionTeams as $iconAction)
                            @if ($iconAction->status == 'Owner')
                                <div class="dropdown dropdown-action opsi-sidebar">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="margin-top: 15px !important; margin-left: 30px !important">
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateTeam">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            <span style="font-weight: 900">Edit</span>
                                        </a>
                                        @if ($statusTeams->contains('Member'))
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#manageMember">
                                                <i class="fa-solid fa-user-gear m-r-5"></i>
                                                <span style="font-weight: 900">Members</span>
                                            </a>
                                        @endif
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#inviteMember">
                                            <i class="fa-solid fa-user-plus m-r-5"></i>
                                            <span style="font-weight: 900">Invite</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#createBoard">
                                            <i class="fa-solid fa-table-columns m-r-5"></i>
                                            <span style="font-weight: 900">Add Board</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteTeam">
                                            <i class="fa fa-trash-o m-r-5"></i>
                                            <span style="font-weight: 900">Delete</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @foreach ($leaveTeams as $iconLeave)
                            @if ($iconLeave->status == 'Member')
                                <li class="add-teams btn btn-outline-danger">
                                    <a href="#" data-toggle="modal" data-target="#leaveTeam" class="link-add-teams">
                                        <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                        <span style="font-weight: 900">Leave Team</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        <li class="add-teams btn btn-outline-danger">
                            <a href="{{ route('showTeams') }}" class="link-add-teams">
                                <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                <span style="font-weight: 900">Back to Team's</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board'))
                        @foreach ($actionTeams as $iconAction)
                            @if ($iconAction->status == 'Owner')
                                <div class="dropdown dropdown-action opsi-sidebar">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="margin-top: 15px !important; margin-left: 30px !important">
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateBoard">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            <span style="font-weight: 900">Edit</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteBoard">
                                            <i class="fa fa-trash-o m-r-5"></i>
                                            <span style="font-weight: 900">Delete</span>
                                        </a>
                                        {{-- @php
                                            $dataRecoverKolom = App\Models\Column::onlyTrashed()->where('board_id', $board->id)->get();
                                            $softDeletedColumns = $dataRecoverKolom->count();
                                            $displayStyle = $softDeletedColumns > 0 ? 'display: block;' : 'display: none;';
                                        @endphp
                                        <a href="#" class="dropdown-item recover-kolom-link" id="recover-kolom-link-{{ $board->id }}" data-toggle="modal" data-target="#pulihkanKolomModal" data-board-id="{{ $board->id }}" style="{{ $displayStyle }}">
                                            <i class="fa-solid fa-recycle fa-lg m-r-5"></i>
                                            <span style="font-weight: 900">Recover Column</span>
                                        </a> --}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <a href="#">
                            <div class="view-board btn btn-outline-danger">
                                <span style="font-weight: 900"><h4>Board:</h4></span>
                                <span style="font-weight: 900"><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                        <li class="add-teams btn btn-outline-danger">
                            <a href="{{ route('viewTeam', ['team_id' => $team->id]) }}" class="link-add-teams">
                                <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                <span style="font-weight: 900">Back to Board's</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                    <li class="{{ set_active(['admin/profile']) }} active-profile">
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
                    @if (Route::is('showTeams2') || Route::is('searchTeam2'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="#" class="link-add-teams" data-toggle="modal" data-target="#createTeam">
                            <i class="icon-add-teams fa-solid fa-cubes"></i>
                            <span style="font-weight: 900">Add Teams</span>
                        </a>
                    </li>
                    @endif
                    @if (Route::is('viewTeam2') || Route::is('searchBoard2'))
                        @foreach ($actionTeams as $iconAction)
                            @if ($iconAction->status == 'Owner')
                                <div class="dropdown dropdown-action opsi-sidebar">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="margin-top: 15px !important; margin-left: 30px !important">
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateTeam">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            <span style="font-weight: 900">Edit</span>
                                        </a>
                                        @if ($statusTeams->contains('Member'))
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#manageMember">
                                                <i class="fa-solid fa-user-gear m-r-5"></i>
                                                <span style="font-weight: 900">Members</span>
                                            </a>
                                        @endif
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#inviteMember">
                                            <i class="fa-solid fa-user-plus m-r-5"></i>
                                            <span style="font-weight: 900">Invite</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#createBoard">
                                            <i class="fa-solid fa-table-columns m-r-5"></i>
                                            <span style="font-weight: 900">Add Board</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteTeam">
                                            <i class="fa fa-trash-o m-r-5"></i>
                                            <span style="font-weight: 900">Delete</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @foreach ($leaveTeams as $iconLeave)
                            @if ($iconLeave->status == 'Member')
                                <li class="add-teams btn btn-outline-danger">
                                    <a href="#" data-toggle="modal" data-target="#leaveTeam" class="link-add-teams">
                                        <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                        <span style="font-weight: 900">Leave Team</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        <li class="add-teams btn btn-outline-danger">
                            <a href="{{ route('showTeams2') }}" class="link-add-teams">
                                <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                <span style="font-weight: 900">Back to Team's</span>
                            </a>
                        </li>
                    @endif
                    @if (Route::is('board2'))
                        @foreach ($actionTeams as $iconAction)
                            @if ($iconAction->status == 'Owner')
                                <div class="dropdown dropdown-action opsi-sidebar">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="margin-top: 15px !important; margin-left: 30px !important">
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateBoard">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            <span style="font-weight: 900">Edit</span>
                                        </a>
                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteBoard">
                                            <i class="fa fa-trash-o m-r-5"></i>
                                            <span style="font-weight: 900">Delete</span>
                                        </a>
                                        {{-- @php
                                            $dataRecoverKolom = App\Models\Column::onlyTrashed()->where('board_id', $board->id)->get();
                                            $softDeletedColumns = $dataRecoverKolom->count();
                                            $displayStyle = $softDeletedColumns > 0 ? 'display: block;' : 'display: none;';
                                        @endphp
                                        <a href="#" class="dropdown-item recover-kolom-link" id="recover-kolom-link-{{ $board->id }}" data-toggle="modal" data-target="#pulihkanKolomModal" data-board-id="{{ $board->id }}" style="{{ $displayStyle }}">
                                            <i class="fa-solid fa-recycle fa-lg m-r-5"></i>
                                            <span style="font-weight: 900">Recover Column</span>
                                        </a> --}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <a href="#">
                            <div class="view-board btn btn-outline-danger">
                                <span style="font-weight: 900"><h4>Board:</h4></span>
                                <span style="font-weight: 900"><h5>{{ $board->name }}</h5></span>
                            </div>
                        </a>
                        <li class="add-teams btn btn-outline-danger">
                            <a href="{{ route('viewTeam2', ['team_id' => $team->id]) }}" class="link-add-teams">
                                <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                <span style="font-weight: 900">Back to Board's</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                    <li class="{{ set_active(['user/profile']) }} active-profile">
                        <a href="{{ route('user-profile') }}">
                            <i class="la la-user"></i>
                            <span style="font-weight: 900"> Profile</span>
                        </a>
                    </li>
                @endif

                <div class="latensi-koneksi" id="network-info">
                    <div class="icon-latensi">
                        <i class="fa-solid fa-wifi" id="wifi-icon"></i>
                    </div>
                    <div class="item-latensi">
                        <p class="text-latensi">Koneksi: <span id="rtt"></span> ms</p>
                    </div>
                </div>
                <script src="{{ asset('assets/js/checking-latensi-connection.js') }}"></script>
                
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->