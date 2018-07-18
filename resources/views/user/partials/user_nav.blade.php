<div class="blk blk-profile profile_user">
        <div class="thumbnail" style="background-image:url('{{ Auth::guard('web')->user()->avatar?Auth::guard('web')->user()->avatar:'' }}');"></div>
        <div class="txt">
            <span class="profile_name">{{ Auth::guard('web')->user()->name?Auth::guard('web')->user()->name:Auth::guard('web')->user()->mobile_number }}</span>
            <a href="/user/profile">Cập nhật thông tin</a>
        </div>
    </div>
    <div class="blk blk-cta">
        <a href="/user/info-cv" class="btn btn-primary"><i class="fas fa-user-circle"></i> CV của tôi</a>
    </div>
    <div class="blk blk-shortcut">
        <a href="/" class="item">
            <i class="icon fas fa-search"></i>
            <span>Tìm việc làm</span>
        </a>
        <a href="/user/cvs" class="item {{ Request::is('user/cvs')?'active':'' }}">
            <i class="icon fas fa-briefcase"></i>
            <span>Công việc của tôi</span>
        </a>
    </div>