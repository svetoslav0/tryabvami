<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
define('WP_CACHE', false);
define('WP_HOME','http://localhost/');
define('WP_SITEURL','http://localhost/');
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tryabvam_wp416' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'hy3covvicbxmolg58fsqjw2bgkulvvtdgghykl3tft4hrhanbcuhk0vfjw4zy7ok' );
define( 'SECURE_AUTH_KEY',  'gx7zzotveakpjh8nmpfsxav67dbaw5q1i5cwijj1kj8lx9copcpzmfltwaqnxp5u' );
define( 'LOGGED_IN_KEY',    'vqipvnidahfgciwdpdifif4qpnlg0x2eimarpzspaw4y88xlku1dfyaqb1zuvcej' );
define( 'NONCE_KEY',        '6p1szjbbtlipbnb3x3ovzwzcftpmfl7wqw2jmhb8w0dmqhrv0x5hpxm1d0gq7csm' );
define( 'AUTH_SALT',        'xon8sjzzei2gfzknsuphdvrhgpdyblfifhcakyprx30czar9vacxaql69fka4mrr' );
define( 'SECURE_AUTH_SALT', 'cfvxucdc0e1r8ykrh4xlhjrm3u1updefagnl7sfz9zkqc1un8ofyrkthhjdcx9jm' );
define( 'LOGGED_IN_SALT',   'egejvmhzftfmddmbpsvq9zbkmuocmdnlkbmnxrkjgyzhegqq1djjxo2pmt66f3ip' );
define( 'NONCE_SALT',       'nc2mwvmpbidthzdagyqkihhavcgaucdadinnps7jlry2lhrprz29qgagkjkednuw' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpg0_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
