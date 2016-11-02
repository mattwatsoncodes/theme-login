<?php
namespace mkdo\front_end_login;
/**
 * Class Front_End_Access_Control
 *
 * Class to control access to the frontend of WordPress
 *
 * @package mkdo\objective_licensing_forms
 */
class Front_End_Access_Control {

	private $options_prefix;
	private $is_oauth1_authorize;
	private $control_access;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {

		$this->options_prefix      = $plugin_options->get_options_prefix();
		$this->is_oauth1_authorize = false;
		$this->control_access      = false;

		// Is this an oauth1 request?
		if ( isset( $_GET['action'] ) ) {
			if ( 'oauth1_authorize' === $_GET['action'] ) {
				$this->is_oauth1_authorize = true;
			}
		} else if ( isset( $_REQUEST['redirect_to'] ) ) {
			if ( strpos( $_REQUEST['redirect_to'], 'oauth1_authorize' ) !== false ) {
				$this->is_oauth1_authorize = true;
			}
		}

		// Should plugin control access to front end?
		$control_access = get_option(
			$this->options_prefix . 'should_plugin_control_front_end_access',
			'true'
		);

		if ( 'true' === $control_access ) {
			$this->control_access = true;
		}

		// If we are doing an ajax request, do not restrict
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->control_access = false;
		}
	}

	/**
	 * Do Work
	 */
	public function run() {
		if ( $this->control_access ) {
			add_action( 'wp', array( $this, 'prevent_page_access' ) );
		}
	}

	public function prevent_page_access() {

		global $post;

		$prefix         = $this->options_prefix;
		$post_id        = 0;
		$rest_oauth1    = get_query_var( 'rest_oauth1' );
		$defaults       = array();
		$page_login     = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
		$page_register  = Helper::get_page_location( $prefix . 'form_location_register', 'register' );
		$page_reset     = Helper::get_page_location( $prefix . 'form_location_reset', 'reset' );
		$page_login_url = get_the_permalink( $page_login->ID );

		if ( ! in_array( $page_login->ID, $defaults ) ) {
			$defaults[] = $page_login->ID;
		}

		if ( ! in_array( $page_register->ID, $defaults ) ) {
			$defaults[] = $page_register->ID;
		}

		if ( ! in_array( $page_reset->ID, $defaults ) ) {
			$defaults[] = $page_reset->ID;
		}

		$locations = get_option(
			$prefix . 'control_front_end_access_pages_anon_users',
			$defaults
		);

		if ( ! is_array( $locations ) ) {
			$locations = array();
		}

		if ( is_object( $post ) ) {
			$post_id = $post->ID;
		}

		if ( ( $key = array_search( '0', $locations ) ) !== false ) {
		    unset( $locations[ $key ] );
		}

		// Allow anon access to certain pages
		if ( ! is_admin() && ! is_user_logged_in() && ! in_array( $post_id, $locations ) ) {

			// Allow oauth1 access
			if ( empty( $rest_oauth1 ) ) {
				wp_safe_redirect( $page_login_url, 302 );
				exit;
			}
		}
	}
}
