<?php
/* @var $user_data array */
?>
<div class="row">
    <div class="col-sm-offset-3 col-sm-6">
        <form class="form-validate" method="post" autocomplete="off">
            <div class="form-group">
                <label for="txt_name">Tên điểm đầu/cuối<span class="red"> *</span></label>
                <input type="text" name="txt_name" id="txt_name" class="form-control" value="<?php echo fetch_array($user_data, 'c_name') ?>"
                       data-rule-required="true"/>
            </div>
            <div class="form-group">
                <label for="txt_representer">Tên người đại diện</label>
                <input type="text" name="txt_representer" id="txt_representer" class="form-control" value="<?php echo fetch_array($user_data, 'c_representer') ?>"/>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="txt_phone">Số điện thoại <span class="red"> *</span></label>
                        <input type="text" name="txt_phone" id="txt_phone" class="form-control" value="<?php echo fetch_array($user_data, 'c_phone_no') ?>" 
                               data-rule-required="true"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="txt_email">Email</label>
                        <input type="text" name="txt_email" id="txt_email" class="form-control" value="<?php echo fetch_array($user_data, 'c_email') ?>"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="txt_account">Tài khoản <span class="red"> *</span></label>
                <input type="text" name="txt_account" id="txt_account" class="form-control" value="<?php echo fetch_array($user_data, 'c_account') ?>"
                       data-rule-required="true"/>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="txt_password">Mật khẩu <span class="red"> *</span></label>
                        <input type="password" name="txt_password" id="txt_password" class="form-control" value="<?php echo fetch_array($user_data, 'c_password') ?>" 
                               data-rule-required="true" data-rule-minlength="6" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="txt_repassword">Nhập lại mật khẩu <span class="red"> *</span></label>
                        <input type="password" name="txt_repassword" id="txt_repassword" class="form-control" value="<?php echo fetch_array($user_data, 'c_password') ?>" 
                               data-rule-required="true" data-rule-equalTo="#txt_password" data-rule-minlength="6"/>
                    </div>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <span class="red"><?php echo $error ?></span>
                <br>
            <?php endif; ?>
            <input type="submit" class="btn btn-primary" value="Cập nhật"/>
            <a href="<?php echo site_url('/admin/account') ?>" class="btn btn-default">Quay về danh sách</a>
        </form>
    </div>
</div>

<script>
    $('#txt_password').keyup(function () {
        $('#txt_repassword').val('');
    });
</script>