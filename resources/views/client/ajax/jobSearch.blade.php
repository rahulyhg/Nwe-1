@if(!empty($jobs))
@foreach($jobs as $job)
@if(empty($employer))
<div class="job-item {{ (!empty($type) && $type=='box')?'':'' }}" job-id="{{ $job->id }}" job-lat="{{ $job->lat }}" job-lng="{{ $job->lng }}" job-wage="{{ $job->job_wage }}" >
    <div class="profile">
        <div class="thumbnail" style="background-image: url('{{ $job->employer['avatar'] }}')"></div>
        <div class="rate-star" >
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
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur dolorum magnam maiores doloremque, maxime facere quisquam.</p> -->
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
@else
<div class="job-item" job-id="{{ $job->id }}" job-lat="{{ $job->lat }}" job-lng="{{ $job->lng }}" job-wage="{{ $job->job_wage }}">
    <!-- <div class="thumbnail" style="background-image: url('{{ $job->employer['avatar'] }}')"></div> -->
    <div class="detail">
        <span class="job-name">{{ $job->job_name }}</span>
        <div class="meta">
            <ul>
                <li class="info-location" data-toggle="tooltip" data-placement="top" title="Địa điểm làm việc"><i class="fas fa-location-arrow"></i> {{ !empty($job->job_address)?$job->job_address:$job->employer['company_address'] }}</li>
                <li class="info-salary" data-toggle="tooltip" data-placement="top" title="Mức lương"><i class="fas fa-coins"></i> <span class="parse-price">{{ $job->job_wage?$job->job_wage:'' }}</span></li>
            </ul> 
        </div>
        <div class="status">
            <ul>
                <li class="total-number" data-toggle="tooltip" data-placement="top" title="Số lượng yêu cầu"><i class="fas fa-users"></i> {{ !empty($job->job_people)?$job->job_people:0 }}</li>
                <li data-toggle="tooltip" data-placement="top" title="Đã nộp CV"><i class="fas fa-file-alt"></i> {{ !empty($job->cv)?count($job->cv):0 }}</li>
                <li data-toggle="tooltip" data-placement="top" title="Đã làm việc"><i class="fas fa-handshake"></i> {{ !empty($job->work_cv)?count($job->work_cv):0 }}</li>
            </ul>
        </div>
    </div>
</div>
@endif
@endforeach
@endif
<input type="hidden" id="totalJob" value="{{ !empty($totalJob)?$totalJob:'' }}">