<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @if (!Auth::guard('web')->guest())
        <meta name="userId" content="{{ Auth::guard('web')->user()->id }}">
        @endif
        @if (!Auth::guard('employers')->guest())
        <meta name="employerId" content="{{ Auth::guard('employers')->user()->id }}">
        @endif
        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        <link rel="icon" type="image/ico" href="{{ asset('/tiva/img/favicon.png') }}" sizes="120x120">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="{{ asset('/tiva/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('/tiva/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('/tiva/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/tiva/css/alertify.min.css') }}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">


        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700&amp;subset=vietnamese" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&amp;subset=vietnamese" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('/tiva/css/jquery.fancybox.min.css') }}">
        <!-- <link rel="stylesheet" href="{{ asset('/tiva/css/jquery.mCustomScrollbar.min.css') }}"> -->
        <link rel="stylesheet" href="{{ asset('/tiva/css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('/tiva/css/flickity.css') }}">
        <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/lib/chosen/chosen.min.css">
        <link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet"/>
        

        <!-- material -->
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
        <link href="{{ asset('/tiva/css/bootstrap-slider.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ url('sufee-admin') }}/assets/css/selectize.default.css">
        <!-- <script src="{{ asset('/js/jquery-3.2.1.min.js') }}" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> 
        -->
        <!-- custom css -->
        <link rel="stylesheet" href="{{ asset('/tiva/css/style.css?v=1.2') }}">
        <link rel="stylesheet" href="{{ asset('/tiva/css/responsive.css?v=1.2') }}">

        <!-- js -->
        
        <!-- <script src="{{ asset('/js/jquery-3.3.1.min.js') }}" type="text/javascript"></script> -->
        <script src="https://code.jquery.com/jquery-3.0.0.js"></script>
        <script src="https://code.jquery.com/jquery-migrate-3.0.1.js"></script>
        <!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script> -->
        <script src="{{ asset('/js/jquery-ui.js') }}" type="text/javascript"></script>
        <!-- <script>window.jQuery || document.write('<script src="{{ asset('/tiva/js/vendor/jquery-3.2.1.min.js') }}"><\/script>')</script> -->
        <script src="{{ asset('/js/markerclusterer.js') }}"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZ7WWqLmqlal2ipbzkIWjX_5y1xe9EiMQ&libraries=places&sensor=false"></script>
        </style>
        <script type="text/javascript" src="{{ asset('/js/accountkit.js') }}"></script>
        <script type="text/javascript" src="https://sdk.accountkit.com/vi_VN/sdk.js"></script>
        
    </head>

    <div class="modal" id="modal-login" tabindex="-1" role="dialog">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	        	<!-- <img class="logo" src="{{ asset('tiva/img/logo.png') }}" alt=""> -->
                <div class="login-content">
                    <div class="inner">
                        <div class="blk for_users">
                            <i class="icon fas fa-user-circle"></i>
                            <div class="welcome-text">
                                <span>Chào mừng tới TIVA</span>
                                <h4>Xin mời đăng nhập</h4>
                            </div>
            	        	@if (Auth::guest() && Auth::guard('web')->guest())
            			    <!-- <div class="select-role">
            	            	<span class="ui-radio"><input type="radio" name="radio-role" value="user" checked><label for="">Ứng viên</label></span>
            	            	<span class="ui-radio"><input type="radio" name="radio-role" value="employer"><label for="">Nhà tuyển dụng</label></span>
            				</div> -->
                            <form class="login_w_email">
                                <input type="email" name="email" class="form" placeholder="Email" required>
                                <input type="password" name="password" class="form" placeholder="*****" required>
                                <button type="button" class="btn btn-primary" btn-login="user">Đăng nhập</button>
                            </form>
                            <div class="divider"><span>Hoặc đăng nhập nhanh sử dụng</span></div>
                            <div class="button-group">
                                <button class="login_as_fb" onclick="location.href='{{ url('/auth/facebook') }}'"><i class="fab fa-facebook"></i> Facebook</button>
                                <button class="login_as_phone" onclick="smsLogin('user')"><i class="fas fa-mobile-alt"></i> Số điện thoại</button>
                            </div>
                            <div class="switch_toggle to_employer">Bạn là nhà tuyển dụng ? Đăng nhập</div>
                        </div>
                        <div class="blk for_employer">
                            <div class="switch_toggle to_user"><i class="fas fa-angle-up"></i></div>
                            <i class="icon fas fa-briefcase"></i>
                            <div class="welcome-text">
                                <span>Chào mừng tới TIVA</span>
                                <h4>Đăng nhập Nhà tuyển dụng</h4>
                            </div>
                            <form class="login_w_email">
                                <input type="email" name="email" class="form" placeholder="Email" required>
                                <input type="password" name="password" class="form" placeholder="*****" required>
                                <button type="button" class="btn btn-primary" btn-login="employer">Đăng nhập</button>
                            </form>
                            <div class="divider"><span>Hoặc đăng nhập nhanh sử dụng</span></div>
                            <div class="button-group">
                                <button class="login_as_fb" onclick="location.href='{{ url('/auth/facebook/employer') }}'"><i class="fab fa-facebook"></i> Facebook</button>
                                <button class="login_as_phone" onclick="smsLogin('employer')"><i class="fas fa-mobile-alt"></i> Số điện thoại</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <form action="/otp-login" method="post" id="form">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="code" id="code" />
                    <input type="hidden" name="role" id="role" />
                </form>
				
                <!-- <ul class="nav nav-tabs" role="tablist" style="">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" href="#sms-login" role="tab" aria-expanded="true">SMS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#email-login" role="tab" aria-controls="home" aria-expanded="false">Email</a>
                    </li>
                </ul> -->


                <!-- <div class="tab-content">
                    <div class="tab-pane fade in active" id="sms-login" role="tabpanel" aria-expanded="true">
                    <br>
                        <div class="fbkit-form">
                            <input class="prefix" type="text" id="country" name="country" value="+84">
                            <input class="number" type="text" id="phone" name="phone" placeholder="Số điện thoại">
                            <br>
                            <button class="btn btn-primary" onclick="smsLogin()">Đăng nhập</button>
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="email-login" role="tabpanel" aria-expanded="true">
                        <br>
                        <div class="fbkit-form">
                            <input class="number" type="text" id="email-fb" name="email-fb" placeholder="Email">
                            <button class="btn btn-primary" onclick="emailLogin()">Đăng nhập</button>
                            <a style="display: none" href="{{ url('/auth/facebook') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
                            <a style="display: none" href="{{ url('/auth/facebook/employer') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
                        </div>
                    </div>
                </div>
		        <form action="/otp-login" method="post" id="form">
		            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
		            <input type="hidden" name="code" id="code" />
		            <input type="hidden" name="role" id="role" />
		        </form> -->
			    
	        </div>
	    </div>
	</div>

    <header>
        <div id="app" class="container">
            <div id="logo">
                <a href="/" id="">
                    <img src="{{ asset('tiva/img/logo.png') }}" alt="">
                </a>
                <span class="m-hidden">Mạng kết nối việc làm duy nhất tại Việt Nam</span>
            </div>
            <div class="main-menu">
                <div id="main_menu-toggle" class="m-shown">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul>
                    @if(!Auth::guard('web')->guest())
                    <li class="menu-icon">
                        <!-- <a href="/" class="icon"><i aria-hidden="true" class="fas fa-globe"></i><span class="tooltip-note">Tìm việc</span></a> -->
                    </li>
                    <li class="menu-icon">
                        <!-- <a href="/xung-quanh" class="icon"><i aria-hidden="true" class="fas fa-map"></i><span class="tooltip-note">Việc xung quanh</span> -->
                        </a>
                    </li>
                    <!-- <li class="active"><a href="/">Tìm việc</a></li> -->
                    <!-- <li><a href="/xung-quanh">Việc xung quanh</a></li> -->
                    <notification-user v-bind:notifications="notifications"></notification-user>
                    <li>
                    	<span><span class="thumbnail" style="background-image:url('{{ Auth::guard('web')->user()->avatar?Auth::guard('web')->user()->avatar:'' }}');"></span>{{ Auth::guard('web')->user()->name?Auth::guard('web')->user()->name:Auth::guard('web')->user()->mobile_number }}</span>
                    	<div class="sub">
                    		<ul>
                    			<li><a href="/user/profile">Quản lý tài khoản</a></li>
                                <li><a href="/user/change-password">Đổi mật khẩu</a></li>
                    			<li><a href="/user/cvs">Công việc của tôi</a></li>
                    			<li><a href="/user/logout">Thoát</a></li>
                    		</ul>
                    	</div>
                    </li>
                    <!-- <li><a href="/user/info-cv" class="btn btn-primary"></i>Cập nhật CV</a></li> -->
                    @elseif(!Auth::guard('employers')->guest())
                        <li class="menu-icon">
                            <!-- <a href="/employer/jobs" class="icon"><i aria-hidden="true" class="fas fa-briefcase"></i><span class="tooltip-note">Quản lý công việc</span></a> -->
                        </li>
                        <li class="menu-icon">
                            <!-- <a href="{{ route('client.employer.info_company') }}" class="icon"><i aria-hidden="true" class="fas fa-building"></i><span class="tooltip-note">{{  Auth::guard('employers')->user()->company_name }}</span></a> -->
                        </li>
                    	<notification v-bind:notifications="notifications"></notification>
                        <li>
                    		<span><span class="thumbnail" style="background-image:url('{{ Auth::guard('employers')->user()->avatar?Auth::guard('employers')->user()->avatar:'' }}');"></span> {{ Auth::guard('employers')->user()->name?Auth::guard('employers')->user()->name:Auth::guard('employers')->user()->mobile_number }}</span>
                    		<div class="sub">
                    			<ul>
                    				<li><a href="/employer/profile">Quản lý tài khoản</a></li>
                                    <li><a href="/employer/change-password">Đổi mật khẩu</a></li>
                    				<li><a href="/employer/logout">Thoát</a></li>
                    			</ul>
                    		</div>
                    	</li>
                    	<!-- <li><a href="/employer/job/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm công việc</a></li> -->
                    @else
                        <li class="menu-icon active">
                            <!-- <a href="/" class="icon"><i aria-hidden="true" class="fas fa-globe"></i><span class="tooltip-note">Tìm việc</span>
                            </a> -->
                        </li>
                        <li class="menu-icon">
                            <!-- <a href="/xung-quanh" class="icon"><i aria-hidden="true" class="fas fa-map"></i><span class="tooltip-note">Việc xung quanh</span> -->
                            </a>
                        </li>
                        <li class="menu-icon">
                            <a href="" class="icon" data-toggle="modal" data-target="#modal-login"><i aria-hidden="true" class="fas fa-user"></i><span class="tooltip-note">Đăng nhập</span>
                            </a>
                        </li>
                    @endif
                        
                    {{--<li><a href="" class="btn btn-white">Nộp CV</a></li>--}}
                </ul>
            </div>
        </div>
    </header>
    <body>
    
    @yield('content')
    <!-- Add your site or application content here -->
            <script src="{{ asset('/tiva/js/vendor/modernizr-3.5.0.min.js') }}"></script>

            <script type="text/javascript" src="{{ asset('/tiva/js/bootstrap.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('/tiva/js/alertify.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
            <script src="{{ asset('/tiva/js/bootstrap-slider.min.js') }}"></script>
            <script src="{{ url('sufee-admin') }}/assets/js/selectize.min.js"></script>
            <script type="text/javascript" src="{{ asset('/tiva/js/jquery.fancybox.min.js') }}"></script>
            <!-- <script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script> -->
            <script type="text/javascript" src="{{ asset('/tiva/js/flickity.pkgd.min.js') }} "></script>
            <!-- <script type="text/javascript" src="js/jquery.textillate.js"></script> -->
            <!-- <script type="text/javascript" src="js/jquery.fittext.js"></script> -->
            <!-- <script type="text/javascript" src="js/jquery.lettering.js"></script> -->
            <script src="{{ url('sufee-admin') }}/assets/js/lib/chosen/chosen.jquery.min.js"></script>
            <script src="{{ asset('/tiva/js/jquery.sticky.js') }}"></script>
            <script src="{{ asset('/js/bootstrap-datepicker.js') }}"></script>
            <script src="{{ asset('/sufee-admin/assets/js/lib/ckeditor/ckeditor.js') }}"></script>
            <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
            <script src="{{ asset('/tiva/js/plugins.js') }}"></script>
            
            <script src="{{ asset('/tiva/js/main.js?v=1.3') }}"></script>
            <script src="{{ asset('/tiva/js/frontend.js?v=1.2') }}"></script>
        
            <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
            <script>
                window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
                ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
            </script>
            <script src="https://www.google-analytics.com/analytics.js" async defer></script>
            @if(!Auth::guard('web')->guest() || !Auth::guard('employers')->guest()) 
            <script src="{{ asset('js/app.js') }}" defer></script>
            @endif
            <audio id="notify-sound" >
              <source src="horse.ogg" type="audio/ogg">
              <source src="/audios/plucky.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
            </audio>
            
        </body>
    </html>


