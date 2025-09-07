
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title> @yield('title') </title>
        <!-- Font Awesome Icons -->
        {{-- <link rel="stylesheet" href="{{url('assets/admin/plugins/fontawesome-free/css/all.min.css')}}"> --}}
        <!-- Fontawesome -->
        <script src="https://kit.fontawesome.com/1bc0535d43.js" crossorigin="anonymous"></script>

        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('assets/admin/dist/css/adminlte.min.css')}}">
        
        <link rel="stylesheet" href="{{url('assets/admin/dist/css/custom.css')}}">
        
        <link rel="stylesheet" href="{{url('assets/admin/css/mycustomstyle.css')}}">
        
        <!-- SweetAlert 2 -->
        <link rel="stylesheet" href="{{url('assets/admin/plugins/sweetalert2/sweetalert2.min.css')}}">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{url('assets\admin\plugins\toastr\toastr.css')}}">

        @yield('css')

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="{{url('assets/admin/fonts/SansPro/SansPro.min.css')}}">
        <link rel="stylesheet" href="{{url('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{url('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')}}">
        
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

        <!-- Navbar -->
            @include('admin.includes.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/hrms_laravel" class="brand-link">
            {{-- <img src="{{url('assets/admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" --}}
            <img src="{{url('assets/admin/imgs/system_logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8; background-color: white">
            <span class="brand-text font-weight-light">HRMS</span>
            </a>

            <!-- Sidebar -->
            @include('admin.includes.sidebar')
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        @include('admin.includes.content')
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.includes.footer')
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->

        <!-- jQuery -->
        <script src="{{url('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{url('assets/admin/dist/js/adminlte.min.js')}}"></script>
        
        <!-- General JS -->
        <script src="{{url('assets/admin/js/General.js')}}"></script>
        
        {{-- SweetAlert 2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- <script src="{{ url('assets/admin/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
        
        {{-- Toastr --}}
        {{-- <script src="{{url('assets/admin/plugins/toastr/toastr.min.js')}}"></script> --}}
        
        @yield('script')
    </body>
</html>
