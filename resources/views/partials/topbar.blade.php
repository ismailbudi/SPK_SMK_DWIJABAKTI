<header class="app-topbar">
    <button type="button" class="topbar-toggle" id="sidebarToggle" aria-label="Buka menu">
        <i class="bi bi-list"></i>
    </button>

    <div class="brand d-flex align-items-center gap-2">
        <i class="bi bi-mortarboard-fill"></i>
        <span>SPK Siswa Berprestasi ROC-MOORA</span>
    </div>

    <div class="topbar-user">
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="meta d-none d-sm-block">
            <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
            <span>{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Viewer' }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="topbar-logout" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
                <span class="d-none d-md-inline">Logout</span>
            </button>
        </form>
    </div>
</header>
