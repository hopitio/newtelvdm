<div class='row'>
    <div class='col-sm-offset-4 col-sm-4'>
        <form class="form-validate" autocomplete="off" method='post'>
            <div class="form-group">
                <label class="control-label" for="txt_account"><i class="fa fa-user"></i> Tài khoản</label>
                <input class="form-control" id="txt_account" name='txt_account' type="text" data-rule-required="true" value="<?php echo get_post_var('txt_account') ?>">
            </div>
            <div class="form-group">
                <label class="control-label" for="txt_pass"><i class="fa fa-key"></i> Mật khẩu</label>
                <input class="form-control" id="txt_pass" name='txt_pass' type="password" data-rule-required="true">
                <?php if (isset($error)): ?>
                    <label class="error"><?php echo $error ?></label>
                <?php endif; ?>
            </div>
            <input type='submit' class='btn btn-primary btn-block' value='Đăng nhập' data-rule-required="true"/>
        </form>
    </div>
</div>