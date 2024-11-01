<?php

/**
 * WP Post List Table
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('wpb_post_pable_list') ):

	class wpb_post_pable_list {

		public function __construct(){
			add_shortcode( 'wpb_post_list_table', array( $this, 'wpb_post_list_table' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wpb_plt_adding_scripts' ) );
		}


		/**
		 * Post List Table Shortcode
		 */

		public function wpb_post_list_table( $atts ){
			extract(shortcode_atts(array(
				'orderby'				=> '',
				'order'					=> '',
				'number_of_posts'		=> 9,
				'post_type'				=> $this->wpb_plt_get_option( 'wpb_plt_post_type_select', 'wpb_plt_general', 'post'),
				'pagination'			=> 'on',
				'style'					=> $this->wpb_plt_get_option( 'table_style', 'wpb_plt_general', 'bordered'),
				'table_content'			=> $this->wpb_plt_get_option( 'table_content', 'wpb_plt_general', array('no' => 'no', 'title' => 'title', 'author' => 'author', 'date' => 'date', 'category' => 'category', 'comment' => 'comment', 'edit_link' => 'edit_link') ),
			), $atts));

			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

			$args = array(
				'post_type' 		=> $post_type,
				'posts_per_page'	=> $number_of_posts,
				'orderby' 			=> $orderby,
				'order' 			=> $order,
				'paged' 			=> $paged,
			);

			$category_taxonomy 	= $this->wpb_plt_get_option( 'wpb_plt_category_taxonomy', 'wpb_plt_general', 'category');
			$tag_taxonomy 		= $this->wpb_plt_get_option( 'wpb_plt_tag_taxonomy', 'wpb_plt_general', 'post_tag');

			$loop = new WP_Query( $args );
			$i = 1;
			if ( $loop->have_posts() ):
				ob_start();
					?>
						<table class="wpb-plt-table wpb-plt-table-<?php echo $style;?>">
							<thead>
								<tr>
									<?php if ( array_key_exists('no', $table_content) ):?><th><?php esc_html_e( 'No.', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('id', $table_content) ):?><th><?php esc_html_e( 'ID', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('title', $table_content) ):?><th><?php esc_html_e( 'Title', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('price', $table_content) && class_exists( 'WooCommerce' ) ):?><th><?php esc_html_e( 'Price', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('sku', $table_content) && class_exists( 'WooCommerce' )):?><th><?php esc_html_e( 'SKU', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('stock', $table_content) && class_exists( 'WooCommerce' )):?><th><?php esc_html_e( 'Stock', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('review', $table_content) && class_exists( 'WooCommerce' )):?><th><?php esc_html_e( 'Review', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('wpb_woo_lightbox', $table_content) && class_exists( 'WooCommerce' ) && function_exists('get_wpb_woocommerce_lightbox') ):?><th><?php esc_html_e( 'Product Quick View', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('yith_quickview', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_WCQV_Frontend' ) ):?><th><?php esc_html_e( 'Product Quick View', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('yith_wishlist', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_WCWL' )):?><th><?php esc_html_e( 'Wishlist', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('yith_compare', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_Woocompare' )):?><th><?php esc_html_e( 'Compare', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('cart', $table_content) && class_exists( 'WooCommerce' )):?><th><?php esc_html_e( 'Cart', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('author', $table_content) ):?><th><?php esc_html_e( 'Author', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('date', $table_content) ):?><th><?php esc_html_e( 'Date', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('category', $table_content) ):?><th><?php esc_html_e( 'Category', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('tag', $table_content) ):?><th><?php esc_html_e( 'Tag', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('comment', $table_content) ):?><th><?php esc_html_e( 'Comment', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php if ( array_key_exists('edit_link', $table_content) && is_user_logged_in() ):?><th><?php esc_html_e( 'Edit', WPB_PLT_TEXTDOMAIN ); ?></th><?php endif;?>
									<?php ?>
								</tr>
							</thead>
							<tbody>
								<?php while ( $loop->have_posts() ) : $loop->the_post();?>
								<?php 
									global $post, $woocommerce, $product;
								?>
								<tr>
									<?php if ( array_key_exists('no', $table_content) ):?><td><?php echo $i; ?></td><?php endif;?>
									<?php if ( array_key_exists('id', $table_content) ):?><td><?php the_id(); ?></td><?php endif;?>
									<?php if ( array_key_exists('title', $table_content) ):?><td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td><?php endif;?>
									<?php if ( array_key_exists('price', $table_content) && class_exists( 'WooCommerce' ) ):?><td><?php echo $product->get_price_html(); ?></td><?php endif;?>
									<?php if ( array_key_exists('sku', $table_content) && class_exists( 'WooCommerce' ) ):?><td><?php echo $product->get_sku(); ?></td><?php endif;?>
									<?php if ( array_key_exists('stock', $table_content) && class_exists( 'WooCommerce' ) ):?><td><?php echo $product->get_stock_quantity(); ?></td><?php endif;?>
									<?php if ( array_key_exists('review', $table_content) && class_exists( 'WooCommerce' ) ):?><td><?php echo wc_get_rating_html( $product->get_average_rating() ); ?></td><?php endif;?>
									<?php if ( array_key_exists('wpb_woo_lightbox', $table_content) && class_exists( 'WooCommerce' ) && function_exists('get_wpb_woocommerce_lightbox')):?><td><?php echo get_wpb_woocommerce_lightbox( $post->ID, esc_html__( 'Quick View', WPB_PLT_TEXTDOMAIN ), 'button', 'on', 'off' ); ?></td><?php endif;?>
									<?php if ( array_key_exists('yith_quickview', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_WCQV_Frontend' ) ):?><td><?php $yith_quickview = new YITH_WCQV_Frontend(); echo $yith_quickview->yith_add_quick_view_button(); ?></td><?php endif;?>
									<?php if ( array_key_exists('yith_wishlist', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_WCWL' ) ):?><td><?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></td><?php endif;?>
									<?php if ( array_key_exists('yith_compare', $table_content) && class_exists( 'WooCommerce' ) && class_exists( 'YITH_Woocompare' ) ):?><td><?php echo do_shortcode('[yith_compare_button]'.esc_html__( 'Compare',WPB_PLT_TEXTDOMAIN ).'[/yith_compare_button]'); ?></td><?php endif;?>
									<?php if ( array_key_exists('cart', $table_content) && class_exists( 'WooCommerce' ) ):?><td><?php echo $this->wpb_plt_cart_button( $post->ID ); ?></td><?php endif;?>
									<?php if ( array_key_exists('author', $table_content) ):?><td><?php the_author(); ?></td><?php endif;?>
									<?php if ( array_key_exists('date', $table_content) ):?><td><?php echo get_the_date(); ?></td><?php endif;?>
									<?php if ( array_key_exists('category', $table_content) ):?><td><?php $this->wpb_plt_taxonomy_list( $category_taxonomy, get_the_id() ) ?></td><?php endif;?>
									<?php if ( array_key_exists('tag', $table_content) ):?><td><?php $this->wpb_plt_taxonomy_list( $tag_taxonomy, get_the_id() ) ?></td><?php endif;?>
									<?php if ( array_key_exists('comment', $table_content) ):?><td><?php comments_popup_link( '<span class="leave-reply">' . esc_html__( 'Leave a reply', WPB_PLT_TEXTDOMAIN ) . '</span>', esc_html__( '1 Reply', WPB_PLT_TEXTDOMAIN ), esc_html__( '% Replies', WPB_PLT_TEXTDOMAIN ) ); ?></td><?php endif;?>
									<?php if ( array_key_exists('edit_link', $table_content) && is_user_logged_in() ):?><td><?php edit_post_link( esc_html__( 'Edit', WPB_PLT_TEXTDOMAIN ), '', '', 0, 'wpb-post-edit-link' ); ?></td><?php endif;?>
								</tr>

								<?php $i++;?>

								<?php endwhile;?>
							</tbody>
						</table>
					<?php
				wp_enqueue_style( 'wpb-plt-main-style' );
				wp_reset_postdata();

				if ( $pagination == 'on' ) {
					echo $this->wpb_plt_pagination( $loop->max_num_pages, '', $paged );
				}

				return ob_get_clean();

			else:
				return esc_html__( 'No post found according to your shortcode.', WPB_PLT_TEXTDOMAIN );
			endif;
		}
		

		/**
		 * Adding Scripts
		 */

		public function wpb_plt_adding_scripts(){
			wp_register_style('wpb-plt-main-style', plugins_url('../assets/css/main.css', __FILE__), '', '1.0', false);
		}

		/**
		 * Category list
		 */

		public function wpb_plt_taxonomy_list( $taxonomy = 'category', $post_id ) {

			global $post;

			if( $post_id == '' || !$post_id ){
				$post_id = $post->ID;
			}

			$terms = get_the_terms( $post_id , $taxonomy );

			if( $terms && !empty($terms) ){
				$copy = $terms;
				echo '<ul class="wpb-plt-cat-list">';
				foreach ( $terms as $term ) {
					printf( '<li><a href="%s">%s</a></li>%s', esc_url( get_term_link( $term, $taxonomy ) ), esc_html( $term->name ), ( next($copy)  ? ', ' : '' ) );
				}
				echo '</ul>';
			}
		}

		/**
		 * Pagination
		 */

		public function wpb_plt_pagination($numpages = '', $pagerange = '', $paged=''){

			if (empty($pagerange)) {
				$pagerange = 2;
			}

			/**
			* This first part of our function is a fallback
			* for custom pagination inside a regular loop that
			* uses the global $paged and global $wp_query variables.
			* 
			* It's good because we can now override default pagination
			* in our theme, and use this function in default quries
			* and custom queries.
			*/
			global $paged;
			if (empty($paged)) {
				$paged = 1;
			}
			if ($numpages == '') {
				global $wp_query;
				$numpages = $wp_query->max_num_pages;
				if(!$numpages) {
					$numpages = 1;
				}
			}
			$big = 999999999; // need an unlikely integer

			/** 
			* We construct the pagination arguments to enter into our paginate_links
			* function. 
			*/
			$pagination_args = array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'          => '?paged=%#%',
				'total'           => $numpages,
				'current'         => max( 1, $paged ),
				'show_all'        => False,
				'end_size'        => 1,
				'mid_size'        => $pagerange,
				'prev_next'       => True,
				'prev_text'       => esc_html__('&laquo;'),
				'next_text'       => esc_html__('&raquo;'),
				'type'            => 'array',
				'add_args'        => false,
				'add_fragment'    => ''
			);

			$pages = paginate_links($pagination_args);

			if( is_array( $pages ) ) {
				ob_start();
					echo "<ul class='wpb-plt-pagination pagination'>";
					echo "<li class='page-numbers page-num'>" .esc_html__( 'Page ', WPB_PLT_TEXTDOMAIN ). $paged . esc_html__( ' of ', WPB_PLT_TEXTDOMAIN ) . $numpages . "</li> ";
					foreach ( $pages as $page ) {
				        echo "<li>$page</li>";
				    }
					echo "</ul>";
				return ob_get_clean();
			}

		}


		/**
		 * Get Settings Options
		 */
		

		public function wpb_plt_get_option( $option, $section, $default = '' ) {
 
		    $options = get_option( $section );
		 
		    if ( isset( $options[$option] ) ) {
		        return $options[$option];
		    }
		 
		    return $default;
		}


		/**
		 * Add to cart button
		 */


		public function wpb_plt_cart_button( $id ){
			echo '<div class="wpb_plt_cart_button">'. do_shortcode( '[add_to_cart id="'. esc_attr( $id ) .'" style="" show_price="false"]' ) .'</div>'; 
		}

		
	}

endif;

new wpb_post_pable_list();