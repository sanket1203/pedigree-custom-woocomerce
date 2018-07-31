jQuery(document).ready(function($){

    var current_variation = null,
        form_variation = $('form.variations_form'),
        lock= false,
        block_params = {
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            },
            ignoreIfBlocked: true
        };

    function init_venobox_modal (){

        var video_modal = $('a.ywcfav_video_show_modal');

        var audio_modal =   $('a.ywcfav_audio_show_modal');

        audio_modal.venobox();
        video_modal.venobox();

    };
    function get_featured_content () {

        var data = {
            'product_id': ywcfav_zoom_param.product_id,
            'action': ywcfav_zoom_param.actions.get_featured_content
        };

        $('.images').block( block_params );
        $.ajax({
            type: 'POST',
            data: data,
            url: ywcfav_zoom_param.admin_url,
            dataType: 'json',
            success: function (response) {

                if (response.result) {

                    var images = $(document).find('.yith_magnifier_zoom_wrap');

                    images.before(response.template);
                    images.addClass('ywcfav_has_featured');
                }

                init_venobox_modal();
                adjust_zindex(images.parent());
                lock = false;


                $('.images').unblock();
            }
        });
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

            video_content.remove();
            remove_content = true;
        }

        if( audio_content.size() ){

            audio_content.remove();
            remove_content= true;
        }

        return remove_content;
    };

    var adjust_zindex = function( div_img ){

        var video_content = div_img.find('>div[class^="ywcfav_video"]'),
            audio_content = div_img.find('>div[class^="ywcfav_audio"]');

        if( video_content.length ){
            video_content.css('z-index', 0 );
        }

        if( audio_content.length ){
            audio_content.css( 'z-index', 0 );
        }
    };


    $('.variations_form').on('show_variation',  function (e, variation) {

        current_variation = variation;
        var video_content = $('div[class^="ywcfav_video"]'),
            audio_content = $('div[class^="ywcfav_audio"]');

        video_content.removeClass('ywcfav_hide');
        audio_content.removeClass('ywcfav_hide');

    })
        .on('reset_data', function(e){
        current_variation = null;
        var video_content = $('div[class^="ywcfav_video"]'),
            audio_content = $('div[class^="ywcfav_audio"]');

        video_content.removeClass('ywcfav_hide');
        audio_content.removeClass('ywcfav_hide');
    });
    get_featured_content();

    $(document).on('yith_magnifier_after_init_zoom', function(e){


        var div_img = $('.images'),
            zoom_content =	div_img.find('.yith_magnifier_zoom_wrap:eq(0)'),
            video_content = $('div[class^="ywcfav_video"]'),
            audio_content = $('div[class^="ywcfav_audio"]');


        if ( video_content.hasClass('ywcfav_hide') || audio_content.hasClass('ywcfav_hide') ) {
            if (!lock) {

                lock = true;
                zoom_content.removeClass('ywcfav_has_featured').show();
            }
            lock = false;
        }
        else {

            if (current_variation != null) {
                if (!lock) {

                    $('.images').block(block_params);
                    lock = true;

                    if (!( typeof current_variation.video_variation == 'undefined' )) {
                        zoom_content.hide();
                        var data = {
                            video_id: current_variation.video_variation,
                            product_id: current_variation.variation_id,
                            action: ywcfav_zoom_param.actions.change_product_variation_image
                        };
                        remove_old_content(div_img);
                        $.ajax({
                            type: 'POST',
                            data: data,
                            url: ywcfav_zoom_param.admin_url,
                            dataType: 'json',
                            success: function (response) {

                                remove_old_content(div_img);
                                zoom_content.addClass('ywcfav_has_featured').show();
                                zoom_content.after(response.template);
                                init_venobox_modal();
                                adjust_zindex(div_img);
                                lock = false;
                                $('.images').unblock();
                            }
                        });
                    }
                    else {

                        remove_old_content(div_img);
                        zoom_content.removeClass('ywcfav_has_featured').show();
                        lock = false;
                    }

                    $('.images').unblock();
                }
            }
            else if (form_variation.length && current_variation == null) {

                if (!lock) {
                    lock = true;

                    var product_id = $('input:hidden[name="product_id"]').val();
                    var data = {

                        product_id: product_id,
                        action: ywcfav_zoom_param.actions.reset_featured_video
                    };
                    $('.images').block(block_params);
                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: ywcfav_zoom_param.admin_url,
                        dataType: 'json',
                        success: function (response) {

                            remove_old_content(div_img);
                            if (response.result === 'no_featured_content') {
                                zoom_content.removeClass('ywcfav_has_featured').show();
                            }
                            else {
                                zoom_content.addClass('ywcfav_has_featured').hide();
                                zoom_content.after(response.template);
                            }
                            init_venobox_modal();
                            adjust_zindex(div_img);
                            $('.images').unblock();
                            lock = false;
                        }
                    });
                }
            }
        }

    });
    $(document).on('click','.yith_magnifier_gallery li', function(e){

        var		video_content = $('div[class^="ywcfav_video"]'),
            audio_content = $('div[class^="ywcfav_audio"]');


        video_content.addClass('ywcfav_hide');
        audio_content.addClass('ywcfav_hide');

    });

    if( ywcfav_zoom_param.zoom_option == 'yes' ){

        $(document).on('click', '.ywcfav_item a.ywcfav_video_as_zoom' ,function(e){

            e.preventDefault();
            var product_id = $(this).data('product_id'),
                video_id 	= $(this).data('video_id'),
                div_img = $('.images'),
                zoom_content =	div_img.find('.yith_magnifier_zoom_wrap:eq(0)');

            if( !lock ){

                lock = true;

                var data ={
                    video_id : video_id,
                    product_id : product_id,
                    action: ywcfav_zoom_param.actions.change_video_in_featured
                };
                $('.images').block(block_params);
                $.ajax({
                    type: 'POST',
                    data: data,
                    url: ywcfav_zoom_param.admin_url,
                    dataType: 'json',
                    success: function (response) {

                        remove_old_content(div_img);
                        zoom_content.addClass('ywcfav_has_featured').hide();
                        zoom_content.after(response.template);
                        init_venobox_modal();
                        adjust_zindex(div_img);
                        if( response.type == 'video' ){

                            var video_modal = $('.ywcfav_video_show_modal');

                            if( video_modal.length && ywcfav_zoom_param.video_autoplay=='yes' )
                                video_modal.click();

                        }
                        $('.images').unblock();
                        lock = false;
                    }
                });
            }

        });
        $(document).on('click', '.ywcfav_item a.ywcfav_audio_as_zoom' ,function(e){

            e.preventDefault();
            var product_id = $(this).data('product_id'),
                audio_id 	= $(this).data('audio_id'),
                div_img = $('.images'),
                zoom_content =	div_img.find('.yith_magnifier_zoom_wrap:eq(0)');

            if( !lock ){

                lock = true;

                var data ={
                    audio_id : audio_id,
                    product_id : product_id,
                    action: ywcfav_zoom_param.actions.change_audio_in_featured
                };

                $('.images').block(block_params);
                $.ajax({
                    type: 'POST',
                    data: data,
                    url: ywcfav_zoom_param.admin_url,
                    dataType: 'json',
                    success: function (response) {

                        remove_old_content(div_img);
                        zoom_content.addClass('ywcfav_has_featured').hide();
                        zoom_content.after(response.template);
                        init_venobox_modal();
                        adjust_zindex(div_img);
                        if( response.type == 'audio' ){

                            var audio_modal = $('.ywcfav_audio_show_modal');

                            if( audio_modal.length && ywcfav_zoom_param.audio_autoplay=='yes' )
                                audio_modal.click();

                        }
                        $('.images').unblock();
                        lock = false;
                    }
                });
            }

        });
    }
});