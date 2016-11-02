<?php
namespace mkdo\front_end_login;
/**
 * Class Password_Reset_Form
 *
 * Class to render the password reset form
 *
 * @package mkdo\objective_licensing_forms
 */
class Password_Reset_Form {

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
		add_action( 'init', array( $this, 'password_reset_submit' ) );
		add_action( 'init', array( $this, 'forgot_password_submit' ) );
		add_action( 'front_end_login_render_password_reset_form', array( $this, 'render_password_reset_form' ) );
		add_action( 'front_end_login_render_forgot_password_form', array( $this, 'render_forgot_password_form' ) );
	}

	public function render_password_reset_form() {
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
			list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
		}

		$password         = null;
		$confirm_password = null;

		if ( isset( $_POST['confirm_password'] ) && isset( $_POST['password'] ) && isset( $_POST['set_password_nonce'] ) ) {
			$confirm_password = $_POST['confirm_password'];
			$password         = $_POST['password'];
		}
		require Helper::get_template_path( 'password-reset-form' );
	}

	public function render_forgot_password_form() {
		$password = null;
		if ( isset( $_POST['username'] ) && isset( $_POST['reset_nonce'] ) ) {
			$username = $_POST['username'];
		}
		require Helper::get_template_path( 'forgot-password-form' );
	}

	public function reset_password_submit() {
		list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$prefix          = $this->options_prefix;
		$page_login      = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
		$page_forgot     = Helper::get_page_location( $prefix . 'form_location_forgot', 'forgot' );
		$page_reset      = Helper::get_page_location( $prefix . 'form_location_reset', 'reset' );
		$page_login_url  = get_permalink( $page_login->ID );
		$page_forgot_url = get_permalink( $page_forgot->ID );
		$page_reset_url  = get_permalink( $page_reset->ID );

		// Set a cookie from the login and token
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;

		if ( isset( $_GET['key'] ) ) {
			$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			wp_safe_redirect( remove_query_arg( array( 'key', 'login', 'action' ) ), 302 );
			exit;
		}

		if ( isset( $_POST['password'] ) && isset( $_POST['set_password_nonce'] ) ) {

			if ( ! wp_verify_nonce( $_POST['set_password_nonce'], 'set_password' ) ) {
				wp_redirect( $page_forgot_url . '?expired_key=true' ), 302 );
				exit;
			}

			if (
				strlen( $_POST['password'] ) < 8 ||
				! preg_match( '#[0-9]+#', $_POST['password'] ) ||
		        ! preg_match( '#[a-zA-Z]+#', $_POST['password'] )
			) {
				wp_redirect( $page_reset_url . '?weak_password=true' ), 302 );
				exit;
		    }

			if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
				list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
				$user = check_password_reset_key( $rp_key, $rp_login );
				if ( isset( $_POST['password'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
					$user = false;
				}
			} else {
				$user = false;
			}

			if ( ! $user || is_wp_error( $user ) ) {
				setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( $page_forgot_url . '?expired_key=true' ), 302 );
				} else {
					wp_redirect( $page_forgot_url . '?invalid_key=true' ), 302 );
				}
				exit;
			}

			$errors = new WP_Error();

			if ( isset( $_POST['password'] ) && $_POST['password'] != $_POST['confirm_password'] ) {
				wp_redirect( site_url( $page_reset_url . '?password_match=false' ), 302 );
				exit;
			}

			if ( ( ! $errors->get_error_code() ) && isset( $_POST['password'] ) && ! empty( $_POST['password'] ) ) {
				reset_password( $user, $_POST['password'] );
				setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
				wp_safe_redirect( $page_login_url . '?password_reset=true' ), 302 );
				exit;
			}
		}
	}

	public function forgot_password_submit() {
		global $wpdb, $wp_hasher;

		if ( isset( $_POST['username'] ) && isset( $_POST['reset_nonce'] ) ) {


			$prefix          = $this->options_prefix;
			$page_login      = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
			$page_forgot     = Helper::get_page_location( $prefix . 'form_location_forgot', 'forgot' );
			$page_reset      = Helper::get_page_location( $prefix . 'form_location_reset', 'reset' );
			$page_login_url  = get_permalink( $page_login->ID );
			$page_forgot_url = get_permalink( $page_forgot->ID );
			$page_reset_url  = get_permalink( $page_reset->ID );
			$invalid_email   = false;

			if ( ! wp_verify_nonce( $_POST['reset_nonce'], 'reset' ) ) {
				$invalid_email = true;
			}

			if ( ! is_email( $_POST['username'] ) ) {
				$invalid_email = true;
			}

			if ( ! $invalid_email ) {

			    $user_login = sanitize_text_field( $_POST['username'] );
				$user_data  = null;

			    if ( empty( $user_login ) ) {
			        $invalid_email = true;
			    } else {
			        $user_data = get_user_by( 'email', trim( $user_login ) );

			        if ( empty( $user_data ) ) {
			        	$invalid_email = true;
				    }
			    }

				do_action( 'lostpassword_post' );

			    if ( ! $user_data ) {
					$invalid_email = true;
				}

				if ( ! $invalid_email ) {

				    // redefining user_login ensures we return the right case in the email
				    $user_login = $user_data->user_login;
				    $user_email = $user_data->user_email;

					do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
					do_action( 'retrieve_password', $user_login );

				    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

				    if ( ! $allow ) {
				        $invalid_email = true;
					} else if ( is_wp_error( $allow ) ) {
				        $invalid_email = true;
					}

					if ( ! $invalid_email ) {

					    $key = wp_generate_password( 20, false );
						do_action( 'retrieve_password_key', $user_login, $key );

					    if ( empty( $wp_hasher ) ) {
					        require_once ABSPATH . 'wp-includes/class-phpass.php';
					        $wp_hasher = new PasswordHash( 8, true );
					    }

					    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
					    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

					    $message = __( 'Someone requested that the password be reset for the following account:' ) . "\r\n\r\n";
					    $message .= network_home_url( '/' ) . "\r\n\r\n";
					    $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
					    $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
					    $message .= '<' . network_home_url( "reset?key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

					    $title = __( 'Objective Licensing - Password Reset', MKDO_FEL_TEXT_DOMAIN );

					    $title   = apply_filters( 'retrieve_password_title', $title );
					    $message = apply_filters( 'retrieve_password_message', $message, $key );
					    if ( $message && ! wp_mail( $user_email, $title, $message ) ) {
					        wp_die( __( 'The e-mail could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...' ) );
						} else {
							wp_safe_redirect( esc_url( $page_forgot_url . '?success=true' ) ), $status = 302 );
							exit;
						}
					}
				}
			}

			if ( $invalid_email ) {
				wp_safe_redirect( esc_url( $page_forgot_url . '?invalid_email=true' ) ), $status = 302 );
				exit;
			}
		}
	}
}
