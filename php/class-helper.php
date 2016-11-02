<?php
namespace mkdo\front_end_login;
/**
 * Class Helper
 *
 * Helper Class
 *
 * @package mkdo\objective_licensing_forms
 */
class Helper {

	/**
	 * Constructor
	 */
	function __construct() {
	}

	/**
	 * Get Template Path
	 *
	 * @param  String $template_name      The name of the template
	 * @param  array  $template_locations Places to look for the template
	 * @return String                     The template Path
	 */
	public static function get_template_path( $template_name, $template_locations = array() ) {

		$template_found 		= false;
		$template_path 			= $template_name;

		foreach ( $template_locations as $location ) {
			$template_path = get_stylesheet_directory() . '/' . $location . '/' . $template_name . '.php';
			if ( file_exists( $template_path ) ) {
				$template_found = true;
				break;
			}
		}

		if ( ! $template_found ) {
			$template_path = plugin_dir_path( MKDO_FEL_ROOT ) . 'views/' . $template_name . '.php';
		}

		return $template_path;
	}

	public static function get_page_location( $option, $fallback_slug ) {
		$page_default = get_page_by_path( $fallback_slug );
		$page_default_id = 0;
		if ( is_object( $page_default ) ) {
			$page_default_id = $page_default->ID;
		}
		$page_id = get_option(
			$option,
			$page_default_id
		);
		$page = get_post( $page_id );

		if ( ! is_object( $page ) ) {
			$page = new \stdClass();
			$page->ID = 0;
		}
		return $page;
	}
}
