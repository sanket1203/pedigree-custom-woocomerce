<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $options;

$tab_pos = (!empty($options['product_tab_position']) && $options['product_tab_position'] == 'fullwidth') ? 'fullwidth': 'default';

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) );
?>

<?php if ( $heading && $tab_pos != 'fullwidth'): ?>
  <h2><?php echo $heading; ?></h2>
<?php endif; ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/css/bootstrap.css'; ?>">

<div style="display:none;"><?php the_content(); ?></div>
<?php $videourl = get_post_meta(get_the_ID(),'_auction_animal_video',true);
if(!empty($videourl)){
	$videofrom = '';
	$videourl = getYoutubeEmbedUrl($videourl);
}else{
	$videourl = "";
}
$_auction_animal_sex = get_post_meta(get_the_ID(),'_auction_animal_sex',true);
$_auction_animal_id_tag = get_post_meta(get_the_ID(),'_auction_animal_id_tag',true);
$dateofbirth = get_post_meta(get_the_ID(),'_auction_date_of_birth',true);
$birth_weight = get_post_meta(get_the_ID(),'_auction_birth_weight',true);
$weaning_weight = get_post_meta(get_the_ID(),'_auction_weaning_weight',true);
$yearling_weight = get_post_meta(get_the_ID(),'_auction_yearling_weight',true);
$expecated_sale_weight = get_post_meta(get_the_ID(),'_auction_expecated_sale_weight',true);
$daily_gain_weight = get_post_meta(get_the_ID(),'_auction_average_daily_gain_weight',true);

$sire_text = get_post_meta(get_the_ID(),'_auction_sire',true);
$sire_image_id = get_post_meta(get_the_ID(),'_auction_sire_image',true);
$sire_image = get_the_guid($sire_image_id);

$sire_second_gen_text_one = get_post_meta(get_the_ID(),'_auction_second_genration_one',true);
$sire_second_gen_text_two = get_post_meta(get_the_ID(),'_auction_second_genration_two',true);
$third_genration_one  = get_post_meta(get_the_ID(),'_auction_third_genration_one',true);
$third_genration_two  = get_post_meta(get_the_ID(),'_auction_third_genration_two',true);
$third_genration_three = get_post_meta(get_the_ID(),'_auction_third_genration_three',true);
$third_genration_four  = get_post_meta(get_the_ID(),'_auction_third_genration_four',true);

$dam_text = get_post_meta(get_the_ID(),'_auction_dam',true);
$dam_image_id = get_post_meta(get_the_ID(),'_auction_dam_image',true);
$dam_image = get_the_guid($dam_image_id);

$dam_second_gen_text_one = get_post_meta(get_the_ID(),'_auction_dam_second_genration_one',true);
$dam_second_gen_text_two = get_post_meta(get_the_ID(),'_auction_dam_second_genration_two',true);


$dam_third_genration_one   = get_post_meta(get_the_ID(),'_auction_dam_third_genration_one',true);
$dam_third_genration_two   = get_post_meta(get_the_ID(),'_auction_dam_third_genration_two',true);
$dam_third_genration_three = get_post_meta(get_the_ID(),'_auction_dam_third_genration_three',true);
$dam_third_genration_four  = get_post_meta(get_the_ID(),'_auction_dam_third_genration_four',true);

 ?>
