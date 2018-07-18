<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tiva.vn | Mạng việc làm #1 Việt Nam</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="{{ url('/sufee-admin') }}/apple-icon.png">
    <link rel="shortcut icon" href="{{ url('/sufee-admin') }}/favicon.ico">

    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/normalize.css">
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/themify-icons.css">
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="{{ url('/sufee-admin') }}/assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    {{--<a href="index.html">--}}
                        {{--<img class="align-content" src="{{ url('/sufee-admin') }}/images/logo.png" alt="">--}}
                    {{--</a>--}}
                </div>
                <div class="login-form">
                    <form action="{{ url('/user/login') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group" style="display: grid;grid-template-columns: auto auto;">
                            <div style="text-align: center;margin-right: -100px;"><label><input type="radio" name="role" value="user" checked=""/><span> User</span></label></div>
                            <div style="text-align: center;margin-left: -100px;"><label><input type="radio" name="role" value="employer"><span> Employer</span></label></div>
                        </div>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="text" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Ghi nhớ
                            </label>
                            {{--<label class="pull-right">--}}
                                {{--<a href="#">Forgotten Password?</a>--}}
                            {{--</label>--}}

                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
						@if ($errors->any())
						<div class="alert alert-danger">
						    <ul>
						        @foreach ($errors->all() as $error)
						            <li>{{ $error }}</li>
						        @endforeach
						    </ul>
						</div>
						@endif


                        {{--<div class="social-login-content">--}}
                            {{--<div class="social-button">--}}
                                {{--<button type="button" class="btn social facebook btn-flat btn-addon mb-3"><i class="ti-facebook"></i>Sign in with facebook</button>--}}
                                {{--<button type="button" class="btn social twitter btn-flat btn-addon mt-2"><i class="ti-twitter"></i>Sign in with twitter</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="register-link m-t-15 text-center">--}}
                            <!-- <p>Don't have account ? <a href="/#register"> register</a></p> -->
                        {{--</div>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ url('/sufee-admin') }}/assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="{{ url('/sufee-admin') }}/assets/js/popper.min.js"></script>
    <script src="{{ url('/sufee-admin') }}/assets/js/plugins.js"></script>
    <script src="{{ url('/sufee-admin') }}/assets/js/main.js"></script>


</body>
</html>
