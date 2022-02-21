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
define( 'DB_NAME', 'wp6418555' );

/** MySQL database username */
define( 'DB_USER', 'wpuser69656' );

/** MySQL database password */
define( 'DB_PASSWORD', 'apJLSHLKQD7mJN7' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'kn+t?tm%$jFhA->yWnA;eRl+5C|TU[G^)H+@VVT;uK$>ZRa:_&5F~Ddq *w$qCMc' );
define( 'SECURE_AUTH_KEY',   'oq_}x]B>V[_fign*e&A$7pa(U?3};?XWRdEg7Km s8)&Fu_-&h5u~vD@]RV<VV}M' );
define( 'LOGGED_IN_KEY',     '1 p*zHgK`N89ma(O ;Sk&BL^>0.:fDEtXHq8I/rI`xFCo.>rSf?gQ0hOoGE=?+h.' );
define( 'NONCE_KEY',         'hH3$&0Z):T-_*^Irv}rBY<m,YX,,lxIn1lWWt&H&TbY=_$,X%p0t0C3OOwxM]4AM' );
define( 'AUTH_SALT',         'n1%a=Is/+3uC|=91:[vs/`O[n6F4`G0hwc.y.`V(_mVux-u`%ulOm`?V6*NPU2j5' );
define( 'SECURE_AUTH_SALT',  '6&s7!x[hxx!aWb4HKaz2H>BM~zF):TK6+n>d!c R!G4R`3=_5.#5 X!i_pOsBpm/' );
define( 'LOGGED_IN_SALT',    'm[O#cQk[{{=qa,nfhgf0{+:25+YbqWM=R5pNCF2^+5#7`P2iVKly=18%:|RJT::I' );
define( 'NONCE_SALT',        ':kHJcYvpa<f:)K0,4be9HuLjiebC Fn))7LucxYXc}$D0e2kPJ_G]9@.pfO3DL1:' );
define( 'WP_CACHE_KEY_SALT', '1{s@hSm4[QU;l3be!gD3;,!5O!:gKge;l)5rIbH8J#I.7aV&h4gC.u}mI=K$<F+i' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


define( 'FORCE_SSL_ADMIN', false );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
