<?php
/**
 * Theme widgets
 *
 * @package Steep
 */

if ( ! function_exists( 'steep_load_widgets' ) ) :

	/**
	 * Load widgets.
	 *
	 * @since 1.0.0
	 */
	function steep_load_widgets() {
		// Social widget.
		register_widget( 'Steep_Social_Widget' );

		// Latest News widget.
		register_widget( 'Steep_Latest_News_Widget' );
	}

endif;

add_action( 'widgets_init', 'steep_load_widgets' );

if ( ! class_exists( 'Steep_Social_Widget' ) ) :

	/**
	 * Social widget Class.
	 *
	 * @since 1.0.0
	 */
	class Steep_Social_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'steep_widget_social',
				'description'                 => esc_html__( 'Social Icons Widget', 'steep' ),
				'customize_selective_refresh' => true,
			);

			parent::__construct( 'steep-social', esc_html__( 'Steep: Social', 'steep' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$size  = ! empty( $instance['size'] ) ? $instance['size'] : 'medium';

			echo $args['before_widget'];

			// Render title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			if ( has_nav_menu( 'social' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'social',
						'container'      => false,
						'menu_class'     => 'size-' . esc_attr( $size ),
						'depth'          => 1,
						'link_before'    => '<span class="screen-reader-text">',
						'link_after'     => '</span>',
						'item_spacing'   => 'discard',
					)
				);
			}

			echo $args['after_widget'];
		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            {@see WP_Widget::form()}.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['size']  = sanitize_key( $new_instance['size'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {
			// Defaults.
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title' => '',
					'size'  => 'medium',
				)
			);
			$title    = $instance['title'];
			$size     = $instance['size'];

			// Social menu status.
			$is_menu_set = ( has_nav_menu( 'social' ) ) ? true : false;
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'steep' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Size:', 'steep' ); ?></label>
				<?php
				$this->dropdown_size(
					array(
						'id'       => $this->get_field_id( 'size' ),
						'name'     => $this->get_field_name( 'size' ),
						'selected' => esc_attr( $size ),
					)
				);
				?>
			</p>

			<?php if ( false === $is_menu_set ) : ?>
				<p>
					<?php esc_html_e( 'Social menu is not set. Please create menu and assign it to Social menu.', 'steep' ); ?>
				</p>
			<?php endif; ?>
			<?php
		}

		/**
		 * Render image size dropdown.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments.
		 * @return string Rendered content.
		 */
		function dropdown_size( $args ) {
			$defaults = array(
				'id'       => '',
				'name'     => '',
				'selected' => 0,
				'echo'     => 1,
			);

			$r = wp_parse_args( $args, $defaults );

			$output = '';

			$choices = array(
				'small'       => __( 'Small', 'steep' ),
				'medium'      => __( 'Medium', 'steep' ),
				'large'       => __( 'Large', 'steep' ),
				'extra-large' => __( 'Extra Large', 'steep' ),
			);

			if ( ! empty( $choices ) ) {
				$output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
				foreach ( $choices as $key => $choice ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ';
					$output .= selected( $r['selected'], $key, false );
					$output .= '>' . esc_html( $choice ) . '</option>\n';
				}
				$output .= "</select>\n";
			}

			if ( $r['echo'] ) {
				echo $output;
			}

			return $output;
		}
	}

endif;

