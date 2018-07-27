<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

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
define('DB_NAME', 'heavent1_sanktext');

/** MySQL database username */
define('DB_USER', 'heavent1_heavent');

/** MySQL database password */
define('DB_PASSWORD', 'secure2010%%');

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
define('AUTH_KEY',         'keunmdbuuk8uxlwcjzk4cvq0orqpcj8pxmmwccv4okye2lts0q1ymvwww7nxwb8c');
define('SECURE_AUTH_KEY',  'oqt3m5mz47f2cgochdrubxerxq4mvmiyrroyyeigfasvqixtiwq9cved6ksiywlz');
define('LOGGED_IN_KEY',    '8nnq4jwk9ku9wzyn86s9hoxh1gdvn54x8bca6qoq8t3m3a0nkfuzfbtgxjs6wrg7');
define('NONCE_KEY',        'iffl5novaiwknjwo4pkhuyf3jakkqa9pktbjkerotcorsmojregjan8usqhjyyk8');
define('AUTH_SALT',        'lpwvrcpv6apwwtsohf7xsr89ubmynu52midp6ksytlbxfvw7wk9lobyvmm8om05u');
define('SECURE_AUTH_SALT', '7px2pjrsbhklbujqsvz4jrcph45nyyp3phzhwc68e4c3wk9pslmgtdpbflhja7ug');
define('LOGGED_IN_SALT',   'ssgxb4ishevqnigpmakvycf1a25uqd6mejtse6tptbzp13omef8udflwqcwl6x81');
define('NONCE_SALT',       'eptecyrbyq7mzq2ouddr2ihkozbdozii2mdhifl9gqzcdligw0b8ri3d4bowua79');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tsvu_';

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
