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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wyzxuaam_wp417' );

/** MySQL database username */
define( 'DB_USER', 'wyzxuaam_wp417' );

/** MySQL database password */
define( 'DB_PASSWORD', 'pS!774@9r8' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'aoyl4tekfixojypsjuysedb4mspqeohwhscytq2rxr4udpxwp6eulfuepd2ymsgz' );
define( 'SECURE_AUTH_KEY',  'htrgv9me6uognfr4npj4nnx56dxws622e6qlldzxq5j5nxsufrqmvubnfrjzpu2f' );
define( 'LOGGED_IN_KEY',    '6ee90htuyqgqlemi0w25kwgafjmsbpzvhteitbqkmqjsljslrxynxgavuydbm4ac' );
define( 'NONCE_KEY',        'ije4idvspyzx8k6bx7rqho9rgw2lodbvzzmvw1arbikinfu6oysicvga8twow7ob' );
define( 'AUTH_SALT',        'iqtmde6hmqfdb7jq66tame7gath1lcxulwvqzjfaglsjwtsy1chesjyimuc70x81' );
define( 'SECURE_AUTH_SALT', 'otzebfj0ltmbrxcwoepnnsvblhlxnfhnbtnb1fh0tzb0ercvvxbkw3xgwdz74fr4' );
define( 'LOGGED_IN_SALT',   'ncfwjtsmqb3kzofsdm8iocmbdjn8kvjr0nubxapb3qak8hjnny2nvwnylvg8hosf' );
define( 'NONCE_SALT',       'xnrswylhgruyxrvmcsa0n3oehghqz4hndedl4t1syu671fcnts32saca5ibnyooc' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpl2_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
