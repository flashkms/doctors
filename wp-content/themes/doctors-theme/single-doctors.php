<?php
get_header();

$post_id    = get_the_ID();
$experience = (int) get_post_meta( $post_id, 'experience_years', true );
$price_from = (int) get_post_meta( $post_id, 'price_from', true );
$rating     = (float) get_post_meta( $post_id, 'rating', true );

$archive_url = get_post_type_archive_link( 'doctors' );
?>
<main class="dt-container dt-single">
	<p><a href="<?php echo esc_url( $archive_url ); ?>">← <?php esc_html_e( 'К списку докторов', 'doctors-theme' ); ?></a></p>

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<section class="dt-single__hero">
				<div class="dt-single__thumb">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium_large' ); ?>
					<?php else : ?>
						<span class="dt-thumb-placeholder" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
								<path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.42 0-8 2-8 4.5V21h16v-2.5c0-2.5-3.58-4.5-8-4.5Z" fill="currentColor"/>
							</svg>
						</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Без фото', 'doctors-theme' ); ?></span>
					<?php endif; ?>
				</div>

				<div>
					<h1 class="dt-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>

					<div class="dt-single__facts">
						<?php if ( $experience ) : ?>
							<span class="dt-pill"><?php echo esc_html( sprintf( 'Стаж: %d лет', $experience ) ); ?></span>
						<?php endif; ?>
						<?php if ( $price_from ) : ?>
							<span class="dt-pill"><?php echo esc_html( sprintf( 'Цена от: %s ₽', number_format_i18n( $price_from ) ) ); ?></span>
						<?php endif; ?>
						<?php if ( $rating ) : ?>
							<span class="dt-pill"><?php echo esc_html( sprintf( 'Рейтинг: %.1f', $rating ) ); ?></span>
						<?php endif; ?>
					</div>

					<?php
					$spec_terms = get_the_terms( $post_id, 'specialization' );
					$city_terms = get_the_terms( $post_id, 'city' );
					?>

					<?php if ( is_array( $spec_terms ) && ! empty( $spec_terms ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Специализация:', 'doctors-theme' ); ?></strong>
							<?php
							$links = array();
							foreach ( $spec_terms as $term ) {
								$url      = add_query_arg( array( 'specialization' => $term->slug ), $archive_url );
								$links[]  = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $term->name ) );
							}
							echo wp_kses_post( implode( ', ', $links ) );
							?>
						</p>
					<?php endif; ?>

					<?php if ( is_array( $city_terms ) && ! empty( $city_terms ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Город:', 'doctors-theme' ); ?></strong>
							<?php
							$links = array();
							foreach ( $city_terms as $term ) {
								$url     = add_query_arg( array( 'city' => $term->slug ), $archive_url );
								$links[] = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $term->name ) );
							}
							echo wp_kses_post( implode( ', ', $links ) );
							?>
						</p>
					<?php endif; ?>
				</div>
			</section>

			<section class="dt-single__content">
				<?php if ( has_excerpt() ) : ?>
					<p><em><?php echo esc_html( get_the_excerpt() ); ?></em></p>
				<?php endif; ?>

				<?php the_content(); ?>
			</section>
		<?php endwhile; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
