@extends('layouts.layout_client')
@section('content')
@if(!empty($user))
@section('title',$user->name)
<style type="text/css">

</style>

<div id="profile-setting">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-3">
				<div class="sidebar vertical-nav">
					<!-- <ul>
						<li class="active"><a class="scrollTo" href="#employer_profile-account">Thông tin công ty</a></li>
						<li><a class="scrollTo" href="#employer_profile-change_password">Đổi mật khẩu</a></li>
					</ul> -->
					@include('user.partials.employer_nav')
				</div>
			</div>
			<div class="col-md-6">
			    <div class="main-content">
			    	<form id="form-update" action="{{ route('client.employer.info_company') }}" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>   	
				    	<div class="section">
				    		<div class="section-title">
				    			<h3>Thông tin Doanh nghiệp</h3>
				    		</div>
				    		<div class="section-content">
				    			<div class="form-group">
				    				<div class="form row">
				    					<div class="col-md-6">
					    					<label for="">Tên DN</label>
					    					<input name="company_name" class="form-content" type="text" placeholder="" value="{{ $user->company_name }}">
					    					@if ($errors->has('company_name'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_name') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    					<div class="col-md-6">
					    					<label for="">Lĩnh vực hoạt động</label>
					    					<input name="company_activity" type="text" class="form-content" placeholder="" value="{{ $user->company_activity }}">
					    					@if ($errors->has('company_activity'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_activity') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    				</div>
				    				<!-- <div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Số điện thoại</label>
					    					<input name="company_mobile" type="text" class="form-content" placeholder="" value="{{ $user->company_mobile }}">
					    					@if ($errors->has('company_mobile'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_mobile') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    				</div> -->
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Địa chỉ</label>
					    					<input name="company_address" type="text" class="form-content" placeholder="" value="{{ $user->company_address }}">
					    					@if ($errors->has('company_address'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('company_address') }}</strong>
			                                    </span>
			                                @endif
				    					</div>
				    				</div>
				    				
				    				<!-- <div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Số nhân viên</label>
					    					<input name="company_employees" type="text" class="form-content" placeholder="" value="{{ $user->company_employees }}">
				    					</div>
				    					
				    				</div> -->
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Quy mô Doanh nghiệp</label>
					    					<textarea id="editor2" name="company_branches" rows="4" placeholder="" class="form-control">{{ $user->company_branches }}</textarea>
				    					</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Mô tả</label>
					    					<textarea id="editor1" name="company_description" rows="4" placeholder="" class="form-control">{{ json_decode($user->company_description) }}</textarea>
				    					</div>
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Link website</label>
					    					<input name="company_website" type="text" class="form-content" placeholder="Link website" value="{{ $user->company_website }}">
				    					</div>
				    					
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Link facebook</label>
					    					<input name="company_facebook" type="text" class="form-content" placeholder="Link facebook" value="{{ $user->company_facebook }}">
				    					</div>
				    					
				    				</div>
				    				<div class="form row">
				    					<div class="col-md-12">
					    					<label for="">Thư viện ảnh</label>
					    					<div class="form-content form-update_gallery">
		                                        <!-- gallery -->
		                                        <div id="sortable" class="" file-output="gallery">
			                                    @if(!empty($user->company_gallery) && $user->company_gallery != "null")
			                                    @foreach(json_decode($user->company_gallery) as $item)
			                                        <div class="card">
			                                            <img class="card-img-top" src="{{ config('app.api_url').$item }}" alt="Card image cap">
			                                            <input type="hidden" name="gallery[]" value="{{ $item }}">
			                                            <i class="photo-close fas fa-trash" employer-id="{{ $user->id }}"></i>
			                                        </div>
			                                    @endforeach
			                                    @endif
			                                    </div>
			                                    <button type="button" class="btn btn-white btn-chosen-image">Tải ảnh lên</button>
	                                        	<input type="file" accept="image/*" multiple employer-id="{{ $user->id }}" file-input="gallery" style="display: none">
			                                </div>
				    					</div>
				    				</div>
				    			</div>
				    		</div>
				    	</div>
				    	<!-- @if($tabs->count())
						@foreach($tabs as $tab)
						<div class="section">
							<div class="section-title">
								<h3>{{ $tab->name }}</h3>
								<input  type="hidden" name="tabs[{{ $tab->id }}][input_type]" value="{{ $tab->input_type }}">
							</div>
							<div class="section-content">
								<div class="form-group">
									<div class="form row">
										<div class="col-md-12">
											<label for=""></label>
											<div class="form-content">
												<table id="option-table-{{ $tab->id }}" class="table table-striped table-bordered">
									                <thead>
									                    <tr>
									                        <th>Tiêu đề</th>
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
				                                            	<input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][name]" class="form-content" value="{{ $row->name }}">
				                                            </td>
				                                            @endif
				                                            @if(!empty($tab->input_type) && $tab->input_type == "select")
				                                            <td style="display: none">
				                                            	<label>
													        		<input type="hidden" class="form-content" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][option_id]" value="{{ $row->option['id'] }}">
													        	</label>
													        </td>
				                                            <td>
				                                            	<input type="text" class="form-content" disabled class="form-content" value="{{ $row->option['name'] }}">
				                                            </td>
				                                            @endif
				                                            <td>
				                                            	<input type="text" class="form-content" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][description]" value="{{ $row->description }}">
				                                            </td>
				        									<td>
				        										<input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_start]" value="{{ $row->date_start }}" class="form-content yearpicker-new">
				        									</td>
				        									<td>
				        										<input type="text" name="tabs[{{ $tab->id }}][rows][{{ $row->id }}][date_end]" value="{{ $row->date_end }}" class="form-content yearpicker-new">
				        									</td>
				        									<td class="action">
				        										<a href="javascript:void(0)" in-data="{{ $tab->input_type }}" a-href="{{ url('/employer/row/delete/'.$row->id) }}"><i class="fas fa-trash"></i></a>
				        									</td>
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
						@endforeach
						@endif -->
					</form>
					<div class="page-form-action">
						<div class="container">
							@if(Session::has('alert'))
					       	{!! Session::get('alert') !!}
					        @endif
							<button class="btn btn-smoke" onclick="document.getElementById('form-update').reset();">Huỷ thay đổi</button>
							<button type="button" onclick="$('#form-update').submit()" class="btn btn-primary">Cập nhật</button>
						</div>
					</div>
			    </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function ($) {
		CKEDITOR.replace( 'editor1' );
		CKEDITOR.replace( 'editor2' );
		$( "#sortable" ).sortable({
	        placeholder: {
	            element: function(currentItem) {
	                return '<div class="card"><img class="card-img-top" src="/imgs/no_image.jpg" alt="Card image cap"> </div>';
	            },
	             update: function(container, p) {
	                return;
	            }
	        }
	        //sort: function(event, ui) { console.log($('.col-highlight').length); }
	    });
	    $( "#sortable" ).disableSelection();
	})
</script>
@endif
@endsection