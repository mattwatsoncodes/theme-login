<?php
/**
 * Class Form_Register
 *
 * @since	0.1.0
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

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
		add_action( MKDO_THEME_LOGIN_PREFIX . '_render_register_form', array( $this, 'render_form' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Form Submission
	 */
	public function submit() {
		global $wpdb;

		if (
			isset( $_POST['email'] ) &&
			isset( $_POST['form_register_nonce'] )
		) {

			$email    = sanitize_email( $_POST['email'] );
			$username = '';

			if ( isset( $_POST['username'] ) ) {
				$username = sanitize_user( $_POST['username'] );
			}

			$invalid_username = false;
			$invalid_email    = false;
			$invalid_form     = false;

			// We may wish to lock down the username to be the email address only.
			$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );

			// Check the nonce.
			if ( ! wp_verify_nonce( $_POST['form_register_nonce'], 'form_register' ) ) {
				$invalid_email = true;
			}

			// Check that the email is an email address.
			if ( ! $username_is_email && ( empty( $username ) || username_exists( $username ) ) ) {
				$invalid_username = true;
			}

			// Check that the email is an email address.
			if ( ! is_email( $email ) || email_exists( $email ) ) {
				$invalid_email = true;
			}

			// We may wish to extend the form, so lets put in filters so we can
			// extra checks.
			$invalid_username = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_invalid_username', $invalid_username );
			$invalid_email    = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_invalid_email', $invalid_email );

			if ( $invalid_username || $invalid_email ) {
				$invalid_form = true;
			}

			// Further filter to put in new validation. We should still be able
			// to access $_POST for checks.
			$invalid_form = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_invalid_form', $invalid_form );

			// If the usernames and passwords pass the tests, try to login.
			if ( ! $invalid_form ) {

				// Generate a new password.
				$password = wp_generate_password();

				// Workout the unique username.
				if ( ! $username_is_email ) {
					$unique_username = sanitize_title( $username );
				} else {
					$email_name      = explode( '@', $email );
					$unique_username = sanitize_title( $email_name[0] );
				}

				// Lets make sure that the username only has the supported characters.
				$unique_username = preg_replace( '/[^\da-z]/i' , '', $unique_username );

				// We need to make sure the username is unique.
				if ( username_exists( $unique_username ) ) {
					$unique_username = $unique_username . ' ' . uniqid();
					$unique_username = preg_replace( '/[^\da-z]/i' , '', $unique_username );
				}

				// Add a filter to the username.
				$unique_username = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_unique_username', $unique_username );

				$user_data = array(
					'user_login'           => $unique_username,
					'user_pass'            => $password,
					'user_email'           => sanitize_email( $email ),
					'display_name'         => sanitize_email( $email ),
					'first_name'           => '',
					'last_name'            => '',
					'role'                 => get_option( 'default_role', 'subscriber' ),
					'show_admin_bar_front' => false,
				);

				// Add a filter to the user data.
				$user_data = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_user_data', $user_data );

				// Create the user.
				$user_id = wp_insert_user( $user_data );

				// If the user was created.
				if ( ! is_wp_error( $user_id ) ) {

					// Get the user data.
					$user_data = get_user_by( 'email', trim( $email ) );

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

						// Get a new WP Hasher
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
						$email_subject  = get_bloginfo( 'name' ) . esc_html__( ' - Welcome', 'theme-login' );
						$email_message  = esc_html__( 'Welcome to ', 'theme-login' ) . get_bloginfo( 'name' ) . '.' . "\r\n\r\n";
						$email_message .= esc_html__( 'To create your password, visit the following address:', 'theme-login' ) . "\r\n\r\n";
						$email_message .= esc_html__( '[link]' ) . "\r\n\r\n";

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
						}

						// Remove HTML emails.
						add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );
					}

					/**
					 * Do the redirect after email.
					 */

					// Get the login slug.
					$login_slug = apply_filters(
						MKDO_THEME_LOGIN_PREFIX . '_login_slug',
						'login'
					);

					// Get the login URL.
					$redirect_url = home_url( '/' . $login_slug . '/' );

					// Get the redirect URL if it is set.
					if ( isset( $_GET['redirect_to'] ) ) {
						$redirect_url = sanitize_url( $_GET['redirect_to'] );
					}

					// Add a filter for the redirect URL. We may wish to extend
					// this later.
					$redirect_url = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_form_register_redirect_url', $redirect_url );

					// Do actions before redirect.
					do_action( MKDO_THEME_LOGIN_PREFIX . '_form_register_before_redirect', $redirect_url, $user_login );

					// Do the redirect.
					wp_safe_redirect( $redirect_url, 302 );
					exit;

				} else {
					$invalid_form = true;
				}
			}

			// If we had errors.
			if ( $invalid_form ) {
				add_action( MKDO_THEME_LOGIN_PREFIX . '_form_register_render_notice', array( $this, 'render_notice' ) );
			}
		}
	}

	/**
	 * Render notice
	 */
	public function render_notice() {
		require Helper::render_view( 'view-notice-register-invalid-form' );
	}

	/**
	 * Render Form
	 */
	public function render_form() {
		$username          = null;
		$email             = null;
		$username_is_email = apply_filters( MKDO_THEME_LOGIN_PREFIX . '_username_is_email', false );

		if (
			isset( $email ) &&
			isset( $_POST['form_register_nonce'] )
		) {
			if ( isset( $username ) ) {
				$username = esc_attr( $username );
			}
			$email = sanitize_email( $email );
		}
		require Helper::render_view( 'view-form-register' );
	}

	/**
	 * Register the shortcodes
	 */
	public function register_shortcodes() {

		// add the shortcodes.
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_form_register', array( $this, 'render_form_action' ) );
		add_shortcode( MKDO_THEME_LOGIN_PREFIX . '_notice_register', array( $this, 'render_notice_action' ) );
	}

	/**
	 * Render form action.
	 *
	 * @return string Form
	 */
	public function render_form_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_render_register_form' );
		return ob_get_clean();
	}

	/**
	 * Render the notice action.
	 *
	 * @return string Notices
	 */
	public function render_notice_action() {
		ob_start();
		do_action( MKDO_THEME_LOGIN_PREFIX . '_form_register_render_notice' );
		return ob_get_clean();
	}

	/**
	 * Allow HTML emails
	 */
	public function wp_mail_content_type() {
		return 'text/html';
	}
}
