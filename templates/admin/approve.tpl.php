<?php ?>

<form>
    <div class="pull-right form-inline">
        <input type="hidden" name="page" value="<?php echo (int) get_request_var('page', 1) ?>"/>
        <input type="text" name="search" value="<?php echo get_request_var('search') ?>" class="form-control" placeholder="tìm chủ đề họp"/>
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</form>
<h4 class="clearfix"></h4>
<form method="post">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="13%">Bắt đầu</th>
                <th width="13%">Kết thúc</th>
                <th width="30%">Chủ đề</th>
                <th width="4%" class="center"><i class="fa fa-signal"></i></th>
                <th width="20%">Đơn vị chủ trì</th>
                <th width="20%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($arr_conf) == 0): ?>
                <tr>
                    <td colspan="6" class="center">Hiện tại chưa có lịch họp</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($arr_conf as $conf): ?>
                <?php
                $start_date = DateTimeEx::create($conf['startTime']);
                $end_date = DateTimeEx::create($conf['finishTime']);
                $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
                $edit_url = site_url('/admin/edit_appointment', array('app_id' => $conf['app_id']));
                $recording_url = site_url('/recording', array('app_id' => $conf['app_id']));
                $online = is_conference_started($conf['confroom_id']);
                $confroom_id = explode('(', $conf['confroom_id']);
                $confroom_id = $confroom_id[0];
                ?>
                <tr>
                    <td>
                        <strong><?php echo $start_date->format('d.m.Y') ?></strong> - <?php echo $start_date->format('H:i') ?>
                    </td>
                    <td>
                        <strong><?php echo $end_date->format('d.m.Y') ?></strong> - <?php echo $end_date->format('H:i') ?>
                    </td>
                    <td>
                        <?php echo $conf['topic'] ?>
                    </td>
                    <td class="center">
                        <?php if ($online): ?>
                            <button type="button" name="btn_stop" value="<?php echo $conf['app_id'] ?>" class="label label-success" title="Click để dừng hội nghị" 
                                    onclick="btn_stop_onclick(this)" data-confroom_id="'<?php echo $confroom_id ?>'">
                                <i class="fa fa-signal"></i>
                            </button>
                        <?php else: ?>
                            <span class="label label-default" title="Chưa khởi động">
                                <i class="fa fa-signal"></i>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $conf['owner_name'] ?>
                    </td>
                    <td>
                        <?php if (!$conf['is_approved']): ?>
                            <button type="submit" name="btn_approve" value="<?php echo $conf['app_id'] ?>" class="btn btn-primary btn-xs"  title="Duyệt">
                                <i class="fa fa-check"></i>
                            </button>
                        <?php endif ?>
                        <?php if ($conf['is_approved']): ?>
                            <button type="submit" name="btn_decline" value="<?php echo $conf['app_id'] ?>" class="btn btn-default btn-xs"  title="Từ chối">
                                <i class="fa fa-close"></i>
                            </button>
                            <a href="<?php echo $join_url ?>" class="btn btn-default btn-xs" title="Tham gia"><i class="fa fa-arrow-circle-right"></i></a>
                        <?php else: ?>
                            <button type="button" class="btn btn-default btn-xs" title="Tham gia" disabled><i class="fa fa-arrow-circle-right"></i></button>
                        <?php endif; ?>
                        <a href="<?php echo $recording_url ?>" class="btn btn-default btn-xs" title="Recording">
                            <i class="fa fa-film"></i>
                        </a>
                        <a href="<?php echo $edit_url ?>" class="btn btn-default btn-xs" title="Sửa">
                            <i class="fa fa-pencil-square"></i>
                        </a>
                        <button type="button" class="btn btn-default btn-xs" data-app_id="<?php echo $conf['app_id'] ?>"
                                data-confroom_id="<?php echo $confroom_id ?>" title="Xóa" onclick="btn_delete_onclick(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pull-right">
        <ul class="pagination">
            <?php
            $page = (int) get_request_var('page', 1);
            $prev = $page == 1 ? 'javascript:;' : site_url('/admin/approve', array('page' => $page - 1, 'search' => get_request_var('search')));
            $next = $page <= $total_page - 1 ? site_url('/admin/approve', array('page' => $page + 1, 'search' => get_request_var('search'))) : 'javascript:;';
            ?>
            <li><a href="<?php echo $prev ?>"><i class="fa fa-long-arrow-left"></i> Trang trước</a></li>
            <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
            <li><a href="<?php echo $next ?>">Trang sau <i class="fa fa-long-arrow-right"></i></a></li>
        </ul>
    </div>
