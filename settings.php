<?php 

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    $edit_name_success=$edit_password_success="";
    $edit_name_error=$edit_password_error="";
    $settings_fname_value=$settings_lname_value=$settings_oldpass_value=$settings_newpass_value="";
    $mainTitle="settings";
    include ROOT.'/inc/user_cred.php';
    include ROOT.'/inc/edit_settings.php';
    include ROOT.'/inc/header.php';
?>       
        <div id="settings_main_wrapper">
            <div id="settings_header">
                Settings
            </div>
            <form action="" id="settings_edit_form" method="post" onsubmit="return checkSettingsEditForm()">
                <div id="settings_unchangeable">
                    <div class="settings_edit_input_wrapper"><span class="settings_registered">Your Name:</span><?php echo $fname_db." ".$lname_db; ?></div>
                    <div class="settings_edit_input_wrapper"><span class="settings_registered">Registered Email:</span><?php echo $email_db;?></div>
                    <div class="settings_edit_input_wrapper"><span class="settings_registered">Your Username:</span><?php echo $username_db;?></div>
                </div>
                <div class="editing_settings" id="edit_name">
                    <div class="settings_ask">Edit name:</div>
                    <div class="settings_edit_input_wrapper"><span class="settings_input_span">First Name:</span><input type="text" value="<?php echo $settings_fname_value;?>" name="edit_first_name"/></div>
                    <div class="settings_edit_input_wrapper"><span class="settings_input_span">Last Name:</span><input type="text" value="<?php echo $settings_lname_value;?>" name="edit_last_name"/></div>
                    <aside class="settings_error" id="name_error_settings"><?php echo $edit_name_error;?></aside>
                    <aside class="settings_success" id="name_success_settings"><?php echo $edit_name_success;?></aside>
                </div>
                <div class="editing_settings" id="edit_password">
                    <div class="settings_ask">Change password:</div>
                    <div class="settings_edit_input_wrapper"><span class="settings_input_span">Your Password:</span><input type="password" value="<?php echo $settings_oldpass_value;?>" name="edit_check_pass"/></div>
                    <div class="settings_edit_input_wrapper"><span class="settings_input_span">New Password:</span><input type="password" value="<?php echo $settings_newpass_value;?>" name="edit_new_pass"/></div>
                    <div class="settings_edit_input_wrapper"><span class="settings_input_span">Confirm New Password:</span><input type="password" name="edit_confirm_pass"/></div>
                    <aside class="settings_error" id="password_error_settings"><?php echo $edit_password_error;?></aside>
                    <aside class="settings_success" id="password_success_settings"><?php echo $edit_password_success;?></aside>
                </div>
                <div id="submit_edit_settings">
                    <div id="submit_edit_settings_button_Wrapper">
                        <input type="submit" value="Save" name="settings_submit"/><span id="go_back_settings">or go back to the <a href="<?php echo $username_db?>">root</a></span>
                    </div>
                </div>
            </form> 

            <div id="other_settings">
                <p>Want <a href="help">help</a>? | Wanna <a href="edit">edit other informations</a>?</p>
            </div>
            
        </div>
        <footer class="settingsFooter">
            <div class="settingsFooterInner">
                <?php include ROOT.'/inc/footer.php';?>
            </div>
        </footer>
    </body>
</html>