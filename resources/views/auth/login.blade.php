<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{asset('theme/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/adminlte/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{url('')}}" class="h1"><b>UJIAN</b>SERVER</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="{{url('api/auth/login')}}" id="form-login">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="{{asset('theme/adminlte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('theme/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('theme/adminlte/js/adminlte.min.js')}}"></script>
<script>
    const token = localStorage.getItem('token');
    if(token !== null) window.location.href = window.origin + '/home';
    $('#form-login').on('submit', function (event) {
        $.ajax({
            url: event.target.action, method: 'post',
            headers: {'Accept':'application/json'},
            data: $('#form-login').serialize(),
            error: (e)=> {
                if(typeof e.responseJSON !== "undefined") {
                    if(typeof e.responseJSON.status_message !== "undefined") {
                        alert(e.responseJSON.status_message);
                    }
                }
            },
            success: (e)=> {
                if(typeof e.status_message !== "undefined") {
                    alert(e.status_message);
                }
                if(typeof e.status_data !== "undefined") {
                    if(typeof e.status_data.token !== "undefined") localStorage.setItem('token', e.status_data.token);
                    if(typeof e.status_data.data !== "undefined") localStorage.setItem('user', e.status_data.data);
                    window.location.href = window.origin + '/home';
                }
            }
        })
        event.preventDefault();
    })
</script>
</body>
</html>
