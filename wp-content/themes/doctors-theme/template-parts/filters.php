<?php

$archive_url = get_post_type_archive_link( 'doctors' );
$current     = function_exists( 'doctors_theme_get_current_filters' ) ? doctors_theme_get_current_filters() : array();

$current_spec = isset( $current['specialization'] ) ? $current['specialization'] : '';
$current_city = isset( $current['city'] ) ? $current['city'] : '';
$current_sort = isset( $current['sort'] ) ? $current['sort'] : '';

$spec_terms = get_terms(
	array(
		'taxonomy'   => 'specialization',
		'hide_empty' => false,
	)
);

$city_terms = get_terms(
	array(
		'taxonomy'   => 'city',
		'hide_empty' => false,
	)
);

if ( is_wp_error( $spec_terms ) ) {
	$spec_terms = array();
}

if ( is_wp_error( $city_terms ) ) {
	$city_terms = array();
}
?>
<form class="dt-filters" method="get" action="<?php echo esc_url( $archive_url ); ?>">
	<div class="dt-filters__row">
		<label>
			<?php esc_html_e( 'Специализация', 'doctors-theme' ); ?>
			<select name="specialization">
				<option value=""><?php esc_html_e( 'Все', 'doctors-theme' ); ?></option>
				<?php if ( is_array( $spec_terms ) ) : ?>
					<?php foreach ( $spec_terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_spec, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</label>

		<label>
			<?php esc_html_e( 'Город', 'doctors-theme' ); ?>
			<select name="city">
				<option value=""><?php esc_html_e( 'Все', 'doctors-theme' ); ?></option>
				<?php if ( is_array( $city_terms ) ) : ?>
					<?php foreach ( $city_terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_city, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</label>

		<label>
			<?php esc_html_e( 'Сортировка', 'doctors-theme' ); ?>
			<select name="sort">
				<option value=""><?php esc_html_e( 'По умолчанию', 'doctors-theme' ); ?></option>
				<option value="rating_desc" <?php selected( $current_sort, 'rating_desc' ); ?>><?php esc_html_e( 'Рейтинг (по убыванию)', 'doctors-theme' ); ?></option>
				<option value="price_asc" <?php selected( $current_sort, 'price_asc' ); ?>><?php esc_html_e( 'Цена (по возрастанию)', 'doctors-theme' ); ?></option>
				<option value="experience_desc" <?php selected( $current_sort, 'experience_desc' ); ?>><?php esc_html_e( 'Стаж (по убыванию)', 'doctors-theme' ); ?></option>
			</select>
		</label>
	</div>

	<div class="dt-filters__actions">
		<button class="dt-button dt-button--primary" type="submit"><?php esc_html_e( 'Применить', 'doctors-theme' ); ?></button>
		<a class="dt-button" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Сбросить', 'doctors-theme' ); ?></a>
	</div>
</form>
