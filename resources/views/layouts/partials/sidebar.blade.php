<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="monitor"></i>
                        <span data-key="t-monitoring">Monitoring</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('stasiun-cuaca') }}" data-key="t-stasiun-cuaca">Stasiun Cuaca</a></li>
                        <li><a href="{{ route('monitoring.solar-dome') }}" data-key="t-solar-dome">Solar Dome</a></li>
                        <li><a href="{{ route('monitoring.lora') }}" data-key="t-greenhouse-quality">Lora Monitoring</a></li>
                        <li><a href="{{ route('soil-test') }}" data-key="t-soil-monitoring">Soil Test</a></li>
                        <li><a href="#" data-key="t-air-monitoring">Soil Monitoring</a></li>
                    </ul>
                </li>
                @if(Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('users.index') }}">
                        <i data-feather="users"></i>
                        <span data-key="t-users">Manajemen User</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        </div>
</div>