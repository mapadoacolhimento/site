<div class="pd_pcf_item <?php echo 'pd-pcf-col-'.$columns_per_row; ?> <?php echo esc_attr($tax_class); ?>">
	<div class="pd_pcf_single_item">
	<?php if( isset($settings['display_image']) && ( $settings['display_image'] == 'yes' ) ){ ?>
		<div class="pd_pcf_thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php
					if( $thumbnail_id ){
						$image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $thumbnail_id, 'thumbnail_size', $settings );
						echo sprintf( '<img src="%s" title="%s" alt="%s"%s />', esc_attr( $image_src ), get_the_title( $thumbnail_id ), pd_pcf_attachment_alt($thumbnail_id), '' ); 
					}
				?>
			</a>
		</div>
	<?php } ?>
		<div class="pd_pcf_content">

			<div class="pd_pcf_title">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</div>

			<div class="pd_pcf_description">

				<div class="pd_pcf_text"><?php echo wpautop(pd_pcf_get_excerpt()); ?></div>

				<?php if( trim($read_more_text) != '' ){ ?>
					<div class="pd_pcf_readmore">
						<a class="pd_pcf_readmore_link" href="<?php the_permalink(); ?>"><?php echo $read_more_text; ?></a>
					</div>
				<?php } ?>
			</div>
		
		</div>
	</div>
</div>