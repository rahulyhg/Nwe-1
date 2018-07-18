{{ !empty($mobile)?$mobile:'' }}
@extends('layouts.layout_client')
@section('content')
@if(!empty($user))
@section('title',$user->name)
<style type="text/css">

</style>

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-3">
				<div class="sidebar vertical-nav">
					@include('user.partials.employer_nav')
				</div>
			</div>
			<div class="col-md-6">
			    <div class="main-content">
			    	<form id="form-update" action="{{ route('client.employer.profile') }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
				    	<div id="employer_profile-account" class="section">
				    		<div class="section-title">
				    			<h3>Thông tin tài khoản</h3>
				    		</div>
				    		<div class="section-content">
				    			<div class="form-group">
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Ảnh đai diện</label>
					    					<div class="form-content form-update_avatar">
					    						<img class="page-feature-icon" src="{{ !empty($user->avatar)?$user->avatar:"/imgs/no_image.jpg" }}" old-src="{{ $user->avatar }}">
					    						<button class="btn btn-white">
					    							<input type="file" name="avatar" accept="image/*" class="chosen-image">
					    							Cập nhật ảnh
					    						</button>
					    					</div>
					    					@if ($errors->has('avatar'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('avatar') }}</strong>
			                                    </span>
			                                @endif
					    				</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-6">
					    					<label for="">Họ & tên</label>
					    					<input class="form-content" name="name" type="text" placeholder="" value="{{ $user->name }}">
						                     @if ($errors->has('name'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('name') }}</strong>
			                                    </span>
			                                @endif
			                            </div>
			                            <div class="col-md-6">
			                            	<label for="">Chức vụ</label>
				    						<input name="position" type="text" class="form-content" placeholder="" value="{{ $user->position }}">
				    						@if ($errors->has('position'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('position') }}</strong>
			                                    </span>
			                                @endif	
			                            </div>
				    				</div>
				    				
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Email</label>
					    					<input name="email" type="email" class="form-content" placeholder=""  value="{{ $user->email }}">
							                    @if ($errors->has('email'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('email') }}</strong>
				                                    </span>
				                                @endif
				                        </div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Số điện thoại</label>
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
					<div class="page-form-action">
						<div class="container">
					        @if(Session::has('alert'))
					       	{!! Session::get('alert') !!}
					        @endif
							<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Cập nhật</button>
							{{ !empty($error)?$error:'' }}
						</div>
					</div>
			    </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
</script>
@endif
@endsection