<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'doctors_theme_setup' );

function doctors_theme_enqueue_styles() {
	wp_enqueue_style( 'doctors-theme', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'doctors_theme_enqueue_styles' );

function doctors_theme_get_current_filters() {
	return array(
		'specialization' => isset( $_GET['specialization'] ) ? sanitize_title( wp_unslash( $_GET['specialization'] ) ) : '',
		'city'           => isset( $_GET['city'] ) ? sanitize_title( wp_unslash( $_GET['city'] ) ) : '',
		'sort'           => isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : '',
	);
}

function doctors_theme_paginate_links() {
	global $wp_query;

	if ( ! $wp_query instanceof WP_Query ) {
		return '';
	}

	$current = max( 1, (int) get_query_var( 'paged' ) );
	$total   = max( 1, (int) $wp_query->max_num_pages );

	if ( $total <= 1 ) {
		return '';
	}

	$filters = doctors_theme_get_current_filters();
	$add     = array();
	foreach ( $filters as $key => $value ) {
		if ( $value ) {
			$add[ $key ] = $value;
		}
	}

	return paginate_links(
		array(
			'total'     => $total,
			'current'   => $current,
			'prev_text' => '←',
			'next_text' => '→',
			'add_args'  => $add,
		)
	);
}