if ( ! class_exists( 'Steep_Latest_News_Widget' ) ) :

	/**
	 * Latest news widget class.
	 *
	 * @since 1.0.0
	 */
	class Steep_Latest_News_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'steep_widget_latest_news',
				'description'                 => esc_html__( 'Latest News Widget. Displays latest posts in grid.', 'steep' ),
				'customize_selective_refresh' => true,
			);

			parent::__construct( 'steep-latest-news', esc_html__( 'Steep: Latest News', 'steep' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {
			$title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$post_category  = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
			$post_layout    = ! empty( $instance['post_layout'] ) ? $instance['post_layout'] : 1;
			$post_column    = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 4;
			$featured_image = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'steep-thumb';
			$post_number    = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;
			$excerpt_length = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 0;

			echo $args['before_widget'];

			// Display widget title.
			if ( $title ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			$qargs = array(
				'posts_per_page'      => absint( $post_number ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			if ( absint( $post_category ) > 0 ) {
				$qargs['cat'] = absint( $post_category );
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="latest-news-widget latest-news-layout-<?php echo absint( $post_layout ); ?> latest-news-col-<?php echo absint( $post_column ); ?>">

					<div class="inner-wrapper">

						<?php
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>

							<div class="latest-news-item">
							<?php $image_class = ( 'disable' === $featured_image || ! has_post_thumbnail() ) ? 'no-featured-image' : ''; ?>
								<div class="latest-news-wrapper <?php echo esc_attr( $image_class ); ?>">
									<?php if ( 'disable' !== $featured_image && has_post_thumbnail() ) : ?>
										<div class="latest-news-thumb">
											<a href="<?php the_permalink(); ?>">
												<?php
												$img_attributes = array( 'class' => 'aligncenter' );
												the_post_thumbnail( esc_attr( $featured_image ), $img_attributes );
												?>
											</a>
										</div><!-- .latest-news-thumb -->
									<?php endif; ?>

									<div class="latest-news-text-wrap">
										<h3 class="latest-news-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>

										<div class="latest-news-meta">
											<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
											<?php
											if ( comments_open( get_the_ID() ) ) {
												echo '<span class="comments-link">';
												comments_popup_link( '<span class="leave-reply">' . esc_html__( '0 Comment', 'steep' ) . '</span>', esc_html__( '1 Comment', 'steep' ), esc_html__( '% Comments', 'steep' ) );
												echo '</span>';
											}
											?>
										</div><!-- .latest-news-meta -->

										<?php if ( absint( $excerpt_length ) > 0 ) : ?>
											<div class="latest-news-summary">
												<?php
												$excerpt = steep_the_excerpt( absint( $excerpt_length ) );
												echo wp_kses_post( wpautop( $excerpt ) );
												?>
											</div><!-- .latest-news-summary -->
										<?php endif; ?>
									</div><!-- .latest-news-text-wrap -->
								</div><!-- .latest-news-wrapper -->
							</div><!-- .latest-news-item -->

						<?php endwhile; ?>

					</div><!-- .inner-wrapper -->
				</div><!-- .latest-news-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['post_category']  = absint( $new_instance['post_category'] );
			$instance['post_layout']    = absint( $new_instance['post_layout'] );
			$instance['post_number']    = absint( $new_instance['post_number'] );
			$instance['post_column']    = absint( $new_instance['post_column'] );
			$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );
			$instance['featured_image'] = sanitize_text_field( $new_instance['featured_image'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {
			// Defaults.
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'          => '',
					'post_category'  => '',
					'post_layout'    => 1,
					'post_column'    => 2,
					'featured_image' => 'steep-thumb',
					'post_number'    => 4,
					'excerpt_length' => 15,
					'more_text'      => esc_html__( 'Read more', 'steep' ),
				)
			);
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'steep' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_category' ) ); ?>"><?php esc_html_e( 'Select Category:', 'steep' ); ?></label>
				<?php
				$cat_args = array(
					'orderby'         => 'name',
					'hide_empty'      => true,
					'taxonomy'        => 'category',
					'name'            => $this->get_field_name( 'post_category' ),
					'id'              => $this->get_field_id( 'post_category' ),
					'selected'        => $instance['post_category'],
					'show_option_all' => esc_html__( 'All Categories', 'steep' ),
				);
				wp_dropdown_categories( $cat_args );
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_layout' ) ); ?>"><?php esc_html_e( 'Select Layout:', 'steep' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'post_layout' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'post_layout' ) ); ?>">
					<option value="1" <?php selected( '1', $instance['post_layout'] ); ?>><?php esc_html_e( 'One', 'steep' ); ?></option>
					<option value="2" <?php selected( '2', $instance['post_layout'] ); ?>><?php esc_html_e( 'Two', 'steep' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"><?php esc_html_e( 'Number of Posts:', 'steep' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['post_number'] ); ?>" min="1" max="20" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_column' ) ); ?>"><?php esc_html_e( 'Number of Columns:', 'steep' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'post_column' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_column' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['post_column'] ); ?>" min="1" max="4" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'featured_image' ) ); ?>"><?php esc_html_e( 'Select Image Size:', 'steep' ); ?></label>
				<?php
				$this->dropdown_image_sizes(
					array(
						'id'       => $this->get_field_id( 'featured_image' ),
						'name'     => $this->get_field_name( 'featured_image' ),
						'selected' => $instance['featured_image'],
					)
				);
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'steep' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" min="0" max="200" />&nbsp;<small><?php esc_html_e( 'in words', 'steep' ); ?></small>
			</p>
			<?php
		}

		/**
		 * Render image size dropdown.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments.
		 * @return string Rendered content.
		 */
		function dropdown_image_sizes( $args ) {
			$defaults = array(
				'id'       => '',
				'name'     => '',
				'selected' => 0,
				'echo'     => 1,
			);

			$r = wp_parse_args( $args, $defaults );

			$output = '';

			$choices = $this->get_image_sizes_options();

			if ( ! empty( $choices ) ) {

				$output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
				foreach ( $choices as $key => $choice ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ';
					$output .= selected( $r['selected'], $key, false );
					$output .= '>' . esc_html( $choice ) . '</option>\n';
				}
				$output .= "</select>\n";
			}

			if ( $r['echo'] ) {
				echo $output;
			}

			return $output;
		}

		/**
		 * Return image size options.
		 *
		 * @since 1.0.0
		 *
		 * @return array Options.
		 */
		function get_image_sizes_options() {
			global $_wp_additional_image_sizes;

			$choices = array();

			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $_size . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}

			$choices['full'] = esc_html__( 'full (original)', 'steep' );

			if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
				foreach ( $_wp_additional_image_sizes as $key => $size ) {
					$choices[ $key ] = $key . ' (' . $size['width'] . 'x' . $size['height'] . ')';
				}
			}

			return $choices;
		}
	}

endif;

