<form class="form-validate" method="post" id="frm-main" ng-app="myApp" ng-controller="editCtrl">
    <input type="hidden" name="hdn_app_id" value="<?php echo $app['app_id'] ?>"/>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="txt_conf_name">Chủ đề họp<span class="red"> *</span></label>
                <input type="text" name="txt_conf_name" id="txt_conf_name" class="form-control" data-rule-required="true" value="<?php echo $app['topic'] ?>"/>
            </div>
            <div class="form-group">
                <label for="sel_conf_owner">Chủ trì<span class="red"> *</span></label>
                <select name="sel_conf_owner" id="sel_conf_owner" class="form-control" data-rule-required="true">
                    <?php
                    foreach ($arr_user as $user)
                    {
                        $selected = $app['owner_id'] == $user['pk_user'] ? 'selected' : '';
                        echo "<option value='" . $user['pk_user'] . "' $selected>" . $user['c_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_start_date">Bắt đầu<span class="red"> *</span></label>
                        <div class="row">
                            <?php
                            $begin = DateTimeEx::create($app['startTime'])->addHour(8);
                            ?>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_date" id="txt_conf_start_date" class="form-control datepicker" 
                                       data-date-start-date="<?php echo date_create()->format('d/m/Y') ?>"
                                       placeholder="ngày" data-rule-required="true" value="<?php echo $begin->format('d/m/Y') ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_time" id="txt_conf_start_time" class="form-control timepicker" 
                                       placeholder="giờ" data-rule-required="true" value="<?php echo $begin->format('H:i') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_end_date">Kết thúc<span class="red"> *</span></label>
                        <div class="row">
                            <?php
                            $finish = DateTimeEx::create($app['finishTime'])->addHour(7);
                            ?>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_date" id="txt_conf_end_date" class="form-control datepicker"
                                       data-date-start-date="<?php echo date_create()->format('d/m/Y') ?>"
                                       placeholder="ngày" data-rule-required="true" value="<?php echo $finish->format('d/m/Y') ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_time" id="txt_conf_end_time" class="form-control timepicker" 
                                       placeholder="giờ" data-rule-required="true" value="<?php echo $finish->format('H:i') ?>"/>
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
                            <?php $checked = $show_only_owner == 0 ? 'checked' : '' ?>
                            <input type="radio" name="chk_show_only_owner" id="soo0" value="0" <?php echo $checked ?>/>
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
                            <?php $checked = $show_only_owner == 1 ? 'checked' : '' ?>
                            <input type="radio" name="chk_show_only_owner" id="soo1" value="1" <?php echo $checked ?>/>
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
            <?php if (isset($error)): ?>
                <span class="red"><?php echo $error ?></span>
                <br>
            <?php endif; ?>
            <input type="submit" class="btn btn-primary" value="Cập nhật" ng-click="save()"/>
            <a href="<?php echo site_url() ?>" class="btn btn-default">Quay về danh sách</a>
        </div>
    </div>
</form>


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
    var users = <?php echo json_encode($arr_user) ?>;
    var attendiees = <?php echo json_encode($arr_attendiees) ?>;
</script>
<script>
    angular.module('myApp', []).controller('editCtrl', function ($scope) {
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

        for (var i in $scope.users) {
            var user = $scope.users[i];
            for (var i in attendiees) {
                var id = attendiees[i];
                if (user.pk_user == id) {
                    console.log(i);
                    $scope.items[i] = true;
                    break;
                }
            }

        }

        for (var i in attendiees) {
            var id = attendiees[i];

        }

        $scope.check = function (checked) {
            setTimeout(function () {
                $scope.$apply(function () {
                    $('.chk').each(function (index) {
                        $scope.items[index] = checked;
                    });
                });
            });

        };

        $scope.save = function () {
            if ($('#frm-main').valid()) {
                $('#frm-main').submit();
            }
        };
    });
</script>
