<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-superscript"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Montesse</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('saham.index') }}">
      <i class="fas fa-fw fa-chart-pie"></i>
      <span>Data</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('monte.index') }}">
      <i class="fas fa-calculator"></i>
      <span>Monte Carlo Method</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('profile') }}">
      <i class="fas fa-user"></i>
      <span>Profile</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>


</ul>
