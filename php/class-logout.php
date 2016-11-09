<?php
namespace mkdo\front_end_login;
/**
 * Class Logout
 *
 * Class to control logging out
 *
 * @package mkdo\objective_licensing_forms
 */
class Logout {

	private $options_prefix;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {

		$this->options_prefix = $plugin_options->get_options_prefix();
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'logout_url', array( $this, 'logout_url' ) );
		add_action( 'wp', array( $this, 'do_logout' ) );
	}

	public function logout_url() {

		$prefix          = $this->options_prefix;
		$page_login     = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
		$page_login_url = get_the_permalink( $page_login->ID );

		if ( is_multisite() && 0 === intval( $page_login->ID ) ) {
			switch_to_blog( BLOG_ID_CURRENT_SITE );
			$page_login = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
			$page_login_url = get_the_permalink( $page_login->ID );
			restore_current_blog();
		}

		return $page_login_url . '?logout=true';
	}

	public function do_logout() {

		if ( isset( $_GET['logout'] ) ) {
			wp_logout();
			wp_safe_redirect( remove_query_arg( array( 'logout' ) ), 302 );
			exit;
		}
	}
}
