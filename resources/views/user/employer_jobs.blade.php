@extends('layouts.layout_client')
@section('title','Tiva.vn | Mạng việc làm #1 Việt Nam')
@section('content')
<div id="job-management" class="for-employer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                    @include('user.partials.employer_nav')
            </div>
            <div class="col-md-4">
                <div class="job_listing sticky">
                    <div class="search-panel">
                        <div class="bar">
                            <div class="search">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Tìm kiếm">
                            </div>
                            <div class="filter-toggle">
                                <i class="fas fa-filter"></i>
                            </div>
                        </div>
                        <div class="advanced">

                        </div>
                    </div>
                    <div class="listing jobs-listing">
                        
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="job-detail">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="applicant-panel fixed-right-panel">
    <div class="back applicant-toggle"><i class="fas fa-times"></i> ĐÓNG</div>
    <h1>Phan Đức Chiến</h1>
</div>
<div class="modal" id="modal-job" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection