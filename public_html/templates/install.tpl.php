<?php


?>

<style>
    label{font-weight: bold;display:block;margin-top: 10px;}
    input[type="text"]{padding: 5px 10px;min-width: 200px;}
</style>

<h1>Cài đặt ứng dụng hội nghị truyền hình</h1>

<form method="post">
    <div>
        <label for='txt_db_user'>DB user</label>
        <input type='text' name='txt_db_user' id='txt_db_user' value='<?php echo $db_user ?>' required/>
    </div>
    <div>
        <label for='txt_db_pass'>DB pass</label>
        <input type='text' name='txt_db_pass' id='txt_db_pass' value='<?php echo $db_pass ?>' required/>
    </div>
    <div>
        <label for='txt_db_name'>DB name</label>
        <input type='text' name='txt_db_name' id='txt_db_name' value='<?php echo $db_name ?>' required/>
    </div>
    <div>
        <label for='txt_vdm_root'>Videomost's root directory</label>
        <input type='text' name='txt_vdm_root' id='txt_vdm_root' value='<?php echo $vdm_root ?>' required/>
    </div>
    <div>
        <label for='txt_vdm_admin_acc'>Videmost admin account</label>
        <input type='text' name='txt_vdm_admin_acc' id='txt_vdm_admin_acc' value='<?php echo $vdm_admin_acc ?>' required/>
    </div>
    <div>
        <label for='txt_vdm_admin_pass'>Videmost admin password</label>
        <input type='text' name='txt_vdm_admin_pass' id='txt_vdm_admin_pass' value='<?php echo $vdm_admin_pass ?>' required/>
    </div>
    <div>
        <label for='txt_sms_url'>SMS URL</label>
        <input type='text' name='txt_sms_url' id='txt_sms_url' value='<?php echo $sms_url ?>' required/>
    </div>
    <br>
    <input type='submit' name='btn_submit' value='Đồng ý'/>
    <input type='reset' value='Cài đặt lại'/>
</form>
