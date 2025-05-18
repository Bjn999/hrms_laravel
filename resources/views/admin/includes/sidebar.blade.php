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
        <li class="nav-item has-treeview {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calenders*') || request()->is('admin/branches*') || request()->is('admin/shiftTypes*') || request()->is('admin/departments*') || request()->is('admin/jobs_categories*') || request()->is('admin/qualifications*') || request()->is('admin/occasions*') || request()->is('admin/resignations*') || request()->is('admin/nationalities*') || request()->is('admin/religions*') ) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calenders*') || request()->is('admin/branches*') || request()->is('admin/shiftTypes*') || request()->is('admin/departments*') || request()->is('admin/jobs_categories*') || request()->is('admin/qualifications*') || request()->is('admin/occasions*') || request()->is('admin/resignations*') || request()->is('admin/nationalities*') || request()->is('admin/religions*') ) ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              قائمة الضبط
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin_panel_settings.index') }}" class="nav-link {{request()->is('admin/generalSettings*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/generalSettings*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>الضبط العام</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('finance_calenders.index') }}" class="nav-link {{request()->is('admin/finance_calenders*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/finance_calenders*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-money-bill"></i> --}}
                <p>السنوات المالية</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('branches.index') }}" class="nav-link {{request()->is('admin/branches*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/branches*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>الفروع</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('shiftstypes.index') }}" class="nav-link {{request()->is('admin/shiftTypes*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/shiftTypes*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>أنواع الشفتات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('departments.index') }}" class="nav-link {{request()->is('admin/departments*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/departments*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>إدارات الموظفين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('jobs_categories.index') }}" class="nav-link {{request()->is('admin/jobs_categories*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/jobs_categories*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>فئات الوظائف</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('qualifications.index') }}" class="nav-link {{request()->is('admin/qualifications*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/qualifications*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>مؤهلات الموظفين</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('occasions.index') }}" class="nav-link {{request()->is('admin/occasions*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/occasions*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>المناسبات الرسمية</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('resignations.index') }}" class="nav-link {{request()->is('admin/resignations*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/resignations*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>أنواع ترك العمل</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('nationalities.index') }}" class="nav-link {{request()->is('admin/nationalities*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/nationalities*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>أنواع الجنسيات</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('religions.index') }}" class="nav-link {{request()->is('admin/religions*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/religions*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-building"></i> --}}
                <p>أنواع الديانات</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ ( request()->is('admin/employees*') || request()->is('admin/additionalSalTypes*') || request()->is('admin/discountSalTypes*') || request()->is('admin/allowances*') ) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ ( request()->is('admin/employees*') || request()->is('admin/additionalSalTypes*') || request()->is('admin/discountSalTypes*') || request()->is('admin/allowances*') ) ? 'active' : '' }}">
            <i class="nav-icon fas fa-user"></i>
            <p>
              قائمة شؤون الموظفين
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('employees.index') }}" class="nav-link {{request()->is('admin/employees*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/employees*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>بيانات الموظفين</p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{ route('employees.index') }}" class="nav-link {{request()->is('admin/employees*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/employees*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                <p>بيانات موظفين الإدارة</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{ route('additionalsaltypes.index') }}" class="nav-link {{request()->is('admin/additionalSalTypes*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/additionalSalTypes*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>أنواع الإضافة للراتب</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('discountsaltypes.index') }}" class="nav-link {{request()->is('admin/discountSalTypes*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/discountSalTypes*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>أنواع الخصم للراتب</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('allowances.index') }}" class="nav-link {{request()->is('admin/allowances*') ? 'active' : ''}} ">
                <i class="{{request()->is('admin/allowances*') ? 'fas' : 'far'}} fa-circle nav-icon"></i>
                {{-- <i class="nav-icon fas fa-cogs"></i> --}}
                <p>أنواع البدلات للراتب</p>
              </a>
            </li>
            
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>