<aside class="app-sidebar">
    <div class="sidebar-title">Menu Utama</div>
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('siswa.index') }}" class="{{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Data Siswa</span>
        </a>
        <a href="{{ route('kriteria.index') }}" class="{{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
            <i class="bi bi-table"></i>
            <span>Data Kriteria</span>
        </a>
        <a href="{{ route('nilai.index') }}" class="{{ request()->routeIs('nilai.*') ? 'active' : '' }}">
            <i class="bi bi-pencil-square"></i>
            <span>Input Nilai</span>
        </a>
        <a href="{{ route('roc.index') }}" class="{{ request()->routeIs('roc.*') ? 'active' : '' }}">
            <i class="bi bi-calculator"></i>
            <span>Perhitungan ROC</span>
        </a>
        <a href="{{ route('moora.index') }}" class="{{ request()->routeIs('moora.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i>
            <span>Perhitungan MOORA</span>
        </a>
        <a href="{{ route('ranking.index') }}" class="{{ request()->routeIs('ranking.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line-fill"></i>
            <span>Hasil Perangkingan</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Laporan</span>
        </a>
    </nav>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
