@extends('layouts.layout_client')
@section('title','Tiva.vn | Mạng việc làm #1 Việt Nam')
@section('content')
<div id="map">
    <div class="container">
        <div class="sidebar">
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
        <div id="map-wrapper">

        </div>
    </div>
</div>
<div class="modal" id="modal-job" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
@include('client.modal.put_cv'); 
@endsection