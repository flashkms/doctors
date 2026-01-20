<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_register_post_type() {
	$labels = array(
		'name'                  => __( 'Доктора', 'doctors-cpt' ),
		'singular_name'         => __( 'Доктор', 'doctors-cpt' ),
		'menu_name'             => __( 'Доктора', 'doctors-cpt' ),
		'name_admin_bar'        => __( 'Доктор', 'doctors-cpt' ),
		'add_new'               => __( 'Добавить', 'doctors-cpt' ),
		'add_new_item'          => __( 'Добавить доктора', 'doctors-cpt' ),
		'new_item'              => __( 'Новый доктор', 'doctors-cpt' ),
		'edit_item'             => __( 'Редактировать доктора', 'doctors-cpt' ),
		'view_item'             => __( 'Просмотр', 'doctors-cpt' ),
		'all_items'             => __( 'Все доктора', 'doctors-cpt' ),
		'search_items'          => __( 'Поиск докторов', 'doctors-cpt' ),
		'not_found'             => __( 'Доктора не найдены.', 'doctors-cpt' ),
		'not_found_in_trash'    => __( 'В корзине нет докторов.', 'doctors-cpt' ),
		'featured_image'        => __( 'Фото доктора', 'doctors-cpt' ),
		'set_featured_image'    => __( 'Установить фото', 'doctors-cpt' ),
		'remove_featured_image' => __( 'Удалить фото', 'doctors-cpt' ),
		'use_featured_image'    => __( 'Использовать как фото', 'doctors-cpt' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'doctors' ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-id',
		'show_in_nav_menus'  => true,
		'publicly_queryable' => true,
	);

	register_post_type( 'doctors', $args );
}
add_action( 'init', 'doctors_cpt_register_post_type' );
