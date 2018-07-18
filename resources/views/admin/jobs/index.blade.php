@extends('layouts.layout')



@section('title', 'Công việc')

@section('content')

    <div class="breadcrumbs">

        <div class="col-sm-9">

            <div class="page-header float-left">

                <div class="page-title">

                    <h1>Danh sách công việc</h1>

                </div>

            </div>

        </div>
        <div class="col-sm-3">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="{{ url('job/create') }}" class="btn btn-outline-primary btn-sm">Thêm mới</a>&nbsp&nbsp
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
                                        <th>Công việc</th>
                                        <th width="30%">Công ty</th>
                                        <th width="14%">Hồ sơ</th>
                                        <th width="11%">Action</th>
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
            "ajax": "/ajax/jobs",
            "columns": [
                {
                    "data": "thumb",
                    "render": function ( data, type, full ) {

                        return  '<img src="'+data+'" style="max-width: 50px;">';
                    }
                },
                { "data": "job_name" },
                { "data": "company_name" },
//                        { "data": "email" },
//                        { "data": "company_name" },
				{
                    "data": "id",
                    "render": function ( data, type, full ) {

                        return  '<a href="/job/'+data['id']+'/users">Hồ Sơ đăng ký('+data['cv']+')</a>';
                    }
                },
                {
                    "data": "id",
                    "render": function ( data, type, full ) {

                        return  '<a href="/job/duplicate/'+data['id']+'" alt="Copy"><i class="fa fa-copy" style="cursor: pointer;"></i></a>&nbsp&nbsp&nbsp' +
                                '<a href="/job/edit/'+data['id']+'" alt="Edit"><i class="fa fa-edit" style="cursor: pointer;"></i></a>&nbsp&nbsp&nbsp' +
                                '<a href="javascript:void(0)" alt="Delete" job-delete a-href="/job/delete/'+data['id']+'"><i class="fa fa-trash-o"></i></a>';
                    }
                },
            ]
        });
    });

        </script>

@endsection