<?php
namespace PD_PCF\AJAX_LOAD_MORE;
/**
 * Elementor Post Slider Ajax
 *
 * Ajax Class that handles all Ajax Request
 *
 * @since 1.0.0
*/
class Ajax
{
	
	function __construct()
	{
		add_action('wp_ajax_nopriv_load_posts', [$this, 'load_posts']);
		add_action('wp_ajax_load_posts', [$this, 'load_posts']);
	}

	public function load_posts(){
		$settings = isset($_POST['args']) ? $_POST['args'] : array();
		// print_r($settings);
		if( !empty($settings) ){
			
			$template_style = $settings['template_style'];
		
			$columns_per_row = isset($settings['columns_per_row']) && $settings['columns_per_row'] ? $settings['columns_per_row'] : 3;
			$read_more_text = isset($settings['read_more_text']) && $settings['read_more_text'] ? $settings['read_more_text'] : 'Load More';
			$max_excerpt_word_limit = isset($settings['max_excerpt_word_limit']) && $settings['max_excerpt_word_limit'] ? $settings['max_excerpt_word_limit'] : 40;

			$orderby = 'date';
			$order = 'DESC';



			$args = array();

			$args['post_type'] = $settings['post_types'];
			$args['post_status'] = 'publish';
			$args['orderby'] = $orderby;
			$args['order'] = $order;

			if( isset($settings['posts_per_page']) && intval($settings['posts_per_page']) > 0 ){
				$args['posts_per_page'] = $settings['posts_per_page'];
			}

			if( isset($settings['posts_per_page']) && intval($settings['posts_per_page']) == -1 ){
				$args['posts_per_page'] = $settings['posts_per_page'];
			}

			if( isset($settings['offset']) && intval($settings['offset']) > 0 ){
				$args['offset'] = $settings['offset'];
			}
	        
	        $tax_query = [];
	        if( $args['post_type'] && $args['post_type'] != 'none' ){
		        if( $args['post_type'] !== 'page' ) {
		            $args['tax_query'] = [];
		            $taxonomies = get_object_taxonomies('post', 'objects');

		            foreach ($taxonomies as $object) {
		            	if( $object->name == 'category' ){
			                $setting_key = $object->name . '_ids';
			                
			                $settings_args[$object->name . '_ids'] = $settings[$setting_key];
			 
		                	if( empty($settings[$setting_key]) ){
		                		$taxonomy = $object->name;
								$taxonomy_terms = get_terms( $taxonomy, array(
								    'hide_empty' => 0,
								    'fields' => 'ids'
								) );
								// print_r($taxonomy_terms);
		                		$args['tax_query'][] = [
			                        'taxonomy' => $taxonomy,
			                        'field' => 'id',
			                        'terms' => $taxonomy_terms,
			                    ];
		                	}else{
			                    $args['tax_query'][] = [
			                        'taxonomy' => $object->name,
			                        'field' => 'term_id',
			                        'terms' => $settings[$setting_key],
			                    ];
		                	}
		            	}
		                
		            }

		            if (!empty($args['tax_query'])) {
		                $args['tax_query']['relation'] = 'OR';
		            }
		        }

		        
		        $tax_query = json_encode($args['tax_query']);
		        $post_query = new \WP_Query($args);
		        if( $post_query->have_posts() ){
		        	$count=0;
					while( $post_query->have_posts() ){
						$post_query->the_post();
						$count++;
						$thumbnail_id = get_post_thumbnail_id();
						// $taxonomies=get_taxonomies([],'names');
						$taxonomies = get_post_taxonomies(get_the_ID());
						$taxonomy_names = wp_get_object_terms(get_the_ID(), $taxonomies);
						$tax_class = '';
					    foreach($taxonomy_names as $tax){
					    	$tax_class .= $tax->slug.'-'.$tax->term_id.' ';
					    }
						if( $template_style === 'default' ){
							require( PD_PCF_PATH . 'templates/style-1/template.php' );
						}elseif ( $template_style === 'style-2' ) {
							require( PD_PCF_PATH . 'templates/style-2/template.php' );
						}
					}
					// echo $offset;
					// echo '<br>'.$post_query->found_posts;
					if( ($settings['offset']+$post_query->post_count) >= $post_query->found_posts ){
						echo '<span class="pd_pcf_reach_limit pd-pcf-d-none"></span>';
					}else{
					}
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}
}

new Ajax();