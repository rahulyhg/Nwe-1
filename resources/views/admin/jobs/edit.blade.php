@extends('layouts.layout')

@section('title', 'Job')

@section('content')
@if(!empty($job))
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/jobs') }}">Danh sách công việc</a></li>
                        <li>Sửa</li>
                    </ol>

                </div>

            </div>

        </div>

    </div>
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <div class="sufee-alert alert with-close alert-{{ $msg }} alert-dismissible fade show">
            <span class="badge badge-pill badge-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</span>
                {{ Session::get('mg-' . $msg) }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
         @endif
    @endforeach
    <div class="content mt-3">

        <div class="animated fadeIn">

            <div class="row">
                <div  class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Sửa</strong>
                        </div>
                        <form id="form-horizontal" action="{{ url('/job/edit/'.$job->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="card-body card-block">
                                <div class="default-tab">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="true">Cơ bản</a>
                                            <a class="nav-item nav-link" id="nav-company-tab" data-toggle="tab" href="#nav-company" role="tab" aria-controls="nav-company" aria-selected="false">Chi tiết</a>
                                            <a class="nav-item nav-link" id="nav-tabs-tab" data-toggle="tab" href="#nav-tabs" role="tab" aria-controls="nav-tabs" aria-selected="false">Chuyên môn</a>
                                            <a class="nav-item nav-link" id="nav-map-tab" data-toggle="tab" href="#nav-map" role="tab" aria-controls="nav-map" aria-selected="false">Map</a>
                                        </div>
                                    </nav>
                                    <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                            <!-- <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Ảnh</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <label>
                                                        <img class="page-feature-icon" src="{{ !empty($job->thumb)?$job->thumb:"/imgs/no_image.jpg" }}" old-src="{{ $job->thumb }}" style="max-width: 100px">
                                                        <input type="file" name="thumb" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                                    </label>
                                                </div>
                                            </div> -->
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Công việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_name" placeholder="Job name" class="form-control" value="{{ $job->job_name }}">
                                                </div>
                                            </div>
                                            @if (Auth::guard('employers')->user()->isAdmin() )
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Sở hữu</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="employer_id" data-placeholder="Tên công ty" class="standardSelect" >
                                                        <option value=""></option>
                                                        @if(!empty($employers))
                                                        @foreach($employers as $employer)
                                                        <option value="{{ $employer->id }}">{{ $employer->company_name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Hiển thị liên kết</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="employer_view" data-placeholder="Tên công ty" class="standardSelect" >
                                                        <option value=""></option>
                                                        @if(!empty($employers))
                                                        @foreach($employers as $employer)
                                                        <option value="{{ $employer->id }}">{{ $employer->company_name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            @endif    
                                        </div>
                                        <div class="tab-pane fade" id="nav-company" role="tabpanel" aria-labelledby="nav-company-tab">

                                            <!-- <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Nhận hồ sơ</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <label class="switch switch-text switch-success switch-pill">
                                                        <input type="checkbox" name="job_status" value="1" class="switch-input" {{ ( $job->job_status == "1")?"checked":"" }} size="lg">
                                                        <span data-on="On" data-off="Off" class="switch-label"></span>
                                                        <span class="switch-handle"></span>
                                                    </label>
                                                </div>
                                            </div> -->
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Hình thức công việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="job_form" data-placeholder="Hình thức công việc" class="standardSelect" >
                                                        <option value=""></option>
                                                        @if(!empty($work_forms))
                                                        @foreach($work_forms as $work_form)
                                                        <option value="{{ $work_form->id }}" {{ ($work_form->id == $job->job_form)?'selected':'' }}>{{ $work_form->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>    
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Loại công việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="job_type" data-placeholder="Loại công việc" class="standardSelect" >
                                                        <option value=""></option>
                                                        @if(!empty($work_types))
                                                        @foreach($work_types as $work_type)
                                                        <option value="{{ $work_type->id }}" {{ ($work_type->id == $job->job_type)?'selected':'' }}>{{ $work_type->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Nơi làm việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_address" placeholder="Nơi làm việc" class="form-control" value="{{ $job->job_address }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Tỉnh / Thành phố</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="job_city" data-placeholder="Tỉnh / Thành phố" >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Thời gian làm việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_time" placeholder="Thời gian làm việc" class="form-control" value="{{ $job->job_time }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Ngày bắt đầu</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_date_start" placeholder="Ngày bắt đầu" class="form-control datepicker" value="{{ $job->job_date_start }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Giới tính</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select name="job_gender" data-placeholder="Giới tính" >
                                                        <option value=""></option>
                                                        <option value="1">Nam</option>
                                                        <option value="2">Nữ</option>
                                                        <option value="3">Nam hoặc nữ</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Độ tuổi</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_age" placeholder="Độ tuổi" class="form-control" value="{{ $job->job_age }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Số người</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_people" placeholder="Số người" class="form-control" value="{{ $job->job_people }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Hạn nhận hồ sơ</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="job_end_cv" placeholder="Hạn nhận hồ sơ" class="form-control datepicker" value="{{ $job->job_end_cv }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Mức lương</label>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <input type="number" name="job_wage" placeholder="Mức lương" class="form-control" value="{{ $job->job_wage }}">
                                                    <span class="print_job_wage">{{ $job->job_wage }}</span>
                                                </div>
                                                <div class="col-12 col-md-7">
                                                     <select name="job_wage_type" id="" class="standardSelect">
                                                        <option value="day" {{ $job->job_wage_type=='day'?'selected':'' }}>/ ngày</option>
                                                        <option value="month" {{ $job->job_wage_type=='month'?'selected':'' }}>/ tháng</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Mô tả</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <textarea id="editor1" name="job_description" rows="4" placeholder="" class="form-control">{{ json_decode($job->job_description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Quyền lợi khác</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <textarea id="editor2" name="job_benefit" rows="4" placeholder="" class="form-control">{{ json_decode($job->job_benefit) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Yêu cầu công việc</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <textarea id="editor3" name="job_request" rows="4" placeholder="" class="form-control">{{ json_decode($job->job_request) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Tiện ích</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                   <select id="selectize" name="utility_ids[]" multiple class="demo-default" placeholder="Chọn...">
                                                        <option value="">Chọn...</option>
                                                    @if($utilities)
                                                    @foreach($utilities as $utility)    
                                                        <option {{ ($job->utility_ids && $job->utility_ids !='null' && in_array($utility->id, json_decode($job->utility_ids)))?'selected':'' }} value="{{ $utility->id }}">{{ $utility->name }}</option>
                                                    @endforeach
                                                    @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-tabs" role="tabpanel" aria-labelledby="nav-tabs-tab">

                                            <div class="tabs-list">
                                            @if(count($tabs))
                                            @foreach($tabs as $tab)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <a href="javascript:void(0)" class="show-hide" status="show">Hide</a>
                                                        <strong>{{ $tab->name }}</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row form-group">
                                                            <div class="col col-md-2">
                                                                <label class="switch switch-text switch-success switch-pill">
                                                                    <input type="checkbox" name="tab_active[]" value="{{ $tab->id }}" class="switch-input" {{ ( count($job->tab_active) > 0 && in_array($tab->id, $job->tab_active))?"checked":"" }} size="lg">
                                                                    <span data-on="On" data-off="Off" class="switch-label"></span>
                                                                    <span class="switch-handle"></span>
                                                                </label>
                                                                <input  type="hidden" name="tab_sort[]" value="{{ $tab->id }}">
                                                                <input  type="hidden" name="tabs[{{ $tab->id }}][input_type]" value="{{ $tab->input_type }}">
                                                            </div>
                                                            @if($tab->input_type == "select")
                                                            <div class="col col-md-3">
                                                                <select class="select-row" tab-id="{{ $tab->id }}" data-placeholder="Thêm option">
                                                                <option value=""></option>
                                                                @if(!empty($tab->options))
                                                                @foreach($tab->options as $option)
                                                                    <option value="{{ $option->id }}" src="{{ $option->icon }}" {{ !empty($option->disabled)?"disabled":"" }}>{{ $option->name }}</option>
                                                                @endforeach
                                                                @endif
                                                                </select>
                                                            </div>
                                                            @else
                                                            <button type="button" class="btn btn-outline-primary btn-sm add-row" tab-id="{{ $tab->id }}">Thêm dòng</button>
                                                            @endif
                                                        </div>
                                                        <table id="tabs-table-{{ $tab->id }}" class="table table-striped table-bordered">
                                                            <thead>

                                                                <tr>
                                                                    <th>Drag</th>
                                                                    <th>Ảnh</th>
                                                                    <th>Tiêu đề</th>
                                                                    <th>Mô tả</th>
                                                                    <th>Năm đầu</th>
                                                                    <th>Năm cuối</th>
                                                                    <th>Action</th>
                                                                </tr>

                                                            </thead>

                                                            <tbody>
                                                            @if(!empty($tab->rows))
                                                            @foreach($tab->rows as $row)
                                                            @if(!empty($tab->input_type) && (($tab->input_type == "select" && $row->option) || ($tab->input_type == "text" && !$row->option)))
                                                                <tr>
                                                                    <td><i class="fa fa-arrows"></i></td>
                                                                    @if(!empty($tab->input_type) && $tab->input_type == "text")
                                                                    <td>
                                                                        <label>
                                                                            <img class="page-feature-icon" src="{{ !empty($row->icon)?$row->icon:"/imgs/no_image.jpg" }}" old-src="{{ $row->icon }}"  style="max-width: 50px">
                                                                            <input type="file" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][icon]" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                                                            </label>
                                                                        </td>
                                                                    <td>
                                                                        <input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][name]" class="form-control" value="{{ $row->name }}">
                                                                    </td>
                                                                    @endif
                                                                    @if(!empty($tab->input_type) && $tab->input_type == "select")
                                                                    <td>
                                                                        <label>
                                                                            <img class="page-feature-icon" src="{{ $row->option['icon'] }}" style="max-width: 50px">
                                                                            <input type="hidden" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][option_id]" value="{{ $row->option['id'] }}">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control" disabled value="{{ $row->option['name'] }}">
                                                                    </td>
                                                                    @endif
                                                                    <td>
                                                                        <input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][description]" class="form-control" value="{{ $row->description }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_start]" class="form-control yearpicker" value="{{ $row->date_start }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_end]" class="form-control yearpicker" value="{{ $row->date_end }}">
                                                                    </td>
                                                                    <td>
                                                                        <a href="javascript:void(0)" in-data="{{ $tab->input_type }}" a-href="{{ url('/row/delete/'.$row->id) }}"><i class="fa fa-trash-o"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @endforeach
                                                            @endif
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-map" role="tabpanel" aria-labelledby="nav-map-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <input id="pac-input" class="controls form-control" type="text" placeholder="Search Box">
                                                    <input type="hidden" name="lat" value="{{ $job->lat }}">
                                                    <input type="hidden" name="lng" value="{{ $job->lng }}">
                                                </div>

                                                <div id="map" class="card-body" style="height: 500px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                  <i class="fa fa-dot-circle-o"></i> Lưu
                                </button>
                                <button type="button" onclick="location.href='{{ url('/jobs') }}'" class="btn btn-danger btn-sm">
                                  <i class="fa fa-ban"></i> Thoát
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <style>
    .hide{
        display: none!important;
    }
    .fa-arrows{
        cursor: -webkit-grab;
    }
    .show-hide{
        float: right;
    }
    #sortable img{
        width: 223px;
        height: 123px;
    }
    #sortable .card:hover .photo-close{
        opacity: 1;
    }
    .photo-close{
        width: 20px;
        height: 20px;
        line-height: 20px;
        background-color: white;
        color: #262626;
        box-shadow: 0 6px 12px 0 rgba(0, 0, 0, 0.75);
        text-align: center;
        position: absolute;
        right: 10px;
        top: 10px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 11;
        transition: all 0.25s cubic-bezier(0.05, 0.88, 0.63, 0.96);
        -webkit-transition: all 0.25s cubic-bezier(0.05, 0.88, 0.63, 0.96);
        opacity: 0;
        transform: scale(1.5,1.5);
        -webkit-transform: scale(1.5,1.5);
    }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace( 'editor1' );
            CKEDITOR.replace( 'editor2' );
            CKEDITOR.replace( 'editor3' );

            $('#selectize').selectize();
            $.get( "{{ asset('/tiva/js/city.json') }}", function( data ) {
              $.each(data,function(index, value){
                
                if(value.name == "{{ $job->job_city }}"){
                    $('select[name="job_city"]').append($('<option>').attr('data-id',index).attr('selected','').attr('value',value.name).text(value.name)); 
                }else{
                    $('select[name="job_city"]').append($('<option>').attr('data-id',index).attr('value',value.name).text(value.name)); 
                }
              })
              $('select[name="job_city"]').chosen({
                    //disable_search_threshold: 10,
                    no_results_text: "Oops, nothing found!",
                    width: "30%"
                });  
            });
            $('select[name="employer_id"]').val("{{ $job->employer_id }}");
            $('select[name="employer_view"]').val("{{ $job->employer_view }}");
            $('select[name="job_gender"]').val("{{ $job->job_gender }}");
            $('select[name="job_gender"]').chosen({
               disable_search_threshold: 10,
               no_results_text: "Oops, nothing found!",
               width: "30%"
           });
            $(".standardSelect").chosen({
                //disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                width: "30%"
            });
            $( ".tabs-list" ).sortable({
                handle:".card-header",
                placeholder: {

                    element: function(currentItem) {
                        var $cur = $(currentItem[0]);
                        return '<div class="card" style="opacity: 0.3;">' +
                                '<div class="card-header">' +
                                    '<strong>'+$cur.find('.card-header strong').text()+'</strong>' +
                                '</div>' +
                              '</div>';
                    },
                     update: function(container, p) {
                        return;
                    }
                },
                sort: function(event, ui) {
                    $('.tabs-list .card-body').hide('slow');
                    $('.tabs-list .card-header .show-hide').attr('status','hide');
                    $('.tabs-list .card-header .show-hide').text('Show');
                }
            });
            $( ".tabs-list" ).disableSelection();
            $("tbody").sortable({
                handle:".fa-arrows",
                placeholder: {

                    element: function(currentItem) {
                        var $cur = $(currentItem[0]);
                        var row = '<tr>';
                                row +='<td></td>';
                                row +='<td><label> ' +
                                '<img class="page-feature-icon" src="'+$cur.find('td:nth-child(2) img').attr('src')+'" style="max-width: 50px"> ' +
                                '</label></td>';
                                row +='<td><input type="text" class="form-control" value="'+$cur.find('td:nth-child(3) input').val()+'"></td>';
                                row +='<td><input type="text"class="form-control" value="'+$cur.find('td:nth-child(4) input').val()+'"></td>>';
                                row +='<td><input type="text" class="form-control" value="'+$cur.find('td:nth-child(5) input').val()+'"></td>';
                                row +='<td><input type="text" class="form-control" value="'+$cur.find('td:nth-child(6) input').val()+'"></td>';
                                row +='<td><a href="javascript:void(0)"><i class="fa fa-trash-o"></i></a></td>'
                                row +='</tr>';
                        return row;
                    },
                     update: function(container, p) {
                        return;
                    }
                }
            });
            $( "#sortable" ).sortable({
                placeholder: {
                    element: function(currentItem) {
                        return '<div class="col-md-3"><div class="card"><img class="card-img-top" src="/imgs/no_image.jpg" alt="Card image cap"> </div></div>';
                    },
                     update: function(container, p) {
                        return;
                    }
                }
                //sort: function(event, ui) { console.log($('.col-highlight').length); }
            });
            $( "#sortable" ).disableSelection();
            $.validator.setDefaults({ ignore: ":hidden:not(select)" })
            $.validator.addMethod("valueNotEquals", function(value, element, arg){
              return arg !== value;
             }, "This select is required.");

            $("#form-horizontal").validate({
                ignore: "",

                rules: {
                // no quoting necessary
                    thumb:{
                        accept: 'image/*',
                        extension: 'jpg|png|ico'
                    },
                    job_name: {
                        required: true
                    },
                    @if (Auth::guard('employers')->user()->isAdmin() )
                    employer_id: {
                        valueNotEquals: ""
                    }
                    @endif
                 // quoting necessary!
                },
                messages: {

                }

            });
        });
        </script>
        <script>

                initAutocomplete();
              function initAutocomplete() {
                var marker;
                var lat = -33.8688;
                var lng = 151.2195;
                if($('input[name="lat"]').val() != "" && $('input[name="lng"]').val()){
                    lat = parseFloat($('input[name="lat"]').val());
                    lng = parseFloat($('input[name="lng"]').val());
                }

                var map = new google.maps.Map(document.getElementById('map'), {
                  center: {lat: lat, lng: lng},
                  zoom: 14,
                  mapTypeId: 'roadmap'
                });

                if($('input[name="lat"]').val() != "" && $('input[name="lng"]').val()){
                    marker = new google.maps.Marker({
                       position: {lat: lat, lng: lng},
                       map: map
                   });
                }
                // Create the search box and link it to the UI element.
                var input = document.getElementById('pac-input');
                var searchBox = new google.maps.places.SearchBox(input);
                //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function() {
                  searchBox.setBounds(map.getBounds());
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    if(marker){
                        marker.setPosition(event.latLng);
                    }else{
                        marker = new google.maps.Marker({
                           position: event.latLng,
                           map: map
                       });
                    }
                    $('input[name="lat"]').val(event.latLng.lat());
                    $('input[name="lng"]').val(event.latLng.lng());

                });



                var markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function() {
                  var places = searchBox.getPlaces();

                  if (places.length == 0) {
                    return;
                  }

                  // Clear out the old markers.
                  markers.forEach(function(marker) {
                    marker.setMap(null);
                  });
                  markers = [];

                  // For each place, get the icon, name and location.
                  var bounds = new google.maps.LatLngBounds();
                  places.forEach(function(place) {
                    if (!place.geometry) {
                      console.log("Returned place contains no geometry");
                      return;
                    }

                    if(marker){
                        marker.setPosition(place.geometry.location);
                    }else{
                        marker = new google.maps.Marker({
                           position: place.geometry.location,
                           map: map
                       });
                    }
                    $('input[name="lat"]').val(place.geometry.location.lat());
                    $('input[name="lng"]').val(place.geometry.location.lng());

                    if (place.geometry.viewport) {
                      // Only geocodes have viewport.
                      bounds.union(place.geometry.viewport);
                    } else {
                      bounds.extend(place.geometry.location);
                    }
                  });
                  map.fitBounds(bounds);
                });
              }

            </script>
@endif
@endsection