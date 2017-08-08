<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

define('SAVEQUERIES', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '#(D<=vdma2xO3:mNw3NYz@ygiYN@o$>>.3dJ@_I-Q>>NyO>^LBaTw2Hi8[E:ox_k');
define('SECURE_AUTH_KEY',  'X&=mB;]Ye4.Xs#~wyP6JIpI=^y<pnj$vU{G7qFVBjkU(38kQ}eVRrM,@2}.+]cZ`');
define('LOGGED_IN_KEY',    '-v+!1Uhrk#S#]1JcFquW`BOw)},;I4Iw-omJjx6K%!BvfJvG7[}f#BPL.)@=U{PK');
define('NONCE_KEY',        'VNLr9e7VkD9)mzs<?xLk-L_,wIs9}Gs_?pOMtSbDgDJrZp^|_1Um-VWNBy:S${Gh');
define('AUTH_SALT',        '3{^>dFrbO{nA;Mtze:S%pVsz*8bp>$r ]dU2:rH}TcYH^!g(8*(6F;RCmhGlCvMa');
define('SECURE_AUTH_SALT', 'Oaeq*.0?UkY6zv6_^ud&`=3HY_o8R/I9m XXH1L]UF{=Adu83.W4P=. a?r?ta(k');
define('LOGGED_IN_SALT',   'z[BY}_!<{yV:XM>a^wYr)7h%SUI_^`sPIy_05ZN99.(6Ik+zK]cS MMkB&Bu)g.B');
define('NONCE_SALT',       'b}a(Zx7);W~ZAzcP`d~A ne@4l]DySSSNy#;:s?YI#;],?H|iBvAs~fJ rgoFPIY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
