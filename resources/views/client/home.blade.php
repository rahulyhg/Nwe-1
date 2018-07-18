 @extends('layouts.layout_client')
@section('title','Tiva.vn | Mạng việc làm #1 Việt Nam')
@section('content')

<div id="lookup">
    
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="side-nav sticky">
                    <div class="inner">
                        @if(!Auth::guard('web')->guest())
                        <div class="blk blk-profile profile_user">
                            <div class="thumbnail" style="background-image:url('{{ Auth::guard('web')->user()->avatar?Auth::guard('web')->user()->avatar:'' }}');"></div>
                            <div class="txt">
                                <span class="profile_name">{{ Auth::guard('web')->user()->name?Auth::guard('web')->user()->name:Auth::guard('web')->user()->mobile_number }}</span>
                                <a href="/user/profile">Cập nhật thông tin</a>
                            </div>
                        </div>
                        <div class="blk blk-cta">
                            <a href="/user/info-cv" class="btn btn-primary"><i class="fas fa-user-circle"></i> CV của tôi</a>
                        </div>
                        @elseif(!Auth::guard('employers')->guest())
                        <div class="blk blk-profile profile_org">
                            <div class="thumbnail" style="background-image:url('{{ Auth::guard('employers')->user()->avatar?Auth::guard('employers')->user()->avatar:'' }}');"></div>
                            <div class="txt">
                                <span class="profile_name">{{ Auth::guard('employers')->user()->name?Auth::guard('employers')->user()->name:Auth::guard('employers')->user()->mobile_number }}</span>
                                <a href="/employer/info-company">Cập nhật thông tin</a>
                            </div>
                        </div>
                        <div class="blk blk-cta">
                            <a href="/employer/job/create" class="btn btn-primary"><i class="fas fa-user-circle"></i> Đăng việc mới</a>
                        </div>
                        @else
                        @endif
                        <div class="blk blk-lookup active">
                            <div id="global-search" class="form-group active">
                                <div class="title">
                                    <h5><i class="fas fa-search"></i> Xin mời nhập tiêu chí công việc ?</h5>
                                </div>
                                <div class="form-wrapper">
                                    <div class="form-inner">
                                        <!-- <div class="form form-radio">
                                            @if(!empty($work_forms))
                                            @foreach($work_forms as $work_form)
                                            <span class="item ui-radio"><input type="radio" name="job_form" value="{{ $work_form->id }}"><label for="">{{ $work_form->name }}</label></span>
                                            @endforeach
                                            @endif
                                        </div> -->
                                        <div class="form" style="display: none">
                                            <label for="">Hình thức</label>
                                            <div class="form-content">
                                                <select name="job_form" class="selectpicker" title="--- Chọn ---">
                                                    @if(!empty($work_forms))
                                                    @foreach($work_forms as $work_form)
                                                    <option value="{{ $work_form->id }}">{{ $work_form->name }}</option>
                                                    @endforeach
                                                    @endif      
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form">
                                            <label for="">Nhập từ khóa công việc</label>
                                            <div class="form-group">
                                               <input type="text" id="job_search" class="form-control" name="job_search" placeholder="Nhập từ khóa">
                                            </div>
                                        </div>
                                        <div class="form">
                                            <label for="">Loại công việc</label>
                                            <div class="form-content">
                                                <!-- <select name="job_type" class="selectpicker" multiple  data-live-search="true" data-max-options="5" data-size="5" title="--- Chọn ---">
                                                    @if(!empty($work_types))
                                                    @foreach($work_types as $work_type)
                                                    <option value="{{ $work_type->id }}">{{ $work_type->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>  -->
                                                <select id="job-type" name="job_type" multiple class="demo-default" placeholder="Loại công việc">
                                                <option value="">Tiện ích</option>
                                                @if(!empty($work_types))
                                                @foreach($work_types as $work_type)
                                                    <option value="{{ $work_type->id }}">{{ $work_type->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>       
                                            </div>
                                        </div>
                                        
                                        <div class="form">
                                            <label for="">Địa điểm làm việc</label>
                                            <div class="form-content">
                                                <select name="job_city" class="selectpicker" data-live-search="true" data-size="5" title="--- Chọn ---">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form">
                                            <label for="">Mức lương</label>
                                            <div class="form-content">
                                                <!-- <b>0</b> -->
                                                <input id="ex2" name="job_wage" type="text" class="span2" value="" data-slider-min="0" data-slider-max="50000000" data-slider-step="100000" data-slider-value="[0,50000000]"/>
                                                <!-- <b>50.000.000</b> -->
                                                <!-- <select name="job_wage_type" class="form-input" style="width:30%; flex:0 0 auto;">
                                                    <option value="day">/ ngày</option>
                                                    <option value="month">/ tháng</option>
                                                </select> -->
                                            </div>
                                        </div>
                                        <div class="form">
                                            <label for=""> </label>
                                            <div class="form-content">
                                                <button class="btn btn-primary btn-search">Tìm việc</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!Auth::guard('web')->guest())
                        <div class="blk blk-shortcut">
                            <a href="/user/cvs" class="item">
                                <i class="icon fas fa-briefcase"></i>
                                <span>Công việc của tôi</span>
                            </a>
                        </div>
                        @elseif(!Auth::guard('employers')->guest())
                        <div class="blk blk-shortcut">
                            <a href="{{ route('client.employer.info_company') }}" class="item">
                                <i class="icon fas fa-building"></i>
                                <span>Về {{  Auth::guard('employers')->user()->company_name }}</span>
                            </a>
                            <a href="/employer/jobs" class="item">
                                <i class="icon fas fa-calendar-alt"></i>
                                <span>Quản trị Công việc</span>
                            </a>
                            <a href="" class="item">
                                <i class="icon fas fa-address-book"></i>
                                <span>Quản lý Ứng viên</span>
                            </a>
                        </div>
                        @else
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="result">
                    <h5 class="title hide">Có Công việc phù hợp</h5>
                    <ul class="jobs-listing">
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div id="map-wrapper"></div>
            </div>
        </div>
    </div>
</div>
<button class="load-map" style="display: none">Tai lai</button>
<div class="modal" id="modal-job" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function($) {
        $('#job-type').selectize({
            maxItems: 5
        });
    })
</script>
@include('client.modal.put_cv') 
@endsection