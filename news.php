<?php
/**
 * News Archive Redirect (Shim)
 * Allows the standalone shim to handle home_url('/news/') by including index.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'index.php';
} else {
    // In WordPress, index.php is already the archive. 
    // This file acts as a fallback for the /news/ slug if no page is assigned.
    include get_template_directory() . '/index.php';
}
