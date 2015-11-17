<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_087');

/** MySQL database username */
define('DB_USER', 'wordpress_087');

/** MySQL database password */
define('DB_PASSWORD', '2md5$U0KjF');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'PT@v!rkQQFWFx*Yy36lTAfTld5&%PAt7#WsZ!%RMw1*GroFbb$Ddet(Q9TU^$a1^');
define('SECURE_AUTH_KEY',  '(j^Q@)mD51yP89wIxKAU^B4UyRZF6ctlAnfG%hnPPY^Mgt6*LdrleMSQSBW5(%vN');
define('LOGGED_IN_KEY',    'qXv3#fxxZNrp%avltxbyD1ppMOlsAskm1lvLi$opOSNgAZii#5ntnwA^9r&&wFFE');
define('NONCE_KEY',        'bnlFqdlZL15#NGKtzZcHhyj7#MI!BqTy5YQpzR@H5uDQ^fzi$X2YgOycfv6Uwkx^');
define('AUTH_SALT',        '$e)Jso7QnWFaRv3)olXtFGQ8Nzs7El#*#lLT7EY#85444rH9qIjudJWcbx*GshS9');
define('SECURE_AUTH_SALT', 'v(HI5(EFgesjD@dP8rvaDMRmFGv*ek)Utq#kEf2Wz4$E1Gxev#mpSzX^9UEr4NlD');
define('LOGGED_IN_SALT',   'x4t1KjWxexhkWNI&hQm7ukLvmdXaZPXh)tCBLH*F3gnVNG6aqjEngSPL9nDeXFIb');
define('NONCE_SALT',       'ceXHio*XAYV2nmZgcc&Tgo%&(R@ppxkNLw3r(mM7QNJ0gn*62Kz&nzr%D)AYfgpI');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'en_US');

define ('FS_METHOD', 'direct');

define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>
