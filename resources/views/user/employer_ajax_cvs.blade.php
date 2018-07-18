@if(!empty($job))	
	<!-- modal review -->

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
	                		<input type="checkbox" name="reason[]" value="Không đến phỏng vấn">
	                		<label for="">Không đến phỏng vấn</label>
	                	</div>
	                	<div class="ui-radio">
	                		<input type="checkbox" name="reason[]" value="Không đi làm">
	                		<label for="">Không đi làm</label>
	                	</div>
	                	<div class="ui-radio">
	                		<input type="checkbox" name="reason[]" value="Nghỉ không báo trước">
	                		<label for="">Nghỉ không báo trước</label>
	                	</div>
	                	<div class="ui-radio">
	                		<input type="checkbox" name="reason[]" value="Thái độ khiếm nhã">
	                		<label for="">Thái độ khiếm nhã</label>
	                	</div>
	                	<div class="ui-radio">
	                		<input type="checkbox" name="reason[]" value="Không đủ năng lực">
	                		<label for="">Không đủ năng lực</label>
	                	</div>
	                </div>
	                <div class="review-note">
	                	<span>Nhận xét khác</span>
	                	<textarea name="review_content" id="" cols="30" rows="10"></textarea>
	                	<input type="hidden" name="review_job_id" value="{{ $job->id }}" />
                		<input type="hidden" name="review_user_id" value="" />
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-primary review-submit">Gửi</button>
	            </div>
	        </div>
	    </div>
	</div>

	<span class="date">Đăng ngày {{ $job->created_at }}</span>
    <h3 class="name">{{ (!empty($job->employerView) && $job->employerView['id'] != $job->employer['id'] && Auth::guard('employers')->user()->isAdmin())?$job->employerView['company_name'].': ':'' }}{{ $job->job_name }}</h3>
    <div class="action">
    	<a href="/employer/job/duplicate/{{ $id }}"><i class="fas fa-copy"></i> Sao chép</a>
        <a href="/employer/job/edit/{{ $id }}"><i class="fas fa-pencil-alt"></i> Sửa</a>
        <a href="javascript:void(0)" job-delete="{{ $id }}" ><i class="fas fa-trash-alt"></i> Xoá</a>
    </div>
    <div class="meta">
        
    </div>
