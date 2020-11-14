(function($) {

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$('form').each(function() {
		var cmdcode = $(this).find('input[name="cmd"]').val();
		var bncode = $(this).find('input[name="bn"]').val();

		if (cmdcode && bncode) {
			$('input[name="bn"]').val('Multidots_SP');
		} else if ((cmdcode) && ( ! bncode)) {
			$(this).find('input[name="cmd"]').after('<input type=\'hidden\' name=\'bn\' value=\'Multidots_SP\' />');
		}

	});

	/*** Open popup ***/
	$('#chart-button').click(function() {
		var modal = document.getElementById('md-size-chart-modal');
		modal.style.display = 'block';
	});

	/*** Close popup ***/
	$('div#md-size-chart-modal .remodal-close').click(function() {
		var modal = document.getElementById('md-size-chart-modal');
		modal.style.display = 'none';
	});

	$('div.md-size-chart-overlay').click(function() {
		var modal = document.getElementById('md-size-chart-modal');
		modal.style.display = 'none';
	});

})(jQuery);
