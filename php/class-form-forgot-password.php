<?php
/**
 * Class Form_Forgot_Password
 *
 * @since	0.1.0
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

/**
 * The forgot password form.
 */
class Form_Forgot_Password {

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'init', array( $this, 'submit' ) );
		add_action( MKDO_THEME_LOGIN_PREFIX . '_render_forgot_password_form', array( $this, 'render_form' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Form Submission
	 */
	public function submit() {

		global $wpdb;

		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;

		// Render notices on redirect.
		if ( isset( $_GET['expired_key'] ) || isset( $_GET['invalid_key'] ) ) {
			add_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice', array( $this, 'render_notice_key_issue' ) );
		}

		/**
		 *  Forgot password submit.
		 */
		if (
			isset( $_POST['username'] ) &&
			isset( $_POST['form_forgot_password_nonce'] )
		) {
			$username = '';
			$invalid_email = false;
			$invalid_form  = false;

			// We may wish to lock down the username to be the email address only.
			$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );

			// Set the username.
			if ( $username_is_email ) {
				$username = sanitize_email( $_POST['username'] );
			} else {
				$username = sanitize_user( $_POST['username'] );
			}

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_forgot_password_nonce'], 'form_forgot_password' ) ) {
				$invalid_email = true;
			}

			// Check that the username is an email address.
			if ( $username_is_email && ! is_email( $username ) ) {
				$invalid_email = true;
			}

			// We may wish to extend the form, so lets put in filters so we can
			// extra checks.
			$invalid_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_invalid_email', $invalid_email );

			if ( $invalid_email ) {
				$invalid_form = true;
			}

			// Further filter to put in new validation. We should still be able
			// to access $_POST for checks.
			$invalid_form = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_invalid_form', $invalid_form );

			// If the usernames and passwords pass the tests, try to login.
			if ( ! $invalid_form ) {

				// Depending on if the username is an email or not, we need to
				// sanitize it appropriately.
				$user_data  = '';
				if ( $username_is_email ) {
					$user_data = get_user_by( 'email', trim( $username ) );
				} else {
					$user_data = get_user_by( 'login', trim( $username ) );
				}

				if ( $user_data ) {

					// Do some built in WP actions.
					do_action( 'lostpassword_post' );

					// Grab our username and email.
					$user_login = $user_data->user_login;
				    $user_email = $user_data->user_email;

					// Do some built in WP actions.
					do_action( 'retreive_password', $user_login );  // Misspelled and deprecated.
					do_action( 'retrieve_password', $user_login );

					// Are we allowed to send out passwords?
				    $proceed = apply_filters( 'allow_password_reset', true, $user_data->ID );

				    if ( ! $proceed ) {
				        $invalid_form = true;
					} elseif ( is_wp_error( $proceed ) ) {
				        $invalid_form = true;
					}

					// Are we good to proceed?
					if ( ! $invalid_form ) {

						// Get the Password.
					    $key = wp_generate_password( 20, false );
						do_action( 'retrieve_password_key', $user_login, $key );

						// Get a new WP Hasher.
					    if ( empty( $wp_hasher ) ) {
					        require_once ABSPATH . 'wp-includes/class-phpass.php';
					        $wp_hasher = new \PasswordHash( 8, true );
					    }

						// Hash the password.
					    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );

						// Update the user activation key.
					    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

						/**
						 * Send the email
						 */

						// Setup email data.
						$slug = apply_filters(
							MKDO_THEME_LOGIN_PREFIX . '_lostpassword_slug',
							'forgot-password'
						);
						$email_link     = home_url( '/' . $slug . '/?key=' . rawurlencode( $key ) . '&salt=' . urlencode( base64_encode( $user_login ) ) . '&action=password-reset' );
						$email_subject  = get_bloginfo( 'name' ) . esc_html__( ' - Password Reset', 'theme-login' );
						$email_message  = esc_html__( 'Someone requested that the password be reset for the ', 'theme-login' ) . get_bloginfo( 'name' ) . esc_html__( ' website.', 'theme-login' ) . "\r\n\r\n";
						$email_message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'theme-login' ) . "\r\n\r\n";
						$email_message .= esc_html__( 'To reset your password, visit the following address:', 'theme-login' ) . "\r\n\r\n";
						$email_message .= esc_html__( '[link]', 'theme-login' ) . "\r\n\r\n";

						// Allow filtering of subject and message.
						$email_subject = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_email_subject', $email_subject );
						$email_message = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_email_message', $email_message );

						// Builin WP subject filter.
						$email_subject = apply_filters( 'retrieve_password_title', $email_subject );

						// Replace the link in the body.
						$email_message = str_replace( '[link]', '<a href="' . esc_attr( $email_link ) . '">' . esc_html( $email_link ) . '</a>', $email_message );

						// Render HTML in the message.
						$email_message = apply_filters( 'the_content', $email_message );

						// Do actions before email.
						do_action( MKDO_THEME_LOGIN_PREFIX . '_form_register_before_email', $email_message, $email_subject );

						// Enable HTML emails.
						add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );

						// Send the message.
						if ( ! empty( $email_message ) && ! wp_mail( $user_email, $email_subject, $email_message ) ) {
							// If we could not send the message then warn the user.
							wp_die( esc_html__( 'The e-mail could not be sent.', 'theme-login' ) . "<br />\n" . esc_html__( 'Possible reason: your host may have disabled the mail() function...', 'theme-login' ) );
						} else {
							// Success, notify the user.
							add_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice', array( $this, 'render_notice' ) );
						}

						// Disable HTML emails.
						remove_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );
					}
				} else {
					$invalid_form = true;
				}
			}
			// If we had errors.
			if ( $invalid_form ) {
				add_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice', array( $this, 'render_notice' ) );
			}
		}

		/**
		 *  Reset password submit.
		 */

		// Set the cookie and redirect.
		list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
		if ( isset( $_GET['key'] ) && false === strpos( $_GET['key'], 'wc_order_' ) ) {

			$value = sprintf( '%s:%s', base64_decode( urldecode( $_GET['salt'] ) ), wp_unslash( $_GET['key'] ));
			error_log( $value );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

			wp_safe_redirect( remove_query_arg( array( 'key', 'salt' ) ), 302 );
			exit;
		}

		if (
			isset( $_POST['password'] ) &&
			isset( $_POST['form_forgot_password_reset_nonce'] )
		) {

			$password         = $_POST['password'];
			$password_confirm = $_POST['password_confirm'];
			$password_regex   = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/';

			// Filter the secure password regex.
			$password_regex = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_regex', $password_regex );

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_forgot_password_reset_nonce'], 'form_forgot_password_reset' ) ) {
				// Redirect to error.
				wp_safe_redirect( remove_query_arg( array( 'key', 'salt', 'action' ) ) . '?expired_key=true', 302 );
			}

			// Check the security of the password.
			if ( ! preg_match( $password_regex, $password ) ) {
				add_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice', array( $this, 'render_notice_insecure_password' ) );
		    } else {

				// Get the user from the cookie.
				if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
					list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
					$user = check_password_reset_key( $rp_key, $rp_login );
					if ( isset( $_POST['password'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
						$user = false;
					}
				} else {
					$user = false;
				}

				// If the does not exist.
				if ( ! $user || is_wp_error( $user ) ) {

					// Set the cookie.
					setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

					// Redirect to error.
					if ( $user && $user->get_error_code() === 'expired_key' ) {
						wp_safe_redirect( remove_query_arg( array( 'key', 'salt', 'action' ) ) . '?expired_key=true', 302 );
					} else {
						wp_safe_redirect( remove_query_arg( array( 'key', 'salt', 'action' ) ) . '?invalid_key=true', 302 );
					}
					exit;
				}

				// Setup errors.
				$errors = new \WP_Error();

				// If password does not match.
				if ( isset( $password ) && $password !== $password_confirm ) {
					// Add the notice.
					add_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice', array( $this, 'render_notice_password_no_match' ) );
				} elseif ( ( ! $errors->get_error_code() ) && isset( $_POST['password'] ) && ! empty( $_POST['password'] ) ) {
					// We are error free!
					//
					// Do the password reset.
					reset_password( $user, $password );

					// Set the cookie.
					setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

					// Get the login slug.
					$login_slug = apply_filters(
						MKDO_THEME_LOGIN_PREFIX . '_login_slug',
						'login'
					);

					// Get the login URL.
					$redirect_url = home_url( '/' . $login_slug . '/?password_reset=true' );

					// Get the redirect URL if it is set.
					if ( isset( $_GET['redirect_to'] ) ) {
						$redirect_url = sanitize_url( $_GET['redirect_to'] );
					}

					// Add a filter for the redirect URL. We may wish to extend
					// this later.
					$redirect_url = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_redirect_url', $redirect_url );

					// Do actions before redirect.
					do_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_before_redirect', $redirect_url );

					// Do the redirect.
					wp_safe_redirect( $redirect_url, 302 );
					exit;
				}
			}
		}
	}

	/**
	 * Render notice
	 */
	public function render_notice() {
		require Helper::render_view( 'view-notice-forgot-password-check-email' );
	}

	/**
	 * Render notice
	 */
	public function render_notice_key_issue() {
		require Helper::render_view( 'view-notice-forgot-password-key-issue' );
	}

	/**
	 * Render notice
	 */
	public function render_notice_insecure_password() {
		require Helper::render_view( 'view-notice-forgot-password-insecure' );
	}

	/**
	 * Render notice
	 */
	public function render_notice_password_no_match() {
		require Helper::render_view( 'view-notice-forgot-password-no-match' );
	}

	/**
	 * Render Form
	 */
	public function render_form() {

		// Render the password reset form.
		if ( isset( $_GET['key'] ) || ( isset( $_GET['action'] ) && 'password-reset' === $_GET['action'] ) ) {

			// Setup the Cookie.
			$rp_cookie = 'wp-resetpass-' . COOKIEHASH;

			// Check if the cookie exists.
			if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
				list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
			}

			// Init the password and reset password.
			$password         = null;
			$password_confirm = null;

			if ( isset( $_POST['password_confirm'] ) && isset( $_POST['password'] ) && isset( $_POST['set_password_nonce'] ) ) {
				$password_confirm = $_POST['password_confirm'];
				$password         = $_POST['password'];
			}

			require Helper::render_view( 'view-form-forgot-password-reset' );
		} else {

			// Render the lost password form.
			$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );
			$username = '';
			if ( isset( $_POST['username'] ) && isset( $_POST['reset_nonce'] ) ) {

				// Set the username.
				if ( $username_is_email ) {
					$username = sanitize_email( $_POST['username'] );
				} else {
					$username = sanitize_user( $_POST['username'] );
				}
			}
			require Helper::render_view( 'view-form-forgot-password' );
		}
	}

	/**
	 * Register the shortcodes
	 */
	public function register_shortcodes() {

		// add the shortcodes.
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password', array( $this, 'render_form_action' ) );
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_notice_forgot_password', array( $this, 'render_notice_action' ) );
	}

	/**
	 * Render form action.
	 *
	 * @return string Form
	 */
	public function render_form_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_render_forgot_password_form' );
		return ob_get_clean();
	}

	/**
	 * Render the notice action.
	 *
	 * @return string Notices
	 */
	public function render_notice_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password_render_notice' );
		return ob_get_clean();
	}

	/**
	 * Allow HTML emails
	 */
	public function wp_mail_content_type() {
		return 'text/html';
	}
}
