@extends('layouts.layout')

@section('title', 'Utilities')

@section('content')
@if(!empty($tab))
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/utilities') }}">Tiện ích</a></li>
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
                            <strong>Edit</strong>
                        </div>
                        <form id="form-horizontal" action="{{ url('/utilities/edit/'.$tab->id) }}" method="post" class="form-horizontal">
                            <div class="card-body card-block">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Name</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $tab->name }}">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                  <i class="fa fa-dot-circle-o"></i> Submit
                                </button>
                                <button type="button" onclick="location.href='{{ url('/utilities') }}'" class="btn btn-danger btn-sm">
                                  <i class="fa fa-ban"></i> Cancel
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
            $('select[name="user_type[]"]').val({!! ($tab->user_type && $tab->user_type!="null")?$tab->user_type:"" !!});
            $('select[name="input_type"]').val("{{ $tab->input_type }}");
            $(".standardSelect").chosen({
                disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                width: "30%"
            });

            $("#form-horizontal").validate({
                rules: {
                    // no quoting necessary

                    name: {
                        required: true,
                       
                    },
                  
                    // quoting necessary!
                },
                messages: {
                   
                }

            });
        });

        </script>
@endif
@endsection