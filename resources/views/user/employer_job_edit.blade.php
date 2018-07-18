@extends('layouts.layout_client')
@section('content')
@if(!empty($job))
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px!important;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-3">
				<div class="sidebar vertical-nav">
					@include('user.partials.employer_nav')
				</div>
			</div>
			<div class="col-md-6">
				<div class="main-content">
			    	<form id="form-update" action="{{ url('/employer/job/edit/'.$job->id) }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					    <div id="" class="section">
					    	<div class="section-title">
					        	<h3 class="blk-title">Chỉnh sửa công việc </h3>
					        </div>
					        <div class="section-content">
					        	<div class="form-group">
						        	<div class="form row">
						                <div class="col-md-12">
						                    <label>Tên công việc</label>
						                    <input name="job_name" type="text" class="form-content" placeholder="" value="{{ $job->job_name}}">
						                    @if ($errors->has('job_name'))
		                                    <span class="help-block">
		                                        <strong>{{ $errors->first('job_name') }}</strong>
		                                    </span>
		                                    @endif
						                </div>
						            </div>
						           <!--  <div class="form row">
						                <div class="col-md-12">
						                    <label>Nhận hồ sơ</label>
						                    <label class="switch">
												<input type="checkbox" name="job_status" value="1" {{ ( $job->job_status == "1")?"checked":"" }}>
												<span class="slider round"></span>
											</label>
						                </div>
						            </div> -->
						            <div class="form row">
						                <div class="col-md-6">
						                    <label>Hình thức công việc</label>
						                    <select name="job_form" id="" class="selectpicker form-content" data-live-search="true" data-max-options="5" data-size="5">
												<!-- <option value=""></option> -->
												@if(!empty($work_forms))
												@foreach($work_forms as $work_form)
												<option value="{{ $work_form->id }}" {{ ($work_form->id == $job->job_form)?'selected':'' }}>{{ $work_form->name }}</option>
												@endforeach
												@endif
											</select>
						                </div>
						                <div class="col-md-6">
						                    <label>Loại công việc</label>
						                    <select name="job_type" id="" class="selectpicker form-content" data-live-search="true" data-max-options="5" data-size="5">
												<!-- <option value=""></option> -->
												@if(!empty($work_types))
												@foreach($work_types as $work_type)
												<option value="{{ $work_type->id }}" {{ ($work_type->id == $job->job_type)?'selected':'' }}>{{ $work_type->name }}</option>
												@endforeach
												@endif   	
											</select>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-6">
						                    <label>Số lượng</label>
						                    <input name="job_people" class="form-content" type="text" placeholder="" value="{{ $job->job_people }}">
						                </div>
										<div class="col-md-6">
						                    <label>Mức lương</label>
						                    <input name="job_wage" class="form-content" type="text" placeholder="" value="{{ $job->job_wage }}">
					                    	<span class="print_job_wage" >{{ $job->job_wage }}</span>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-8 form-group">
						                    <label>Nơi làm việc</label>
						                    <input name="job_address" class="form-content" type="text" placeholder="" value="{{ $job->job_address }}">
						                </div>
						                <div class="col-md-4 form-group">
						                    <label>Tỉnh / Thành phố</label>
						                    <select name="job_city" id="" data-placeholder="Tỉnh / Thành phố" data-live-search="true" data-max-options="5" data-size="5"  class="selectpicker form-control">
						                    	<!-- <option value=""></option> -->
						                    </select>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <div class="form_get_location">
				                                <div class="search-form">
				                                    <input id="pac-input" class="controls form-control" type="text" placeholder="Nhập địa điểm">
				                                    <input type="hidden" name="lat" value="{{ $job->lat }}">
				                                    <input type="hidden" name="lng" value="{{ $job->lng }}">
				                                </div>
			                                	<div id="map" class=""></div>
		                            		</div>
						                </div>
						            </div>
						            <!-- <div class="form row">
						                <div class="col-md-6">
						                    <label>Thời gian làm việc</label>
						                    <input name="job_time" class="form-content" type="text" placeholder="" value="{{ $job->job_time }}">
						                </div>
						                <div class="col-md-6">
						                    <label>Ngày bắt đầu</label>
						                    <input name="job_date_start" class="form-content" type="text" class="datepicker" placeholder="" value="{{ $job->job_date_start }}">
						                </div>
						            </div> -->
						            <!-- <div class="form row">
						                <div class="col-md-6">
						                    <label>Giới tính</label>
						                    <select name="job_gender" data-placeholder="Giới tính" class="selectpicker form-content">
	                                            <option value=""></option>
	                                            <option value="1">Nam</option>
	                                            <option value="2">Nữ</option>
	                                            <option value="3">Nam hoặc nữ</option>
	                                        </select>
						                </div>
						                <div class="col-md-6">
						                    <label>Độ tuổi</label>
						                    <input name="job_age" class="form-content" type="text" placeholder="" value="{{ $job->job_age }}">
						                </div>
						            </div> -->
						            
						            <!-- <div class="form row">
						                <div class="col-md-12">
						                    <label>Hạn nhận hồ sơ</label>
						                    <input name="job_end_cv" class="form-content" type="text" placeholder="" readonly="readonly" value="{{ $job->job_end_cv }}">
						                </div>
						            </div> -->
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Mô tả</label>
						                    <textarea id="editor1" name="job_description" rows="4" placeholder="" class="form-content form-control">{{ json_decode($job->job_description) }}</textarea>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Quyền lợi</label>
						                    <select id="selectize" name="utility_ids[]" multiple class="demo-default" placeholder="Tiện ích">
		                                        <option value="">Tiện ích</option>
		                                        @if($utilities)
		                                        @foreach($utilities as $utility)    
		                                            <option {{ ($job->utility_ids && $job->utility_ids !='null' && in_array($utility->id, json_decode($job->utility_ids)))?'selected':'' }} value="{{ $utility->id }}">{{ $utility->name }}</option>
		                                        @endforeach
		                                        @endif
		                                    </select>
		                                </div>
		                            </div>
		                            <div class="form row">
		                            	<div class="col-md-12">
		                            		<label for="">Quyền lợi khác</label>
		                            		<textarea id="editor2" name="job_benefit" rows="4" placeholder="" class="form-content form-control">{{ json_decode($job->job_benefit) }}</textarea>
		                            	</div>
		                            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Yêu cầu công việc</label>
						                    <textarea id="editor3" name="job_request" rows="4" placeholder="" class="form-content form-control">{{ json_decode($job->job_request) }}</textarea>
						                </div>
						            </div>
						        </div>
					        </div>
					    </div>
					</form>
				</div>
			</div>

			<div class="page-form-action">
				<div class="container">
					@if(Session::has('alert'))
			       	{!! Session::get('alert') !!}
			        @endif
					<button class="btn btn-smoke">Huỷ thay đổi</button>
					<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary btn-update-cv">Cập nhật</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function($){
	CKEDITOR.replace( 'editor1' );
    CKEDITOR.replace( 'editor2' );
    CKEDITOR.replace( 'editor3' );
    $('#selectize').selectize();
	$.get( "{{ asset('/tiva/js/city.json') }}", function( data ) {
      $.each(data,function(index, value){
        
        if(value.name == "{{ $job->job_city }}"){
            $('select[name="job_city"]').append($('<option>').attr('data-id',index).attr('selected','').attr('value',value.name).text(value.name)); 
        }else{
            $('select[name="job_city"]').append($('<option>').attr('data-id',index).attr('value',value.name).text(value.name)); 
        }
      })
     })  
  	// $('select[name="job_city"]').chosen({
   //          //disable_search_threshold: 10,
   //          no_results_text: "Oops, nothing found!",
   //          //width: "30%"
   //      });  
   //  });
	$('select[name="job_gender"]').val("{{ $job->job_gender }}");
	$('select[name="job_gender"]').chosen({
       disable_search_threshold: 10,
       no_results_text: "Oops, nothing found!",
      // width: "30%"
   });
    $('select[name="job_wage_type"]').chosen({
       disable_search_threshold: 10,
       no_results_text: "Oops, nothing found!",
      // width: "30%"
   });
	$('[name="job_end_cv"]').datepicker({
		format: "dd/mm/yyyy",
	    startDate: new Date(),
	});
});    
</script>
<script>

    initAutocomplete();
  function initAutocomplete() {
    var marker;
    var lat = -33.8688;
    var lng = 151.2195;
    if($('input[name="lat"]').val() != "" && $('input[name="lng"]').val()){
        lat = parseFloat($('input[name="lat"]').val());
        lng = parseFloat($('input[name="lng"]').val());
    }

    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: lat, lng: lng},
      zoom: 14,
      mapTypeId: 'roadmap'
    });

    if($('input[name="lat"]').val() != "" && $('input[name="lng"]').val()){
        marker = new google.maps.Marker({
           position: {lat: lat, lng: lng},
           map: map
       });
    }
    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    google.maps.event.addListener(map, 'click', function(event) {
        if(marker){
            marker.setPosition(event.latLng);
        }else{
            marker = new google.maps.Marker({
               position: event.latLng,
               map: map
           });
        }
        $('input[name="lat"]').val(event.latLng.lat());
        $('input[name="lng"]').val(event.latLng.lng());

    });



    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      // Clear out the old markers.
      markers.forEach(function(marker) {
        marker.setMap(null);
      });
      markers = [];

      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }

        if(marker){
            marker.setPosition(place.geometry.location);
        }else{
            marker = new google.maps.Marker({
               position: place.geometry.location,
               map: map
           });
        }
        $('input[name="lat"]').val(place.geometry.location.lat());
        $('input[name="lng"]').val(place.geometry.location.lng());

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
  }

</script>
@endif
@endsection