<?php ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="15%">Bắt đầu</th>
            <th width="15%">Kết thúc</th>
            <th width="30%">Chủ đề</th>
            <th width="20%">Đơn vị chủ trì</th>
            <th width="20%">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($arr_conf) == 0): ?>
            <tr>
                <td class="center" colspan="5">Chưa có cuộc họp nào</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($arr_conf as $conf): ?>
            <?php
            $begin = DateTimeEx::create($conf['startTime'])->addHour(7);
            $finish = DateTimeEx::create($conf['finishTime'])->addHour(7);
            $recording_url = site_url('/recording', array('app_id' => $conf['app_id']));
            ?>
            <tr>
                <td>
                    <?php echo '<strong>' . $begin->format('d') . ' thg ' . $begin->format('m') . '</strong> - ' . $begin->format('H:i') ?>
                </td>
                <td>
                    <?php echo '<strong>' . $finish->format('d') . ' thg ' . $finish->format('m') . '</strong> - ' . $finish->format('H:i') ?>
                </td>
                <td><?php echo $conf['topic'] ?></td>
                <td><?php echo $conf['owner_name'] ?></td>
                <td>
                    <a href="<?php echo $recording_url ?>" class="btn btn-xs btn-info" title="Recording">
                        <i class="fa fa-film"></i> Recording
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$prev = $page <= 1 ? 'javascript:;' : site_url('history', array('page' => $page - 1));
$next = $page >= $total_page ? 'javascript:;' : site_url('history', array('page' => $page + 1));
?>
<div class="pull-right">
    <ul class="pagination">
        <li><a href="<?php echo $prev ?>"><i class="fa fa-long-arrow-left"></i> Trang trước</a></li>
        <li class="active"><a href="javascript:;"><?php echo $page ?></a></li>
        <li><a href="<?php echo $next ?>">Trang sau <i class="fa fa-long-arrow-right"></i></a></li>
    </ul>
</div>