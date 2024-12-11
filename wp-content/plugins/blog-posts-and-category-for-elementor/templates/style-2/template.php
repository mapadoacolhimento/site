<div class="pd_pcf_item <?php echo 'pd-pcf-col-'.$columns_per_row; ?>">
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
			<div class="pd_pcf_content_inner">
			<?php if( isset($settings['display_title']) && ( $settings['display_title'] == 'yes' ) ){ ?>
				<div class="pd_pcf_title">
					<h2 style="text-align: <?php echo $settings['title_text_align']; ?>" ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
			<?php } ?>
				<div class="pd_pcf_description">
				<?php if( isset($settings['display_content']) && ( $settings['display_content'] == 'yes' ) ){ ?>
					<div class="pd_pcf_text"><?php echo wpautop(pd_pcf_get_excerpt($excerpt_args)); ?></div>
				<?php } ?>
				<?php if( isset($settings['display_read_more']) && ( $settings['display_read_more'] == 'yes' ) ){ ?>
					<?php if( trim($read_more_text) != '' ){ ?>
						<div class="pd_pcf_readmore">
							<a class="pd_pcf_readmore_link" href="<?php the_permalink(); ?>"><?php echo $read_more_text; ?></a>
						</div>
					<?php } ?>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>