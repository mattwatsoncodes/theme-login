<?php
namespace mkdo\front_end_login;
/**
 * Class Login_Form
 *
 * Class to render the login form
 *
 * @package mkdo\objective_licensing_forms
 */
class Login_Form {

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
		add_action( 'init', array( $this, 'submit' ) );
		add_action( 'front_end_login_render_form', array( $this, 'render_form' ) );
	}

	/**
	 * Form Submission
	 */
	public function render_form() {
		require Helper::get_template_path( 'login-form' );
	}

	public function submit() {
		if ( isset( $_POST['username'] ) && isset( $_POST['login_nonce'] ) ) {

			$prefix = $this->options_prefix;

			$page_home     = Helper::get_page_location( $prefix . 'form_location_home', 'home' );
			$page_home_url = get_the_permalink( $page_home->ID );

			$incorrect_password = false;
			$invalid_email      = false;

			if ( ! wp_verify_nonce( $_POST['login_nonce'], 'login' ) ) {
				$invalid_email = true;
			}

			if ( ! isset( $_POST['password'] ) ) {
				$incorrect_password = true;
			}

			if ( ! is_email( $_POST['username'] ) ) {
				$invalid_email = true;
			}

			if ( ! $incorrect_password && ! $invalid_email ) {

				$credentials = array(
					'user_login'    => sanitize_email( $_POST['username'] ),
					'user_password' => $_POST['password'],
				);

				$user = wp_signon( $credentials );

				if ( is_wp_error( $user ) ) {

					if ( isset( $user->errors['incorrect_password'] ) ) {
						$incorrect_password = true;
					}

					if ( isset( $user->errors['invalid_email'] ) ) {
						$invalid_email = true;
					}
				} else {
					wp_safe_redirect( $page_home_url, $status = 302 );
					exit;
				}
			}

			if ( $incorrect_password ) {
				add_action( 'front_end_login_render_notices', array( $this, 'render_notice_invalid_password' ) );
			} else if ( $invalid_email ) {
				add_action( 'front_end_login_render_notices', array( $this, 'render_notice_invalid_email_address' ) );
			}
		}
	}

	public function render_notice_invalid_password() {
		require Helper::get_template_path( 'login-notice-invalid-password' );
	}

	public function render_notice_invalid_email_address() {
		require Helper::get_template_path( 'login-notice-invalid-email-address' );
	}
}
