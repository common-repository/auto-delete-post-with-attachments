<?php
/**
 * All public facing functions
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
 * @subpackage Front
 * @author Codexpert <hi@codexpert.io>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	public function add_admin_bar( $admin_bar ) {
		if( ! current_user_can( 'manage_options' ) ) return;

		$admin_bar->add_menu( [
			'id'    => $this->slug,
			'title' => $this->name,
			'href'  => add_query_arg( 'page', $this->slug, admin_url( 'admin.php' ) ),
			'meta'  => [
				'title' => $this->name,            
			],
		] );
	}

	public function head() {
		// $schedule = Helper::get_option( 'schedule-post-delete_basic', 'schedule' );
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'IMSPD_DEBUG' ) && IMSPD_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", IMSPD ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", IMSPD ), [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce(),
		];
		wp_localize_script( $this->slug, 'IMSPD', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function modal() {
		echo '
		<div id="schedule-post-delete-modal" style="display: none">
			<img id="schedule-post-delete-modal-loader" src="' . esc_attr( IMSPD_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}
}