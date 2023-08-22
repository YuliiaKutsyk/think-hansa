<?php

defined( 'CUSTOM_THEME_URI' )    or define( 'CUSTOM_THEME_URI', get_template_directory_uri() );
defined( 'CUSTOM_THEME_PATH' ) or define( 'CUSTOM_THEME_PATH', get_template_directory() );
defined( 'CUSTOM_INC_PATH' ) or define( 'CUSTOM_INC_PATH', CUSTOM_THEME_PATH . '/inc' );
defined( 'CUSTOM_EXT_PATH' ) or define( 'CUSTOM_EXT_PATH', CUSTOM_THEME_PATH . '/inc/extensions' );
defined( 'CUSTOM_LIB_PATH' ) or define( 'CUSTOM_LIB_PATH', CUSTOM_THEME_PATH . '/libs' );

require_once CUSTOM_INC_PATH . '/theme-config.php';
require_once CUSTOM_INC_PATH . '/template-functions.php';
require_once CUSTOM_INC_PATH . '/ajax-functions.php';
require_once CUSTOM_INC_PATH . '/wc-customizer.php';
require_once CUSTOM_INC_PATH . '/google_analytics_events.php';

// Extensions
require_once CUSTOM_EXT_PATH . '/woo-multiple-addresses.php';
require_once CUSTOM_EXT_PATH . '/sso-login.php';
require_once CUSTOM_EXT_PATH . '/loyale.php';
require_once CUSTOM_EXT_PATH . '/sage-json-import.php';
require_once CUSTOM_EXT_PATH . '/woo-import-ext.php';
