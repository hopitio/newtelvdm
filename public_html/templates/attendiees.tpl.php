<?php ?>
<style>
    td label{font-weight: normal;margin-bottom:0;width: 100%;}
</style>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <form method="post">
            <div class="row">
                <div class="col-xs-5">
                    <table class="table-hover table-bordered table-condensed" style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="10%" class="center"><input type="checkbox" class="check_all" data-target=".chk_left"/></th>
                                <th width="90%">Danh sách chưa mời</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php foreach ($arr_user as $user): ?>
                                <?php
                                if (in_array($user['pk_user'], $arr_attendiees))
                                {
                                    continue;
                                }
                                $i++;
                                $uid = 'a' . uniqid();
                                ?>
                                <tr>
                                    <td class="center">
                                        <input type="checkbox" name="chk_left[]" value="<?php echo $user['pk_user'] ?>" id="<?php echo $uid ?>" class="chk_left"/>
                                    </td>
                                    <td><label for="<?php echo $uid ?>"><?php echo $user['c_name'] ?></label></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php for ($i; $i < 10; $i++): ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-2">
                    <input type="submit" class="btn btn-default btn-block" name="btn_add" value=">>>" style="color:blue;"/>
                    <input type="submit" class="btn btn-default btn-block" name="btn_remove" value="<<<" style="color:blue;"/>
                </div>
                <div class="col-xs-5">
                    <table class="table-hover table-bordered table-condensed" style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="10%" class="center"><input type="checkbox" class="check_all" data-target=".chk_right"/></th>
                                <th width="90%">Danh sách đã mời</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php foreach ($arr_user as $user): ?>
                                <?php
                                if (!in_array($user['pk_user'], $arr_attendiees))
                                {
                                    continue;
                                }
                                $i++;
                                $uid      = 'a' . uniqid();
                                $disabled = $user['pk_user'] == $appointment['owner_id'] ? 'disabled' : '';
                                $red      = $user['pk_user'] == $appointment['owner_id'] ? 'red' : '';
                                ?>
                                <tr class="<?php echo $red ?>">
                                    <td class="center">
                                        <input type="checkbox" name="chk_right[]" value="<?php echo $user['pk_user'] ?>" 
                                               id="<?php echo $uid ?>" class="chk_right" <?php echo $disabled ?>/>
                                    </td>
                                    <td>
                                        <label for="<?php echo $uid ?>">
                                            <?php echo $user['c_name'] ?>
                                            <?php if ($red) echo '&nbsp;*' ?>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php for ($i; $i < 10; $i++): ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="center">
    <?php
    $sms_url = sms_url($arr_attendiees, $arr_attendiees, $appointment['app_id']);
    ?>
    <?php if (count($arr_attendiees) > 1): ?>
        <p>Nhấn vào nút bên dưới để gửi SMS thông báo cho toàn bộ khách mời</p>
        <a href="<?php echo $sms_url ?>" class="btn btn-primary btn-lg">
            <i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;Gửi SMS
        </a>
    <?php else: ?>
        <p>Cần mời họp trước khi gửi SMS</p>
        <button disabled class="btn btn-primary btn-lg">
            <i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;Gửi SMS
        </button>
    <?php endif; ?>
</div>