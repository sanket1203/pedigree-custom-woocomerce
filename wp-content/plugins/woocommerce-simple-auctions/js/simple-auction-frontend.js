jQuery(document).ready(function($){

	saajaxurl = SA_Ajax.ajaxurl;
	SA_last_activity = SA_Ajax.last_activity;

	running = false;
	var window_focus = true;

	var refreshIntervalId = '';

	if(data.interval){
		if(SA_Ajax.focus == 'yes'){
	   		$(window).focus(function() {
	        	window_focus = true;
	    	});

	    	$(window).blur(function() {
	        	window_focus = false;
	    	});
    	}
	   refreshIntervalId =  setInterval(function(){
	        if(window_focus == true){
	           getPriceAuction();
	        }
    	}, data.interval*1000);

		//refreshIntervalId  = setInterval(getPriceAuction, data.interval*1000);
	}

	$( ".auction-time-countdown" ).each(function( index ) {
		var time 	= $(this).data('time');
		var format 	= $(this).data('format');

		if(format == ''){
			format = 'yowdHMS';
		}
		if(data.compact_counter == 'yes'){
			compact	 = true;
		} else{
			compact	 = false;
		}
		var etext ='';
		if($(this).hasClass('future') ){
			var etext = '<div class="started">'+data.started+'</div>';
		} else{
			var etext = '<div class="over">'+data.finished+'</div>';

		}

		$(this).SAcountdown({
			until:   $.SAcountdown.UTCDate(-(new Date().getTimezoneOffset()),new Date(time*1000)),
			format: format,
			compact:  compact,

			onExpiry: closeAuction,
			expiryText: etext
		});

	});

	$('form.cart').submit(function() {
		clearInterval(refreshIntervalId);

	});

	$( "input[name=bid_value]" ).on('changein', function( event ) {
	 	$(this).addClass('changein');
	});

	$( ".sealed-text a" ).on('click', function(e){
		e.preventDefault();
		$('.sealed-bid-desc').slideToggle('fast');
	});



	$( ".sa-watchlist-action" ).on('click', watchlist);


	function watchlist( event ) {

		var auction_id = jQuery(this).data('auction-id');
		var currentelement  =  $(this);

		jQuery.ajax({
         type : "get",
         url : SA_Ajax.ajaxurl,
         data : { post_id : auction_id, 'wsa-ajax' : "watchlist"},
         success: function(response) {
                     currentelement.parent().replaceWith(response);
                     $( ".sa-watchlist-action" ).on('click', watchlist);
                     jQuery( document.body).trigger('sa-wachlist-action',[response,auction_id] );
        	}
      	});}


closeAuction();


});

function closeAuction(){
		var auctionid = jQuery(this).data('auctionid');
		var future = jQuery(this).hasClass('future') ? 'true' : 'false';
		var ajaxcontainer = jQuery(this).parent().next('.auction-ajax-change');


		ajaxcontainer.empty().prepend('<div class="ajax-working"></div>');
		ajaxcontainer.parent().children('form.buy-now').remove();



		var ajaxurl = saajaxurl+'=finish_auction';


		jQuery( document.body).trigger('sa-close-auction',[auctionid]);
		request =  jQuery.ajax({
         type : "post",
         url : ajaxurl,
         cache : false,
         data : {action: "finish_auction", post_id : auctionid, ret: ajaxcontainer.length, future: future},
         success: function(response) {
         			if (response.length  != 0){
         				ajaxcontainer.children('.ajax-working').remove();
         				ajaxcontainer.prepend(response);
         				jQuery( document.body).trigger('sa-action-closed',[auctionid]);
         			}

        	}
      	});


}



