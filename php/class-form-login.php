<?php
/**
 * Class Form_Login
 *
 * @since	0.1.0
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

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
		add_action( MKDO_THEME_LOGIN_PREFIX . '_render_login_form', array( $this, 'render_form' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Form Submission
	 */
	public function submit() {

		// Render notices on redirect.
		if ( isset( $_GET['password_reset'] ) ) {
			add_action( MKDO_THEME_LOGIN_PREFIX . '_form_login_render_notice', array( $this, 'render_notice_password_reset' ) );
		}

		// Do the form login.
		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['form_login_nonce'] )
		) {

			$username           = '';
			$invalid_email      = false;
			$invalid_form       = false;
			$incorrect_password = false;

			// We may wish to lock down the username to be the email address only.
			$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );

			// Set the username.
			if ( $username_is_email ) {
				$username = sanitize_email( $_POST['username'] );
			} else {
				$username = sanitize_user( $_POST['username'] );
			}

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_login_nonce'], 'form_login' ) ) {
				$invalid_email = true;
			}

			// Check that the password has been set.
			if ( ! isset( $_POST['password'] ) ) {
				$incorrect_password = true;
			}

			// Check that the username is an email address.
			if ( $username_is_email && ! is_email( $username ) ) {
				$invalid_email = true;
			}

			// We may wish to extend the form, so lets put in filters so we can
			// extra checks.
			$invalid_email      = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_login_invalid_email', $invalid_email );
			$incorrect_password = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_login_incorrect_password', $incorrect_password );

			// If the usernames and passwords pass the tests, try to login.
			if ( ! $incorrect_password && ! $invalid_email ) {

				// Setup the credentials.
				$credentials = array(
					'user_login'    => $username,
					'user_password' => $_POST['password'],
				);

				// Do actions before login.
				do_action( MKDO_THEME_LOGIN_PREFIX . '_form_login_before_login', $credentials );

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

					// Get the redirect URL if it is set.
					if ( isset( $_GET['redirect_to'] ) ) {
						$redirect_url = sanitize_url( $_GET['redirect_to'] );
					}

					// Add a filter for the redirect URL. We may wish to extend
					// this later.
					$redirect_url = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_login_redirect_url', $redirect_url );

					// Persist the query strings.
					$qs = isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : null;

					// Do actions before redirect.
					do_action( MKDO_THEME_LOGIN_PREFIX . '_form_login_before_redirect', $redirect_url );

					// Redirect to the home page.
					wp_safe_redirect( $redirect_url . $qs, 302 );
					exit;
				}
			}
			// If we had errors.
			if ( $incorrect_password || $invalid_email ) {
				add_action( MKDO_THEME_LOGIN_PREFIX . '_form_login_render_notice', array( $this, 'render_notice' ) );
			}
		}
	}

	/**
	 * Render notice
	 */
	public function render_notice() {
		require Helper::render_view( 'view-notice-login-invalid-username-or-password' );
	}

	/**
	 * Render notice
	 */
	public function render_notice_password_reset() {
		require Helper::render_view( 'view-notice-login-password-reset' );
	}

	/**
	 * Render Form
	 */
	public function render_form() {
		$username          = null;
		$password          = null;
		$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );

		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['password'] ) &&
			isset( $_POST['form_login_nonce'] )
		) {
			$username = '';
			$password = $_POST['password'];

			// Set the username.
			if ( $username_is_email ) {
				$username = sanitize_email( $_POST['username'] );
			} else {
				$username = sanitize_user( $_POST['username'] );
			}
		}
		require Helper::render_view( 'view-form-login' );
	}

	/**
	 * Register the shortcodes
	 */
	public function register_shortcodes() {

		// add the shortcodes.
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_form_login', array( $this, 'render_form_action' ) );
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_notice_login', array( $this, 'render_notice_action' ) );
	}

	/**
	 * Render form action.
	 *
	 * @return string Form
	 */
	public function render_form_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_render_login_form' );
		return ob_get_clean();
	}

	/**
	 * Render the notice action.
	 *
	 * @return string Notices
	 */
	public function render_notice_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_form_login_render_notice' );
		return ob_get_clean();
	}
}
