@extends('layouts.layout')



@section('title', 'Options')

@section('content')
@if(!empty($tab))
    <div class="breadcrumbs">

        <div class="col-sm-4">

            <div class="page-header float-left">

                <div class="page-title">

                    <ol class="breadcrumb text-right">
                        <li><a href="{{ url('/tabs') }}">Tabs</a></li>
                        <li>Options ({{ $tab->name }})</li>
                    </ol>

                </div>

            </div>

        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="{{ url('/tab/'.$tab->id.'/option/create') }}" class="btn btn-primary">Create option</a>&nbsp&nbsp
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

                                        <th>Icon</th>
                                        <th>Name</th>
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
                    "ajax": "/ajax/tab/{{ $tab->id }}/options",
                    "columns": [
                        {
                            "data": "icon",
                            "render": function ( data, type, full ) {

                                return  '<img src="'+data+'" style="max-width: 50px;">';
                            }
                        },
                        { "data": "name" },
                        {
                            "data": "id",
                            "render": function ( data, type, full ) {

                                return  '<a href="/tab/{{ $tab->id }}/option/edit/'+data+'"><i class="fa fa-edit" style="cursor: pointer;"></i></a>&nbsp&nbsp&nbsp' +
                                        '<a href="javascript:void(0)" option-delete a-href="/option/delete/'+data+'"><i class="fa fa-trash-o"></i></a>';
                            }
                        },
                    ]
                });

            } );

        </script>
@endif
@endsection