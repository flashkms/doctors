<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_register_taxonomies() {
	// specialization (hierarchical)
	$spec_labels = array(
		'name'              => __( 'Специализации', 'doctors-cpt' ),
		'singular_name'     => __( 'Специализация', 'doctors-cpt' ),
		'search_items'      => __( 'Искать специализации', 'doctors-cpt' ),
		'all_items'         => __( 'Все специализации', 'doctors-cpt' ),
		'parent_item'       => __( 'Родительская специализация', 'doctors-cpt' ),
		'parent_item_colon' => __( 'Родительская специализация:', 'doctors-cpt' ),
		'edit_item'         => __( 'Редактировать', 'doctors-cpt' ),
		'update_item'       => __( 'Обновить', 'doctors-cpt' ),
		'add_new_item'      => __( 'Добавить специализацию', 'doctors-cpt' ),
		'new_item_name'     => __( 'Новая специализация', 'doctors-cpt' ),
		'menu_name'         => __( 'Специализации', 'doctors-cpt' ),
	);

	register_taxonomy(
		'specialization',
		array( 'doctors' ),
		array(
			'hierarchical'      => true,
			'labels'            => $spec_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'doctor-specialization' ),
		)
	);

	// city (non-hierarchical)
	$city_labels = array(
		'name'                       => __( 'Города', 'doctors-cpt' ),
		'singular_name'              => __( 'Город', 'doctors-cpt' ),
		'search_items'               => __( 'Искать города', 'doctors-cpt' ),
		'popular_items'              => __( 'Популярные города', 'doctors-cpt' ),
		'all_items'                  => __( 'Все города', 'doctors-cpt' ),
		'edit_item'                  => __( 'Редактировать', 'doctors-cpt' ),
		'update_item'                => __( 'Обновить', 'doctors-cpt' ),
		'add_new_item'               => __( 'Добавить город', 'doctors-cpt' ),
		'new_item_name'              => __( 'Новый город', 'doctors-cpt' ),
		'separate_items_with_commas' => __( 'Разделяйте города запятыми', 'doctors-cpt' ),
		'add_or_remove_items'        => __( 'Добавить или удалить', 'doctors-cpt' ),
		'choose_from_most_used'      => __( 'Выбрать из часто используемых', 'doctors-cpt' ),
		'menu_name'                  => __( 'Города', 'doctors-cpt' ),
	);

	register_taxonomy(
		'city',
		array( 'doctors' ),
		array(
			'hierarchical'      => false,
			'labels'            => $city_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'doctor-city' ),
		)
	);
}
add_action( 'init', 'doctors_cpt_register_taxonomies' );
