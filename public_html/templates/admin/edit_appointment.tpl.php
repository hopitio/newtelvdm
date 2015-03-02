<form class="form-validate" method="post">
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
                            $begin  = DateTimeEx::create($app['startTime'])->addHour(7);
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
                <label>Danh sách tham gia</label>
                <div class="well">
                    <?php for ($i = 0; $i < count($arr_user); $i++): ?>
                        <?php
                        $user     = $arr_user[$i];
                        $uid      = 'a' . uniqid();
                        $checked  = in_array($user['pk_user'], $arr_attendiees) ? 'checked' : '';
                        $readonly = $user['pk_user'] == $app['owner_id'] ? 'disabled' : ''
                        ?>
                        <input type="checkbox" name="chk_user[]" value="<?php echo $user['pk_user'] ?>" 
                               id="<?php echo $uid ?>" <?php echo $checked ?> class='chk'
                               <?php echo $readonly ?>
                               />
                        <label for="<?php echo $uid ?>" class="inline"><?php echo $user['c_name'] ?></label>
                        <br>
                    <?php endfor; ?>

                    <?php if (count($arr_attendiees) > 1): ?>
                        <div class="help-block">Nhấn vào bên dưới để gửi tin nhắn SMS</div>
                    <?php else: ?>
                        <div class="help-block">Bạn cần thêm thành viên rồi nhấn cập nhật trước khi gửi SMS</div>
                    <?php endif; ?>
                    <?php $disabled = count($arr_attendiees) <= 1 ? 'disabled' : '' ?>
                    <button type='button' class='btn btn-primary' id='btn-sms' <?php echo $disabled ?>>
                        <i class='fa fa-envelope'></i>&nbsp;&nbsp;Gửi SMS
                    </button>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <span class="red"><?php echo $error ?></span>
                <br>
            <?php endif; ?>
            <input type="submit" name="btn_submit" value="Cập nhật" class="btn btn-primary"/>
            <a href="<?php echo site_url('/admin/approve') ?>" class="btn btn-default">Quay về danh sách</a>
        </div>
    </div>
</form>

<script>
    var script_data = {
        sms_url: '<?php echo sms_url($arr_attendiees, $arr_attendiees, $app['app_id']) ?>'
    };
</script>
<script>
    $('#txt_conf_start_date').change(function () {
        $('#txt_conf_end_date').datepicker('setStartDate', $(this).val());
    });
    $('#txt_conf_end_date').change(function () {
        $('#txt_conf_start_date').datepicker('setEndDate', $(this).val());
    });
    $('#btn-sms').click(function () {
        window.location = script_data.sms_url;
    });
</script>