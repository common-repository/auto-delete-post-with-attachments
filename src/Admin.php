<?php
/**
 * All admin facing functions
 */
namespace WPPlugines\auto_delete_post_with_attachments;
use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Codexpert <hi@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'auto-delete-post-with-attachments', false, IMSPD_DIR . '/languages/' );
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {

		if( ! get_option( 'schedule-post-delete_version' ) ){
			update_option( 'schedule-post-delete_version', $this->version );
		}
		
		if( ! get_option( 'schedule-post-delete_install_time' ) ){
			update_option( 'schedule-post-delete_install_time', time() );
		}
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'IMSPD_DEBUG' ) && IMSPD_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", IMSPD ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", IMSPD ), [ 'jquery' ], $this->version, true );
	}

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'admin.php' );

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'auto-delete-post-with-attachments' ) . '</a>', add_query_arg( 'page', $this->slug, $this->admin_url ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function modal() {
		echo '
		<div id="schedule-post-delete-modal" style="display: none">
			<img id="schedule-post-delete-modal-loader" src="' . esc_attr( IMSPD_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}
}