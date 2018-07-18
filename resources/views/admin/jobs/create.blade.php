@extends('layouts.layout')

@section('title', 'Job')

@section('content')
    
    <div class="breadcrumbs">

        <div class="col-sm-12">

            <div class="page-header float-left">

                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/jobs') }}">Danh sách công việc</a></li>
                        <li>Thêm mới</li>
                    </ol>
                    

                </div>

            </div>

        </div>

    </div>



    <div class="content mt-3">

        <div class="animated fadeIn">

            <div class="row">
                <div  class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Thêm mới</strong>
                        </div>
                        <form id="form-horizontal" action="{{ url('job/create') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="card-body card-block">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Ảnh</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <label>
                                            <img class="page-feature-icon" src="/imgs/no_image.jpg" style="max-width: 100px">
                                            <input type="file" name="thumb" accept=".jpg,.png,.ico|image/*" class="chosen-image" style="opacity: 0;position: absolute">
                                        </label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Công việc</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" name="job_name" placeholder="Công việc" class="form-control">
                                    </div>
                                </div>
                                @if (Auth::guard('employers')->user()->isAdmin() )
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Công ty</label>
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

    <script type="text/javascript">

            $(document).ready(function() {
                $(".standardSelect").chosen({
                    //disable_search_threshold: 10,
                    no_results_text: "Oops, nothing found!",
                    width: "30%"
                });
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

@endsection