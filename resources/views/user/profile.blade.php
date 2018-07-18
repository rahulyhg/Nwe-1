@extends('layouts.layout_client')
@section('content')
@if(!empty($user))
@section('title',$user->name)

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-3">
				<div class="side-nav sticky">
					<!-- <ul>
						<li class="active"><a class="scrollTo" href="#user_profile-account">Thông tin tài khoản</a></li>
						<li><a class="scrollTo" href="#user_profile-change_password">Đổi mật khẩu</a></li>
						<li><a class="scrollTo" href="#user_profile-wish">Kỳ vọng công việc</a></li>
						<li><a class="scrollTo" href="#user_profile-experience">Kinh nghiệm</a></li>
						<li><a class="scrollTo" href="#user_profile-skill">Kỹ năng chuyên môn</a></li>
					</ul> -->
					@include('user.partials.user_nav')

				</div>
			</div>
			<div class="col-md-6">
			    <div class="main-content">
			    	<form id="form-update" action="{{ url('/user/profile') }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					    <div id="user_profile-account" class="section">
					    	<div class="section-title">
					        	<h3 class="blk-title">Thông tin tài khoản</h3>
					        </div>
					        <div class="section-content">
						        <div class="form-group">
						        	<div class="form row">
						        		<div class="col-md-12">
								        	<label for="">Ảnh đại diện</label>
							                <div class="form-content form-update_avatar">
		                                        <img class="page-feature-icon" src="{{ !empty($user->avatar)?$user->avatar:"/imgs/no_image.jpg" }}" old-src="{{ $user->avatar }}" style="">
		                                        <button class="btn btn-white">
		                                        	<input type="file" name="avatar" accept="image/*" class="chosen-image" style="">
		                                        	Cập nhật ảnh
		                                        </button>
							                </div>
							            </div>
						        	</div>
						            <div class="form row">
						            	<div class="col-md-12">
							                <label for="">Họ & tên</label>
							                <input name="name" type="text" class="form-content" placeholder="" value="{{ $user->name }}">
							                @if ($errors->has('name'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('name') }}</strong>
				                                    </span>
				                                @endif
							            </div>
							           
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Email</label>
						                    <input name="email" type="email" class="form-content" placeholder="" value="{{ $user->email }}">
						                    @if ($errors->has('email'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('email') }}</strong>
				                                    </span>
				                                @endif
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Số điện thoại</label>
						                    <input name="mobile_number" type="text" class="form-content" placeholder="" value="{{ $user->mobile_number }}">
						                    @if ($errors->has('mobile_number'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('mobile_number') }}</strong>
				                                    </span>
				                                @endif
						                </div>
						            </div>
						        </div>
						    </div>
					    </div>
				
					</form>   
			    </div>
			</div>
		</div>
	</div>
	<div class="page-form-action">
		<div class="container">
			@if(Session::has('alert'))
	       	{!! Session::get('alert') !!}
	        @endif
			<button class="btn btn-smoke">Huỷ thay đổi</button>
			<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary btn-update-cv">Cập nhật</button>
		</div>
	</div>
</div>

	<script type="text/javascript">
    	$.get( "{{ asset('/tiva/js/city.json') }}", function( data ) {
          $.each(data,function(index, value){
            
            if(value.name == "{{ $user->work_address }}"){
                $('select[name="work_address"]').append($('<option>').attr('data-id',index).attr('selected','').attr('value',value.name).text(value.name)); 
            }else{
                $('select[name="work_address"]').append($('<option>').attr('data-id',index).attr('value',value.name).text(value.name)); 
            }
          })
          $('select[name="work_address"]').chosen({
                //disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                //width: "30%"
            });  
        });
    </script>
@endif
@endsection