<?php

add_action('admin_menu', 'ikit_menu_change_password_menu_add', 10);

function ikit_menu_change_password_menu_add() {
    add_submenu_page( null, 'Change Password', 'Change Password', 'read', 'ikit-change-password', 'ikit_menu_change_password_render');
}

function ikit_menu_change_password_render() {

    $processed_post = ikit_menu_change_password_process_post();

    ?>
        <style type="text/css">

        /* Remove layout */
        body.wp-admin {
            min-width:0;
        }

        #adminmenuback, #adminmenuwrap, #wphead, #adminmenu, #footer, #wpfooter, #wpadminbar, .update-nag {
            display:none;
        }
        #wpcontent {
            padding:0 !important;
            margin:0 !important;
        }
        html {
            padding:0 !important;
            margin:0 !important;
            background-color:#FEF03D;
        }

        /* Customs styles */

        .header {
            font-size:20px;
            line-height:24px;
            border-bottom:1px solid #000;
            text-transform: uppercase;
        }

        .content, .header {
            padding:50px;
        }

        .content {
            max-width:600px;
        }

        .content-help {
            font-size:40px;
            text-transform:uppercase;
            line-height:44px;
            margin-bottom:30px;
            width:80%;
        }

        .content-help-sub {
            padding-top:10px;
            font-size:20px;
            line-height:24px;
        }

        .field-label {
            text-transform: uppercase;
            font-size:12px;
            padding-bottom:5px;
        }

        .field-input-container {

        }

        .field-input-container input, .field-input-container input:hover, .field-input-container input:focus {
            border:1px solid #000;
            background-color:transparent;
            border-radius:0;
            font-size:12px;
            outline:0;
            padding:10px;
            margin-bottom:20px;
            width:100%;
        }

        .field-submit-container {
            text-align:center;
         }


        .field-submit-container input {
            border:1px solid #000;
            background-color:transparent;
            padding:10px;
            margin-top:20px;
            text-transform: uppercase;
            outline:none;
            cursor: pointer;
            border-radius:0;
        }

        .errors {
            margin-top:20px;
            margin-bottom:20px;
        }

        .error-message {
            font-size:12px;
            font-weight:bold;
            color:#00aeef;
            text-transform: uppercase;
            padding-top:3px;
            padding-bottom:3px;
        }

        </style>


        <div class="layout">

            <div class="header"><b>iKit Security</b> Admin password reset</div>
            <div class="content">

                <div class="content-help">
                Please change your password before continuing.
                <div class="content-help-sub">
                You will be redirected to the login screen once completed.
                </div>
                </div>



                <?php if($processed_post != null && count($processed_post['errors']) > 0) { ?>
                <div class="errors">
                    <?php foreach($processed_post['errors'] as $error) { ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php } ?>
                </div>
                <?php } ?>

                <form method="post" action="">

                    <div class="field-label"><label>Password</label></div>
                    <div class="field-input-container"><input name="password" type="password"/></div>

                    <div class="field-label"><label>Confirm password</label></div>
                    <div class="field-input-container"><input name="password_confirm" type="password"/></div>

                    <div class="field-submit-container">
                        <input type="hidden" name="action" value="ikit_menu_change_password"/>
                        <input type="submit" name="submit"/>
                    </div>

                </form>

            </div>

        </div>


    <?php

}

// Redirect if successful submission
add_action('init', 'ikit_menu_change_password_init');
function ikit_menu_change_password_init() {


    $processed_post = ikit_menu_change_password_process_post();
    if($processed_post != null) {
        if(count($processed_post['errors']) <= 0) {

            $user = wp_get_current_user();

            // Set password
            wp_set_password($processed_post['password'], $user->ID);

            // Set meta for date when last updated
            update_user_meta($user->ID, 'ikit_change_password_datetime', date("Y-m-d H:i:s"));

            wp_redirect(admin_url());
            exit();

        }
    }

}

// Process post if form submission
function ikit_menu_change_password_process_post() {

    if(isset($_POST['submit']) && $_POST['action'] == 'ikit_menu_change_password') {

        $errors = array();
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if(empty($password)) {
            array_push($errors, 'Please enter a password.');
        }
        else {
            // Has password
            if(empty($password_confirm)) {
                array_push($errors, 'Please enter a password confirmation.');
            }
            else {
                // Has password confirm
                if($password != $password_confirm) {
                    array_push($errors, 'Your password and password confirmation do not match.');
                }
                else {
                    // Passwords match
                    $contains_lowercase_letter = preg_match('/[a-z]/', $password);
                    $contains_uppercase_letter = preg_match('/[A-Z]/', $password);
                    $contains_number = preg_match('/[0-9]/', $password);
                    $valid_length = strlen($password) < 12 ? false : true;

                    if($valid_length == false || $contains_lowercase_letter == false || $contains_uppercase_letter == false || $contains_number == false) {

                        array_push($errors, 'Your password must be at least 12 characters in length containing at least one lowercase letter, one uppercase letter, and one number.');

                        if($valid_length == false) {
                            array_push($errors, 'Password is less than 12 characters.');
                        }
                        if($contains_lowercase_letter == false) {
                            array_push($errors, 'Password is missing lowercase letter.');
                        }
                        if($contains_uppercase_letter == false) {
                            array_push($errors, 'Password is missing uppercase letter.');
                        }
                        if($contains_number == false) {
                            array_push($errors, 'Password is missing number.');
                        }


                    }
                    else {

                        $user = wp_get_current_user();
                        if(wp_check_password($password, $user->data->user_pass, $user->ID)) {

                            // Password is the old one
                            array_push($errors, 'You cannot use your existing password.');
                        }
                        else {

                            // No errors!
                        }

                    }
                }

            }

        }

        return array(
                "errors" => $errors,
                "password" => $password
        );

    }
    else {

        return null;

    }

}

?>