</form>

<!-- Modal -->
<div class="modal" id="modal-processing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    var script_data = {
        videomost_url: '<?php echo VIDEOMOST_URL ?>',
        videomost_acc: '<?php echo VIDEOMOST_ADMIN_ACC ?>',
        videmost_pass: '<?php echo VIDEOMOST_ADMIN_PASS ?>',
        site_url: '<?php echo site_url() ?>'
    };
</script>
<script>
    var proccess_done;
    var $modal = $('#modal-processing');

    $('#modal-processing').on('show.bs.modal', function () {
        $('.modal-body', this).html('<i class="fa fa-spinner"></i> Đang xử lý yêu cầu, vui lòng chờ...');
        $('.btn', this).prop('disabled', true);
    }).on('hide.bs.modal', function (e) {
        if (!proccess_done) {
            e.preventDefault();
        }
    });

    function process_begin() {
        proccess_done = false;
        $('#modal-processing').modal('show');
    }

    function process_done() {
        proccess_done = true;
        $('#modal-processing').modal('hide');
    }

    function process_error() {
        proccess_done = true;
        message = 'Xảy ra lỗi, vui lòng thử lại sau ít phút!';
        $('.modal-body', $modal).html('<span class="red">' + message + '</span>');
        $('.btn', $modal).prop('disabled', false);
    }

    function btn_stop_onclick(btn) {
        if (!confirm('Dừng cuộc họp ngay lập tức?')) {
            return;
        }
        var confroom_id = $(btn).attr('data-confroom_id');

        process_begin();
        stop_conf(confroom_id, success, process_error);

        function success() {
            $(btn).removeClass('label-success')
                    .addClass('label-default')
                    .attr('title', 'Chưa khởi động');
            process_done();
        }
    }

    function btn_delete_onclick(btn) {
        if (!confirm('Xóa cuộc họp và tất cả dữ liệu liên quan?')) {
            return;
        }
        var confroom_id = $(btn).attr('data-confroom_id');
        var app_id = $(btn).attr('data-app_id');

        process_begin();
        stop_conf(confroom_id, stop_success, process_error);

        function stop_success() {
            delete_conf(app_id, function () {
                window.location.reload();
            }, process_error);
        } //fn

    } //fn

    function delete_conf(app_id, delete_success, delete_error) {
        $.ajax({
            type: 'post',
            url: script_data.site_url + 'admin/service/',
            data: {action: 'delete_conference', app_id: app_id},
            success: delete_success,
            error: delete_error
        });
    } //fn

    function stop_conf(confroom_id, success_callback, error_callback) {
        login_videomost(stop);

        function login_videomost(login_success) {
            var login_data = {
                ajax: 1,
                login: script_data.videomost_acc,
                password: script_data.videmost_pass,
                realmname: '',
                rem: 0
            };
            $.ajax({
                cache: false,
                type: 'post',
                data: login_data,
                url: script_data.videomost_url + 'service/join/',
                success: function () {
                    if (login_success)
                        login_success();
                },
                error: function () {
                    console.error('Đăng nhập videomost thất bại');
                    if (error_callback)
                        error_callback();
                }
            });
        }

        function stop() {
            $.ajax({
                type: 'post',
                cache: false,
                dataType: 'json',
                url: script_data.videomost_url + 'service/ext/vmi',
                data: {id: confroom_id, task: 'stopConference'},
                success: function (resp) {
                    if (resp.result == 1)
                        success_callback();
                    else
                        error_callback();
                },
                error: function () {
                    error_callback();
                }
            });
        }
    } //fn
</script>