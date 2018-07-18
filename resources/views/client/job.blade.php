@extends('layouts.layout_client')
@section('content')
@if(!empty($job))
@section('title',$job->job_name)
<div id="job-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="main">
					<h2 class="name">{{ $job->job_name }}</h2>

					<div class="meta">
						@if($job->job_wage)
						<div class="item job-salary">
							<i class="icon fas fa-coins"></i>
							<div class="txt">
								<span>Mức lương</span>
								<p class="parse-price">{{ $job->job_wage?$job->job_wage:'' }}</p>
							</div>
						</div>
						@endif
						@if($job->job_people)
						<div class="item job-quantity">
							<i class="icon fas fa-users"></i>
							<div class="txt">
								<span>Số lượng cần tuyển</span>
								<p>{{ $job->job_people?$job->job_people:'...' }}</p>
							</div>
						</div>
						@endif
						@if($job->job_address)
						<div class="item">
							<i class="icon fas fa-map-marker-alt"></i>
							<div class="txt">
								<span>Nơi làm việc</span>
								<p>{{ $job->job_address?$job->job_address:'...' }}</p>
								<div id="map-canvas">
									
								</div>
							</div>
						</div>
						@endif
					</div>

					<div class="tags">
						<ul>
							@if($job->work_form)
							<li class="item"><a href=""><i class="fas fa-hashtag"></i> {{ $job->work_form?$job->work_form['name']:'...' }}</a></li>
							@endif
							@if($job->job_type)
							<li class="item"><a href=""><i class="fas fa-hashtag"></i> {{ $job->work_type?$job->work_type['name']:'...' }}</a></li>
							@endif
							@if($job->job_city)
							<li class="item"><a href=""><i class="fas fa-hashtag"></i> {{ $job->job_city?$job->job_city:'...' }}</a></li>
							@endif
						</ul>
					</div>

					<div class="blk-desc">
						<h4>Mô tả công việc</h4>
						<article>
			                 {!! json_decode($job->job_description) !!}
			            </article>
					</div>

					<div class="blk-desc">
						<h4>Quyền lợi</h4>
						@if(!empty($job->job_benefit))
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
						@endif
					</div>

					<div class="blk-desc">
						<h4>Yêu cầu công việc</h4>
						@if(!empty($job->job_request))
		                <article>
		                    {!! json_decode($job->job_request) !!}
		                </article>
			            @endif
					</div>

					
				</div>

				<div class="suggest">
					<h4>{{ count($jobs) }} công việc khác</h4>
					<div class="listing">
						@if(!empty($jobs))
						@foreach($jobs as $_job)
		            	<div class="job-item" onclick="location.href='/cong-viec/{{ $_job->slug }}'">
		            		<div class="detail">
						        <span class="job-name">{{ $_job->job_name }}</span>
						        <div class="meta">
						            <ul>
						                <li class="info-location" data-toggle="tooltip" data-placement="top" title="Địa điểm làm việc"><i class="fas fa-location-arrow"></i> {{ $_job->job_address }}</li>
						                <li class="info-salary" data-toggle="tooltip" data-placement="top" title="Mức lương"><i class="fas fa-coins"></i> <span class="parse-price">{{ $_job->job_wage }}</span></li>
						            </ul> 
						        </div>
						    </div>
		            	</div>
		            	@endforeach
		            	@endif
		            </div>
	            </div>

			</div>
			<div class="col-md-3">
				<div class="company sticky">
	                <div class="logo" style="background-image:url('{{ $job->employer['avatar'] }}');"></div>
	                <h5 class="org_name"><a href="/doanh-nghiep/{{ $job->employer['company_slug'] }}">{{ $job->employer['company_name'] }}</a></h5>
	                <div class="rate-star">
	                	 @for ($i = 1; $i < 6; $i++)
			                <span class="item {{ ($i <= $job->star())?'active':'' }}"><i class="fas fa-star"></i></span>
			            @endfor
	                </div>
	                <div class="intro">
	                	<p>{!! json_decode($job->employer['company_description']) !!}</p>
	                </div>
	                <div class="contact-link">
	                	<ul>
	                		<li>
	                			<a href="{{ $job->employer['company_website'] }}"><i class="icon fas fa-globe-americas"></i> <span>Website</span></a>
	                		</li>
	                		<li>
	                			<a href="{{ $job->employer['company_facebook'] }}"><i class="icon fab fa-facebook"> </i> <span>Fanpage</span></a>
	                		</li>
	                	</ul>
	                </div>
	                
	            </div>
			</div>
		</div>
	</div>
</div>

<div class="action">
					    
</div>
@if (Auth::guest())
<div class="page-form-action">
	<div class="container">
		<button type="button" class="btn btn-primary btn-cv btn-post-cv" job-id="{{ $job->id }}" {{ (!empty($job->cv_active))?'disabled="disabled"':'' }} >{{ (!empty($job->cv_active))?'Bạn đã nộp cv':'Nộp CV' }}</button>
	</div>
</div>
@endif
<script type="text/javascript">
	function initMap() {
	  var myLatLng = {lat: {{ !empty($job->lat)?$job->lat:-25.363 }}, lng:{{ !empty($job->lng)?$job->lng:131.044 }} };

	  var map = new google.maps.Map(document.getElementById('map-canvas'), {
	    zoom: 16,
	    center: myLatLng
	  });

	  var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    //title: 'Hello World!'
	  });
	}
	initMap();
</script>
@include('client.modal.put_cv'); 
@endif
@endsection