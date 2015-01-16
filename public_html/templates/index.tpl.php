<style>
    td{vertical-align: middle;}

</style>
    <h4>Danh sách cuộc họp chủ trì</h4>
<table class="table  table-bordered">
    <thead>
        <tr>
            <th width="12%">Bắt đầu</th>
            <th width="12%">Kết thúc</th>
            <th width="40%">Chủ đề</th>
            <th width="6%" class="center"><i class="fa fa-signal"></i></th>
            <th width="30%">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($arr_host_conf) == 0): ?>
            <tr>
                <td colspan="5" class="center">Hiện tại chưa có lịch họp</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_host_conf as $conf): ?>
            <?php
            $start_date = DateTimeEx::create($conf['startTime']);
            $end_date = DateTimeEx::create($conf['finishTime']);
            $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
            $manage_url = site_url('/attendiees', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <?php echo '<strong>' . $start_date->format('d') . ' thg ' . $start_date->format('m') . '</strong> - ' . $start_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo '<strong>' . $end_date->format('d') . ' thg ' . $end_date->format('m') . '</strong> - ' . $end_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo $conf['topic'] ?>
                </td>
                <td class="center">
                    <?php if (is_conference_started($conf['confroom_id'])): ?>
                        <span class="label label-success" title="Đã bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php else: ?>
                        <span  class="label label-default" title="Chưa bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo $join_url ?>" title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                        <i class="fa fa-arrow-circle-right"></i> Tham gia
                    </a>
                    <a href="<?php echo $manage_url ?>" class="btn btn-default btn-xs">
                        <i class="fa fa-group"></i> Danh sách khách mời
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h4>Danh sách cuộc họp được mời</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="12%">Bắt đầu</th>
            <th width="12%">Kết thúc</th>
            <th width="30%">Chủ đề</th>
            <th width="6%" class="center"><i class="fa fa-signal"></i></th>
            <th width="30%">Đơn vị chủ trì</th>
            <th width="10%">Tham gia</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($arr_invited_conf) == 0): ?>
            <tr>
                <td colspan="6" class="center">Hiện tại chưa có lịch họp</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_invited_conf as $conf): ?>
            <?php
            $start_date = DateTimeEx::create($conf['startTime']);
            $end_date = DateTimeEx::create($conf['finishTime']);
            $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <?php echo '<strong>' . $start_date->format('d') . ' thg ' . $start_date->format('m') . '</strong> - ' . $start_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo '<strong>' . $end_date->format('d') . ' thg ' . $end_date->format('m') . '</strong> - ' . $end_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo $conf['topic'] ?>
                </td>
                <td class="center">
                    <?php if (is_conference_started($conf['confroom_id'])): ?>
                        <span class="label label-success" title="Đã bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php else: ?>
                        <span  class="label label-default" title="Chưa bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $conf['owner_name'] ?>
                </td>
                <td>
                    <a href="<?php echo $join_url ?>" title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                        <i class="fa fa-arrow-circle-right"></i> Tham gia
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<h2><center>Yêu cầu thiết lập cuộc họp</center></h2>
<hr>
<br>
<form class="form-validate" method="post">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="txt_conf_name">Chủ đề họp<span class="red"> *</span></label>
                <input type="text" name="txt_conf_name" id="txt_conf_name" class="form-control" data-rule-required="true"/>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_start_date">Bắt đầu<span class="red"> *</span></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_date" id="txt_conf_start_date" class="form-control datepicker"
                                       placeholder="ngày" data-rule-required="true"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_time" id="txt_conf_start_time" class="form-control timepicker" 
                                       placeholder="giờ" data-rule-required="true"/>
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
                                       placeholder="ngày" data-rule-required="true"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_time" id="txt_conf_end_time" class="form-control timepicker" placeholder="giờ" data-rule-required="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <center><input type="submit" name="btn_request_conf" value="Gửi yêu cầu" class="btn btn-primary btn-block"/></center>
        </div>
    </div>
</form>