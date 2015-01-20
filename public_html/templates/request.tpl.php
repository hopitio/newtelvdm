<form class="form-validate" method="post" action="#txt_conf_name">
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
            <?php if (isset($request_error)): ?>
                <span class="red"><?php echo $request_error ?></span>
            <?php endif; ?>
            <center><input type="submit" name="btn_request_conf" value="Gửi yêu cầu" class="btn btn-primary btn-block"/></center>
        </div>
    </div>
</form>
<br>
<br>
<h2 class="center">Yêu cầu chờ duyệt</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="15%">Bắt đầu</th>
            <th width="15%">Kết thúc</th>
            <th width="70%">Chủ đề</th>
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
                <td><?php echo $conf['topic'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $('#txt_conf_start_date').change(function () {
        $('#txt_conf_end_date').datepicker('setStartDate', $(this).val());
    });
    $('#txt_conf_end_date').change(function () {
        $('#txt_conf_start_date').datepicker('setEndDate', $(this).val());
    });
</script>