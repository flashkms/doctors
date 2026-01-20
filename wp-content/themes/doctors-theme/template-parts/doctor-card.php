<?php

$post_id    = get_the_ID();
$experience = (int) get_post_meta( $post_id, 'experience_years', true );
$price_from = (int) get_post_meta( $post_id, 'price_from', true );
$rating     = (float) get_post_meta( $post_id, 'rating', true );
$permalink  = get_permalink( $post_id );
$title      = get_the_title( $post_id );

$spec_terms = get_the_terms( $post_id, 'specialization' );
$spec_terms = is_array( $spec_terms ) ? array_values( $spec_terms ) : array();
$spec_total = count( $spec_terms );
$spec_show  = array_slice( $spec_terms, 0, 2 );
?>
<article class="dt-card">
	<a class="dt-card__thumb" href="<?php echo esc_url( $permalink ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium' ); ?>
		<?php endif; ?>
	</a>

	<div>
		<h2 class="dt-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h2>

		<div class="dt-card__meta">
			<?php if ( ! empty( $spec_show ) ) : ?>
				<span>
					<?php
					$names = array();
					foreach ( $spec_show as $term ) {
						$names[] = $term->name;
					}
					echo esc_html( implode( ', ', $names ) );
					?>
					<?php if ( $spec_total > 2 ) : ?>
						<?php echo esc_html( sprintf( ' +%d', $spec_total - 2 ) ); ?>
					<?php endif; ?>
				</span>
			<?php endif; ?>

			<?php if ( $experience ) : ?>
				<span><?php echo esc_html( sprintf( 'Стаж: %d', $experience ) ); ?></span>
			<?php endif; ?>

			<?php if ( $price_from ) : ?>
				<span><?php echo esc_html( sprintf( 'от %s ₽', number_format_i18n( $price_from ) ) ); ?></span>
			<?php endif; ?>

			<?php if ( $rating ) : ?>
				<span><?php echo esc_html( sprintf( '★ %.1f', $rating ) ); ?></span>
			<?php endif; ?>
		</div>

		<p><a class="dt-button" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'Подробнее', 'doctors-theme' ); ?></a></p>
	</div>
</article>
