{{ !empty($mobile)?$mobile:'' }}
@extends('layouts.layout_client')
@section('content')
@section('title', 'Register')
<style type="text/css">

</style>

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-2">
				<div class="sidebar vertical-nav">
					
				</div>
			</div>
			<div class="col-md-6">
			    <div class="main-content">
			    	<form id="form-update" action="{{ route('client.employer.register') }}" method="post" enctype="multipart/form-data">
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
					    						<img class="page-feature-icon" src="/imgs/no_image.jpg">
					    						<button class="btn btn-white">
					    							<input type="file" name="avatar" accept="image/*" class="chosen-image">
					    							Cập nhật ảnh
					    						</button>
					    					</div>
					    				</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-6">
					    					<label for="">Họ & tên</label>
					    					<input class="form-content" name="name" type="text" placeholder="" value="{{ old('name')}}">
						                     @if ($errors->has('name'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('name') }}</strong>
			                                    </span>
			                                @endif
			                            </div>
			                            <div class="col-md-6">
			                            	<label for="">Chức vụ</label>
				    						<input name="position" type="text" class="form-content" placeholder="" value="{{ old('position')}}">
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
					    					<input name="email" type="email" class="form-content" placeholder="" {{ !empty($email)?'readonly="readonly"':'' }} value="{{ !empty($email)?$email:old('email') }}">
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
					    					<input name="mobile_number" type="text" class="form-content" {{ !empty($mobile)?'readonly="readonly"':'' }} value="{{ !empty($mobile)?$mobile:old('mobile_number') }}">
							                    @if ($errors->has('mobile_number'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('mobile_number') }}</strong>
				                                    </span>
				                                @endif
				                        </div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Mật khẩu</label>
					    					<input name="password" type="password" class="form-content" value="">
							                    @if ($errors->has('password'))
				                                    <span class="help-block">
				                                        <strong>{{ $errors->first('password') }}</strong>
				                                    </span>
				                                @endif
				                        </div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Nhập lại mật khẩu</label>
					    					<input name="password_confirmation" type="password" class="form-content">
							              
				                        </div>
				    				</div>
				    			</div>
				    		</div>
				    	</div>
				    	<div class="section">
				    		<div class="section-title">
				    			<h3>Thông tin Doanh nghiệp</h3>
				    		</div>
				    		<div class="section-content">
				    			<div class="form-group">
				    				<div class="form row">
				    					<div class="col-md-6">
					    					<label for="">Tên DN</label>
					    					<input name="company_name" class="form-content" type="text" placeholder="" value="{{ old('company_name')}}">
					    					@if ($errors->has('company_name'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_name') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    					<div class="col-md-6">
					    					<label for="">Lĩnh vực hoạt động</label>
					    					<input name="company_activity" type="text" class="form-content" placeholder="" value="{{ old('company_activity')}}">
					    					@if ($errors->has('company_activity'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_activity') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Số điện thoại</label>
					    					<input name="company_mobile" type="text" class="form-content" placeholder="" value="{{ old('company_mobile')}}">
					    					@if ($errors->has('company_mobile'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_mobile') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Địa chỉ</label>
					    					<input name="company_address" type="text" class="form-content" placeholder="" value="{{ old('company_address')}}">
					    					@if ($errors->has('company_address'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_address') }}</strong>
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
							<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Đăng ký</button>
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
@endsection