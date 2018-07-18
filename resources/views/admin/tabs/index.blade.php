@extends('layouts.layout')



@section('title', 'Tabs')

@section('content')

    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">

                    <h1>Tabs</h1>

                </div>

            </div>

        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="{{ url('tab/create') }}" class="btn btn-primary">Create tab</a>&nbsp&nbsp
                        {{--<a href="{{ url('export/users') }}" class="btn btn-primary">Export</a>--}}
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">

        <div class="animated fadeIn">

            <div class="row">



                <div class="col-md-12">

                    <div class="card">

                        {{--<div class="card-header">--}}

                            {{--<strong class="card-title">User list</strong>--}}

                        {{--</div>--}}

                        <div class="card-body">

                            <table id="tabs-table" class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th>Tab</th>
                                        <th>Model type</th>
                                        <th>Input type</th>
                                        <th width="5%">Action</th>
                                    </tr>

                                </thead>

                                <tbody></tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
            var table;
            $(document).ready(function() {

                table = $('#tabs-table').DataTable({
                    //lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "/ajax/tabs",
                    "columns": [
                        { "data": "name" },
                        {
                            "data": "user_type",
                            "render": function ( data, type, full ) {
                                if(data !="null" && data){
                                    data = JSON.parse(data).join(", ");
                                }else{
                                    data = "";
                                }

                                return data;
                            }
                        },
//                          { "data": "user_type" },
                        {
                            "data": "input_type",
                            "render": function ( data, type, full ) {

                                if(data['value'] == "select"){
                                    var optHref = ' - <a href="/tab/'+data['id']+'/options">Options</a>';
                                     return data['value']+optHref;
                                }
                                return data['value'];
                            }
                        },
                        {
                            "data": "id",
                            "render": function ( data, type, full ) {

                                return  '<a href="/tab/edit/'+data+'"><i class="fa fa-edit" style="cursor: pointer;"></i></a>&nbsp&nbsp&nbsp' +
                                        '<a href="javascript:void(0)" tab-delete a-href="/tab/delete/'+data+'"><i class="fa fa-trash-o"></i></a>';
                            }
                        },
                    ]
                });
                
            } );

        </script>

@endsection