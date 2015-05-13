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
        <h3 style="text-align:center">
            Đang kiểm tra thiết bị, vui lòng chờ trong giây lát...<br><br>
            <a href="<?php echo site_url() ?>">Hoặc nhấn vào đường dẫn này để trở về trang chủ</a>
        </h3>
        <form method="post" action="<?php echo VIDEOMOST_URL ?>join" id="frm_join">
            <input type="hidden" name="mac_go_app" value="0"/>
            <input type="hidden" name="confid" value="<?php echo $conf_id ?>"/>
            <input type="hidden" name="confpass" value="<?php echo $app['password'] ?>"/>
            <input type="hidden" name="username" value="<?php echo user()->name ?>"/>
            <input type="hidden" name="remember" value="0"/>
        </form>

        <script src="<?php echo site_url('public/js') ?>/jquery-1.10.2.min"></script>
        <script>
            var script_data = {
                account: '<?php echo user()->account ?>',
                videomost_url: '<?php echo VIDEOMOST_URL ?>'
            };
        </script>
        <script>
            function join() {
                //dang xuat roi moi dang nhap
                $.ajax({
                    cache: false,
                    url: script_data.videomost_url + '/logout?auth',
                    complete: function () {
                        //dang nhap vs tai khoan vdm
                        var login_data = {
                            ajax: 1,
                            login: script_data.account,
                            password: 123456,
                            realmname: '',
                            rem: 0
                        };
                        $.ajax({
                            cache: false,
                            type: 'post',
                            data: login_data,
                            url: script_data.videomost_url + '/join/',
                            success: function () {
                                document.getElementById('frm_join').submit();
                            }
                        });
                    }
                });
            }
        </script>

        <?php if (VIDEOMOST_TRICK_HD == '1'): ?>
            <iframe id="frm_test_cam" src="<?php echo VIDEOMOST_URL ?>join" style="width: 600px; height: 400px; position: fixed;top:-1000px;"></iframe>

            <script>
                document.getElementById('frm_test_cam').onload = function () {
                    var frm = this;
                    var contentJQ = frm.contentWindow.$;
                    contentJQ('.diag-dialog span').trigger('click');

                    var inv = setInterval(function () {
                        if (contentJQ('.cuselText').length) {
                            clearInterval(inv);
                            join();
                        }
                    }, 500);
                };
            </script>
        <?php else: ?>
            <script>
                join();
            </script>
        <?php endif; ?>
    </body>
</html>
