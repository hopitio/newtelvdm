<style>
    td{vertical-align: middle;}

</style>
<h4>Danh sách cuộc họp chủ trì</h4>
<table class="table  table-bordered">
    <thead>
        <tr>
            <th width="12%">Bắt đầu</th>
            <th width="12%">Kết thúc</th>
            <th width="40%">Chủ đề</th>
            <th width="6%" class="center"><i class="fa fa-signal"></i></th>
            <th width="30%">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($arr_host_conf) == 0): ?>
            <tr>
                <td colspan="5" class="center">Hiện tại chưa có lịch họp</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_host_conf as $conf): ?>
            <?php
            $start_date = DateTimeEx::create($conf['startTime'])->addHour(8);
            $end_date = DateTimeEx::create($conf['finishTime'])->addHour(7);
            $now = DateTimeEx::create();
            $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
            $manage_url = site_url('/edit_app', array('app_id' => $conf['app_id']));
            $record_url = site_url('/recording', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <?php echo '<strong>' . $start_date->format('d') . ' thg ' . $start_date->format('m') . '</strong> - ' . $start_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo '<strong>' . $end_date->format('d') . ' thg ' . $end_date->format('m') . '</strong> - ' . $end_date->format('H:i') ?>
                </td>
                <td title="<?php echo $conf['topic'] ?>">
                    <?php echo mb_strlen($conf['topic'], 'UTF-8') > 50 ? mb_substr($conf['topic'], 0, 50, 'UTF-8') . '...' : $conf['topic'] ?>
                </td>
                <td class="center">
                    <?php if (is_conference_started($conf['confroom_id'])): ?>
                        <span class="label label-success" title="Đã bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php else: ?>
                        <span  class="label label-default" title="Chưa bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($start_date <= $now && $end_date >= $now): ?>
                        <a href="<?php echo $join_url ?>" title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                            <i class="fa fa-arrow-circle-right"></i> Tham gia
                        </a>
                    <?php else: ?>
                        <button type="button" disabled title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                            <i class="fa fa-arrow-circle-right"></i> Tham gia
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo $manage_url ?>" class="btn btn-default btn-xs">
                        <i class="fa fa-edit"></i> Sửa cuộc họp
                    </a>
                    <a href="<?php echo $record_url ?>" class="btn btn-default btn-xs" title="Recording">
                        <i class="fa fa-film"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h4>Danh sách cuộc họp được mời</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="12%">Bắt đầu</th>
            <th width="12%">Kết thúc</th>
            <th width="30%">Chủ đề</th>
            <th width="6%" class="center"><i class="fa fa-signal"></i></th>
            <th width="30%">Đơn vị chủ trì</th>
            <th width="10%">Tham gia</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($arr_invited_conf) == 0): ?>
            <tr>
                <td colspan="6" class="center">Hiện tại chưa có lịch họp</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_invited_conf as $conf): ?>
            <?php
            $start_date = DateTimeEx::create($conf['startTime'])->addHour(8);
            $end_date = DateTimeEx::create($conf['finishTime'])->addHour(7);
            $now = DateTimeEx::create();
            $join_url = site_url('/join_conf', array('app_id' => $conf['app_id']));
            $record_url = site_url('/recording', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <?php echo '<strong>' . $start_date->format('d') . ' thg ' . $start_date->format('m') . '</strong> - ' . $start_date->format('H:i') ?>
                </td>
                <td>
                    <?php echo '<strong>' . $end_date->format('d') . ' thg ' . $end_date->format('m') . '</strong> - ' . $end_date->format('H:i') ?>
                </td>
                <td title="<?php echo $conf['topic'] ?>">
                    <?php echo mb_strlen($conf['topic'], 'UTF-8') > 40 ? mb_substr($conf['topic'], 0, 40, 'UTF-8') . '...' : $conf['topic'] ?>
                </td>
                <td class="center">
                    <?php if (is_conference_started($conf['confroom_id'])): ?>
                        <span class="label label-success" title="Đã bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php else: ?>
                        <span  class="label label-default" title="Chưa bắt đầu">
                            <i class="fa fa-signal"></i>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $conf['owner_name'] ?>
                </td>
                <td>
                    <?php if ($start_date <= $now && $end_date >= $now): ?>
                        <a href="<?php echo $join_url ?>" title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                            <i class="fa fa-arrow-circle-right"></i> Tham gia
                        </a>
                    <?php else: ?>
                        <button type="button" disabled title="Tham gia hội nghị" class="btn btn-primary btn-xs">
                            <i class="fa fa-arrow-circle-right"></i> Tham gia
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo $record_url ?>" class="btn btn-default btn-xs" title="Recording">
                        <i class="fa fa-film"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
