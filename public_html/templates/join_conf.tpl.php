<?php
$conf_id = explode('(', $app['confroom_id']);
$conf_id = $conf_id[0];
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Tham gia hội nghị</title>
    </head>
    <body>
        Đang tham gia hội nghị, vui lòng chờ...<br>
        <a href="<?php echo site_url() ?>">Hoặc nhấn vào link này để trở về trang chủ</a>
        <form method="post" action="<?php echo VIDEOMOST_URL ?>join" id="frm_join">
            <input type="hidden" name="mac_go_app" value="0"/>
            <input type="hidden" name="confid" value="<?php echo $conf_id ?>"/>
            <input type="hidden" name="confpass" value="<?php echo $app['password'] ?>"/>
            <input type="hidden" name="username" value="<?php echo user()->name ?>"/>
            <input type="hidden" name="remember" value="0"/>
        </form>

        <script>
            document.getElementById('frm_join').submit();
        </script>
    </body>
</html>
