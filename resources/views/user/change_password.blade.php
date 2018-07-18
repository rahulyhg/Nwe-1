@extends('layouts.layout_client')
@section('content')
<div id="page-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
			    
			    <div class="job-content">
			    	<form id="form-update" action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					    <div class="blk-info type-info">
					        <h4 class="blk-title">Đổi mật khẩu</h4>
					        <div class="listing">
					            <li class="item">
					                <div class="left-obj">
					                    <span>Mật khẩu cũ</span>
					                </div>
					                <div class="right-obj">
					                    <input name="current_password" type="text" placeholder="" >
					                     @if ($errors->has('current_password'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('current_password') }}</strong>
		                                    </span>
		                                @endif
					                </div>
					            </li>
					            <li class="item">
					                <div class="left-obj">
					                    <span>Mật khẩu mới</span>
					                </div>
					                <div class="right-obj">
					                    <input name="password" type="text" placeholder="" >
					                     @if ($errors->has('password'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('password') }}</strong>
		                                    </span>
		                                @endif
					                </div>
					            </li>
					            <li class="item">
					                <div class="left-obj">
					                    <span>Nhập lại mật khẩu</span>
					                </div>
					                <div class="right-obj">
					                    <input name="password_confirmation" type="text" placeholder="" >
					                     @if ($errors->has('password_confirmation'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
		                                    </span>
		                                @endif
					                </div>
					            </li>
					        </div>
					       @if (session('error'))
                                <span class="help-block">
                                    <strong>{{ session('error') }}</strong>
                                </span>
                            @endif
                             @if (session('success'))
                                <span class="help-block">
                                    <strong>{{ session('success') }}</strong>
                                </span>
                            @endif
					    </div>
					</form>   
			    </div>
				<div class="modal-footer">
				@if(Session::has('alert'))
		       	{!! Session::get('alert') !!}
		        @endif
                <button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Gửi</button>
            </div>
			</div>
		</div>
	</div>
</div>
@endsection