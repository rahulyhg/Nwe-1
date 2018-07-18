@extends('layouts.layout_client')
@section('content')
<div class="modal fade" id="modal-review" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<i class="fas fa-times"></i>
                </button>
                <div class="review-title">
                	<span>Đánh giá cho Công việc</span>
                	<h5>[Hà Nội] Tuyển chuyên gia UI/UX xây dựng Ứng dụng di động</h5>
                </div>
                <div class="review-profile">
                	<div class="logo" style="background-image:url();"></div>
                	<h5>Trịnh Tùng Anh</h5>
                </div>
                <div class="review-rate">
                	<div class="rate-star">
                		<span class="active"><i class="fas fa-star"></i></span>
                		<span><i class="fas fa-star"></i></span>
                		<span><i class="fas fa-star"></i></span>
                		<span><i class="fas fa-star"></i></span>
                		<span><i class="fas fa-star"></i></span>
                	</div>
                </div>
                <div class="review-list">
                	<span></span>
                	<div class="ui-radio">
                		<input type="checkbox" name="reason[]" value="Khác với thông tin đăng tuyển">
                		<label for="">Khác với thông tin đăng tuyển</label>
                	</div>
                	<div class="ui-radio">
                		<input type="checkbox" name="reason[]" value="Môi trường độc hại">
                		<label for="">Môi trường độc hại</label>
                	</div>
                	<div class="ui-radio">
                		<input type="checkbox" name="reason[]" value="Đồng nghiệp không thân thiện">
                		<label for="">Đồng nghiệp không thân thiện</label>
                	</div>
                	<div class="ui-radio">
                		<input type="checkbox" name="reason[]" value="Thử việc quá 2 tháng (với chuyên môn)">
                		<label for="">Thử việc quá 2 tháng (với chuyên môn)</label>
                	</div>
                </div>
                <div class="review-note">
                	<span>Nhận xét khác</span>
                	<textarea name="review_content" id="" cols="30" rows="10"></textarea>
                	<input type="hidden" name="review_job_id" value="" />
            		<input type="hidden" name="review_user_id" value="{{ Auth::guard('web')->user()->id }}" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary review-user-submit">Gửi</button>
            </div>
        </div>
    </div>
</div>

<div id="job-management" class="for-applicant">
    <div class="container">
    	<div class="row">
    		<div class="col-md-3">
    			<div class="side-nav sticky">
    				@include('user.partials.user_nav')
    			</div>
    		</div>
    		<div class="col-md-9">
			    <div class="cv-list listing row">
			    	@if($cvs)
			    	@foreach($cvs as $cv)
			    	@if(!empty($cv->job))
			    	<div class="col-md-6">
				    	<div class="job-detail" cv-id="{{ $cv->id }}" jobId="{{ $cv->job['id'] }}">
				            <span class="date">Đăng nộp cv {{ $cv->created_at }}</span>
						    <h3 class="name">{{ $cv->job['job_name'] }}</h3>
						    <a href="/cong-viec/{{ $cv->job['slug'] }}" class="btn toggle">Xem nội dung công việc <i class="fas fa-external-link-alt"></i></a>
						    <div class="applicant-item">
							    <div class="employer-info">
							    	<div class="info">
							    		<div class="profile">
							    			<div class="profile_detail">
										    	<div class="thumbnail" style="background-image: url('{{ $cv->job['employer']['avatar'] }}');"></div>
										    	<div class="txt">
										    		<h5 class="name"><a href="/doanh-nghiep/{{ $cv->job['employer']['company_slug'] }}">{{ $cv->job['employer']['company_name'] }}</a></h5>

										    	</div>
										    </div>
									    </div>
								    </div>
							    </div>
							    <div class="progress">
						            <div class="step status-pass">
						                <div class="icon"><i class="fas fa-file-alt"></i></div><span>Nhận CV</span>
						            </div>
						            <div status="interview" class="step {{ ($cv->active_interview == '0')?'status-ongoing':'' }}{{ ($cv->active_interview == '1')?'status-pass':'' }}{{ ($cv->active_interview == '2')?'status-fail':'' }}">
						                <div class="icon"><i class="fas fa-comments"></i></div><span>Phỏng vấn</span>
						            </div>
						            <!-- <div status="trial_work" class="step {{ ($cv->active_trial_work == '0')?'status-ongoing':'' }}{{ ($cv->active_trial_work == '1')?'status-pass':'' }}{{ ($cv->active_trial_work == '2')?'status-fail':'' }}">
						                <div class="icon"><i class="fas fa-random"></i></div><span>Thử việc</span>
						            </div> -->
						            <div status="work" class="step {{ ($cv->active_work == '0')?'status-ongoing':'' }}{{ ($cv->active_work == '1')?'status-pass':'' }}{{ ($cv->active_work == '2')?'status-fail':'' }}">
						                <div class="icon"><i class="fas fa-handshake"></i></div><span>Làm việc</span>
						            </div>
						            <!-- <div status="complete_work" class="step {{ ($cv->active_complete_work == '0')?'status-ongoing':'' }}{{ ($cv->active_complete_work == '1')?'status-pass':'' }}{{ ($cv->active_complete_work == '2')?'status-fail':'' }}">
						                <div class="icon"><i class="fas fa-check"></i></div><span>Hoàn thành</span>
						            </div> -->
						        </div>
							    <div class="action">
							        <div class="button-group">
						   				@if($cv->active_interview == "0" || $cv->active_trial_work == "0" || $cv->active_work == "0" || $cv->active_complete_work == "0")
						        		<button class="btn btn-primary" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="1">Đồng ý</button>
						        		@if($cv->active_complete_work != "0")
						        		<button class="btn btn-red" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</button>
						        		@endif	
						        		@endif
						            </div>
						            @if($cv->active_interview == "1")
						            <button {{ (!$cv->review)?'disabled':'' }} class="btn btn-primary user-review" data-toggle="modal" data-target="#modal-review" >{{ (!$cv->review)?'Đã đánh giá':'Đánh giá' }}</button>
						            @endif
								    <!-- {{ (!$cv->review)?'Đã đánh giá':'Đánh giá' }} -->
							    </div>
							</div>
				        </div>
				    </div>
			        @endif
			       	@endforeach	
					@endif
			    </div>
			</div>
        </div>
    </div>
