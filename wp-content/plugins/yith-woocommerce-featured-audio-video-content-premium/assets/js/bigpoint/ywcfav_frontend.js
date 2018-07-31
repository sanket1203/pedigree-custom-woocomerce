/**
 * Created by Your Inspiration on 07/08/2015.
 */

jQuery(document).ready(function($){
/**
 * Hide/Show active slide
 */
	$('.product .images .slides').find('li').not('.flex-active-slide').hide();

	$('.flexslider.flex-product').on('click','.flex-next, .flex-prev',function(e){
		
		$('.product .images .slides').find('li').not('.flex-active-slide').hide();

		$('.product .images .slides').find('li.flex-active-slide').show();
	});
	$('.product .images .thumbnails-nav').on('click', 'ul li',function(e){
	
		$('.product .images .slides').find('li').not('.flex-active-slide').hide();

		$('.product .images .slides').find('li.flex-active-slide').show();
	});
	//=====================START VIDEO/AUDIO VARIATION==============================//
	
	var lock = false,
	aspect_ratio = ywcfav_frontend.aspect_ratio,
    aspect_ratio = aspect_ratio.split('_'),
    w_ratio = aspect_ratio[0],
    h_ratio = aspect_ratio[1];

//custom prettyPhoto
var init_plugin = function () {

    var defaults_video = {
        framewidth :  ywcfav_frontend.width_modal,
        frameheight: Math.round( ( ( ywcfav_frontend.width_modal )/w_ratio )*h_ratio ) ,
        numeratio  : false,
        infinigall : false
    };

    var defaults_audio = {
        framewidth :  ywcfav_frontend.width_modal,
        frameheight:  ywcfav_frontend.width_modal,
        numeratio  : false,
        infinigall : false
    };
    var video_modal = $('a.ywcfav_video_show_modal');

    var audio_modal =   $('a.ywcfav_audio_show_modal');

    audio_modal.venobox( defaults_audio );
    video_modal.venobox( defaults_video );
};

var remove_old_content = function( div_img ){
	
	var video_content = div_img.find('div[class^="ywcfav_video"]'),
		audio_content = div_img.find('div[class^="ywcfav_audio"]'),
		remove_content = false;

	video_content.hide();
	audio_content.hide();
	if( video_content.size() ){
		//videojs element
		if( video_content.hasClass('host')){

			var video_id = video_content.data('video_id');
			if( typeof video_id !== 'undefined' )
			videojs(video_id).dispose();
		}

		video_content.next('script').remove();
		video_content.remove();
		remove_content = true;
	}
	
	if( audio_content.size() ){
		audio_content.next('script').remove();
		audio_content.remove();
		remove_content= true;
	}
	
	return remove_content;
};

var single_variation = $('.single_variation');

single_variation.on('show_variation',function(e,variation){
	//flex-active-slide
	var div_img = $('.product .images'),
		current_slide = div_img.find('.slides li:eq(0)'),
		slide_img_cont = current_slide.find('>a');
	
	if( !lock ){
		
		lock = true;
		remove_old_content( current_slide );
		if( ! ( typeof variation.video_variation == 'undefined') ){
		
			current_slide.hide();
			var data = {
					video_id: variation.video_variation,
					product_id: variation.variation_id,
					action: ywcfav_frontend.change_product_variation_image
				};
				
				$.ajax({
					type: 'POST',
					data: data,
					url: ywcfav_frontend.admin_url,
					dataType: 'json',
					success: function (response) {
						
					
					 div_img.find('.slides li').removeClass('flex-active-slide').hide();
					 current_slide.addClass('flex-active-slide');
					 
					 slide_img_cont.addClass('ywcfav_has_featured');
					 slide_img_cont.after(response.template);
						current_slide.css({'opacity':''});
						current_slide.show();
						
						init_plugin();
						lock = false;
					}
				});
		}else{
		
			slide_img_cont.removeClass('ywcfav_has_featured');
			lock = false;
		}
	}
});

single_variation.on('hide_variation',function(e){
	var div_img = $('.product .images'),
	current_slide = div_img.find('.slides li:eq(0)'),
	slide_img_cont = current_slide.find('>a');
	
	if( !lock ){
		remove_old_content( current_slide );
		lock = true;
		var product_id = $('input:hidden[name="product_id"]').val();
		var data = {

			product_id : product_id,
			action : ywcfav_frontend.reset_featured_video
		};

		$.ajax({
			type: 'POST',
			data: data,
			url: ywcfav_frontend.admin_url,
			dataType: 'json',
			success: function (response) {

				if( response.result === 'no_featured_content' ){
					
					slide_img_cont.removeClass('ywcfav_has_featured');
				
				}
				else {
					 div_img.find('.slides li').removeClass('flex-active-slide').hide();
					 current_slide.addClass('flex-active-slide');
					 
					 slide_img_cont.addClass('ywcfav_has_featured');
					 slide_img_cont.after(response.template);
						current_slide.css({'opacity':''});
						current_slide.show();	
				}
				init_plugin();
				lock= false;
			}
		});
	}
});
});
