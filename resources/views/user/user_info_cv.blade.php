@extends('layouts.layout_client')
@section('content')
@if(!empty($user))
@section('title','User')

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-3">
				<div class="side-nav sticky">
					<!-- <ul>
						<li class="active"><a class="scrollTo" href="#user_profile-account">Thông tin tài khoản</a></li>
						<li><a class="scrollTo" href="#user_profile-change_password">Đổi mật khẩu</a></li>
						<li><a class="scrollTo" href="#user_profile-wish">Kỳ vọng công việc</a></li>
						<li><a class="scrollTo" href="#user_profile-experience">Kinh nghiệm</a></li>
						<li><a class="scrollTo" href="#user_profile-skill">Kỹ năng chuyên môn</a></li>
					</ul> -->
					@include('user.partials.user_nav')
				</div>
			</div>
			<div class="col-md-6">
			    <div class="main-content">
			    	<form id="form-update" action="{{ url('/user/info-cv') }}" method="post" enctype="multipart/form-data" autocomplete="off">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					    <div id="user_profile-account" class="section">
					    	<div class="section-title">
					        	<h3 class="blk-title">Thông tin tài khoản</h3>
					        </div>
					        <div class="section-content">
						        <div class="form-group">
						        	<div class="form row">
						                <div class="col-md-12">
						                    <label>Giới tính</label>
						                    <div class="form-content">
								            	<span class="ui-radio"><input type="radio" value="1" name="gender" {{ $user->gender=="1"?"checked":"" }}><label for="">Nam</label></span>
								            	<span class="ui-radio"><input type="radio" value="2" name="gender" {{ $user->gender=="2"?"checked":"" }}><label for="">Nữ</label></span>
											</div>
						                </div>
						            </div>
						            <div class="form row">
							            <div class="col-md-12">
							                <label>Ngày sinh</label>
							                <input name="birthday" type="text" class="form-content datepicker" placeholder="" value="{{ $user->birthday }}">
							                @if ($errors->has('birthday'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('birthday') }}</strong>
			                                    </span>
			                                @endif
							            </div>
						            </div>
						            
						            <div class="form row">
						            	<div class="col-md-12">
							                <label>Địa chỉ</label>
							                <input name="address" type="text" class="form-content" placeholder="" value="{{ $user->address }}">
							                @if ($errors->has('address'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('address') }}</strong>
			                                    </span>
			                                @endif
							            </div>
						            </div>
						        </div>
						    </div>
					    </div>
					    
					    <div id="user_profile-wish" class="section">
					    	<div class="section-title">
					        	<h3 class="">Kỳ vọng công việc</h3>
					        </div>
					        <div class="section-content">
						        <div class="form-group">
							        <div class="form row">
						                <div class="col-md-12">
						                    <label>Hình thức công việc</label>
						                    <div class="form-content">
							            	@if(!empty($work_forms))
                                            @foreach($work_forms as $work_form)
								            	<span class="ui-radio"><input type="radio" name="work_form" value="{{ $work_form->id }}" {{ $user->work_form==$work_form->id?"checked":"" }}><label for="">{{ $work_form->name }}</label></span>
								            	
								            @endforeach
                                            @endif
											</div>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Loại công việc</label>
						                    <select name="work_type" id="" class="form-content standardSelect">
						                    	<option value=""></option>
	                                            @if(!empty($work_types))
	                                            @foreach($work_types as $work_type)
	                                            <option value="{{ $work_type->id }}" {{ ($work_type->id == $user->work_type)?'selected':'' }}>{{ $work_type->name }}</option>
	                                            @endforeach
	                                            @endif   	
						                    </select>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-12">
						                    <label>Địa điểm làm việc</label>
						                    <select name="work_address" id="" class="form-content standardSelect">
						                    	<option value=""></option>
						                    </select>
						                </div>
						            </div>
						            <div class="form row">
						                <div class="col-md-6">
						                    <label>Mức lương</label>
						                    <input name="work_wage" type="text" class="form-content" placeholder="" value="{{ $user->work_wage }}">
						                </div>
						                <div class="col-md-6">
						                    <label>Vị trí</label>
						                    <input name="work_position" type="text" class="form-content" placeholder="" value="{{ $user->work_position }}">
						                </div>
						            </div>
						        </div>
						    </div>
					    </div>
					    @if($tabs->count())
					    @foreach($tabs as $tab)
					    <div class="section">
					    	<div class="section-title">
					        	<h3 class="">{{ $tab->name }}</h3>
					        </div>
					        <input  type="hidden" name="tabs[{{ $tab->id }}][input_type]" value="{{ $tab->input_type }}">
					        <div class="section-content">
						        <div class="form-group">
						        	<div class="form row">
						        		<div class="col-md-12">
						        			<div class="form-content">
									            <table id="option-table-{{ $tab->id }}" class="table table-striped table-bordered">
									                <thead>
									                    <tr>
									                        <th>Tiều đề</th>
									                        <th>Mô tả</th>
									                        <th>Năm đầu</th>
									                        <th>Năm cuối</th>
									                        <th></th>
									                    </tr>
									                </thead>
									                <tbody>
									                @if(!empty($tab->rows))
				                                    @foreach($tab->rows as $row)
				                                    @if(!empty($tab->input_type) && (($tab->input_type == "select" && $row->option) || ($tab->input_type == "text" && !$row->option)))
				                                        <tr>
				                                            @if(!empty($tab->input_type) && $tab->input_type == "text")
				                                            <td>
				                                            	<input type="text" class="form-content" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][name]" class="" value="{{ $row->name }}"></td>
				                                            @endif
				                                            @if(!empty($tab->input_type) && $tab->input_type == "select")
				                                            <td style="display: none">
				                                            	<label>
													        		<input type="hidden" class="form-content" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][option_id]" value="{{ $row->option['id'] }}">
													        	</label>
													        </td>
				                                            <td>
				                                            	<input type="text" disabled class="form-content" value="{{ $row->option['name'] }}"></td>
				                                            @endif
				                                            <td>
				                                            	<input type="text" class="form-content" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][description]" value="{{ $row->description }}"></td>
				        									<td>
				        										<input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_start]" value="{{ $row->date_start }}" class="form-content yearpicker-new"></td>
				        									<td>
				        										<input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_end]" value="{{ $row->date_end }}" class="form-content yearpicker-new"></td>
				        									<td>
				        										<a href="javascript:void(0)" in-data="{{ $tab->input_type }}" a-href="{{ url('/user/row/delete/'.$row->id) }}"><i class="icon fas fa-trash"></i></a></td>
				                                        </tr>
				                                    @endif
				                                    @endforeach
				                                    @endif
									                </tbody>
									            </table>
									            @if($tab->input_type == "select")
										            <select class="form-content select-row" tab-id="{{ $tab->id }}" data-placeholder="Thêm option">
										                <option value=""></option>
										                @if(!empty($tab->options))
										                @foreach($tab->options as $option)
										                    <option value="{{ $option->id }}" src="{{ $option->icon }}" {{ !empty($option->disabled)?"disabled":"" }}>{{ $option->name }}</option>
										                @endforeach
										                @endif
										            </select>
										        @else
										        <button type="button" class="btn btn-smoke add-row" tab-id="{{ $tab->id }}">Thêm mới</button>
										        @endif
										    </div>
									    </div>
									</div>
						        </div>
						    </div>
					    </div>
					    @endforeach
					    @endif
					    <div class="section">
					    	<div class="section-title">
					        	<h3 class="">Mô tả khác</h3>
					        </div>
					        <div class="section-content">
						        <div class="form-group">
							        <div class="form row">
				    					<div class="col-md-12">
					    					<label for=""></label>
					    					<textarea id="editor1" name="description" rows="4" placeholder="" class="form-control">{{ $user->description }}</textarea>
				    					</div>
				    				</div>
						        </div>
						    </div>
					    </div>
					</form>   
			    </div>
				<div class="modal-footer">
            </div>
			</div>
		</div>
	</div>
	<div class="page-form-action">
		<div class="container">
			@if(Session::has('alert'))
	       	{!! Session::get('alert') !!}
	        @endif
			<button class="btn btn-smoke">Huỷ thay đổi</button>
			<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Cập nhật</button>
		</div>
	</div>
</div>

	<script type="text/javascript">
		$(document).ready(function(){
			CKEDITOR.replace( 'editor1' );
		})
    	$.get( "{{ asset('/tiva/js/city.json') }}", function( data ) {
          $.each(data,function(index, value){
            
            if(value.name == "{{ $user->work_address }}"){
                $('select[name="work_address"]').append($('<option>').attr('data-id',index).attr('selected','').attr('value',value.name).text(value.name)); 
            }else{
                $('select[name="work_address"]').append($('<option>').attr('data-id',index).attr('value',value.name).text(value.name)); 
            }
          })
          $('select[name="work_address"]').chosen({
                //disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                //width: "30%"
            });  
        });
    </script>
@endif
@endsection