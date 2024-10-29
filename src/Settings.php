<?php
/**
 * All settings related functions
 */
namespace WPPlugines\auto_delete_post_with_attachments;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Settings as Settings_API;

/**
 * @package Plugin
 * @subpackage Settings
 * @author Codexpert <hi@codexpert.io>
 */
class Settings extends Base {

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
	
	public function init_menu() {

		$settings = [
			'id'            => $this->slug,
			'label'         => $this->name,
			'title'         => "{$this->name}",
			'header'        => $this->name,
			// 'parent'     => 'woocommerce',
			// 'priority'   => 10,
			// 'capability' => 'manage_options',
			// 'icon'       => 'dashicons dashicons-trash',
			// 'position'   => 25,
			// 'topnav'	=> true,
			'sections'      => [
				'schedule-post-delete_basic'	=> [
					'id'        => 'schedule-post-delete_basic',
					'label'     => __( 'Basic Settings', 'auto-delete-post-with-attachments' ),
					'icon'      => 'dashicons dashicons-trash',
					// 'color'		=> '#4c3f93',
					'sticky'	=> false,
					'fields'    => [
						'post_status' => [
							'id'      => 'post_status',
							'label'     => __( 'Post Status', 'auto-delete-post-with-attachments' ),
							'type'      => 'checkbox',
							'desc'      => __( 'Select the post status whice you waint to delete posts.', 'auto-delete-post-with-attachments' ),
							// 'class'     => '',
							'options'   => [
								'draft'   => __( 'Draft' ),
						        'pending' => __( 'Pending Review' ),
						        'private' => __( 'Private' ),
						        'publish' => __( 'Published' ),
							],
							'default'   => [ 'publish' ],
							'disabled'  => false, // true|false
							'multiple'  => true, // true|false
						],
						'delete_posts' => [
							'id'        => 'delete_posts',
							'label'     => __( 'Delete Posts', 'auto-delete-post-with-attachments' ),
							'type'      => 'number',
							'default'   => 10,
							'desc'      => __( 'Deletion all posts whice are some days.', 'auto-delete-post-with-attachments' ),
						],
						'force_delete' => [
							'id'        => 'force_delete',
							'label'     => __( 'Force Delete', 'auto-delete-post-with-attachments' ),
							'type'      => 'switch',
							'desc'      => __( 'The post is moved to Trash instead of permanently deleted unless Trash is disabled.', 'auto-delete-post-with-attachments' ),
						],
						'attachment_delete' => [
							'id'        => 'attachment_delete',
							'label'     => __( 'Delete with Attachments', 'auto-delete-post-with-attachments' ),
							'type'      => 'switch',
							'desc'      => __( 'Deletion all post meta fields, taxonomy, comments, etc. associated with the attachment (except the main post).', 'auto-delete-post-with-attachments' ),
						],
						'schedule' 		=> [
							'id'      	=> 'schedule',
							'label'     => __( 'Schedule', 'auto-delete-post-with-attachments' ),
							'type'      => 'select',
							// 'class'     => '',
							'options'   => [
								'minutely' 		=> __( 'Minutely', 'auto-delete-post-with-attachments' ),
								'hourly'  		=> __( 'Hourly', 'auto-delete-post-with-attachments' ),
								'daily'  		=> __( 'Daily', 'auto-delete-post-with-attachments' ),
								'twicedaily'  	=> __( 'Twicedaily', 'auto-delete-post-with-attachments' ),
								'weekly'  		=> __( 'Weekly', 'auto-delete-post-with-attachments' ),
								'fifteendays'  	=> __( 'Every 15 Days', 'auto-delete-post-with-attachments' ),
								'monthly'  		=> __( 'Monthly', 'auto-delete-post-with-attachments' ),
								'yearly'  		=> __( 'Yearly', 'auto-delete-post-with-attachments' ),
							],
							'default'   => 'daily',
							'disabled'  => false, // true|false
							'multiple'  => false, // true|false
							'desc'      => __( 'Select a time when schedule run.', 'auto-delete-post-with-attachments' ),
						],
					]
				],
			],
		];

		new Settings_API( $settings );
	}
}