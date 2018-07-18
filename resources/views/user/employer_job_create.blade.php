@extends('layouts.layout_client')
@section('content')
<div id="page-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
			    
			    <div class="job-content">
			    	<form id="form-update" action="{{ url('/employer/job/create') }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					    <div class="blk-info type-info">
					        <h4 class="blk-title">Thêm công việc</h4>
					        <div class="listing">
					        	<!-- <li class="item">
						        	<div class="left-obj">
					                    <span>Ảnh đại diện</span>
					                </div>
					                <div class="right-obj">
					                    <label>
                                            <img class="page-feature-icon" src="/imgs/no_image.jpg" style="max-width: 100px">
                                            <input type="file" name="thumb" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                        </label>
                                        @if ($errors->has('thumb'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('thumb') }}</strong>
		                                    </span>
		                                @endif
					                </div>
					        	</li> -->
					            <li class="item">
					                <div class="left-obj">
					                    <span>Tên công việc</span>
					                </div>
					                <div class="right-obj">
					                    <textarea name="job_name" cols="30" rows="3" type="text" placeholder="" ></textarea>
					                     @if ($errors->has('job_name'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('job_name') }}</strong>
		                                    </span>
		                                @endif
					                </div>
					            </li>
					        </div>
					    </div>
					</form>   
			    </div>
				<div class="modal-footer">
                <button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Tạo công việc mới</button>
            </div>
			</div>
		</div>
	</div>
</div>
@endsection