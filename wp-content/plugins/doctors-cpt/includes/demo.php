<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_register_demo_tools_page() {
	add_management_page(
		__( 'Generate Doctors Demo', 'doctors-cpt' ),
		__( 'Generate Doctors Demo', 'doctors-cpt' ),
		'manage_options',
		'doctors-cpt-demo',
		'doctors_cpt_render_demo_page'
	);
}
add_action( 'admin_menu', 'doctors_cpt_register_demo_tools_page' );

function doctors_cpt_render_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Недостаточно прав.', 'doctors-cpt' ) );
	}

	$created = null;
	if ( isset( $_POST['doctors_cpt_generate_demo'] ) ) {
		check_admin_referer( 'doctors_cpt_generate_demo', 'doctors_cpt_demo_nonce' );
		$created = doctors_cpt_generate_demo_data( 20 );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Generate Doctors Demo', 'doctors-cpt' ); ?></h1>

		<p><?php esc_html_e( 'Создаст 20 докторов, несколько специализаций и городов, а также заполнит мета‑поля.', 'doctors-cpt' ); ?></p>

		<?php if ( is_array( $created ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<?php
					printf(
						esc_html__( 'Готово: создано докторов: %d. Термины добавлены/проверены.', 'doctors-cpt' ),
						(int) $created['posts']
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'doctors_cpt_generate_demo', 'doctors_cpt_demo_nonce' ); ?>
			<p>
				<button type="submit" class="button button-primary" name="doctors_cpt_generate_demo" value="1">
					<?php esc_html_e( 'Создать 20 докторов', 'doctors-cpt' ); ?>
				</button>
			</p>
		</form>
	</div>
	<?php
}

function doctors_cpt_generate_demo_data( $count = 20 ) {
	$is_cli = defined( 'WP_CLI' ) && WP_CLI;
	if ( ! current_user_can( 'manage_options' ) && ! $is_cli ) {
		return array( 'posts' => 0 );
	}

	$specializations = array(
		array( 'name' => 'Терапевт', 'slug' => 'terapevt' ),
		array( 'name' => 'Хирург', 'slug' => 'hirurg' ),
		array( 'name' => 'Дерматолог', 'slug' => 'dermatolog' ),
		array( 'name' => 'Кардиолог', 'slug' => 'kardiolog' ),
	);

	$cities = array(
		array( 'name' => 'Москва', 'slug' => 'moskva' ),
		array( 'name' => 'Санкт‑Петербург', 'slug' => 'spb' ),
		array( 'name' => 'Казань', 'slug' => 'kazan' ),
	);

	foreach ( $specializations as $term ) {
		if ( ! term_exists( $term['slug'], 'specialization' ) ) {
			wp_insert_term( $term['name'], 'specialization', array( 'slug' => $term['slug'] ) );
		}
	}

	foreach ( $cities as $term ) {
		if ( ! term_exists( $term['slug'], 'city' ) ) {
			wp_insert_term( $term['name'], 'city', array( 'slug' => $term['slug'] ) );
		}
	}

	$first_names = array(
		array( 'name' => 'Иван', 'slug' => 'ivan' ),
		array( 'name' => 'Анна', 'slug' => 'anna' ),
		array( 'name' => 'Ольга', 'slug' => 'olga' ),
		array( 'name' => 'Мария', 'slug' => 'maria' ),
		array( 'name' => 'Дмитрий', 'slug' => 'dmitriy' ),
		array( 'name' => 'Сергей', 'slug' => 'sergey' ),
		array( 'name' => 'Екатерина', 'slug' => 'ekaterina' ),
		array( 'name' => 'Алексей', 'slug' => 'alexey' ),
		array( 'name' => 'Наталья', 'slug' => 'natalya' ),
		array( 'name' => 'Павел', 'slug' => 'pavel' ),
	);

	$last_names = array(
		array( 'name' => 'Иванов', 'slug' => 'ivanov' ),
		array( 'name' => 'Петров', 'slug' => 'petrov' ),
		array( 'name' => 'Сидоров', 'slug' => 'sidorov' ),
		array( 'name' => 'Смирнова', 'slug' => 'smirnova' ),
		array( 'name' => 'Кузнецов', 'slug' => 'kuznetsov' ),
		array( 'name' => 'Фёдорова', 'slug' => 'fedorova' ),
		array( 'name' => 'Николаев', 'slug' => 'nikolaev' ),
		array( 'name' => 'Васильева', 'slug' => 'vasilyeva' ),
		array( 'name' => 'Попов', 'slug' => 'popov' ),
		array( 'name' => 'Морозова', 'slug' => 'morozova' ),
	);

	$created_posts = 0;
	$count         = max( 1, (int) $count );

	for ( $i = 0; $i < $count; $i++ ) {
		if ( 0 === $i ) {
			$first = $first_names[0];
			$last  = $last_names[0];
		} else {
			$first = $first_names[ array_rand( $first_names ) ];
			$last  = $last_names[ array_rand( $last_names ) ];
		}

		$title = sprintf( '%s %s', $first['name'], $last['name'] );

		$slug_seed = sprintf( '%s-%s', $first['slug'], $last['slug'] );
		$slug      = 0 === $i ? $slug_seed : sprintf( '%s-%d', $slug_seed, $i + 1 );

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'doctors',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_excerpt' => 'Короткое описание доктора. Принимает по записи, помогает с подбором плана лечения.',
				'post_content' => 'Полное описание доктора. Здесь можно разместить информацию об образовании, подходе к пациентам и доступных услугах.',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$created_posts++;

		$experience = random_int( 1, 25 );
		$price_from = random_int( 1500, 10000 );
		$rating     = doctors_cpt_clamp_float( ( random_int( 35, 50 ) / 10 ), 0, 5, 1 );

		update_post_meta( $post_id, 'experience_years', $experience );
		update_post_meta( $post_id, 'price_from', $price_from );
		update_post_meta( $post_id, 'rating', $rating );

		$spec_slugs = wp_list_pluck( $specializations, 'slug' );
		shuffle( $spec_slugs );
		if ( $i < 12 ) {
			$take_specs = array( 'hirurg' );
			if ( random_int( 0, 1 ) ) {
				$take_specs[] = $spec_slugs[0];
			}
			$take_specs = array_values( array_unique( $take_specs ) );
		} else {
			$take_specs = array_slice( $spec_slugs, 0, random_int( 1, 2 ) );
		}
		wp_set_object_terms( $post_id, $take_specs, 'specialization', false );

		$city_slugs = wp_list_pluck( $cities, 'slug' );
		$city_slug  = $i < 12 ? 'moskva' : $city_slugs[ array_rand( $city_slugs ) ];
		wp_set_object_terms( $post_id, array( $city_slug ), 'city', false );
	}

	return array(
		'posts' => $created_posts,
	);
}
