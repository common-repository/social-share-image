<?php
/**
 * Plugin Name: Social Share Image
 * Plugin URI:  https://bhargavb.com/
 * Description: Create Custom Scoial Share Image.
 * Version:     1.0.1
 * Author:      Bili Plugins
 * Text Domain: social-share-image
 * Domain Path: /languages
 * Author URI:  https://biliplugins.com/
 *
 * @package      Social_Share_Image
 */

/**
 * Defining Constants.
 *
 * @package    Restrict_Country
 */
if ( ! defined( 'SSI_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'SSI_VERSION', '1.0.1' );
}

if ( ! defined( 'SSI_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'SSI_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SSI_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'SSI_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'SSI_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'SSI_BASE_NAME', plugin_basename( __FILE__ ) );
}
/**
 * Apply transaltion file as per WP language.
 */
function ssi_text_domain_loader() {

	// Get mo file as per current locale.
	$mofile = SSI_PATH . 'languages/' . get_locale() . '.mo';

	// If file does not exists, then apply default mo.
	if ( ! file_exists( $mofile ) ) {
		$mofile = SSI_PATH . 'languages/plugin.mo';
	}

	load_textdomain( 'social-share-image', $mofile );
}

add_action( 'plugins_loaded', 'ssi_text_domain_loader' );

/**
 * Setting link for plugin.
 *
 * @param  array $links Array of plugin setting link.
 * @return array
 */
function ssi_setting_page_link( $links ) {

	$settings_link = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( admin_url( 'admin.php?page=social-share-image' ) ),
		esc_html__( 'Settings', 'social-share-image' )
	);

	array_unshift( $links, $settings_link );
	return $links;
}

add_filter( 'plugin_action_links_' . SSI_BASE_NAME, 'ssi_setting_page_link' );

add_action(
	'init',
	function() {
		add_rewrite_rule( 'ssi-image/([a-z0-9-]+)[/]?$', 'index.php?ssi-image=$matches[1]', 'top' );
	}
);

add_filter(
	'query_vars',
	function( $query_vars ) {
		$query_vars[] = 'ssi-image';
		return $query_vars;
	}
);


add_action(
	'template_include',
	function( $template ) {
		if ( get_query_var( 'ssi-image' ) == false || get_query_var( 'ssi-image' ) == '' ) {
			return $template;
		}
		return SSI_PATH . '/templates/template-loader.php';
	}
);

// Include Function Files.
require SSI_PATH . '/app/admin/class-social-share-admin.php';
require SSI_PATH . '/app/main/class-social-share-main.php';

