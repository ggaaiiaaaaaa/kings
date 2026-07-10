<?php
require_once( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/wp-load.php' );

echo "<pre>";
global $wp_roles;
if ( ! isset( $wp_roles ) ) {
    $wp_roles = new WP_Roles();
}
$roles = $wp_roles->roles;
echo "Available Roles:\n";
foreach($roles as $role => $details) {
    echo "- " . $role . "\n";
}
echo "</pre>";
