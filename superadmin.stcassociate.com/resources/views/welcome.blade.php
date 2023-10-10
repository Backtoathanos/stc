<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                font-weight: bold;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            
            <div class="content">
                <div class="title m-b-md">
                    Login
                </div>
                <form method="POST" action="" method="POST">
                    {{csrf_field()}}
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="user-id" class="mt-2">User Id : </label>
                        </div>
                        <div class="col-8">
                            <input type="text" id="user-id" class="form-control" name="userid" required placeholder="Enter user id">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="user-password" class="mt-2">Password : </label>
                        </div>
                        <div class="col-8">
                            <input type="password" id="user-password" class="form-control" name="password" required placeholder="Enter password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="remember-me" class="mt-2">Remember me : </label>
                        </div>
                        <div class="col-6">
                            <input type="checkbox" id="remember-me" name="remember-me" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success loginbtn">Login</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>@include('layouts._message')</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
