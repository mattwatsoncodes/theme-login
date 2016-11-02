<?php
namespace mkdo\front_end_login;
/**
 * Class Back_End_Access_Control
 *
 * Class to control access to the backend of WordPress
 *
 * @package mkdo\objective_licensing_forms
 */
class Back_End_Access_Control {

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

		// Should plugin control access to back end?
		$control_access = get_option(
			$this->options_prefix . 'should_plugin_control_back_end_access',
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
			add_action( 'init', array( $this, 'prevent_login_screen_access' ) );
			add_action( 'init', array( $this, 'prevent_admin_screen_access' ) );
		}
	}

	public function prevent_login_screen_access() {

		global $wp_roles;

		$user_can_access = false;
		$roles           = $wp_roles->roles;
		$prefix          = $this->options_prefix;
		$users           = get_option(
			$prefix . 'control_login_access_users',
			array()
		);

		if ( ! is_array( $users ) ) {
			$users = array();
		}

		foreach ( $roles as $key => $role ) {
			if ( current_user_can( $key ) ) {
				$user_can_access = true;
			}
		}

		if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {

			$page_login     = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
			$page_login_url = get_the_permalink( $page_login->ID );

			// Allow oauth1 access
			if ( ! $this->is_oauth1_authorize && ! $user_can_access ) {
				wp_safe_redirect( $page_login_url );
				exit;
			}
		}
	}

	public function prevent_admin_screen_access() {

		global $wp_roles;

		$user_can_access = false;
		$roles           = $wp_roles->roles;
		$prefix          = $this->options_prefix;
		$users           = get_option(
			$prefix . 'control_back_end_access_users',
			array( 'administrator' )
		);

		if ( ! is_array( $users ) ) {
			$users = array( 'administrator' );
		}

		if ( ! in_array( 'administrator', $users ) ) {
			$users[] = 'administrator';
		}

		foreach ( $roles as $key => $role ) {
			if ( current_user_can( $key ) ) {
				$user_can_access = true;
			}
		}

		if ( is_user_logged_in() && is_admin() ) {

			$page_home     = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
			$page_home_url = get_the_permalink( $page_home->ID );

			if ( ! current_user_can( 'administrator' ) && ! $user_can_access ) {
				wp_safe_redirect( $page_home_url, 302 );
				exit;
			}
		}

		if ( is_user_logged_in() && ! is_admin() ) {
			if ( ! $user_can_access ) {
				show_admin_bar( false );
			} else {
				show_admin_bar( true );
			}
		}
	}
}