function getPriceAuction(){

    if(jQuery('.auction-price').length<1){
        return;
    }

    if (running == true){
    	return;
    }

    // var auction_ids={};
    // jQuery(".auction-price").each(function(){

    //     	var auction_id = jQuery(this).data('auction-id');
    //     	var auction_bid = jQuery(this).data('bid');
    //     	var auction_status= jQuery(this).data('status');
    // 		auction_ids [auction_id]= {'price': auction_bid , 'status': auction_status};

    // });

    // if(jQuery.isEmptyObject(auction_ids)){
    // 	return;
    // }

    running = true;

	var ajaxurl = saajaxurl+'=get_price_for_auctions';

	jQuery.ajax({

	 type : "post",
     encoding:"UTF-8",
     url : ajaxurl,
     dataType: 'json',
     data : {action: "get_price_for_auctions", "last_activity" : SA_last_activity},
     success: function(response) {

	    if(response != null ) {
	     	if (typeof response.last_activity != 'undefined') {
	     		SA_last_activity = response.last_activity;
	     	}
	     	jQuery.each( response, function( key, value ) {
	     		auction = jQuery("body").find(".auction-price[data-auction-id='" + key + "']");
			    auction.replaceWith(value.curent_bid);
			    jQuery("body").find("[data-auction-id='" + key + "']").addClass('changed blink').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100, function(){jQuery(this).removeClass('blink');});

			    if (typeof value.timer != 'undefined') {
			    	var curenttimer = jQuery("body").find(".auction-time-countdown[data-auctionid='" + key + "']");
			    	if(curenttimer.attr('data-time') != value.timer){
		  				curenttimer.attr('data-time',value.timer );
		 				curenttimer.SAcountdown('option',  'until',  jQuery.SAcountdown.UTCDate(-(new Date().getTimezoneOffset()),new Date(value.timer*1000)) );
		 				curenttimer.addClass('changed blink').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100, function(){jQuery(this).removeClass('blink');});
	  				}
				}
				 if (typeof value.curent_bider != 'undefined' ) {
				 		var curentuser = jQuery("input[name=user_id]");
				 		if (curentuser.length){
				 			if(value.curent_bider != curentuser.val()){
				 				jQuery('.woocommerce-message').replaceWith(data.outbid_message );

				 			}
				 		}
				 	if(jQuery( "span.winning[data-auction_id='"+key+"']" ).attr('data-user_id') != value.curent_bider){
				 				jQuery( "span.winning[data-auction_id='"+key+"']" ).remove()
				 		}


				 }

				 if (typeof value.bid_value != 'undefined' ) {
				 	if(!jQuery( "input[name=bid_value][data-auction-id='"+key+"']" ).hasClass('changedin')){
				 		jQuery( "input[name=bid_value][data-auction-id='"+key+"']" ).val(value.bid_value).removeClass('changedin');
				 	}
				 }

				 if (typeof value.reserve != 'undefined' ) {

				 	jQuery( ".auction-ajax-change .reserve[data-auction-id='"+key+"']" ).text(value.reserve);

				 }

				 if (typeof value.activity != 'undefined' ) {

				 	jQuery("#auction-history-table-" + key +" tbody > tr:first" ).before(value.activity);
				 	jQuery("#auction-history-table-" + key +" tbody > tr:first" ).addClass('changed blink').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100, function(){jQuery(this).removeClass('blink');})

				 }

				 if (typeof value.add_to_cart_text != 'undefined' ) {

				 	jQuery( "a.button.product_type_auction[data-product_id='"+key+"']" ).text(value.add_to_cart_text);

				 }

				 jQuery( document.body).trigger('sa-action-price-changed',[key, value]);

			});

	     	//console.log(response);
	     	}
	     	jQuery( document.body).trigger('sa-action-price-respons', response);
	     	running = false;
    	},
    	error: function() {
    		running = false;
    	}
    });

}
jQuery(function($){$(".auction_form div.quantity:not(.buttons_added),.auction_form td.quantity:not(.buttons_added)").addClass("buttons_added").append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />'),$(document).on("click",".auction_form .plus,.auction_form .minus",function(){var t=$(this).closest(".quantity").find("input[name=bid_value]"),a=parseFloat(t.val()),n=parseFloat(t.attr("max")),s=parseFloat(t.attr("min")),e=t.attr("step");a&&""!==a&&"NaN"!==a||(a=0),(""===n||"NaN"===n)&&(n=""),(""===s||"NaN"===s)&&(s=0),("any"===e||""===e||void 0===e||"NaN"===parseFloat(e))&&(e=1),$(this).is(".plus")?t.val(n&&(n==a||a>n)?n:a+parseFloat(e)):s&&(s==a||s>a)?t.val(s):a>0&&t.val(a-parseFloat(e)),t.trigger("change")})});
