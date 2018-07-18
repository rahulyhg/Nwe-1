@extends('layouts.layout')

@section('title', 'Tab')

@section('content')
@if(!empty($tab))
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/tabs') }}">Tabs</a></li>
                        <li>Edit</li>
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
                        <form id="form-horizontal" action="{{ url('/tab/edit/'.$tab->id) }}" method="post" class="form-horizontal">
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
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Model type</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        {{--<input type="text" name="user_type" placeholder="User type" class="form-control">--}}
                                        <select name="user_type[]" data-placeholder="Choose a country..." multiple class="standardSelect">
                                            <option value=""></option>
                                            <option value="employer">Employer</option>
                                            <option value="user">User</option>
                                            <option value="job">Job</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="text-input" class=" form-control-label">Input type</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="input_type" data-placeholder="Choose a country..." class="standardSelect" >
                                            <option value=""></option>
                                            <option value="text">Text</option>
                                            <option value="select">Select</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                  <i class="fa fa-dot-circle-o"></i> Submit
                                </button>
                                <button type="button" onclick="location.href='{{ url('/tabs') }}'" class="btn btn-danger btn-sm">
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
                        remote: {
                            url: "{{ url('tab/validate/name/'.$tab->id) }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    },
                    'input_type': {
                        valueNotEquals: ""
                    }
                    // quoting necessary!
                },
                messages: {
                    name:   {
                        remote: "Tab already exist!"
                    }
                }

            });
        });

        </script>
@endif
@endsection