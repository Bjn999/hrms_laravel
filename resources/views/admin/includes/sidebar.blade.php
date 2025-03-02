<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{url('assets/admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ auth()->user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calenders*') || request()->is('admin/branches*') || request()->is('admin/shiftTypes*') || request()->is('admin/departments*') || request()->is('admin/jobs_categories*') || request()->is('admin/qualifications*') ) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calenders*') || request()->is('admin/branches*') || request()->is('admin/shiftTypes*') || request()->is('admin/departments*') || request()->is('admin/jobs_categories*') || request()->is('admin/qualifications*') ) ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              قائمة الضبط
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin_panel_settings.index') }}" class="nav-link {{request()->is('admin/generalSettings*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>الضبط العام</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('finance_calenders.index') }}" class="nav-link {{request()->is('admin/finance_calenders*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-money-bill"></i> --}}
                <p>السنوات المالية</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('branches.index') }}" class="nav-link {{request()->is('admin/branches*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>الفروع</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('shiftstypes.index') }}" class="nav-link {{request()->is('admin/shiftTypes*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>أنواع الشفتات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('departments.index') }}" class="nav-link {{request()->is('admin/departments*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>إدارات الموظفين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('jobs_categories.index') }}" class="nav-link {{request()->is('admin/jobs_categories*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>فئات الوظائف</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('qualifications.index') }}" class="nav-link {{request()->is('admin/qualifications*') ? 'active' : ''}} ">
                <i class="far fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>مؤهلات الموظفين</p>
              </a>
            </li>
          </ul>
        </li>
        {{-- <hr style="height: 2px; margin: 2px 0; background: #999;">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Simple Link
              <span class="right badge badge-danger">New</span>
            </p>
          </a>
        </li> --}}
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>