<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div class="sidebar-menu">
            <ul>
                <li class="sidebar-left">
                    <a href="{{ route('pengaturan-perusahaan') }}">
                        <div class="image">
                            <img src="{{ URL::to('/assets/images/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                            <span class="status online"></span>
                        </div>
                        <span class="text">{{ Session::get('name') }}</span>
                    </a>
                    <div class="line"></div>
                </li>
                <li><a href="{{ route('home') }}"><i class="la la-home"></i> <span>Menu Utama</span></a></li>
                <li class="menu-title"> <span>Pengaturan</span> </li>
                    <li class="{{ set_active(['pengaturan/perusahaan']) }}">
                        <a href="{{ route('pengaturan-perusahaan') }}"
                            class="{{ set_active(['pengaturan/perusahaan']) ? 'noti-dot' : '' }}">
                            <i class="la la-building"></i>
                            <span> Pengaturan</span>
                        </a>
                    </li>
            </ul>
        </div>
    </div>
</div>
<!-- Sidebar -->
