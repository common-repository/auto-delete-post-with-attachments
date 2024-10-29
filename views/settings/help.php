<?php
$base_url 	= schedule_post_delete_site_url();
$buttons 	= [
	'changelog' => [
		'url' 	=> 'https://wordpress.org/plugins/schedule-post-delete/#developers',
		'label' => __( 'Changelog', 'schedule_post_delete' ) 
	],
	'community' 	=> [
		'url' 	=> 'https://facebook.com/groups/codexpert.io',
		'label' => __( 'Community', 'schedule_post_delete' ) 
	],
	'website' 	=> [
		'url' 	=> 'https://codexpert.io/',
		'label' => __( 'Official Website', 'schedule_post_delete' ) 
	],
	'support' 	=> [
		'url' 	=> 'https://help.codexpert.io/',
		'label' => __( 'Ask Support', 'schedule_post_delete' ) 
	],
];
$buttons 	= apply_filters( 'schedule-post-delete_help_btns', $buttons );
?>
<script type="text/javascript">
	jQuery(function($){ $.get( ajaxurl, { action : 'schedule-post-delete_fetch-docs' }); });
</script>
<div class="schedule-post-delete-help-tab">
	<div class="schedule-post-delete-documentation">
		 <div class='wrap'>
		 	<div id='schedule-post-delete-helps'>
		    <?php

		    $helps = get_option( 'schedule-post-delete_docs_json', [] );
			$utm = [ 'utm_source' => 'dashboard', 'utm_medium' => 'settings', 'utm_campaign' => 'faq' ];
		    if( is_array( $helps ) ) :
		    foreach ( $helps as $help ) {
		    	$help_link = add_query_arg( $utm, $help['link'] );
		        ?>
		        <div id='schedule-post-delete-help-<?php echo esc_attr( $help['id'] ); ?>' class='schedule-post-delete-help'>
		            <h2 class='schedule-post-delete-help-heading' data-target='#schedule-post-delete-help-text-<?php echo esc_attr( $help['id'] ); ?>'>
		                <a href='<?php echo esc_url( $help_link ); ?>' target='_blank'>
		                <span class='dashicons dashicons-admin-links'></span></a>
		                <span class="heading-text"><?php echo esc_html( $help['title']['rendered'] ); ?></span>
		            </h2>
		            <div id='schedule-post-delete-help-text-<?php echo esc_attr( $help['id'] ); ?>' class='schedule-post-delete-help-text' style='display:none'>
		                <?php echo wpautop( wp_trim_words( $help['content']['rendered'], 55, " <a class='sc-more' href='" . esc_url( $help_link ) . "' target='_blank'>[more..]</a>" ) ); ?>
		            </div>
		        </div>
		        <?php

		    }
		    else:
		        _e( 'Something is wrong! No help found!', 'schedule-post-delete' );
		    endif;
		    ?>
		    </div>
		</div>
	</div>
	<div class="schedule-post-delete-help-links">
		<?php 
		foreach ( $buttons as $key => $button ) {
			$button_url = add_query_arg( $utm, $button['url'] );
			echo "<a target='_blank' href='" . esc_url( $button_url ) . "' class='schedule-post-delete-help-link'>" . esc_html( $button['label'] ) . "</a>";
		}
		?>
	</div>
</div>

<?php do_action( 'schedule-post-delete_help_tab_content' ); ?>