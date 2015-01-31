<?php
$confroom_id = explode('(', $app['confroom_id']);
$confroom_id = $confroom_id[0];
$dir = VIDEOMOST_DIR . 'files/confs/' . $confroom_id . '/';
$files = array();
if (is_dir($dir))
{
    $files = scandir($dir, 0);
}
?>
<style>
    td{color: #ccc;}
    #subheading{position: relative;}

</style>
<div id="subheading">
    <h4 >Danh sách File của cuộc họp</h4>
</div>
<?php if (!count($files)): ?>
    Thư mục rỗng
<?php endif; ?>
<div style="max-width: 800px">
    <table class="table table-striped">
        <colgroup>
            <col width="10%">
            <col width="80%">
            <col width="10%">
        </colgroup>

        <?php $idx = 1 ?>
        <?php foreach ($files as $file_name): ?>
            <?php
            $file_path = $dir . $file_name;
            if (is_dir($file_path))
            {
                continue;
            }
            $url = VIDEOMOST_URL . '/files/confs/' . $confroom_id . '/' . $file_name;
            $unit = 'MB';
            $file_size = round(filesize($file_path) / (1024 * 1024));
            if (!$file_size)
            {
                $unit = 'KB';
                $file_size = round(filesize($file_path) / 1024);
            }
            ?>
            <tr>
                <td><?php echo $idx++ ?></td>
                <td>
                    <a href="<?php echo $url ?>" target="_blank"><?php echo $file_name ?></a>
                </td>
                <td><?php echo $file_size . ' ' . $unit ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>