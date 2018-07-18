@extends('layouts.layout')

@section('title', 'Options')

@section('content')
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/employers') }}">Danh sách nhà tuyển dụng</a></li>

                        <li>Thêm mới</li>
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
                            <strong>Thêm mới</strong>
                        </div>
                        <form id="form-horizontal" action="{{ url('/employer/create') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="card-body card-block">

                                <div class="tab-content pl-3 pt-2" id="nav-tabContent">

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Ảnh đại diện</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <label>
                                                <img class="page-feature-icon" src="/imgs/no_image.jpg" style="max-width: 100px">
                                                <input type="file" name="avatar" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Họ và tên</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Số điện thoại</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="mobile_number" placeholder="Số điện thoại" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Email</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="email" placeholder="Email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Chức vụ</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="position" placeholder="Chức vụ" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="text-input" class=" form-control-label">Công ty</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="company_name" placeholder="Công ty" class="form-control">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                  <i class="fa fa-dot-circle-o"></i> Lưu
                                </button>
                                <button type="button" onclick="location.href='{{ url('/employers') }}'" class="btn btn-danger btn-sm">
                                  <i class="fa fa-ban"></i> Thoát
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
//            jQuery.validator.addMethod("noSpace", function(value, element) {
//              return value.indexOf(" ") < 0 && value != "";
//            }, "No space please and don't leave it empty");
            $.validator.setDefaults({ ignore: ":hidden:not(select)" })
            $.validator.addMethod("valueNotEquals", function(value, element, arg){
              return arg !== value;
             }, "This select is required.");

            $(".standardSelect").chosen({
                disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                width: "100%"
            });

            $("#form-horizontal").validate({
                rules: {
                    // no quoting necessary
                    avatar:{
                        required: true,
                        accept: 'image/*',
                        extension: 'jpg|png|ico'
                    },
                    mobile_number: {
                        required: true,
                        remote: {
                            url: "{{ url('employer/validate/mobile_number') }}",
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
                            url: "{{ url('employer/validate/email') }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    },
                    company_name:{
                        required: true,
                        remote: {
                            url: "{{ url('employer/validate/company_name') }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    }

                    // quoting necessary!
                },
                messages: {
                    mobile_number:   {
                        remote: "Mobile already exist!"
                    },
                    email:   {
                        remote: "Email already exist!"
                    },
                    company_name:   {
                        remote: "Company name already exist!"
                    }
                }

            });
        });

        </script>
@endsection