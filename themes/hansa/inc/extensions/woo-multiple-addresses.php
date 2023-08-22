<?php
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$tablename = $wpdb->prefix . 'wc_multiple_addresses';
$sql = "CREATE TABLE $tablename (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    userid TEXT NOT NULL,
    userdata TEXT NOT NULL,
    type TEXT NOT NULL,
    PRIMARY KEY (id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

function hansa_profile_get_addresses($user_id) {
	global $wpdb;
	$tablename = $wpdb->prefix . 'wc_multiple_addresses';
	$user_addresses = $wpdb->get_results( "SELECT * FROM {$tablename} WHERE type='shipping' AND userid=" . $user_id );
	return $user_addresses;
}