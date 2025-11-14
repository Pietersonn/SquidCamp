<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- BRAND -->
  <div class="app-brand demo" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/logo/logo-squidcamp1.png') }}" alt="Logo SquidCamp"
          style="height: 280px; width: auto;">
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm"></i>
    </a>
  </div>



  <div class="menu-divider mt-0"></div>

  <!-- MENU LIST -->
  <ul class="menu-inner py-1">

    {{-- DASHBOARD --}}
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- USER MANAGEMENT --}}
    <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('admin.users.index') }}">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>User Management</div>
      </a>
    </li>
    

    {{-- EVENT MANAGEMENT --}}
    <li class="menu-item {{ request()->routeIs('admin.event.index') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('admin.event.index') }}">
        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
        <div>Event Management</div>
      </a>
    </li>

  </ul>

</aside>
