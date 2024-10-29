<?php
/**
 * All cron related functions
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
 * @subpackage Cron
 * @author Codexpert <hi@codexpert.io>
 */
class Cron extends Base {

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
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {

	    $schedule = Helper::get_option( 'schedule-post-delete_basic', 'schedule' );

		/**
		 * Schedule an event to sync help docs
		 */
		if ( ! wp_next_scheduled ( 'codexpert-daily' )) {
		    wp_schedule_event( time(), $schedule, 'codexpert-daily' );
		}
	}

	/**
	 * Uninstaller. Runs once when the plugin in deactivated.
	 *
	 * @since 1.0
	 */
	public function uninstall() {
		/**
		 * Remove scheduled hooks
		 */
		wp_clear_scheduled_hook( 'codexpert-daily' );
	}

	// public function initial_calls() {
	// 	$this->daily();
	// }

	/**
	 * Daily events
	 */
	public function daily() {
		$post_status 		= Helper::get_option( 'schedule-post-delete_basic', 'post_status' );
		$delete_posts 		= Helper::get_option( 'schedule-post-delete_basic', 'delete_posts' );
		$schedule 			= Helper::get_option( 'schedule-post-delete_basic', 'schedule' );
		$force_delete 		= Helper::get_option( 'schedule-post-delete_basic', 'force_delete' );
		$attachment_delete 	= Helper::get_option( 'schedule-post-delete_basic', 'attachment_delete' );
	    $posts 				= Helper::get_posts( [ 'post_status' => $post_status, 'date_query' => [ [ 'before' => $delete_posts . ' days ago' ] ] ] );

	    foreach ( $posts as $post_id => $post_name ) {

	    	if( get_post_type( $post_id ) == 'post' && $attachment_delete = 'on' ) {
			    $attachments = get_attached_media( '', $post_id );
			    
			    foreach ( $attachments as $attachment ) {
			      	wp_delete_attachment( $attachment->ID, 'true' );
			    }
			}

	    	wp_delete_post( $post_id, $force_delete );
	    }	    
	}

	public function add_intervals( $schedules ) {

	    $schedules['minutely'] = array(
	        'interval' 	=> MINUTE_IN_SECONDS,
	        'display' 	=> __('Minutely')
	    );

	    $schedules['hourly'] = array(
	        'interval' 	=> HOUR_IN_SECONDS,
	        'display' 	=> __('Hourly')
	    );
		
	    $schedules['weekly'] = array(
	        'interval' 	=> WEEK_IN_SECONDS,
	        'display' 	=> __('Weekly')
	    );

	    $schedules['monthly'] = array(
	        'interval' 	=> MONTH_IN_SECONDS,
	        'display' 	=> __('Monthly')
	    );
	    
	    $schedules['yearly'] = array(
	        'interval' 	=> YEAR_IN_SECONDS,
	        'display' 	=> __('Monthly')
	    );

	    return $schedules;
	}
}