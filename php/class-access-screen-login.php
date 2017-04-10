<?php
/**
 * Class Access_Screen_Login
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

/**
 * Control access to the login screen.
 */
class Access_Screen_Login {

	/**
	 * Is this an oauth1 request?
	 *
	 * @var 	bool
	 * @access	private
	 * @since	0.1.0
	 */
	private $is_oauth1_authorize;

	/**
	 * Is this an ajax request?
	 *
	 * @var 	bool
	 * @access	private
	 * @since	0.1.0
	 */
	private $is_ajax;

	/**
	 * Constructor
	 */
	function __construct() {

		$this->is_oauth1_authorize = false;
		$this->is_ajax             = false;

		// Is this an oauth1 request?
		if ( isset( $_GET['action'] ) ) {
			if ( 'oauth1_authorize' === $_GET['action'] ) {
				$this->is_oauth1_authorize = true;
			}
		} elseif ( isset( $_REQUEST['redirect_to'] ) ) {
			if ( strpos( $_REQUEST['redirect_to'], 'oauth1_authorize' ) !== false ) {
				$this->is_oauth1_authorize = true;
			}
		}

		// If we are doing an ajax request, do not restrict.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->is_ajax = true;
		}
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'init', array( $this, 'login_screen_access' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function login_screen_access() {

		if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {

			// Allow oauth1 access.
			if ( ! $this->is_oauth1_authorize && ! $this->is_ajax ) {
				auth_redirect();
				exit;
			}
		}
	}
}
