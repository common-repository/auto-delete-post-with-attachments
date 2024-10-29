jQuery(function($){
	$('.schedule-post-delete-help-heading').click(function(e){
		var $this = $(this);
		var $target = $this.data('target');
		$('.schedule-post-delete-help-text:not('+$target+')').slideUp();
		if($($target).is(':hidden')){
			$($target).slideDown();
		}
		else {
			$($target).slideUp();
		}
	});

	$('#schedule-post-delete_report-copy').click(function(e) {
		e.preventDefault();
		$('#schedule-post-delete_tools-report').select();

		try {
			var successful = document.execCommand('copy');
			if( successful ){
				$(this).html('<span class="dashicons dashicons-saved"></span>');
			}
		} catch (err) {
			console.log('Oops, unable to copy!');
		}
	});
})