</div>
<div class="applicant-panel fixed-right-panel">
    <div class="back applicant-toggle"><i class="fas fa-times"></i> ĐÓNG</div>
    <h1>Phan Đức Chiến</h1>
</div>

<!--  -->

<!-- <div id="page-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
			    
			    <div class="job-content">
			    	<table id="table-user-cvs" class="table table-striped table-bordered">
            			@if($cvs)
						<thead>
							<tr>
							    <th>Tên công việc</th>
							    <th>Phỏng vấn</th>
							    <th>Thử việc</th>
							    <th>Làm việc</th>
							    <th>Hủy công việc</th>
							</tr>
						</thead>
						<tbody tbody-id="{{ Auth::guard('web')->user()->id }}">
						@foreach($cvs as $cv)
						<tr cv-id="{{ $cv->id }}">
							<td><a href="/cong-viec/{{ $cv->job['slug'] }}" target="_blank">{{ $cv->job['job_name'] }}</a></td>
							<td status="interview">
								
								@if($cv->active_interview == "0")
									<a style="color: green!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="1">Đồng ý</a><span> - </span><a style="color: red!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</a>
								@endif
								@if($cv->active_interview == "1")
									<span style="color: green">Đã xác nhận</span>
								@endif
								@if($cv->active_interview == "2")
									<span style="color: red">Từ chối</span>
								@endif
							</td>
							<td status="trial_work">
								
								
								@if($cv->active_trial_work == "0")
									<a style="color: green!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="1">Đồng ý</a><span> - </span><a style="color: red!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</a>
								@endif
								@if($cv->active_trial_work == "1")
									<span style="color: green">Đã xác nhận</span>
								@endif
								@if($cv->active_trial_work == "2")
									<span style="color: red">Từ chối</span>
								@endif
								
							</td>
							<td status="work">
								
								@if($cv->active_work == "0")
									<a style="color: green!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="1">Đồng ý</a><span> - </span><a style="color: red!important;" href="javascript:void(0)" cv-active-href="/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</a>
								@endif
								@if($cv->active_work == "1")
									<span style="color: green">Đã xác nhận</span>
								@endif
								@if($cv->active_work == "2")
									<span style="color: red">Từ chối</span>
								@endif
							</td>
							<td>
								<a style="color: red!important;" href="javascript:void(0)" cv-delete-href="/user/cv/delete/{{ $cv->id }}">Xóa</a>
							</td>
						</tr>
						@endforeach
						</tbody>
						@endif
        			</table>
			    </div>
				
			</div>
		</div>
	</div>
</div> -->
@endsection