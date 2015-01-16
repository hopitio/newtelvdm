<?php
/* @var $arr_user array */
?>

<form id="frm_main" method="post">
    <div class="form-actions">
        <a href="<?php echo site_url('/admin/edit_account') ?>" type="button" class="btn btn-default">
            <i class="fa fa-plus"></i> Thêm tài khoản
        </a>
        <button type="submit" name="btn_sort" value="true" class="btn btn-default">
            <i class="fa fa-reorder"></i> Lưu lại sắp xếp
        </button>
        <button type="submit" name="btn_delete" value="true" class="btn btn-default" onclick="return confirm_delete()">
            <i class="fa fa-trash"></i> Xóa
        </button>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="center" width="5%">
                    <input type="checkbox" class="check_all" data-target=".check"/>
                </th>
                <th width="30%">Tên điểm đầu/cuối</th>
                <th width="15%">Tài khoản</th>
                <th width="15%">Người đại diện</th>
                <th width="10%">Số điện thoại</th>
                <th width="15%">Email</th>
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
                $url = site_url('/admin/edit_account', array('id' => $user['pk_user']));
                ?>
                <tr>
                    <td class="center"><input type="checkbox" name="check[]" class="check" value="<?php echo $user['pk_user'] ?>"/></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_name'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_account'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_representer'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_phone_no'] ?></a></td>
                    <td><a href="<?php echo $url ?>"><?php echo $user['c_email'] ?></a></td>
                    <td>
                        <input type="text" name="txt_sort[<?php echo $user['pk_user'] ?>]" class="form-control" style="padding: 0 12px;height:28px;" 
                               value="<?php echo $user['c_sort'] ?>"/>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>