@extends('layouts.layout_client')
@section('content')
@if(!empty($user))
@section('title',$user->name?$user->name:$user->slug)

<div id="page-detail">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
			    <div class="job-header">
			        @if(!empty($user))
			        <!-- <div class="bg" style="background-image:url('');"></div> -->
			        <img src="{{ $user->avatar }}" alt="" class="bg">
			        <div class="txt">
			            <h5 class="modal-title">{{ $user->name }}</h5>
			           
			        </div>
			        @endif
			    </div>
			    <div class="job-content">
			        @if(!empty($user))
			        <div class="blk-info type-info">
			            <h4 class="blk-title">Thông tin cá nhân</h4>
			            <div class="listing">
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Số điện thoại</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->mobile_number?$user->mobile_number:'...' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Email</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->email?$user->email:'...' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Địa chỉ</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->address?$user->address:'...' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Giới tính</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ ($user->gender == 1)?'Nam':'Nữ' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Ngày sinh</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->birthday?$user->birthday:'...' }}</p>
			                    </div>
			                </li>
			            </div>
			        </div>
			        <div class="blk-info type-info">
			            <h4 class="blk-title">Kỳ vọng công việc</h4>
			            <div class="listing">
			            	@if($user->workform)
                            <li class="item">
                                <div class="left-obj">
                                    <span>Hình thức công việc</span>
                                </div>
                                <div class="right-obj">
                                    <p>{{ $user->workform?$user->workform['name']:'...' }}</p>
                                </div>
                            </li>
                            @endif
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Loại công việc</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->worktype['name']?$user->worktype['name']:'...' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Nơi làm việc</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->work_address?$user->work_address:'...' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Mức lương</span>
			                    </div>
			                    <div class="right-obj">
			                        <p class="parse-price">{{ $user->work_wage?$user->work_wage:'' }}</p>
			                    </div>
			                </li>
			                <li class="item">
			                    <div class="left-obj">
			                        <span>Vị trí</span>
			                    </div>
			                    <div class="right-obj">
			                        <p>{{ $user->work_position?$user->work_position:'...' }}</p>
			                    </div>
			                </li>
			                
			            </div>
			        </div>
			        <!-- <div class="blk-info no-box">
			            <article>
			                 {!! $user->company_description !!}
			            </article>
			            
			        </div> -->
			        @if($tabs)
			        @foreach($tabs as $tab)
			        @if($tab->rows->count())
			        <div class="blk-info">
			            <h4 class="blk-title">Yêu cầu {{ $tab->name }}</h4>
			            <div class="listing">
			            @foreach($tab->rows as $row)
			            @if(!empty($tab->input_type) && (($tab->input_type == "select" && $row->option) || ($tab->input_type == "text" && !$row->option)))
			                <li class="item">
			                @if($tab->input_type == "text")
			                    <!-- <div class="thumbnail" style="background-image: url('{{ $row->icon }}')"></div> -->
			                @endif
			                @if($tab->input_type == "select")
			                    <!-- <div class="thumbnail" style="background-image: url('{{ $row->option['icon'] }}')"></div> -->
			                @endif
			                    <div class="meta">
			                        <h5>{{ ($tab->input_type == "text")?$row->name:"" }}{{ ($tab->input_type == "select")?$row->option['name']:"" }}</h5>
			                        <span>{{ $row->description }}</span>
			                        <p>{{ $row->date_start }}{{ (!empty($row->date_end))?" - ".$row->date_end:"" }}</p>
			                    </div>
			                </li>
			            @endif
			            @endforeach
			            </div>
			        </div>
			        @endif
			        @endforeach
			        @endif
			        @endif
					@if($user->description)
			        <div class="blk-info type-info">
			            <h4 class="blk-title">Mô tả khác</h4>
			            <div class="listing">
			            	
                            <li class="item">
                                <div class="left-obj">
                                    <p>{!! $user->description?$user->description:'...' !!}</p>
                                </div>
              
                            </li>
			            </div>
			        </div>
			         @endif
			    </div>
				
			</div>
		</div>
	</div>
</div>

@endif
@endsection