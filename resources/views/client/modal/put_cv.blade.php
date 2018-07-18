<div class="modal" id="modal-send_cv" tabindex="-1" role="dialog" job-id="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <i class="close fas fa-times" data-dismiss="modal"></i>
            <div class="modal-body" status="cv_check">
                <div class="blk-info type-info">
                    <h4 class="blk-title">Nộp hồ sơ</h4>
                    <div class="listing">
                        <li class="item">
                            <div class="left-obj">
                                <span>Mời nhập email</span>
                            </div>
                            <div class="right-obj">
                                <input name="user-email" type="email" placeholder="Ví dụ: anhtt@gmail.com" @if(!Auth::guard('web')->guest()) readonly="readonly" @endif value="{{ (!Auth::guard('web')->guest())?Auth::guard('web')->user()->email:'' }}">
                            </div>
                        </li>
                    </div>
                </div>
            </div>
            <div class="modal-body hide" status="cv_none">
                <form id="form-reg">
                    <div class="blk-info type-info">
                        <h4 class="blk-title">Thông tin cá nhân</h4>
                        <div class="listing">
                            <li class="item">
                                <div class="left-obj">
                                    <span>Họ & tên</span>
                                </div>
                                <div class="right-obj">
                                    <input name="name" type="text" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Ngày sinh</span>
                                </div>
                                <div class="right-obj">
                                    <input name="birthday" type="text" class="datepicker" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Địa chỉ</span>
                                </div>
                                <div class="right-obj">
                                    <input name="address" type="text" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Email</span>
                                </div>
                                <div class="right-obj">
                                    <input name="email" type="email" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Số điện thoại</span>
                                </div>
                                <div class="right-obj">
                                    <input name="mobile_number" type="text" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Giới tính</span>
                                </div>
                                <div class="right-obj">
                                    <select name="gender" class="standardSelect">
                                        <option value="1">Nam</option>
                                        <option value="2">Nữ</option>
                                    </select>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Mật khẩu</span>
                                </div>
                                <div class="right-obj">
                                    <input name="password" type="password" placeholder="">
                                </div>
                            </li>
                        </div>
                    </div>
                    <div class="blk-info type-info">
                        <h4 class="blk-title">Kỳ vọng công việc</h4>
                        <div class="listing">
                            <li class="item">
                                <div class="left-obj">
                                    <span>Loại công việc</span>
                                </div>
                                <div class="right-obj">
                                    <select name="work_type" class="standardSelect">
                                        <option value="Thời vụ">Thời vụ</option>
                                        <option value="Toàn thời gian">Toàn thời gian</option>
                                        <option value="Bán thời gian">Bán thời gian</option>
                                    </select>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Địa điểm làm việc</span>
                                </div>
                                <div class="right-obj">
                                    <input name="work_address" type="text" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Mức lương</span>
                                </div>
                                <div class="right-obj">
                                    <input name="work_wage" type="text" placeholder="">
                                </div>
                            </li>
                            <li class="item">
                                <div class="left-obj">
                                    <span>Vị trí</span>
                                </div>
                                <div class="right-obj">
                                    <input name="work_position" type="text" placeholder="">
                                </div>
                            </li>
                        </div>
                    </div>
                    @if($tabs->count())
                    @foreach($tabs as $tab)
                    <div class="blk-info">
                        <h4 class="blk-title">{{ $tab->name }}</h4>
                        <input  type="hidden" name="tabs[{{ $tab->id }}][input_type]" value="{{ $tab->input_type }}">
                        <div class="listing row-listing">
                            <table id="option-table-{{ $tab->id }}" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tiều đề</th>
                                        <th>Mô tả</th>
                                        <th>Năm đầu</th>
                                        <th>Năm cuối</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        @if($tab->input_type == "select")
                            <select class="select-row" tab-id="{{ $tab->id }}" data-placeholder="Thêm option">
                                <option value=""></option>
                                @if(!empty($tab->options))
                                @foreach($tab->options as $option)
                                    <option value="{{ $option->id }}" src="{{ $option->icon }}" {{ !empty($option->disabled)?"disabled":"" }}>{{ $option->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm add-row" tab-id="{{ $tab->id }}">Thêm mới</button>
                        @endif
                    </div>
                    @endforeach
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-post-cv">Gửi</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>