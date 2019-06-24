<?php
/**
 * The template for displaying the header
 *
 * @package Steep
 */

?>

<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php hybrid_attr( 'body' ); ?>>
	<?php do_action( 'wp_body_open' ); ?>

	<div id="container">

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'steep' ); ?></a>
		</div><!-- .skip-link -->

		<?php hybrid_get_menu( 'primary' ); // Loads the menu/primary.php template. ?>

		<div class="wrap">

			<header <?php hybrid_attr( 'header' ); ?>>

				<div id="branding">
					<?php if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) : ?>
						<?php the_custom_logo(); // Output custom logo. ?>
					<?php endif; // End check for custom logo. ?>

					<?php hybrid_site_title(); ?>
					<?php hybrid_site_description(); ?>
				</div><!-- #branding -->

				<?php if ( is_active_sidebar( 'header-right' ) ) : ?>

					<div class="header-right-area">
						<?php dynamic_sidebar( 'header-right' ); ?>
					</div><!-- .header-right-area -->

				<?php endif; ?>

			</header><!-- #header -->

			<?php hybrid_get_menu( 'secondary' ); // Loads the menu/secondary.php template. ?>

			<div id="main" class="main">

				<?php hybrid_get_menu( 'breadcrumbs' ); // Loads the menu/breadcrumbs.php template. ?>
