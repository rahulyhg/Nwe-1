<?php include 'header.php'; ?>

<div id="map">
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
        <div class="listing">
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
            <?php include 'partial-job_item.php'; ?>
        </div>
    </div>
    <div id="map-wrapper">
        
    </div>
</div>

<div class="modal" id="modal-job" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <i class="close fas fa-times" data-dismiss="modal"></i>
            <div class="modal-header">
                <div class="bg" style="background-image:url();"></div>
                <div class="txt">
                    <h5 class="modal-title">Tư Vấn Viên Sale Dịch Vụ Vietnamworks</h5>
                    <div class="company">
                        <div class="logo" style="background-image:url();"></div>
                        <span>Công ty CP 1Pay</span>
                        <div class="rate-star">
                            <span class="active"></span>
                            <span class="active"></span>
                            <span class="active"></span>
                            <span class="active"></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="blk-info type-info">
                    <h4 class="blk-title">Thông tin công việc</h4>
                    <div class="listing">
                        <li class="item">
                            <div class="left-obj">
                                <span>Loại hình công việc</span>
                            </div>
                            <div class="right-obj">
                                <p>Thời vụ</p>
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Nơi làm việc</span>
                            </div>
                            <div class="right-obj">
                                <p>Xã Đàn - Hà Nội</p>
                            </div>
                        </li>
                    </div>
                </div>
                <div class="blk-info no-box">
                    <article>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus consectetur quod sunt quia rem! Recusandae praesentium quos voluptatem molestiae laboriosam aperiam autem debitis maiores.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus unde fugit laudantium sit dolorum itaque totam! Libero autem illo ad, dolores? Mollitia doloremque dolore quibusdam accusamus distinctio soluta inventore repellendus perspiciatis? Blanditiis facere tempore atque animi.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta quo rem numquam ratione voluptatem nesciunt quasi deserunt, totam quas magni reiciendis minima excepturi iusto omnis temporibus assumenda necessitatibus?</p>
                    </article>
                    <div class="blk">
                        <h5>QUYỀN LỢI KHÁC</h5>
                        <article>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi itaque, id quaerat facere nulla aperiam officia quibusdam nostrum tempore, quas numquam sed culpa consequuntur!</p>
                        </article>
                    </div>
                    <div class="blk">
                        <h5>YÊU CẦU CÔNG VIỆC</h5>
                        <article>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempora facilis eos autem tempore corrupti architecto doloribus magni, modi veniam libero voluptas, nihil eaque, ipsum consequatur quidem neque? Cupiditate dolore deserunt sint illo voluptas autem, a! Quo natus impedit ipsum, consequatur reprehenderit recusandae.</p>
                        </article>
                    </div>
                </div>
                <div class="blk-info">
                    <h4 class="blk-title">Yêu cầu bằng cấp</h4>
                    <div class="listing">
                        <?php include 'partial-info-line.php'; ?>
                    </div>
                </div>
                <div class="blk-info">
                    <h4 class="blk-title">Yêu cầu kỹ năng</h4>
                    <div class="listing">
                        <?php include 'partial-info-line.php'; ?>
                        <?php include 'partial-info-line.php'; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-send_cv">Nộp CV</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-send_cv" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <i class="close fas fa-times" data-dismiss="modal"></i>
            <div class="modal-body" status="cv_check">
                <div class="blk-info type-info">
                    <h4 class="blk-title">Kiểm tra CV</h4>
                    <div class="listing">
                        <li class="item">
                            <div class="left-obj">
                                <span>Mời nhập email</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="Ví dụ: anhtt@gmail.com">
                            </div>
                        </li>
                    </div>
                </div>
            </div>
            <div class="modal-body" status="cv_none">
                <div class="blk-info type-info">
                    <h4 class="blk-title">Thông tin cá nhân</h4>
                    <div class="listing">
                        <li class="item">
                            <div class="left-obj">
                                <span>Họ & tên</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Ngày sinh</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Địa chỉ</span>
                            </div>
                            <div class="right-obj">
                                <select name="" id=""></select>
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Email</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Số điện thoại</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
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
                                <select name="" id=""></select>
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Địa điểm làm việc</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Mức lương</span>
                            </div>
                            <div class="right-obj">
                                <input type="text" placeholder="">
                            </div>
                        </li>
                        <li class="item">
                            <div class="left-obj">
                                <span>Vị trí</span>
                            </div>
                            <div class="right-obj">
                                <select name="" id=""></select>
                            </div>
                        </li>
                    </div>
                </div>
                <div class="blk-info">
                    <h4 class="blk-title">Kinh nghiệm</h4>
                    <div class="listing">
                        ...
                    </div>
                    <button class="btn btn-secondary">Thêm mới</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-send_cv">Kiểm tra</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
