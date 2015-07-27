<table class="table table-bordered">
    <thead>
        <tr>
            <th width="15%">Bắt đầu</th>
            <th width="15%">Kết thúc</th>
            <th width="60%">Chủ đề</th>
            <th width="10%">Sửa</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!count($arr_conf)): ?>
            <tr>
                <td colspan="3" class="center">Chưa có cuộc họp</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_conf as $conf): ?>
            <?php
            $start_date = DateTimeEx::create($conf['startTime'])->addHour(7);
            $end_date = DateTimeEx::create($conf['finishTime'])->addHour(7);
            $manage_url = site_url('/edit_app', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <strong><?php echo $start_date->format('d') . ' thg ' . $start_date->format('m') ?></strong>
                    &nbsp;-&nbsp;
                    <?php echo $start_date->format('H:i') ?>
                </td>
                <td>
                    <strong><?php echo $end_date->format('d') . ' thg ' . $end_date->format('m') ?></strong>
                    &nbsp;-&nbsp;
                    <?php echo $end_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo $conf['topic'] ?>
                    <?php
                    if ($conf['is_approved'] == 0)
                    {
                        echo '<br><span class="green">Đang chờ duyệt</span>';
                    }
                    else if ($conf['is_approved'] == -1)
                    {
                        echo '<br><span class="red">Lý do từ chối: ' . $conf['decline_reason'] . '</span>';
                    }
                    ?>
                </td>
                <td>
                    <a href="<?php echo $manage_url ?>" class="btn btn-primary btn-xs">
                        <i class="fa fa-edit"></i> Sửa cuộc họp
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<br>
<h2 class="center">Yêu cầu cuộc họp</h2>

<form class="form-validate" method="post" action="#txt_conf_name" ng-app="myApp" ng-controller="requestCtrl">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="txt_conf_name">Chủ đề họp<span class="red"> *</span></label>
                <input type="text" name="txt_conf_name" id="txt_conf_name" class="form-control" data-rule-required="true" value="<?php echo get_post_var('txt_conf_name') ?>"/>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_start_date">Bắt đầu<span class="red"> *</span></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_date" id="txt_conf_start_date" class="form-control datepicker"
                                       value="<?php echo get_post_var('txt_conf_start_date') ?>"
                                       placeholder="ngày" data-rule-required="true" data-date-start-date="<?php echo date_create()->format('d/m/Y') ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_time" id="txt_conf_start_time" class="form-control timepicker" 
                                       placeholder="giờ" data-rule-required="true"  value="<?php echo get_post_var('txt_conf_start_time') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_end_date">Kết thúc<span class="red"> *</span></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_date" id="txt_conf_end_date" class="form-control datepicker"
                                       value="<?php echo get_post_var('txt_conf_end_date') ?>"
                                       placeholder="ngày" data-rule-required="true" data-date-start-date="<?php echo date_create()->format('d/m/Y') ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_time" id="txt_conf_end_time" class="form-control timepicker" 
                                       placeholder="giờ" data-rule-required="true" value="<?php echo get_post_var('txt_conf_end_time') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Phương thức thảo luận</label>
                <div class="well">
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="radio" name="chk_show_only_owner" id="soo0" value="0" checked/>
                            <label class="inline" for="soo0">Trao đổi tự do</label>
                        </div>
                        <div class="col-xs-8 help-block">
                            Mọi thành viên tham gia có quyền phát biểu.<br>
                            Người Chủ trì có thể bật/tắt microphone của thành viên bất kỳ.
                        </div>
                    </div>
                </div>
                <div class="well">
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="radio" name="chk_show_only_owner" id="soo1" value="1"/>
                            <label class="inline" for="soo1">Chế độ lần lượt</label>
                        </div>
                        <div class="col-xs-8 help-block">
                            Chỉ Chủ trì, hoặc thành viên được Chủ trì cho phép mới được phát biểu.
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Danh sách tham gia <span ng-cloak>({{check_count}})</span></label>
                <input type="text" class="form-control input-block" ng-model="search" placeholder="tìm theo tên"/>
                <h4></h4>
                <div class="well">
                    <a href="javascript:;" class="btn btn-default btn-sm" ng-click="check(true)">Chọn tất cả</a>
                    <a href="javascript:;"  class="btn btn-default btn-sm" ng-click="check(false)">Bỏ chọn tất cả</a>
                    <h4></h4>
                    <div ng-repeat="user in users| filter: search" ng-cloak>
                        <label class="inline">
                            <input type="checkbox" name="chk_user[]" class="chk" value="{{user.pk_user}}" ng-model="items[$index]">
                            {{user.c_name}}
                        </label>
                    </div>
                </div>
            </div>
            <?php if (isset($request_error)): ?>
                <span class="red"><?php echo $request_error ?></span>
            <?php endif; ?>
            <center><input type="submit" name="btn_request_conf" value="Gửi yêu cầu" class="btn btn-primary btn-block"/></center>
        </div>
    </div>
</form>


<script>
    var users = <?php echo json_encode($arr_user) ?>;
</script>
<script>
    $('#txt_conf_start_date').change(function () {
        $('#txt_conf_end_date').datepicker('setStartDate', $(this).val());
    });
    $('#txt_conf_end_date').change(function () {
        $('#txt_conf_start_date').datepicker('setEndDate', $(this).val());
    });
</script>

<script src="<?php echo site_url() ?>public/js/angular.min.js"></script>
<script>
    angular.module('myApp', []).controller('requestCtrl', function ($scope) {
        $scope.users = users;
        $scope.items = {};
        $scope.check_count = 0;

        $scope.$watchCollection('items', function () {
            $scope.check_count = 0;
            for (var i in $scope.items) {
                if ($scope.items[i] == true)
                    $scope.check_count++;
            }
        });

        $scope.check = function (checked) {
            setTimeout(function () {
                $scope.$apply(function () {
                    $('.chk').each(function (index) {
                        $scope.items[index] = checked;
                    });
                });
            });

        };
    });
</script>
