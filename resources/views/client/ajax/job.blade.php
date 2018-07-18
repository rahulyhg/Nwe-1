<!-- <i class="close fas fa-times" data-dismiss="modal"></i> -->
<div class="inner">
    <div class="job-header modal-header">
        @if(!empty($job))
        <!-- <div class="bg" style="background-image:url('');"></div> -->
        <!-- <img src="{{ $job->thumb }}" alt="" class="bg"> -->
        <div class="txt">
            <div class="company">
                <div class="logo" style="background-image:url('{{ $job->employer['avatar'] }}');"></div>
                <span><a href="/doanh-nghiep/{{ $job->employer['company_slug'] }}" target="_blank">{{ $job->employer['company_name'] }}</a></span>
                <div class="rate-star">
                    <span class="active"><i class="fas fa-star"></i></span>
                    <span class="active"><i class="fas fa-star"></i></span>
                    <span class="active"><i class="fas fa-star"></i></span>
                    <span class="active"><i class="fas fa-star"></i></span>
                    <span><i class="fas fa-star"></i></span>
                </div>
            </div>
            <h5 class="modal-title"><a href="/cong-viec/{{ $job->slug }}" target="_blank">{{ $job->job_name }}</a></h5>
            
            <span class="job_address">{{ $job->job_address?$job->job_address:'' }}</span>
            
            @if($job->job_wage)
            <div class="salary">
                <span>Mức lương</span>
                <h4 class="parse-price">{{ $job->job_wage?$job->job_wage:'' }}</h4>
            </div>
            @endif
        </div>
        @endif
    </div>
    <div class="job-content modal-body">
        @if(!empty($job))
        <div class="blk-info type-flex">
                        <!-- <h4 class="blk-title">Thông tin công việc</h4> -->
                        <div class="listing">
                            @if($job->work_form)
                            <li class="item">
                                <div class="left-obj">
                                    <span>Hình thức</span>
                                </div>
                                <div class="right-obj">
                                    <p>{{ $job->work_form?$job->work_form['name']:'...' }}</p>
                                </div>
                            </li>
                            @endif
                            @if($job->job_type)
                            <li class="item">
                                <div class="left-obj">
                                    <span>Loại hình</span>
                                </div>
                                <div class="right-obj">
                                    <p>{{ $job->work_type?$job->work_type['name']:'...' }}</p>
                                </div>
                            </li>
                            @endif
                            @if($job->job_city)
                            <!-- <li class="item">
                                <div class="left-obj">
                                    <span>Tỉnh/Thành Phố</span>
                                </div>
                                <div class="right-obj">
                                    <p>{{ $job->job_city?$job->job_city:'...' }}</p>
                                </div>
                            </li> -->
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
                                    <span>Số lượng</span>
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
                            
                        </div>
                    </div>
        <div class="blk-info no-box">
            <div class="blk">
                <h5>MÔ TẢ CÔNG VIỆC</h5>
                <article>
                    {!! json_decode($job->job_description) !!}
                </article>    
            </div>
            @if(!empty($job->job_benefit))
            <div class="blk">
                <h5>QUYỀN LỢI</h5>
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
            @endif
            @if(!empty($job->job_request))
            <div class="blk">
                <h5>YÊU CẦU</h5>
                <article>
                    {!! json_decode($job->job_request) !!}
                </article>
            </div>
            @endif
        </div>
        @if($tabs)
        @foreach($tabs as $tab)
        @if($tab->rows->count())
        <!-- <div class="blk-info">
            <h4 class="blk-title">Yêu cầu {{ $tab->name }}</h4>
            <div class="listing">
            @foreach($tab->rows as $row)
            @if(!empty($tab->input_type) && (($tab->input_type == "select" && $row->option) || ($tab->input_type == "text" && !$row->option)))
                <li class="item">
                @if($tab->input_type == "text")
                    <div class="thumbnail" style="background-image: url('{{ $row->icon }}')"></div>
                @endif
                @if($tab->input_type == "select")
                    <div class="thumbnail" style="background-image: url('{{ $row->option['icon'] }}')"></div>
                @endif
                    <div class="meta">
                        <h5>{{ ($tab->input_type == "text")?$row->name:"" }}{{ ($tab->input_type == "select")?$row->option['name']:"" }}</h5>
                        <span>{{ $row->description }}</span>
                        <p>{{ $row->date_start }}{{ (!empty($row->date_end))?" - ".$row->date_end:"" }}</p>
                    </div>
                </li>
            @endif
            @endforeach
            </div>
        </div> -->
        @endif
        @endforeach
        @endif
        @endif
    </div>
</div>
<div class="job-action modal-footer">
    @if(!empty($job) && Auth::guest()) 
    <button type="button" class="btn btn-primary btn-post-cv" job-id="{{ $job->id }}" {{ (!empty($job->cv_active))?'disabled="disabled"':'' }} >{{ (!empty($job->cv_active))?'Bạn đã nộp cv':'Nộp CV' }}</button>
    @endif
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
</div>
<script type="text/javascript">
    window.history.pushState({}, '', '/cong-viec/{{ $job->slug }}');
</script>