<div class="vc_row-fluid row">
	<div class="col-lg-12 col-sm-12 row">
		<div class="row">
				<div class="col-lg-6 col-sm-6 ">
						<?php if(!empty($videourl)){ ?>
							<div class="iframe-embed col-lg-12 col-sm-12">
								<iframe src="<?php echo $videourl; ?>" width="100%" height="100%" style="position:relative;width:100%; height:100%;">
								</iframe>
							</div>
						<?php } ?>	
													
						<div class="col-lg-12 col-sm-12">
							<div class="animal-info_single">
								<div class="grid_animal_odd">
									<div class="grid_animal_name">Date Of Birth:</div>
									<div class="grid_animal_value"><?php if(!empty($dateofbirth)){ echo $dateofbirth; }else{ echo "-"; } ?></div>
								</div> 
								<div class="grid_animal_even">
									<div class="grid_animal_name">Birth Weight:</div>
									<div class="grid_animal_value"><?php if(!empty($birth_weight)){ echo $birth_weight; }else{ echo "-"; } ?></div>
								</div> 
								<div class="grid_animal_odd" style="display:none;">
									<div class="grid_animal_name">Weaning Weight:</div>
									<div class="grid_animal_value"><?php if(!empty($weaning_weight)){ echo $weaning_weight; }else{ echo "-"; } ?></div>
								</div> 
								<div class="grid_animal_odd">
									<div class="grid_animal_name">Yearling Weight:</div>
									<div class="grid_animal_value"><?php if(!empty($yearling_weight)){ echo $yearling_weight; }else{ echo "-"; } ?></div>
								</div> 
								<div class="grid_animal_even">
									<div class="grid_animal_name">Expected Sale Weight:</div>
									<div class="grid_animal_value"><?php if(!empty($expecated_sale_weight)){ echo $expecated_sale_weight; }else{ echo "-"; } ?></div>
								</div> 
								<div class="grid_animal_odd">
									<div class="grid_animal_name">Average Daily Gain:</div>
									<div class="grid_animal_value"><?php if(!empty($daily_gain_weight)){ echo $daily_gain_weight; }else{ echo "-"; } ?></div>
								</div>
								<div class="grid_animal_even">
									<div class="grid_animal_name">Sex:</div>
									<div class="grid_animal_value"><?php if(!empty($_auction_animal_sex)){ echo ucfirst($_auction_animal_sex); }else{ echo "-"; } ?></div>
								</div>
								<div class="grid_animal_odd">
									<div class="grid_animal_name">Animal ID Tag:</div>
									<div class="grid_animal_value"><?php if(!empty($_auction_animal_id_tag)){ echo $_auction_animal_id_tag; }else{ echo "-"; } ?></div>
								</div>
								
							</div>
						</div>
				</div>		
		
		
			 <div class="col-lg-3 col-sm-6 ">			
			 	 <div class="sire-block">
					<?php if(!empty($sire_image)){ ?>
						<div class="sire-heading"><h2>Sire</h2></div>
						<div class="sire-image-lightbox">
							<a href="<?php echo $sire_image; ?>"  class="pp">
								<img src="<?php echo $sire_image; ?>" width="100%" height="170"  alt="<?php the_title(); ?>"/>
							</a>	
						</div>	
					<?php } ?>	
					<div class="sire-heading"><h4>Sire</h4></div>
					<div class="sire-text"><?php if(!empty($sire_text)){ echo $sire_text; }else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>2nd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($sire_second_gen_text_one)){ echo $sire_second_gen_text_one;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($sire_second_gen_text_two)){ echo $sire_second_gen_text_two;}else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>3rd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($third_genration_one)){ echo $third_genration_one;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($third_genration_two)){ echo $third_genration_two;}else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>3rd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($third_genration_three)){ echo $third_genration_three;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($third_genration_four)){ echo $third_genration_four;}else{ echo "-"; } ?></div>
				</div>			
		</div>	
		<div class="col-lg-3 col-sm-6 ">
				<div class="dam-block">
					<?php if(!empty($dam_image)){ ?>
						<div class="dam-heading"><h2>Dam</h2></div>	
						<div class="dam-image-lightbox">
							<a href="<?php echo $dam_image; ?>"  class="pp">
								<img src="<?php echo $dam_image; ?>" width="100%" height="170"  alt="<?php the_title(); ?>"/>
							</a>	
						</div>
					<?php } ?>
					<div class="dam-heading"><h4>Dam</h4></div>
					<div class="dam-text"><?php if(!empty($dam_text)){ echo $dam_text; }else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>2nd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($dam_second_gen_text_one)){ echo $dam_second_gen_text_one;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($dam_second_gen_text_two)){ echo $dam_second_gen_text_two;}else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>3rd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($dam_third_genration_one)){ echo $dam_third_genration_one;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($dam_third_genration_two)){ echo $dam_third_genration_two;}else{ echo "-"; } ?></div>
					<hr>
					<div class="generation-heading"><h4>3rd Generation</h4></div>
					<div class="generation-text"><?php if(!empty($dam_third_genration_three)){ echo $dam_third_genration_three;}else{ echo "-"; } ?></div>
					<div class="generation-text"><?php if(!empty($dam_third_genration_four)){ echo $dam_third_genration_four;}else{ echo "-"; } ?></div>
				</div>		
		</div>
		</div>	
		
	</div>
</div>