@endif    
    <ul class="nav nav-tabs" id="job-info" role="tablist" style="">
        <li class="nav-item active">
            <a class="nav-link active" data-toggle="tab" href="#job-description" role="tab" aria-expanded="true">Thông tin công việc</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#job-applicants" role="tab" aria-controls="home" aria-expanded="false">Danh sách Ứng tuyển</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="job-description" role="tabpanel" aria-expanded="true">
        	<div class="blk-info type-info">
	            <div class="blk-content">
		            <div class="listing">
		            	@if($job->work_form)
                        <li class="item">
                            <div class="left-obj">
                                <span>Hình thức công việc</span>
                            </div>
                            <div class="right-obj">
                                <p>{{ $job->work_form?$job->work_form['name']:'...' }}</p>
                            </div>
                        </li>
                        @endif
		                @if($job->job_type)
		                <li class="item">
		                    <div class="left-obj">
		                        <span>Loại hình công việc</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->work_type?$job->work_type['name']:'...' }}</p>
		                    </div>
		                </li>
		                @endif
		                @if($job->job_address)
		                <li class="item">
		                    <div class="left-obj">
		                        <span>Nơi làm việc</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_address?$job->job_address:'...' }}</p>
		                    </div>
		                </li>
		                @endif
		                @if($job->job_city)
                        <li class="item">
                            <div class="left-obj">
                                <span>Tỉnh/Thành Phố</span>
                            </div>
                            <div class="right-obj">
                                <p>{{ $job->job_city?$job->job_city:'...' }}</p>
                            </div>
                        </li>
                        @endif
		                @if($job->job_time)
		                <!-- <li class="item">
		                    <div class="left-obj">
		                        <span>Thời gian làm việc</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_time?$job->job_time:'...' }}</p>
		                    </div>
		                </li> -->
		                @endif
		                @if($job->job_date_start)
		                <!-- <li class="item">
		                    <div class="left-obj">
		                        <span>Ngày bắt đầu</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_date_start?$job->job_date_start:'...' }}</p>
		                    </div>
		                </li> -->
		                @endif
		                @if($job->job_gender)
		                <!-- <li class="item">
		                    <div class="left-obj">
		                        <span>Giới tính</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ ($job->job_gender == 3)?'Nam hoặc nữ':'' }}{{ ($job->job_gender == 1)?'Nam':'' }}{{ ( $job->job_gender == 2)?'Nữ':'' }}</p>
		                    </div>
		                </li> -->
		                @endif
		                @if($job->job_age)
		                <!-- <li class="item">
		                    <div class="left-obj">
		                        <span>Độ tuổi</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_age?$job->job_age:'...' }}</p>
		                    </div>
		                </li> -->
		                @endif
		                @if($job->job_people)
		                <li class="item">
		                    <div class="left-obj">
		                        <span>Số người</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_people?$job->job_people:'...' }}</p>
		                    </div>
		                </li>
		                @endif
		                @if($job->job_end_cv)
		                <!-- <li class="item">
		                    <div class="left-obj">
		                        <span>Hạn nộp hồ sơ</span>
		                    </div>
		                    <div class="right-obj">
		                        <p>{{ $job->job_end_cv?$job->job_end_cv:'...' }}</p>
		                    </div>
		                </li> -->
		                @endif
		                @if($job->job_wage)
		                <li class="item">
		                    <div class="left-obj">
		                        <span>Mức lương</span>
		                    </div>
		                    <div class="right-obj">
		                        <p class="parse-price">{{ $job->job_wage?$job->job_wage:'' }}</p>
		                    </div>
		                </li>
		                @endif
		            </div>
		        </div>
	        </div>
	        <div class="blk-info">
	        	<h4 class="blk-title">Mô tả công việc</h4>
	        	<div class="blk-content">
	        		<div class="blk">
			            <article>
			                 {!! json_decode($job->job_description) !!}
			            </article>
			            
			        </div>
			    </div>
			</div>
			@if(!empty($job->job_benefit))
			<div class="blk-info">
	        	<h4 class="blk-title">Quyền lợi</h4>
	        	<div class="blk-content">
	        		@if(!empty($utilities))
	        		<div class="utility-list">
	        			<ul>
	                    @foreach($utilities as $utility)
                            <li class="item"><i class="fas fa-check-circle"></i> {{ $utility->name }}</li>
                        @endforeach
	                    </ul>
	                </div>
	        		@endif
	                <article>
	                    {!! json_decode($job->job_benefit) !!}
	                </article>
	            </div>
	        </div>
			@endif
            @if(!empty($job->job_request))
            <div class="blk-info">
                <h4 class="blk-title">Yêu cầu công việc</h4>
                <div class="blk-content">
	                <article>
	                    {!! json_decode($job->job_request) !!}
	                </article>
	            </div>
            </div>
            @endif
		</div>
		<div class="tab-pane fade" id="job-applicants" role="tabpanel" aria-expanded="false">
			
			<ul class="nav nav-tabs" id="applicant-type" role="tablist" style="">
		        <li class="nav-item active">
		            <a class="nav-link active" nav-type="all" data-toggle="tab"  href="" role="tab" aria-expanded="true">Tất cả</a>
		        </li>
		        <li class="nav-item">
		            <a class="nav-link" nav-type="pending" data-toggle="tab" href="" role="tab" aria-expanded="false">Đã nộp CV</a>
		        </li>
		        <li class="nav-item">
		            <a class="nav-link" nav-type="interview" data-toggle="tab" href="" role="tab" aria-expanded="false">Mời PV</a>
		        </li>
		        <li class="nav-item">
		            <a class="nav-link" nav-type="work" data-toggle="tab" href="" role="tab" aria-expanded="false">Làm việc</a>
		        </li>
		        <li class="nav-item">
		            <a class="nav-link" nav-type="false" data-toggle="tab" href="" role="tab" aria-expanded="false">Loại</a>
		        </li>
		    </ul>

		    <div class="tab-content">
        		<div class="tab-pane fade active in" id="applicant_type-all" role="tabpanel" aria-expanded="true">
        			@if($cvs)
					@foreach($cvs as $cv)
				    <div class="applicant-item" applicant-id="{{ $cv->id }}" applicant-status="{{ $cv->status }}" applicant-user="{{ $cv->user['id'] }}">
				        <div class="info">
				        	<div class="profile">
					        	<span class="applied-date"><i class="fas fa-clock"></i> {{ $cv->created_at }}</span>
					        	<div class="profile_detail">
						            <div class="thumbnail" style="background-image:url('{{ $cv->user['avatar'] }}');"></div>
						            <div class="txt">
						                <h5 class="name">{{ $cv->user['name'] }}</h5>
						                <a class="applicant-toggle view-more" href="/ho-so/{{ $cv->user['slug'] }}">Hồ sơ Ứng viên</a>
						            </div>
						        </div>
					        </div>
					        <div class="contact-boxes">
					        	<span class="btn toggle">Liên lạc Ứng viên</span>
					        	<div class="reveal">
					        		<ul>
					        			<li><i class="fas fa-phone"></i> <a href="tel:{{ $cv->user['mobile_number'] }}">{{ $cv->user['mobile_number'] }}</a></li>
					        			<li><i class="fas fa-envelope"></i> <a href="mailTo:{{ $cv->user['email'] }}">{{ $cv->user['email'] }}</a></li>
					        			<!-- <li><i class="fab fa-facebook"></i> <a href="">Liên kết</a></li> -->
					        		</ul>
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
				        	@if($cv->status == "pending" && $cv->active_interview != '2')
				        		<button class="btn btn-primary" cv-update-href="/cv/update-status/{{ $cv->id }}" cv-update-status="interview">Mời phỏng vấn</button>
				        		<button class="btn btn-red" cv-active-href="/employer/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</button>
				    		@endif
				    		@if($cv->status == "interview" && $cv->active_interview == "1" && $cv->active_trial_work != '2')
				    			<!-- <button class="btn btn-primary" cv-update-href="/cv/update-status/{{ $cv->id }}" cv-update-status="trial_work">Mời thử việc</button>
				    			<button class="btn btn-red" cv-active-href="/employer/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</button> -->
							@endif
							@if($cv->status == "interview" && $cv->active_interview == "1" && $cv->active_work != '2')
				    			<button class="btn btn-primary" cv-update-href="/cv/update-status/{{ $cv->id }}" cv-update-status="work">Mời làm việc</button>
								<button class="btn btn-red" cv-active-href="/employer/cv/active-status/{{ $cv->id }}" cv-update-active="2">Từ chối</button>
							@endif

				            @if($cv->status == "work" && $cv->active_work == "1" && $cv->active_complete_work != '2')
				    			<!-- <button class="btn btn-primary" cv-update-href="/cv/update-status/{{ $cv->id }}" cv-update-status="complete_work">Hoàn thành</button>
				    			<button class="btn btn-red" cv-active-href="/employer/cv/active-status/{{ $cv->id }}" cv-update-active="2">Chưa hoàn thành</button> -->
							@endif
							@if($cv->active_interview == "1")
							<button  {{ (!$cv->review)?'disabled':'' }} class="btn btn-primary" data-toggle="modal" data-target="#modal-review" >{{ (!$cv->review)?'Đã đánh giá':'Đánh giá' }}</button>
				            </div>
				            @endif
				        </div>
				    </div>
				    @endforeach
					@endif
        		</div>
        		<!-- <div class="tab-pane fade" id="applicant_type-new" role="tabpanel" aria-expanded="false">
        			
        		</div> -->
        	</div>
		</div>
    </div>
            

