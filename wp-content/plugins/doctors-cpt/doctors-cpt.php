<?php
/**
 * Plugin Name: Doctors CPT
 * Description: CPT "Доктора" + таксономии + мета-поля + фильтры архива + демо-генератор.
 * Version: 0.1.0
 * Text Domain: doctors-cpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DOCTORS_CPT_VERSION', '0.1.0' );
define( 'DOCTORS_CPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DOCTORS_CPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/helpers.php';
require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/cpt.php';
require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/taxonomies.php';
require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/meta.php';
require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/query.php';
require_once DOCTORS_CPT_PLUGIN_DIR . 'includes/demo.php';

function doctors_cpt_activate() {
	doctors_cpt_register_post_type();
	doctors_cpt_register_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'doctors_cpt_activate' );

function doctors_cpt_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'doctors_cpt_deactivate' );
