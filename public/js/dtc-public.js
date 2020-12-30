(function( $ ) {
	'use strict';

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

})( jQuery );
// capturar o click e enviar para o back end vai ajax
// var hitButton = ;
jQuery(document).ready(function(){
	jQuery('#chamar-whats').click(function(event)
	{
		event.preventDefault();

		var link = jQuery(this).attr('href'),
			attrID = jQuery('#main').find('.post').attr('id'),
			attrArr = attrID.split('-'),
			id = attrArr[1];

		console.log(id);
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'tooc_count_click',
				productId: id
			},
			success: function (res) {
				// window.location.href = link;
			}
		});
	});
})
