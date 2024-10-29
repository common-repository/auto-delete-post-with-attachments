<?php
/**
 * Plugin Name: Auto Delete Post with Attachments
 * Description: Auto Delete Post with Attachments by wpplugines
 * Plugin URI: 	https://wpplugines.com/
 * Author:      Al Imran Akash
 * Author URI:  https://profiles.wordpress.org/al-imran-akash/
 * Version: 1.0.0
 * Text Domain: auto-delete-post-with-attachments
 * Domain Path: /languages
 *
 * auto_delete_post_with_attachments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * auto_delete_post_with_attachments is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace WPPlugines\auto_delete_post_with_attachments;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Codexpert <hi@codexpert.io>
 */
final class Plugin {
	
	/**
	 * Plugin instance
	 * 
	 * @access private
	 * 
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * The constructor method
	 * 
	 * @access private
	 * 
	 * @since 0.9
	 */
	private function __construct() {
		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Runs actual hooks
		 */
		$this->hook();
	}

	/**
	 * Includes files
	 * 
	 * @access private
	 * 
	 * @uses composer
	 * @uses psr-4
	 */
	private function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 * 
	 * @access private
	 * 
	 * @uses get_plugin_data
	 * @uses plugin_basename
	 */
	private function define() {

		/**
		 * Define some constants
		 * 
		 * @since 0.9
		 */
		define( 'IMSPD', __FILE__ );
		define( 'IMSPD_DIR', dirname( IMSPD ) );
		define( 'IMSPD_ASSET', plugins_url( 'assets', IMSPD ) );
		define( 'IMSPD_DEBUG', apply_filters( 'schedule-post-delete_debug', true ) );

		/**
		 * The plugin data
		 * 
		 * @since 0.9
		 * @var $plugin
		 */
		$this->plugin					= get_plugin_data( IMSPD );
		$this->plugin['basename']		= plugin_basename( IMSPD );
		$this->plugin['file']			= IMSPD;
		$this->plugin['server']			= apply_filters( 'schedule-post-delete_server', 'https://codexpert.io/dashboard' );
		$this->plugin['min_php']		= '5.6';
		$this->plugin['min_wp']			= '4.0';
		$this->plugin['doc_id']			= 1960;
		$this->plugin['icon']			= IMSPD_ASSET . '/img/icon.png';
		$this->plugin['depends']		= [ '' ];

		/**
		 * Set a global variable
		 * 
		 * @global $auto_delete_post_with_attachments
		 */
		global $auto_delete_post_with_attachments;
		$auto_delete_post_with_attachments = $this->plugin;
	}

	/**
	 * Hooks
	 * 
	 * @access private
	 * 
	 * Executes main plugin features
	 *
	 * To add an action, use $instance->action()
	 * To apply a filter, use $instance->filter()
	 * To register a shortcode, use $instance->register()
	 * To add a hook for logged in users, use $instance->priv()
	 * To add a hook for non-logged in users, use $instance->nopriv()
	 * 
	 * @return void
	 */
	private function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 */
			$admin = new Admin( $this->plugin );
			$admin->activate( 'install' );
			$admin->action( 'admin_footer', 'modal' );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->filter( "plugin_action_links_{$this->plugin['basename']}", 'action_links' );

			/**
			 * Settings related hooks
			 */
			$settings = new Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu' );

		else : // !is_admin() ?

			/**
			 * Front facing hooks
			 */
			$front = new Front( $this->plugin );
			$front->action( 'wp_head', 'head' );
			$front->action( 'wp_footer', 'modal' );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );
			$front->action( 'admin_bar_menu', 'add_admin_bar', 70 );

		endif;

		/**
		 * Cron facing hooks
		 */
		$cron = new Cron( $this->plugin );
		$cron->activate( 'install' );
		$cron->deactivate( 'uninstall' );
		$cron->action( 'codexpert-daily', 'daily' );
		// $cron->action( 'plugins_loaded', 'initial_calls' );
		$cron->filter( 'cron_schedules', 'add_intervals' );

		/**
		 * Common hooks
		 *
		 * Executes on both the admin area and front area
		 */
		$common = new Common( $this->plugin );

		/**
		 * AJAX related hooks
		 */
		$ajax = new AJAX( $this->plugin );
		$ajax->priv( 'schedule-post-delete_fetch-docs', 'fetch_docs' );
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @access public
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @access public
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 * 
	 * @access public
	 * 
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();