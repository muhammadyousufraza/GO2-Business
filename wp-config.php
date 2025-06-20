<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '>xd%=`e|fw6cAE3h$)$6AK6PKqRLmH3+F;1axW25ws/SIY(&wiOyvz-TVM3{cgN8' );
define( 'SECURE_AUTH_KEY',   'K-dQP,0}g^:c$zb!a*{hs9),pm{VUxxI#s$[ihu&WY{(HvtzC0}XLTNZYB31l?N`' );
define( 'LOGGED_IN_KEY',     ')O+IKz66Amhi(l8mCvsY~`YIl?1FWB2FQsG9<#6-.%}&km73R -ndJcUN^pi9Sz+' );
define( 'NONCE_KEY',         '*f`.=)>),Y!Azxw)1z~8.@P0mr%&Pbs#SbLM-58g{WVX0x%H7}+goL]:I^5&vp% ' );
define( 'AUTH_SALT',         '5WDD{bVb0<gn.!drQeaqnr/q~oxOc@4Q)0d$;+aJ2monV8gCLiF)<_s-/KW0EA1z' );
define( 'SECURE_AUTH_SALT',  'QK=|H@j|IA]ftDhDC+lzl!kB2[x_n%#y+-$IY.5_JIR5]8cO]AXx4|4<MM(7k0tF' );
define( 'LOGGED_IN_SALT',    'T!71ytMHaLCQm165~MnQ$@nSTmgr^-s:Kmvv]mBO*#W&u-m1N9(839@Epf &@aA!' );
define( 'NONCE_SALT',        ')x5DO;uKsio[*MBbm` )h(3zGNs))o~<A1j+^*,2F_P0Nk21Thnw/=<xO/I>aon1' );
define( 'WP_CACHE_KEY_SALT', ' =|1a%dIw.Z;:E35y(lGcB>V{__7FL}uI,>R|S@~#Gs%v>Rjq-EY*G>;UJ8uH]ak' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
