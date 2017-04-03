<?php
/**
 * Class Form_Login
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * The login form.
 */
class Form_Login {

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'init', array( $this, 'submit' ) );
		add_action( MKDO_FRONT_END_LOGIN_PREFIX . '_render_form', array( $this, 'render_form' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Form Submission
	 */
	public function submit() {

		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['form_login_nonce'] )
		) {

			$incorrect_password = false;
			$invalid_email      = false;

			// We may wish to lock down the username to be the email address only.
			$username_is_email = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_username_is_email', true );

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_login_nonce'], 'form_login' ) ) {
				$invalid_email = true;
			}

			// Check that the password has been set.
			if ( ! isset( $_POST['password'] ) ) {
				$incorrect_password = true;
			}

			// Check that the username is an email address.
			if ( $username_is_email && ! is_email( $_POST['username'] ) ) {
				$invalid_email = true;
			}

			// We may wish to extend the form, so lets put in filters so we can
			// extra checks.
			$invalid_email      = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login_invalid_email', $invalid_email );
			$incorrect_password = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login_incorrect_password', $incorrect_password );

			// If the usernames and passwords pass the tests, try to login.
			if ( ! $incorrect_password && ! $invalid_email ) {

				// Depending on if the username is an email or not, we need to
				// sanitize it appropriately.
				$user_login = esc_attr( $_POST['username'] );
				if ( $username_is_email ) {
					$user_login = sanitize_email( $_POST['username'] );
				}

				// Setup the credentials.
				$credentials = array(
					'user_login'    => sanitize_email( $user_login ),
					'user_password' => $_POST['password'],
				);

				// Do the login.
				$user = wp_signon( $credentials );

				if ( is_wp_error( $user ) ) {

					// If there was an error.
					if ( isset( $user->errors['incorrect_password'] ) ) {
						$incorrect_password = true;
					}

					if ( isset( $user->errors['invalid_email'] ) ) {
						$invalid_email = true;
					}
				} else {
					// If the login was successful.
					//
					// Lets get the home URL details.
					$page_home    = get_option( 'page_on_front' );
					$redirect_url = get_the_permalink( $page_home );

					// Add a filter for the redirect URL. We may wish to extend
					// this later.
					$redirect_url = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login_redirect_url', $redirect_url );

					// Persist the query strings.
					$qs = isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : null;

					// Redirect to the home page.
					wp_safe_redirect( $redirect_url . $qs, 302 );
					exit;
				}
			}
			// If we had errors.
			if ( $incorrect_password || $invalid_email ) {
				add_action( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login_render_notices', array( $this, 'render_notice' ) );
			}
		}
	}

	/**
	 * Render notice
	 */
	public function render_notice() {
		require Helper::render_view( 'view-login-notice-invalid-username-or-password' );
	}

	/**
	 * Render Form
	 */
	public function render_form() {
		$username          = null;
		$password          = null;
		$username_is_email = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_username_is_email', true );

		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['password'] ) &&
			isset( $_POST['login_nonce'] )
		) {
			$username = sanitize_email( $_POST['username'] );
			$password = $_POST['password'];
		}
		require Helper::render_view( 'view-form-login' );
	}

	/**
	 * Register the shortcodes
	 */
	public function register_shortcodes() {

		// add the shortcodes.
		add_shortcode( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login', array( $this, 'render_form_action' ) );
		add_shortcode( MKDO_FRONT_END_LOGIN_PREFIX . '_notice_login_invalid_username_or_password', array( $this, 'render_notice_actions' ) );
	}

	/**
	 * Render form action.
	 *
	 * @return string Form
	 */
	public function render_form_action() {
		ob_start();
		do_action( MKDO_FRONT_END_LOGIN_PREFIX . '_render_form' );
		return ob_get_clean();
	}

	/**
	 * Render the notice actions.
	 *
	 * @return string Notices
	 */
	public function render_notice_actions() {
		ob_start();
		do_action( MKDO_FRONT_END_LOGIN_PREFIX . '_form_login_render_notices' );
		return ob_get_clean();
	}
}
