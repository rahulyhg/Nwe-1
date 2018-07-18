<div class="side-nav sticky">
    <div class="blk blk-profile profile_org">
        <div class="thumbnail" style="background-image:url('{{ Auth::guard('employers')->user()->avatar?Auth::guard('employers')->user()->avatar:'' }}');"></div>
        <div class="txt">
            <span class="profile_name">{{ Auth::guard('employers')->user()->name?Auth::guard('employers')->user()->name:Auth::guard('employers')->user()->mobile_number }}</span>
            <a href="/employer/profile">Cập nhật thông tin</a>
        </div>
    </div>
    <div class="blk blk-cta">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            <i class="fas fa-plus"></i> Đăng việc mới
        </button>
        
        <div class="modal" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Thêm công việc</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body" style="padding: 16px; padding-bottom: 0;">
               <div class="row">
                   <div class="col-lg-5"><label>Tên công việc</label></div>
                   <div class="col-lg-7">
                       <div class="form-group">
                        <input type="text" class="form-control" name="job_name" id="emp-name">
                      </div>
                   </div>
               </div>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                <button type="button" id="cr" class="btn btn-danger" style="background: #4e1058; border:none;">Tạo công việc mới</button>
              </div>
              <script type="text/javascript">
                $(document).ready(function() {
                    $("#myModal").delegate("#cr", "click", function() {
                       $.ajax({
                            'url' : '{{route('empcreate')}}',
                            'type': 'POST',
                            data: {
                                    job_name : $('#emp-name').val(),
                                },
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(data) {
                               console.log(data.message);
                               if(data.status == 1)
                                 window.location="{{url('employer/job/edit')}}/"+data.result;
                               // $("myModal").css('display', 'none');
                              
                            },
                            error:function(err){
                                console.log(err)
                            }
                        });
                    });
                });
              </script>

            </div>
          </div>
        </div>
    </div>
    <div class="blk blk-shortcut">
        <a href="/employer/info-company" class="item {{ Request::is('employer/info-company')?'active':'' }}">
            <i class="icon fas fa-building"></i>
            <span>Thông tin tổ chức</span>
        </a>
        <a href="/employer/jobs" class="item {{ (Request::is('employer/jobs') || Request::is('employer/job/*'))?'active':'' }}">
            <i class="icon fas fa-calendar-alt"></i>
            <span>Quản trị Công việc</span>
        </a>
        <a href="" class="item">
            <i class="icon fas fa-address-book"></i>
            <span>Quản lý Ứng viên</span>
        </a>
    </div>
</div>