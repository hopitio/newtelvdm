<form class="form-validate" method="post">
    <input type="hidden" name="hdn_app_id" value="<?php echo $app['app_id'] ?>"/>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="txt_conf_name">Chủ đề họp<span class="red"> *</span></label>
                <input type="text" name="txt_conf_name" id="txt_conf_name" class="form-control" data-rule-required="true" value="<?php echo $app['topic'] ?>"/>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="txt_conf_start_date">Bắt đầu<span class="red"> *</span></label>
                        <div class="row">
                            <?php
                            $begin = DateTimeEx::create($app['startTime']);
                            ?>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_start_date" id="txt_conf_start_date" class="form-control datepicker"
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
                            $finish = DateTimeEx::create($app['finishTime']);
                            ?>
                            <div class="col-sm-6">
                                <input type="text" name="txt_conf_end_date" id="txt_conf_end_date" class="form-control datepicker"
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
            <input type="submit" name="btn_submit" value="Cập nhật" class="btn btn-primary"/>
            <a href="<?php echo site_url('/admin/approve') ?>" class="btn btn-default">Quay về danh sách</a>
        </div>
    </div>
</form>
