@extends('layouts.layout')



@section('title', 'Công việc')

@section('content')

    <div class="breadcrumbs">

        <div class="col-sm-9">

            <div class="page-header float-left">

                <div class="page-title">

                    <h1>Hồ sơ đăng ký ({{ $job->job_name }})</h1>

                </div>

            </div>

        </div>
        <div class="col-sm-3">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        {{--<a href="{{ url('job/create') }}" class="btn btn-outline-primary btn-sm">Thêm mới</a>&nbsp&nbsp--}}
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

                            <table id="jobs-table" class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th>Ảnh</th>
                                        <th>Họ và tên</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>View</th>
                                        <th width="5%">Action</th>
                                    </tr>

                                </thead>

                                <tbody>
                                </tbody>

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

            table = $('#jobs-table').DataTable({
            //lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": "/job/{{ $job->id }}/users",
            "columns": [
                {
                    "data": "avatar",
                    "render": function ( data, type, full ) {

                        return  '<img src="'+data+'" style="max-width: 50px;">';
                    }
                },
                { "data": "name" },
                { "data": "mobile_number" },
                { "data": "email" },
//                        { "data": "company_name" },
                {
                    "data": "slug",
                    "render": function ( data, type, full ) {

                        return '<a href="/ho-so/'+data+'" target="_blank">View</a>';
                    }
                },
                {
                    "data": "id",
                    "render": function ( data, type, full ) {

                        return '<a href="javascript:void(0)" user-delete a-href="/user/delete/'+data+'"><i class="fa fa-trash-o"></i></a>';
                    }
                },
            ]
        });
    });

        </script>

@endsection