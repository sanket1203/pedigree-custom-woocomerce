jQuery( document ).ready(function($){

    var lock = false,
       block_params = {
        message: null,
        overlayCSS: {
            background: '#fff',
            opacity: 0.6
        },
        ignoreIfBlocked: true
    };
  function hide_img_container( img_container ){

      if( img_container ) {
          img_container.addClass('ywcfav_has_featured');
      }
      $(document).find( '.woocommerce-product-gallery__trigger' ).addClass('ywcfav_has_featured');
  }

  function show_img_container( img_container ){
      if( img_container ) {
          img_container.removeClass('ywcfav_has_featured');
      }
      $(document).find( '.woocommerce-product-gallery__trigger' ).removeClass('ywcfav_has_featured');
  }

  function set_video_thumbnail_in_gallery( image_url ){

        var gallery_container = $(document).find('.flex-control-nav.flex-control-thumbs li').filter(':first-child');
        
        if( gallery_container.length ){
            var height = gallery_container.innerHeight();
            gallery_container.find('img').attr( 'src', image_url ).css('height', height );
        }
    }

  function set_video_simple_in_slider( img_container ){
      var data = {
          'product_id' : ywcfav_frontend_param.product_id,
          'action' : ywcfav_frontend_param.actions.get_featured_content
      };

      set_video_in_slider( img_container, data );
  }

  function set_video_in_slider( img_container, data ) {


      $('.images').block(block_params);


      $.ajax({
          type: 'POST',
          url: ywcfav_frontend_param.ajax_url,
          data: data,
          dataType: 'json',
          success: function (response) {

              lock = false;
              if (response.result) {

                  img_container.before( response.template );


                  var video_content =  img_container.parent().find('div[class^="ywcfav_video"]'),
                  audio_content =  img_container.parent().find('div[class^="ywcfav_audio"]'),
                   video_info = '';

                  if( video_content.length ){
                      if( !video_content.hasClass( 'ywcfav_video_modal_content') ) {
                          video_content.css({'float': 'left', 'width': $('.images').width()});
                      }
                       video_info  =  video_content.data( 'video_info' );
                  }


                  if( audio_content.length ){
                      audio_content.css( { 'float':'left', 'width' :$('.images').width() } );
                       video_info  =  audio_content.data( 'video_info' );
                  }



                  hide_img_container( img_container );

                
                  init_venobox_modal();
                  $('body').trigger( 'ywcfav_show_video_content_in_slider',[video_info] );
              }else {


                  $('.images').unblock();
              }


          }
      });
  }
  function remove_video_in_slider( img_container ){

      var general_content = img_container.parent(),
          video_content = general_content.find('div[class^="ywcfav_video"]'),
          audio_content = general_content.find('div[class^="ywcfav_audio"]'),
         removed = false;

      if( video_content.length ){

          if( video_content.hasClass('host')){
              var video_id = video_content.data('video_id');
              if( typeof video_id !== 'undefined' )
                  videojs(video_id).dispose();
          }

          removed = true;
          video_content.remove();

      }

      if( audio_content.length ){
          removed = true;
          audio_content.remove();
      }

      if( removed ){
          show_img_container( img_container );
      }
  }
  function set_video_variation_in_slider( img_container, variation_id, video_id ){

     
      var data = {
        'product_id': variation_id,
         'video_id':video_id,
         'action' : ywcfav_frontend_param.actions.get_product_variation_video
      };

      set_video_in_slider( img_container, data );
  }
  function init_venobox_modal (){

      var video_modal = $('a.ywcfav_video_show_modal');

      var audio_modal =   $('a.ywcfav_audio_show_modal');

      audio_modal.venobox();
      video = video_modal.venobox();

  }



  $(document.body).on('ywcfav_init_featured_content', function(e){
      
      if( ywcfav_frontend_param.has_feature_content ){

          var img_container = $( document ).find( ywcfav_frontend_param.no_img_class_container );

          if( !img_container.length ){

              img_container = $(document).find( ywcfav_frontend_param.img_class_container ).filter(':first-child');
          }

          if( $( '.variations_form.cart' ).length ){


              $('.variations_form').on( 'reset_data', function(e){

                  remove_video_in_slider( img_container );
                  if(  !lock ) {
                      lock = true;
                      set_video_simple_in_slider(img_container);

                  }


              }).on( 'show_variation', function(e, variation_data, purchasable ) {
                  remove_video_in_slider(img_container);

                  if (!( typeof variation_data.video_variation == 'undefined' )) {
                        if (!lock) {

                            lock = true;

                          set_video_variation_in_slider( img_container , variation_data.variation_id, variation_data.video_variation );
                      }
                  }
              });


          }else{

              set_video_simple_in_slider( img_container );
          }




      }
  }).trigger( 'ywcfav_init_featured_content' ) ;
   
  $('body').on( 'ywcfav_show_video_content_in_slider', function(e,video_info ){
      
      var data = {
              'video_info': video_info,
              'action': ywcfav_frontend_param.actions.get_video_image
            };

      $.ajax({
          type: 'POST',
          url: ywcfav_frontend_param.ajax_url,
          data: data,
          dataType: 'json',
          success: function (response) {

              set_video_thumbnail_in_gallery( response.image_url );
              $('.images').unblock();
          }
      });

  })  ;


  $(document).on('click', '.flex-control-nav.flex-control-thumbs li', function(e){

      if( $(this).is(':first-child' ) ){
          hide_img_container( false );
      }else{
          show_img_container( false );
      }
  })  ;
    init_venobox_modal();
});