<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - Tiva</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/normalize.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/themify-icons.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/scss/style.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/lib/datatable/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/lib/chosen/chosen.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/selectize.default.css">

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    {{--<script src="{{ url('sufee-admin') }}/assets/js/vendor/jquery-2.1.4.min.js"></script>--}}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    {{--<script src="{{ url('sufee-admin') }}/assets/js/lib/ckeditor/ckeditor.js"/>--}}

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZ7WWqLmqlal2ipbzkIWjX_5y1xe9EiMQ&libraries=places&sensor=false"></script>

</head>
<body>


        <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#">Admin</a>
                <a class="navbar-brand hidden" href="#"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="">
                        <a href="{{ url('dashboard') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">Manager</h3><!-- /.menu-title -->
                    @if( Auth::guard('employers')->user()->isAdmin() )
                     <li class="@if(request()->is('tabs') || request()->is('tab/*')) active @endif">
                        <a href="{{ url('tabs') }}"> <i class="menu-icon ti-user"></i>Tabs </a>
                    </li>
                    <li class="@if(request()->is('employers') || request()->is('employer/*')) active @endif">
                        <a href="{{ url('employers') }}"> <i class="menu-icon ti-user"></i>Nhà tuyển dụng </a>
                    </li>
                    <li class="@if(request()->is('users') || request()->is('user/*')) active @endif">
                        <a href="{{ url('/users') }}"> <i class="menu-icon ti-user"></i>Hồ sơ</a>
                    </li>
                    @endif
                    <li class="@if(request()->is('jobs') || request()->is('job/*')) active @endif">
                        <a href="{{ url('jobs') }}"> <i class="menu-icon ti-user"></i>Công việc </a>
                    </li>
                    <li class="@if(request()->is('utilities') || request()->is('utilities/*')) active @endif">
                        <a href="{{ url('/utilities') }}"> <i class="menu-icon ti-user"></i>Tiện ích</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    {{--<div class="header-left">--}}
                        {{--<button class="search-trigger"><i class="fa fa-search"></i></button>--}}
                        {{--<div class="form-inline">--}}
                            {{--<form class="search-form">--}}
                                {{--<input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">--}}
                                {{--<button class="search-close" type="submit"><i class="fa fa-close"></i></button>--}}
                            {{--</form>--}}
                        {{--</div>--}}

                        {{--<div class="dropdown for-notification">--}}
                          {{--<button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--<i class="fa fa-bell"></i>--}}
                            {{--<span class="count bg-danger">5</span>--}}
                          {{--</button>--}}
                          {{--<div class="dropdown-menu" aria-labelledby="notification">--}}
                            {{--<p class="red">You have 3 Notification</p>--}}
                            {{--<a class="dropdown-item media bg-flat-color-1" href="#">--}}
                                {{--<i class="fa fa-check"></i>--}}
                                {{--<p>Server #1 overloaded.</p>--}}
                            {{--</a>--}}
                            {{--<a class="dropdown-item media bg-flat-color-4" href="#">--}}
                                {{--<i class="fa fa-info"></i>--}}
                                {{--<p>Server #2 overloaded.</p>--}}
                            {{--</a>--}}
                            {{--<a class="dropdown-item media bg-flat-color-5" href="#">--}}
                                {{--<i class="fa fa-warning"></i>--}}
                                {{--<p>Server #3 overloaded.</p>--}}
                            {{--</a>--}}
                          {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="dropdown for-message">--}}
                          {{--<button class="btn btn-secondary dropdown-toggle" type="button"--}}
                                {{--id="message"--}}
                                {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--<i class="ti-email"></i>--}}
                            {{--<span class="count bg-primary">9</span>--}}
                          {{--</button>--}}
                          {{--<div class="dropdown-menu" aria-labelledby="message">--}}
                            {{--<p class="red">You have 4 Mails</p>--}}
                            {{--<a class="dropdown-item media bg-flat-color-1" href="#">--}}
                                {{--<span class="photo media-left"><img alt="avatar" src="images/avatar/1.jpg"></span>--}}
                                {{--<span class="message media-body">--}}
                                    {{--<span class="name float-left">Jonathan Smith</span>--}}
                                    {{--<span class="time float-right">Just now</span>--}}
                                        {{--<p>Hello, this is an example msg</p>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                            {{--<a class="dropdown-item media bg-flat-color-4" href="#">--}}
                                {{--<span class="photo media-left"><img alt="avatar" src="images/avatar/2.jpg"></span>--}}
                                {{--<span class="message media-body">--}}
                                    {{--<span class="name float-left">Jack Sanders</span>--}}
                                    {{--<span class="time float-right">5 minutes ago</span>--}}
                                        {{--<p>Lorem ipsum dolor sit amet, consectetur</p>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                            {{--<a class="dropdown-item media bg-flat-color-5" href="#">--}}
                                {{--<span class="photo media-left"><img alt="avatar" src="images/avatar/3.jpg"></span>--}}
                                {{--<span class="message media-body">--}}
                                    {{--<span class="name float-left">Cheryl Wheeler</span>--}}
                                    {{--<span class="time float-right">10 minutes ago</span>--}}
                                        {{--<p>Hello, this is an example msg</p>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                            {{--<a class="dropdown-item media bg-flat-color-3" href="#">--}}
                                {{--<span class="photo media-left"><img alt="avatar" src="images/avatar/4.jpg"></span>--}}
                                {{--<span class="message media-body">--}}
                                    {{--<span class="name float-left">Rachel Santos</span>--}}
                                    {{--<span class="time float-right">15 minutes ago</span>--}}
                                        {{--<p>Lorem ipsum dolor sit amet, consectetur</p>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                          {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                        </a>

                        <div class="user-menu dropdown-menu">
                                @if(!Auth::guard('employers')->user()->isAdmin() )
                                <a class="nav-link" href="{{ url('/employer/edit/'.Auth::guard('employers')->user()->id) }}"><i class="fa fa- user"></i>My Profile</a>
                                @endif
                                {{--<a class="nav-link" href="#"><i class="fa fa- user"></i>Notifications <span class="count">13</span></a>--}}

                                {{--<a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a>--}}

                                <a class="nav-link" href="{{ url('logout') }}"><i class="fa fa-power -off"></i>Logout</a>
                        </div>
                    </div>

                    {{--<div class="language-select dropdown" id="language-select">--}}
                        {{--<a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">--}}
                            {{--<i class="flag-icon flag-icon-us"></i>--}}
                        {{--</a>--}}
                        {{--<div class="dropdown-menu" aria-labelledby="language" >--}}
                            {{--<div class="dropdown-item">--}}
                                {{--<span class="flag-icon flag-icon-fr"></span>--}}
                            {{--</div>--}}
                            {{--<div class="dropdown-item">--}}
                                {{--<i class="flag-icon flag-icon-es"></i>--}}
                            {{--</div>--}}
                            {{--<div class="dropdown-item">--}}
                                {{--<i class="flag-icon flag-icon-us"></i>--}}
                            {{--</div>--}}
                            {{--<div class="dropdown-item">--}}
                                {{--<i class="flag-icon flag-icon-it"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->
        @yield('content')
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="{{ url('sufee-admin') }}/assets/js/plugins.js"></script>


        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/datatables.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/dataTables.buttons.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/jszip.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/pdfmake.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/vfs_fonts.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/buttons.html5.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/buttons.print.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/buttons.colVis.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/data-table/datatables-init.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/jquery.validate.min.js"></script>
        <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/main.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/chosen/chosen.jquery.min.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/lib/ckeditor/ckeditor.js"></script>
        <script src="{{ url('sufee-admin') }}/assets/js/selectize.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

</body>
</html>