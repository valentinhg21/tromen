<?php

/**

 * This file represents an example of the code that themes would use to register

 * the required plugins.

 *

 * It is expected that theme authors would copy and paste this code into their

 * functions.php file, and amend to suit.

 *

 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.

 *

 * @package    TGM-Plugin-Activation

 * @subpackage Example

 * @version    2.6.1 for parent theme Zetenta for publication on WordPress.org

 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer

 * @copyright  Copyright (c) 2011, Thomas Griffin

 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later

 * @link       https://github.com/TGMPA/TGM-Plugin-Activation

 */





add_action( 'tgmpa_register', 'zetenta_register_required_plugins' );

function zetenta_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'        => 'Custom Post Type UI',
			'slug'        => 'custom-post-type-ui',
			'required'  => true
		),
		array(
			'name'        => 'WooCommerce',
			'slug'        => 'woocommerce',
			'required'  => true
		),
		array(
			'name'        => 'Yoast SEO',
			'slug'        => 'wordpress-seo',
			'required'  => false
		),

		array(
			'name'        => 'Wordfence Security',
			'slug'        => 'wordfence',
			'required'  => false,
			'force_activation'   => false,
		),

		array(
			'name'        => 'WPS Hide Login',
			'slug'        => 'wps-hide-login',
			'required'  => false
		),

		array(
			'name'        => 'SVG Support',
			'slug'        => 'svg-support',
			'required'  => true
		),

		array(
			'name'        => 'Duplicate Post',
			'slug'        => 'duplicate-post',
			'required'  => true
		),
		array(
			'name'        => 'Advanced Editor Tools',
			'slug'        => 'tinymce-advanced',
			'required'  => true,
		),
		array(
			'name'        => 'White Label Cms',
			'slug'        => 'white-label-cms',
			'required'  => true
		),
		array(
			'name'        => 'Updraftplus',
			'slug'        => 'updraftplus',
			'required'  => true

		),
		array(
			'name'        => 'Tuxedo Big File Uploads',
			'slug'        => 'tuxedo-big-file-uploads',
			'required'  => false
		),

		array(
			'name'        => 'WP Super Cache',
			'slug'        => 'wp-super-cache',
			'required'  => false
		),


		array(
			'name'        => 'EWWW Image Optimizer',
			'slug'        => 'ewww-image-optimizer',
			'required'  => false
		),

		array(
			'name'        => 'Redirection',
			'slug'        => 'redirection',
			'required'  => false
		)

	);



	/*

	 * Array of configuration settings. Amend each line as needed.

	 *

	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard

	 * strings available, please help us make TGMPA even better by giving us access to these translations or by

	 * sending in a pull-request with .po file(s) with the translations.

	 *

	 * Only uncomment the strings in the config array if you want to customize the strings.

	 */

	$config = array(
		'id'           => 'zetenta',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.



	);



	tgmpa( $plugins, $config );

}

