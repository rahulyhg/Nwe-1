@extends('layouts.layout_client')
@section('content')
@if(!empty($employer))
@section('title',$employer->company_name)

<div id="employer-detail" class="user-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
		        @if(!empty($employer))
		        <!-- <div class="bg" style="background-image:url('');"></div> -->
		        <div class="avatar" style="background-image:url({{ $employer->avatar }});"></div>
		        <h2 class="name">{{ $employer->company_name?$employer->company_name:'...' }}</h2>
		        <span class="address">{{ $employer->company_address?$employer->company_address:'...' }}</span>
		        <div class="links">
		        	<a href="{{ $employer->company_website?$employer->company_website:'#' }}"><i class="fas fa-globe-asia"></i></a>
		        	<a href="{{ $employer->company_facebook?$employer->company_facebook:'#' }}"><i class="fab fa-facebook"></i></a>
		        </div>
		        <!-- <div class="tags">
					{{ $employer->company_activity }}
		        	<ul>
		        		<li class="item"><a href=""><i class="fas fa-hashtag"></i> Công nghệ thông tin</a></li>
		        		<li class="item"><a href=""><i class="fas fa-hashtag"></i> Nhiếp Ảnh</a></li>
		        		<li class="item"><a href=""><i class="fas fa-hashtag"></i> Thiết kế</a></li>
		        		<li class="item"><a href=""><i class="fas fa-hashtag"></i> Quảng cáo Trực tuyến</a></li>
		        	</ul> 
		        </div>-->
		        <div class="gallery">
		        	@if(!empty($employer->company_gallery))
			        @foreach(json_decode($employer->company_gallery) as $img)
			        	<a href="{{ config('app.api_url').$img }}" data-fancybox="gallery" class="item">
			        		<img src="{{ config('app.api_url').$img }}">
			        	</a>
					@endforeach
			        @endif
			    </div>
		        <div class="blk-desc">
		        	<i class="symbol fas fa-book-open"></i>
		        	<h5 class="blk-title">Giới thiệu</h5>
			        <article>
		                 {!! json_decode($employer->company_description) !!}
		            </article>
		        </div>
		        <div class="blk-desc">
		        	<i class="symbol fas fa-book-open"></i>
		        	<h5 class="blk-title">Lĩnh vực</h5>
			        <article>
		                 {{ $employer->company_activity }}
		            </article>
		        </div>
		        <div class="blk-desc">
		        	<i class="symbol fas fa-sitemap"></i>
		        	<h5 class="blk-title">Quy mô</h5>
			        <article>
		                 {!! $employer->company_branches?$employer->company_branches:'...' !!}
		            </article>
		        </div>
		        <div class="blk-desc">
		        	<i class="symbol fas fa-tasks"></i>
		        	<h5 class="blk-title">Danh sách công việc</h5>
			        <div class="carousel">
			        @if(!empty($jobs))	
			        @foreach($jobs as $job)
			        	<div class="job-item " job-id="{{ $job->id }}" job-lat="{{ $job->lat }}" job-lng="{{ $job->lng }}" job-wage="{{ $job->job_wage }}">
						    <div class="profile">
						        <div class="thumbnail" style="background-image: url('{{ $job->employer['avatar'] }}')"></div>
						        <div class="rate-star">
						            @for ($i = 1; $i < 6; $i++)
						                <span class="item {{ ($i <= $job->star())?'active':'' }}"><i class="fas fa-star"></i></span>
						            @endfor
						        </div>
						        <span class="point">{{ $job->star() }}</span>
						    </div>
						    <div class="detail">
						        <span class="job-owner"><i class="fas fa-building"></i> {{ $job->employer['company_name'] }}</span>
						        <span class="job-name">{{ $job->job_name }}</span>
						        <div class="meta">
						            <ul>
						                <li class="info-location" data-toggle="tooltip" data-placement="top" title="Địa điểm làm việc"><i class="fas fa-location-arrow"></i> {{ !empty($job->job_address)?$job->job_address:'' }}</li>
						                <li class="info-job_form" data-toggle="tooltip" data-placement="top" title="Hình thức làm việc"><i class="fas fa-briefcase"></i> <span>{{ $job->work_form?$job->work_form['name']:'...' }}</span></li>
						                <li class="info-salary " data-toggle="tooltip" data-placement="top" title="Mức lương"><i class="fas fa-coins"></i> <span class="parse-price">{{ $job->job_wage?$job->job_wage:'' }}</span></li>
						            </ul>   
						        </div>
						        <div class="desc">
						            <p>{!! str_limit(strip_tags(json_decode($job->job_description)), $limit = 150, $end = '...') !!}</p>
						        </div>
						        <div class="status">
						            <ul>
						                <li class="total-number" data-toggle="tooltip" data-placement="top" title="Số lượng yêu cầu"><i class="fas fa-users"></i> {{ !empty($job->job_people)?$job->job_people:0 }}</li>
						                <li data-toggle="tooltip" data-placement="top" title="Đã nộp CV"><i class="fas fa-file-alt"></i> {{ !empty($job->cv)?count($job->cv):0 }}</li>
						                <li data-toggle="tooltip" data-placement="top" title="Đã làm việc"><i class="fas fa-handshake"></i> {{ !empty($job->work_cv)?count($job->work_cv):0 }}</li>
						            </ul>
						        </div>
						        <div class="action">
						            <span class="btn btn-primary">Ứng tuyển ngay</span>
						        </div>
						    </div>
						</div>
					@endforeach
				   	@endif    
				    </div>
		        </div>
		        @endif
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-job" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
@endif
@endsection