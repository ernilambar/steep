<?php
/**
 * The template for displaying the footer
 *
 * @package Steep
 */

?>

<?php hybrid_get_sidebar( 'primary' ); // Loads the sidebar/primary.php template. ?>

			</div><!-- #main -->

			<?php hybrid_get_sidebar( 'subsidiary' ); // Loads the sidebar/subsidiary.php template. ?>

		</div><!-- .wrap -->

		<footer <?php hybrid_attr( 'footer' ); ?>>

			<div class="wrap">

				<?php hybrid_get_menu( 'social' ); // Loads the menu/social.php template. ?>

				<p class="credit">
					<?php
					printf(
						esc_html__( 'Copyright &#169; %1$s %2$s.', 'steep' ),
						date_i18n( 'Y' ),
						hybrid_get_site_link()
					);
					printf(
						' ' . esc_html__( '%1$s by %2$s.', 'steep' ),
						esc_html__( 'Steep', 'steep' ),
						'<a href="' . esc_url( 'http://nilambar.net' ) . '" target="_blank">' . esc_html__( 'Nilambar', 'steep' ) . '</a>'
					);
					?>
				</p><!-- .credit -->
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => 'nav',
						'container_id'   => 'footer-navigation',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div><!-- .wrap -->

		</footer><!-- #footer -->

	</div><!-- #container -->

	<?php wp_footer(); // WordPress hook for loading JavaScript, toolbar, and other things in the footer. ?>

</body>
</html>
