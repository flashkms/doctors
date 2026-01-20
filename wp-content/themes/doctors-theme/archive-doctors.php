<?php
get_header();
?>
<main class="dt-container">
	<div class="dt-header">
		<h1 class="dt-title"><?php echo esc_html( post_type_archive_title( '', false ) ); ?></h1>
	</div>

	<?php get_template_part( 'template-parts/filters' ); ?>

	<div class="dt-grid">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php get_template_part( 'template-parts/doctor-card' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Доктора не найдены.', 'doctors-theme' ); ?></p>
		<?php endif; ?>
	</div>

	<?php
	$pagination = doctors_theme_paginate_links();
	if ( $pagination ) :
		?>
		<nav class="dt-pagination" aria-label="<?php esc_attr_e( 'Пагинация', 'doctors-theme' ); ?>">
			<?php echo wp_kses_post( $pagination ); ?>
		</nav>
	<?php endif; ?>
</main>
<?php
get_footer();
