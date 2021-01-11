(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

function filterStores(val)
{
	var items = jQuery('#table-stores').find('tbody tr');
	var str = val.toLocaleLowerCase();
		items.each(function(index){
			var dataName = jQuery(this).attr('data-name').toLocaleLowerCase();
			var result = dataName.indexOf(str);

			if (result != -1 || str.length == 0) {
				jQuery(this).show();
			} else {
				jQuery(this).hide();
			}
		});
}

function filterProducts(el)
{
	// console.log(el.parent().parent().parent().siblings('table').find('tbody tr'))
	var items = el.parent().parent().parent().siblings('table').find('tbody tr');
	var str = el.val().toLocaleLowerCase();
	console.log(str);
		items.each(function(index){
			var dataName = jQuery(this).attr('data-name').toLocaleLowerCase();
			var result = dataName.indexOf(str);

			if (result != -1 || str.length == 0) {
				jQuery(this).show();
			} else {
				jQuery(this).hide();
			}
		});
}

function listProductsByStore(ajaxUrl, storeName, storeId) {
	'use strict';

	var modal = jQuery('#modal-product').children('.uk-modal-body');
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'dtc_products_by_store',
			storeName: storeName,
			storeId: storeId,
		},
		beforeSend: function(){
			modal.html('<div class="uk-flex uk-flex-center"><h3 class="uk-text-center uk-block">Carregando produtos...</h3><div uk-spinner="ratio: 3" class="uk-align-center"></div></div>');
		}
	}).done(function(res, test){
		console.log(test);
		var table = res;
		modal.html(table);
	})
}


function listenChange(el, ajaxUrl){
	'use strict';

	var tableStores = jQuery('#table-stores').find('tbody');
	var dateSelected = el.val();
	console.log(el.val());
	// console.log(tableStores);

	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'dtc_store_clicks_by_month',
			date: dateSelected
		},
		beforeSend: function(){
			tableStores.html('<div class="uk-flex uk-flex-center"><div uk-spinner="ratio: 3" class="uk-align-center"></div></div>');
		}
	}).done(function(res){
		var stores = res;
		tableStores.html(stores);
		// console.log(res);
	});
}