<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>STC Payroll | Login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <style>
    .login-page {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      width: 400px;
    }
    .login-logo {
      font-size: 2.1rem;
      font-weight: 300;
      margin-bottom: 0.9rem;
      text-align: center;
      color: #fff;
    }
    .login-card-body {
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>STC</b> Payroll
  </div>
  <div class="card login-card-body">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form id="loginForm" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" value="1">
              <label for="remember">
                Remember Me (24 hours)
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
              <span class="spinner-border spinner-border-sm d-none" id="loginSpinner"></span>
              <span id="loginBtnText">Sign In</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var email = $('#email').val();
        var password = $('#password').val();
        var remember = $('#remember').is(':checked') ? 1 : 0;
        
        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all fields'
            });
            return;
        }
        
        $('#loginBtn').prop('disabled', true);
        $('#loginSpinner').removeClass('d-none');
        $('#loginBtnText').text('Signing in...');
        
        $.ajax({
            url: '{{ url("/login") }}',
            type: 'POST',
            data: {
                email: email,
                password: password,
                remember: remember,
                _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Login successful!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = response.redirect || '{{ url("/") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message || 'Invalid credentials'
                    });
                    $('#loginBtn').prop('disabled', false);
                    $('#loginSpinner').addClass('d-none');
                    $('#loginBtnText').text('Sign In');
                }
            },
            error: function(xhr) {
                var message = 'Login failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: message
                });
                $('#loginBtn').prop('disabled', false);
                $('#loginSpinner').addClass('d-none');
                $('#loginBtnText').text('Sign In');
            }
        });
    });
});
</script>
</body>
</html>

