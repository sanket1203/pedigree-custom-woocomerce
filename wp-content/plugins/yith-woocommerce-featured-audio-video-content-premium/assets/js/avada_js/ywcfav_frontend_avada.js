/**
 * Created by Your Inspiration on 07/08/2015.
 */

jQuery(document).ready(function($){

    var  lock=false,
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
    };


		$('.single_variation').on('show_variation', function (e, variation) {
			var div_img = $('.product .images'),
					slider = div_img.find('#slider .slides'),
					element_slider = slider.find('li:eq(0)'),
					link_img = element_slider.find('a');

			if (!lock) {

				lock = true;
				link_img.hide();
				element_slider.show();
				remove_old_content(element_slider);
				if (!( typeof variation.video_variation == 'undefined' )) {

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

							element_slider.append( response.template );
							lock = false;
						}
					});
				}
				else {

					link_img.show();
					lock = false;
				}
			}
		});
		$('.single_variation').on('hide_variation', function(e){
			var div_img = $('.product .images'),
				slider = div_img.find('#slider .slides'),
				element_slider = slider.find('li:eq(0)'),
				link_img = element_slider.find('a');

			link_img.hide();
			element_slider.show();
			if(!lock  ){
				lock= true;

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

						remove_old_content( div_img );
						if( response.result === 'no_featured_content' ){
							link_img.show();
						}
						else {
							element_slider.append(response.template);
						}
						init_plugin();
						lock= false;
					}
				});
			}
		});
    $('body').on('ywcfav-init', function () {

        init_plugin();

    }).trigger( 'ywcfav-init');

	$(document).on('venobox-closed', function(){

		var video_content = $('.ywcfav_video_modal_content.host'),
			video_id = video_content.data('video_id');

		if( typeof video_id !== 'undefined' )
			videojs(video_id).dispose();
	});

});
