<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Obtener la categoría actual
$current_category = get_queried_object();
$currentID = $current_category->term_id;

// Función recursiva para calcular profundidad de subcategorías
function count_depth($term_id, $current_depth = 0) {
	$children = get_terms([
		'taxonomy'   => 'product_cat',
		'parent'     => $term_id,
		'hide_empty' => false,
	]);

	if (!empty($children)) {
		$depths = array_map(function($child) use ($current_depth) {
			return count_depth($child->term_id, $current_depth + 1);
		}, $children);
		return max($depths);
	}

	return $current_depth;
}

// Wrapper para calcular profundidad de subcategorías
function get_subcategory_depth($category_id) {
	return count_depth($category_id);
}

$parent_category = ($current_category->parent == 0);
$subcategory_depth = $parent_category ? get_subcategory_depth($currentID) : 0;

// Obtener valor del campo ACF 'destacar'
$premier = get_field('destacar', $current_category) ?: '';

// Cargar plantilla correspondiente
if ($premier === 'Linea Premier') {
	wc_get_template('archive-premier.php');
} elseif ($premier === 'Linea black') {
	wc_get_template('archive-black.php');
} else {
	if ($subcategory_depth >= 2 && is_product_category()) {
		wc_get_template('archive-product.php');
	} else {
		wc_get_template('archive-product-subcategory.php');
	}
}