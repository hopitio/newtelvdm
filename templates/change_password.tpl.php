<?php ?>
<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <form class="form-validate" method="post">
            <div class="form-group">
                <label for="txt_password">Mật khẩu hiện tại<span class="red"> *</span></label>
                <input type="password" name="txt_password" id="txt_password" class="form-control" data-rule-required="true"/>
                <?php if (isset($error)): ?>
                    <label class="error"><?php echo $error ?></label>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="txt_new_password">Mật khẩu mới<span class="red"> *</span></label>
                <input type="password" name="txt_new_password" id="txt_new_password" class="form-control" data-rule-required="true" data-rule-minlength="6"/>
            </div>
            <div class="form-group">
                <label for="txt_repassword">Xác nhận mật khẩu mới<span class="red"> *</span></label>
                <input type="password" name="txt_repassword" id="txt_repassword" class="form-control" data-rule-required="true" data-rule-equalTo="#txt_new_password"/>
            </div>
            <input type="submit" class="btn btn-primary btn-block" value="Đổi mật khẩu"/>
        </form>
    </div>
</div>
