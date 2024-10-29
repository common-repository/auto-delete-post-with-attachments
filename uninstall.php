<?php
/**
 * Perform when the plugin is being uninstalled
 */

// If uninstall is not called, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$deletable_options = [ 'schedule-post-delete_version', 'schedule-post-delete_install_time', 'schedule-post-delete_docs_json', 'codexpert-blog-json' ];
foreach ( $deletable_options as $option ) {
    delete_option( $option );
}