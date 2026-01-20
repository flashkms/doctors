<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_register_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_posts' );
	};

	register_post_meta(
		'doctors',
		'experience_years',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'auth_callback'     => $auth_callback,
			'sanitize_callback' => 'absint',
			'default'           => 0,
		)
	);

	register_post_meta(
		'doctors',
		'price_from',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'auth_callback'     => $auth_callback,
			'sanitize_callback' => 'absint',
			'default'           => 0,
		)
	);

	register_post_meta(
		'doctors',
		'rating',
		array(
			'type'              => 'number',
			'single'            => true,
			'show_in_rest'      => true,
			'auth_callback'     => $auth_callback,
			'sanitize_callback' => 'doctors_cpt_sanitize_rating_meta',
			'default'           => 0,
		)
	);
}
add_action( 'init', 'doctors_cpt_register_meta' );

function doctors_cpt_sanitize_rating_meta( $value ) {
	$value = doctors_cpt_clamp_float( $value, 0, 5, 1 );
	return $value;
}

function doctors_cpt_add_meta_box() {
	add_meta_box(
		'doctors_cpt_details',
		__( 'Данные доктора', 'doctors-cpt' ),
		'doctors_cpt_render_meta_box',
		'doctors',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_doctors', 'doctors_cpt_add_meta_box' );

function doctors_cpt_render_meta_box( $post ) {
	wp_nonce_field( 'doctors_cpt_save_meta', 'doctors_cpt_meta_nonce' );

	$experience = doctors_cpt_get_meta_int( $post->ID, 'experience_years', 0 );
	$price_from = doctors_cpt_get_meta_int( $post->ID, 'price_from', 0 );
	$rating     = doctors_cpt_get_meta_float( $post->ID, 'rating', 0 );
	?>
	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><label for="doctors_cpt_experience_years"><?php esc_html_e( 'Стаж (лет)', 'doctors-cpt' ); ?></label></th>
				<td><input type="number" min="0" step="1" id="doctors_cpt_experience_years" name="doctors_cpt_experience_years" value="<?php echo esc_attr( $experience ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="doctors_cpt_price_from"><?php esc_html_e( 'Цена от', 'doctors-cpt' ); ?></label></th>
				<td><input type="number" min="0" step="1" id="doctors_cpt_price_from" name="doctors_cpt_price_from" value="<?php echo esc_attr( $price_from ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="doctors_cpt_rating"><?php esc_html_e( 'Рейтинг (0–5)', 'doctors-cpt' ); ?></label></th>
				<td><input type="number" min="0" max="5" step="0.1" id="doctors_cpt_rating" name="doctors_cpt_rating" value="<?php echo esc_attr( $rating ); ?>" class="regular-text"></td>
			</tr>
		</tbody>
	</table>
	<?php
}

function doctors_cpt_save_meta( $post_id ) {
	if ( ! isset( $_POST['doctors_cpt_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doctors_cpt_meta_nonce'] ) ), 'doctors_cpt_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$experience_raw = isset( $_POST['doctors_cpt_experience_years'] ) ? wp_unslash( $_POST['doctors_cpt_experience_years'] ) : '';
	$price_raw      = isset( $_POST['doctors_cpt_price_from'] ) ? wp_unslash( $_POST['doctors_cpt_price_from'] ) : '';
	$rating_raw     = isset( $_POST['doctors_cpt_rating'] ) ? wp_unslash( $_POST['doctors_cpt_rating'] ) : '';

	$experience = absint( $experience_raw );
	$price_from = absint( $price_raw );
	$rating     = doctors_cpt_clamp_float( $rating_raw, 0, 5, 1 );

	if ( '' === trim( (string) $experience_raw ) ) {
		delete_post_meta( $post_id, 'experience_years' );
	} else {
		update_post_meta( $post_id, 'experience_years', $experience );
	}

	if ( '' === trim( (string) $price_raw ) ) {
		delete_post_meta( $post_id, 'price_from' );
	} else {
		update_post_meta( $post_id, 'price_from', $price_from );
	}

	if ( '' === trim( (string) $rating_raw ) ) {
		delete_post_meta( $post_id, 'rating' );
	} else {
		update_post_meta( $post_id, 'rating', $rating );
	}
}
add_action( 'save_post_doctors', 'doctors_cpt_save_meta' );

function doctors_cpt_admin_assets( $hook ) {
	$allowed = array( 'post.php', 'post-new.php', 'tools_page_doctors-cpt-demo' );
	if ( ! in_array( $hook, $allowed, true ) ) {
		return;
	}

	wp_enqueue_style(
		'doctors-cpt-admin',
		DOCTORS_CPT_PLUGIN_URL . 'assets/admin.css',
		array(),
		DOCTORS_CPT_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'doctors_cpt_admin_assets' );
