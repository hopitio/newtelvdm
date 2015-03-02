<?php
/* @var $arr_user array */
?>

<form id="frm_main" method="post">
    <div class="form-actions">
        <div class="pull-right">
            <div style="width: 300px;display:inline-block">
                <input type="text" name="search" class="form-control" 
                       placeholder="tên, tài khoản, người đại diện, sđt"
                       value="<?php echo get_post_var('search') ?>"
                       />
            </div>
            <button type="submit" class="btn btn-default">
                <i class="fa fa-search"></i>
            </button>
        </div>
        <a href="<?php echo site_url('/admin/edit_account') ?>" type="button" class="btn btn-default">
            <i class="fa fa-plus"></i> Thêm tài khoản
        </a>
        <?php $disabled = get_post_var('search') ? 'disabled' : '' ?>
        <button type="submit" name="btn_sort" value="true" class="btn btn-default" <?php echo $disabled ?>>
            <i class="fa fa-reorder"></i> Lưu lại sắp xếp
        </button>
        <button type="submit" name="btn_delete" value="true" class="btn btn-default" onclick="return confirm_delete()">
            <i class="fa fa-trash"></i> Xóa
        </button>

    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="center" width="3%">
                    <input type="checkbox" class="check_all" data-target=".check"/>
                </th>
                <th width="28%">Tên điểm đầu/cuối</th>
                <th width="12%">Tài khoản</th>
                <th width="15%">Người đại diện</th>
                <th width="10%">Số điện thoại</th>
                <th width="15%">Email</th>
                <th width="7%" class="center">SMS</th>
                <th width="10%">Sắp xếp</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!count($arr_user)): ?>
                <tr>
                    <td colspan="7" class="center">Không tìm thấy bản ghi nào</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($arr_user as $user): ?>
                <?php
                $url      = site_url('/admin/edit_account', array('id' => $user['pk_user']));
                $sms_url  = sms_url(array($user['pk_user']), array($user['pk_user']));
                ?>
                <tr>
                    <td class="center"><input type="checkbox" name="check[]" class="check" value="<?php echo $user['pk_user'] ?>"/></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_name'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_account'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_representer'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_phone_no'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_email'] ?></a></td>
                    <td>
                        <a href="<?php echo $sms_url ?>" class="btn btn-default btn-sm btn-block">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </td>
                    <td>
                        <?php $disabled = get_post_var('search') ? 'disabled' : '' ?>
                        <input type="text" name="txt_sort[<?php echo $user['pk_user'] ?>]" 
                               class="form-control" style="padding: 0 12px;height:28px;" 
                               <?php echo $disabled ?>
                               value="<?php echo $user['c_sort'] ?>"/>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>