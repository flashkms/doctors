<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_cpt_clamp_float( $value, $min, $max, $precision = null ) {
	$value = (float) $value;
	$value = max( (float) $min, min( (float) $max, $value ) );

	if ( null !== $precision ) {
		$value = round( $value, (int) $precision );
	}

	return $value;
}

function doctors_cpt_get_meta_int( $post_id, $key, $default = 0 ) {
	$value = get_post_meta( $post_id, $key, true );
	if ( '' === $value || null === $value ) {
		return (int) $default;
	}
	return (int) $value;
}

function doctors_cpt_get_meta_float( $post_id, $key, $default = 0.0 ) {
	$value = get_post_meta( $post_id, $key, true );
	if ( '' === $value || null === $value ) {
		return (float) $default;
	}
	return (float) $value;
}

function doctors_cpt_format_price( $value ) {
	$value = (int) $value;
	if ( $value <= 0 ) {
		return '';
	}
	return number_format_i18n( $value, 0 );
}
