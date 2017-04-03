<?php
/**
 * Class Form_Register
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * The registration form.
 */
class Form_Register {

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'init', array( $this, 'submit' ) );
		add_action( MKDO_FRONT_END_LOGIN_PREFIX . '_render_register_form', array( $this, 'render_form' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Form Submission
	 */
	public function submit() {

		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['form_register_nonce'] )
		) {

			$invalid_username = false;
			$invalid_email    = false;

			// We may wish to lock down the username to be the email address only.
			$username_is_email = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_username_is_email', false );

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_register_nonce'], 'form_register' ) ) {
				$invalid_email = true;
			}

			// Check that the username is an email address.
			// if ( $username_is_email && ! is_email( $_POST['username'] ) ) {
			// 	$invalid_email = true;
			// }

			// We may wish to extend the form, so lets put in filters so we can
			// extra checks.
			$invalid_email = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_form_register_invalid_email', $invalid_email );

			// If the usernames and passwords pass the tests, try to login.
			if ( ! $invalid_email ) {

			}
			// If we had errors.
			if ( $invalid_email ) {
				// add_action( MKDO_FRONT_END_LOGIN_PREFIX . '_form_register_render_notices', array( $this, 'render_notice' ) );
			}
		}
	}

	/**
	 * Render notice
	 */
	public function render_notice() {
		// require Helper::render_view( 'view-login-notice-invalid-username-or-password' );
	}

	/**
	 * Render Form
	 */
	public function render_form() {
		$username          = null;
		$email             = null;
		$username_is_email = apply_filters( MKDO_FRONT_END_LOGIN_PREFIX . '_username_is_email', false );

		if (
			isset( $_POST['email'] ) &&
			isset( $_POST['form_register_nonce'] )
		) {
			if ( isset( $_POST['username'] ) ) {
				$username = esc_attr( $_POST['username'] );
			}
			$email = sanitize_email( $_POST['email'] );
		}
		require Helper::render_view( 'view-form-register' );
	}

	/**
	 * Register the shortcodes
	 */
	public function register_shortcodes() {

		// add the shortcodes.
		add_shortcode( MKDO_FRONT_END_LOGIN_PREFIX . '_form_register', array( $this, 'render_form_action' ) );
		add_shortcode( MKDO_FRONT_END_LOGIN_PREFIX . '_notice_register_invalid_username_or_password', array( $this, 'render_notice_actions' ) );
	}

	/**
	 * Render form action.
	 *
	 * @return string Form
	 */
	public function render_form_action() {
		ob_start();
		do_action( MKDO_FRONT_END_LOGIN_PREFIX . '_render_register_form' );
		return ob_get_clean();
	}

	/**
	 * Render the notice actions.
	 *
	 * @return string Notices
	 */
	public function render_notice_actions() {
		ob_start();
		do_action( MKDO_FRONT_END_LOGIN_PREFIX . '_form_register_render_notices' );
		return ob_get_clean();
	}
}
