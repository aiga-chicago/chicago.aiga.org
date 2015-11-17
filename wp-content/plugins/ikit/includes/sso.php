<?php

/**
 * Generate the SSO redirect URL, the user will then be redirected to a login page on aiga.org
 * after successful login, they will then be redirected back to the redirect URL with the
 * neccessary credentials in the URL and also in the cookies.
 *
 * The cookie data can then be used for authentication to use the webservice for more user information:
 *
 * http://www.aiga.org/services/vangoservice.asmx
 *
 */
function ikit_sso_get_login_url($redirect_url) {

    return IKIT_LOGIN_BRIDGE_URL . '?' . IKIT_LOGIN_BRIDGE_REDIRECT_PARAM_NAME . '=' . $redirect_url;

}

/**
 * Use the key that appears in the URL when redirected back from the SSO for this function, it will
 * then return the user details including member type, name, email address etc.
 */
function ikit_sso_get_user($key) {

    $get_user_url = IKIT_AIGA_MEMBER_API_BASE_URL . '/GetUser' . '?' . IKIT_AIGA_MEMBER_API_TOKEN_PARAM_NAME . '=' . IKIT_AIGA_MEMBER_API_TOKEN . '&' . IKIT_AIGA_MEMBER_API_KEY_PARAM_NAME . '=' . $key;

    $curl_get_user_request = curl_init();
    curl_setopt($curl_get_user_request, CURLOPT_URL, $get_user_url);
    curl_setopt($curl_get_user_request, CURLOPT_RETURNTRANSFER, true);
    $curl_get_user_response = curl_exec($curl_get_user_request);
    curl_close($curl_get_user_request);

    return simplexml_load_string($curl_get_user_response);

}

?>