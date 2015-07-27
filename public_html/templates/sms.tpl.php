<?php
if ($app)
{
    $arr_template = array();
    $template_dir = __DIR__ . '/sms';

    $begin = DateTimeEx::create($app['startTime'])->addHour(7);
    $end = DateTimeEx::create($app['finishTime'])->addHour(7);

    $arr_template_data = array(
        "{{topic}}"      => tieng_viet_khong_dau($app['topic']),
        "{{begin_time}}" => $begin->format('H:i'),
        "{{begin_date}}" => $begin->format('d/m/Y'),
        "{{end_time}}"   => $end->format('H:i'),
        "{{end_date}}"   => $end->format("d/m/Y"),
        "{{owner_name}}" => tieng_viet_khong_dau($app['owner_name'])
    );

    foreach (scandir($template_dir) as $item)
    {
        if (is_dir($item) || $item == '.' || $item == '..')
        {
            continue;
        }
        $file = $template_dir . '/' . $item;
        $arr_template[$item] = file_get_contents($file);
        $arr_template[$item] = strtr($arr_template[$item], $arr_template_data);
    }
}
?>

<style>
    #i_post{
        position: fixed;
        top: -1000px;
    }
</style>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <form class="form-validate" id="frm-main" method="post">
            <?php if ($app): ?>
                <div class="form-group">
                    <label for="sel_template">Chọn mẫu tin nhắn</label>
                    <select class="form-control" id="sel_template">
                        <option value=" ">Để trống</option>
                        <?php
                        foreach ($arr_template as $k => $v)
                        {
                            $v = json_encode($v);
                            echo "<option value='$v'>$k</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="txt_msg">Nội dung tin nhắn <span class="red">*</span></label>
                <textarea name="txt_msg" id="txt_msg" class="form-control" data-rule-required="true" rows="7"></textarea>
                <div class="help-block">
                    Độ dài: <span id="msg-length">0</span> ký tự<br>
                    Chú ý *: nội dung tin nhắn phải viết Tiếng Việt KHÔNG dấu
                </div>
            </div>
            <strong>Danh sách người nhận <span style="color:grey" id="count-checked">(0)</span></strong><br>
            <div class="well">
                <a href="javascript:;" id="check-all"><i class="fa fa-check-square-o"></i> Chọn tất cả</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="javascript:;" id="uncheck-all"><i class="fa fa-minus-square-o"></i> Bỏ chọn tất cả</a>
                <br><br>
                <?php foreach ($arr_user as $user): ?>
                    <?php
                    $uid = 'a' . uniqid();
                    $disabled = $user['pk_user'] == user()->pk_user ? 'disabled' : '';
                    $checked = in_array($user['pk_user'], $arr_selected) && !$disabled ? 'checked' : '';
                    ?>
                    <input type="checkbox" class="chk" id="<?php echo $uid ?>"
                           value="<?php echo $user['c_phone_no'] ?>"
                           name="phone[]"
                           <?php echo $disabled ?>
                           <?php echo $checked ?>
                           />
                    <label for="<?php echo $uid ?>" style="font-weight: normal">
                        <?php echo $user['c_name'] ?> <span style="color:grey">(<?php echo $user['c_phone_no'] ?>)</span>
                    </label>
                    <br>
                <?php endforeach; ?>
            </div>
            <input type="submit" class="btn btn-primary " id="send-message" value="Gửi tin nhắn"/>
            <input type="button" class="btn btn-default " onclick="history.go(-1)" value="Quay lại"/>
        </form>
    </div>
</div>

<script>
    $(function () {
        $('#txt_msg').keyup(function () {
            $('#msg-length').html(this.value.length);
        });

        $('#check-all').click(function () {
            $('.chk:enabled').prop('checked', true).trigger('change');
        });

        $('#uncheck-all').click(function () {
            $('.chk').prop('checked', false).trigger('change');
        });

        $('.chk').change(function () {
            $('#count-checked').html("(" + $('.chk:checked').length + ")");
        }).trigger('change');

        $('#sel_template').change(function () {
            var val = $(this).val();
            if (val != " ") {
                val = $.parseJSON(val);
            }
            $('#txt_msg').val(val).trigger('keyup');
        });
    });
</script>
