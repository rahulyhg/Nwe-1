@extends('layouts.layout')

@section('title', 'Tab')

@section('content')
@if(!empty($employer))
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">
            
                <ol class="breadcrumb text-right">
                 @if (Auth::guard('employers')->user()->isAdmin() )
                    <li><a href="{{ url('/employers') }}">Danh sách nhà tuyển dụng</a></li>
                    <li>Sửa</li>
                 @else
                     <li>Profile</li>
                 @endif       
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
                        <form id="form-horizontal" action="{{ url('/employer/edit/'.$employer->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="card-body card-block">
                                <div class="default-tab">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="true">Cá nhân</a>
                                            <a class="nav-item nav-link" id="nav-company-tab" data-toggle="tab" href="#nav-company" role="tab" aria-controls="nav-company" aria-selected="false">Công ty</a>
                                            <a class="nav-item nav-link" id="nav-tabs-tab" data-toggle="tab" href="#nav-tabs" role="tab" aria-controls="nav-tabs" aria-selected="false">Chuyên môn</a>

                                        </div>
                                    </nav>
                                    <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Ảnh đại diện</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <label>
                                                        <img class="page-feature-icon" src="{{ $employer->avatar }}" old-src="{{ $employer->avatar }}" style="max-width: 100px">
                                                        <input type="file" name="avatar" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Họ và tên</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="name" placeholder="Họ và tên" class="form-control" value="{{ $employer->name }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Số điện thoại</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="mobile_number" placeholder="Số điện thoại" class="form-control" value="{{ $employer->mobile_number }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Email</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ $employer->email }}">
                                                </div>
                                            </div>
                                            @if (!Auth::guard('employers')->user()->isAdmin() )
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Password</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="password" id="password" name="password" placeholder="Password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Confirm password</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="password" name="confirm_password" placeholder="Confirm password" class="form-control">
                                                </div>
                                            </div>
                                            @endif
                                           <!--  <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Chức vụ</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="position" placeholder="Chức vụ" class="form-control" value="{{ $employer->position }}">
                                                </div>
                                            </div> -->
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Công ty</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_name" placeholder="Công ty" class="form-control" value="{{ $employer->company_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-company" role="tabpanel" aria-labelledby="nav-company-tab">

                                           <!--  <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Số điện thoại</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_mobile" placeholder="Số điện thoại" class="form-control" value="{{ $employer->company_mobile }}">
                                                </div>
                                            </div> -->
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Địa chỉ</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_address" placeholder="Địa chỉ" class="form-control" value="{{ $employer->company_address }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Lĩnh vực hoạt động</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_activity" placeholder="Lĩnh vực hoạt động" class="form-control" value="{{ $employer->company_activity }}">
                                                </div>
                                            </div>
                                            <!-- <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Nhân viên</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_employees" placeholder="Nhân viên" class="form-control" value="{{ $employer->company_employees }}">
                                                </div>
                                            </div> -->
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Quy mô Doanh nghiệp</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <textarea id="editor2" name="company_branches" rows="4" placeholder="" class="form-control">{{ $employer->company_branches }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Mô tả</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <textarea id="editor1" name="company_description" rows="4" placeholder="" class="form-control">{{ json_decode($employer->company_description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Website</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_website" placeholder="" class="form-control" value="{{ $employer->company_website }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Facebook</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" name="company_facebook" placeholder="" class="form-control" value="{{ $employer->company_facebook }}">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="text-input" class=" form-control-label">Thư viện ảnh</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <label>
                                                        <button type="button" class="btn btn-outline-primary btn-sm btn-chosen-image">Tải ảnh lên</button>
                                                        <input type="file" accept="image/*" multiple employer-id="{{ $employer->id }}" file-input="gallery" style="display: none">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2"></div>
                                                <div id="sortable" class="col col-md-10" file-output="gallery">
                                                @if(!empty($employer->company_gallery) && $employer->company_gallery != "null")
                                                @foreach(json_decode($employer->company_gallery) as $item)
                                                    <div class="col-md-3">
                                                        <div class="card">
                                                            <img class="card-img-top" src="{{ config('app.api_url').$item }}" alt="Card image cap">
                                                            <input type="hidden" name="gallery[]" value="{{ $item }}">
                                                            <i class="photo-close fa fa-trash-o" employer-id="{{ $employer->id }}"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
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
                                                                    <input type="checkbox" name="tab_active[]" value="{{ $tab->id }}" class="switch-input" {{ ( count($employer->tab_active) > 0 && in_array($tab->id, $employer->tab_active))?"checked":"" }} size="lg">
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
                                                                    <th>Tiều đề</th>
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
                                                                            <input type="hidden" select-option="{{ $tab->id }}" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][option_id]" value="{{ $row->option['id'] }}">
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
                                                                        <a href="javascript:void(0)" in-data a-href="{{ url('/row/delete/'.$row->id) }}"><i class="fa fa-trash-o"></i></a>
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

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                  <i class="fa fa-dot-circle-o"></i> Lưu
                                </button>
                                @if (Auth::guard('employers')->user()->isAdmin() )
                                <button type="button" onclick="location.href='{{ url('/employers') }}'" class="btn btn-danger btn-sm">
                                  <i class="fa fa-ban"></i> Thoát
                                </button>
                                @endif
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
                    mobile_number: {
                        required: true,
                        remote: {
                            url: "{{ url('employer/validate/mobile_number/'.$employer->id) }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "{{ url('employer/validate/email/'.$employer->id) }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    },
                    company_name:{
                        required: true,
                        remote: {
                            url: "{{ url('employer/validate/company_name/'.$employer->id) }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    },
                    @if (!Auth::guard('employers')->user()->isAdmin() )
                    password: {
                        minlength: 6
                    },
                    confirm_password: {
                        minlength: 6,
                        equalTo: "#password"
                    },
                    @endif
                    // quoting necessary!
                },
                messages: {
                    mobile_number:   {
                        remote: "Mobile already exist!"
                    },
                    email:  {
                        remote: "Email already exist!"
                    },
                    company_name:   {
                        remote: "Company name already exist!"
                    }
                }

            });
        });

        </script>
@endif
@endsection