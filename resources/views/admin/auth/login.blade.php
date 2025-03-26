<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRMS - Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('assets/admin/fonts/ionicons/2.0.1/css/ionicons.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{url('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('assets/admin/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="{{url('assets/admin/fonts/SansPro/SansPro.min.css')}}">

  <style>
    input.form-control {
        text-align: right;
    }
    p {
        text-align: right;
    }
    .card,
    .login-card-body {
      border-radius: 10px;
    }
    .card-body > form p {
      font-size: 14px;
      font-weight: bold;
    }
    @media (max-width:350px) {
      body {
        background-size: 100%;
      }
    }
  </style>
</head>
<body class="hold-transition login-page" style="background-size: cover; background-image: url('{{ url('assets/admin/imgs/login.jpg') }}');">
<div class="login-box">
  <div class="login-logo">
    <b>HR</b>MS
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      @if (Session::has('error'))
          <div class="alert alert-danger text-right" role="alert">
            {{ Session::get('error') }}
          </div>
      @endif

      <p class="login-box-msg h5 my-4">تسجيل دخول المسؤول</p>
      <form action="{{ route('admin.login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input autofocus type="text" name="username" class="form-control" placeholder="اسم المستخدم" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('username')
            <p class="text-danger">
              {{ $message }}
            </p>
        @enderror

        <div class="input-group mb-3" style="direction: rtl">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
        </div>
        @error('password')
            <p class="text-danger">
              {{ $message }}
            </p>
        @enderror
        <div class="row">
          {{-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" disabled id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div> --}}
          
          <!-- /.col -->
          <div class="col">
            <button type="submit" class="btn btn-primary btn-block btn-flat">تسجيل الدخول</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{url('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

</body>
</html>
