<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_filter_archive_query( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'doctors' ) ) {
		return;
	}

	$query->set( 'posts_per_page', 9 );

	$specialization = isset( $_GET['specialization'] ) ? sanitize_title( wp_unslash( $_GET['specialization'] ) ) : '';
	$city           = isset( $_GET['city'] ) ? sanitize_title( wp_unslash( $_GET['city'] ) ) : '';
	$sort           = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : '';

	$tax_query = array();
	if ( $specialization ) {
		$tax_query[] = array(
			'taxonomy' => 'specialization',
			'field'    => 'slug',
			'terms'    => $specialization,
		);
	}
	if ( $city ) {
		$tax_query[] = array(
			'taxonomy' => 'city',
			'field'    => 'slug',
			'terms'    => $city,
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}

	$allowed_sorts = array( 'rating_desc', 'price_asc', 'experience_desc' );
	if ( ! in_array( $sort, $allowed_sorts, true ) ) {
		$sort = '';
	}

	if ( 'rating_desc' === $sort ) {
		$query->set( 'meta_key', 'rating' );
		$query->set( 'orderby', array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ) );
	} elseif ( 'price_asc' === $sort ) {
		$query->set( 'meta_key', 'price_from' );
		$query->set( 'orderby', array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ) );
	} elseif ( 'experience_desc' === $sort ) {
		$query->set( 'meta_key', 'experience_years' );
		$query->set( 'orderby', array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'doctors_cpt_filter_archive_query' );
