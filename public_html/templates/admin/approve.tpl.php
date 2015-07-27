
<form>
    <div class="pull-left">
<!--        <a href="<?php echo site_url('/admin/edit_appointment', array('app_id' => 0)) ?>" data-toggle="modal" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm mới
        </a>-->
    </div>
    <div class="pull-right form-inline">
        <input type="hidden" name="page" value="<?php echo (int) get_request_var('page', 1) ?>"/>
        <input type="text" name="search" value="<?php echo get_request_var('search') ?>" 
               class="form-control" placeholder="chủ đề, chủ trì"/>
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</form>
<h4 class="clearfix"></h4>
<form method="post">
    <table class="table table-bordered">
        <colgroup>
            <col width="13%">
            <col widh="13%">
            <col width="30%">
            <col width="4%">
            <col width="4%">
            <col width="20%">
            <col width="16%">
        </colgroup>
        <thead>
            <tr>
                <th >Bắt đầu</th>
                <th >Kết thúc</th>
                <th >Chủ đề</th>
                <th class="center"><i class="fa fa-signal"></i></th>
                <th class="center"><i class="fa fa-check-circle"></i></th>
                <th >Đơn vị chủ trì</th>
                <th>Hành động</th>
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
                $start_date = DateTimeEx::create($conf['startTime'])->addHour(7);
                $end_date = DateTimeEx::create($conf['finishTime'])->addHour(7);
                $now = DateTimeEx::create();
                $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
                $edit_url = site_url('/admin/edit_appointment', array('app_id' => $conf['app_id']));
                $recording_url = site_url('/recording', array('app_id' => $conf['app_id']));
                $online = is_conference_started($conf['confroom_id']);
                $confroom_id = explode('(', $conf['confroom_id']);
                $confroom_id = $confroom_id[0];
                $line_through = $conf['is_deleted'] ? 'line-through' : '';
                ?>
                <tr class="<?php echo $line_through ?>">
                    <td>
                        <strong><?php echo $start_date->format('d.m.Y') ?></strong> - <?php echo $start_date->format('H:i') ?>
                    </td>
                    <td>
                        <strong><?php echo $end_date->format('d.m.Y') ?></strong> - <?php echo $end_date->format('H:i') ?>
                    </td>
                    <td title="<?php echo $conf['topic'] ?>">
                        <?php echo mb_strlen($conf['topic'], 'UTF-8') > 40 ? mb_substr($conf['topic'], 0, 40, 'UTF-8') . '...' : $conf['topic'] ?>
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
                    <td class="center">
                        <?php
                        switch ($conf['is_approved'])
                        {
                            case "1":
                                echo '<span class="label label-success" title="Đã duyệt"><i class="fa fa-check"></i></span>';
                                break;
                            case "0":
                                echo '<span class="label label-primary" title="Chưa duyệt"><i class="fa fa-question"></i></span>';
                                break;
                            case "-1":
                                echo '<span class="label label-default" title="'.$conf['decline_reason'].'"><i class="fa fa-times"></i></span>';
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $conf['owner_name'] ?>
                    </td>
                    <td>
                        <?php if (!$conf['is_deleted']): ?>
                            <?php if ($conf['is_approved'] < 1): ?>
                                                                                                    <!--                                <button type="submit" name="btn_approve" value="<?php echo $conf['app_id'] ?>" class="btn btn-primary btn-xs"  title="Duyệt">
                                                                                                                                    <i class="fa fa-check"></i>
                                                                                                                                </button>-->
                                <a href="<?php echo $edit_url ?>" class="btn btn-primary btn-xs"  title="Duyệt">
                                    <i class="fa fa-check"></i>
                                </a>
                            <?php endif ?>
                            <?php if ($conf['is_approved'] == 1): ?>
                                <button
                                    type="button" value="<?php echo $conf['app_id'] ?>" class="btn btn-default btn-xs"  
                                    title="Từ chối" onclick="btn_decline_onclick(<?php echo $conf['app_id'] ?>)">
                                    <i class="fa fa-close"></i>
                                </button>
                                <?php if ($start_date <= $now && $end_date >= $now && $conf['is_approved'] == 1): ?>
                                    <a href="<?php echo $join_url ?>" class="btn btn-default btn-xs" title="Tham gia"><i class="fa fa-arrow-circle-right"></i></a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-default btn-xs" title="Tham gia" disabled><i class="fa fa-arrow-circle-right"></i></button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="button" class="btn btn-default btn-xs" title="Tham gia" disabled><i class="fa fa-arrow-circle-right"></i></button>
                            <?php endif; ?>
                            <a href="<?php echo $recording_url ?>" class="btn btn-default btn-xs" title="Recording">
                                <i class="fa fa-film"></i>
                            </a>
                        <?php endif; ?>
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

<form method="post" class="form-validate">
    <div class="modal" id="modal-decline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Từ chối</h4>
                </div>
                <div class="modal-body">
                    <textarea name="txt_reason" class="form-control input-block" rows="5" placeholder="Nêu lý do" required></textarea> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" name="btn_decline" class="btn btn-danger">Từ chối</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<script>
    var script_data = {
        videomost_url: '<?php echo VIDEOMOST_URL ?>',
        videomost_acc: '<?php echo VIDEOMOST_ADMIN_ACC ?>',
        videmost_pass: '<?php echo VIDEOMOST_ADMIN_PASS ?>',
        site_url: '<?php echo site_url() ?>'
    };</script>
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

    function btn_decline_onclick(app_id) {
        $('[name=btn_decline]', '#modal-decline').val(app_id);
        $('#modal-decline').modal('show');
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
                url: script_data.videomost_url + '/join/',
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
                url: script_data.videomost_url + '/ext/vmi',
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
