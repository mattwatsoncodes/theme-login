<?php
/**
 * Class Rewrite_URLs
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

/**
 * Rewrite common WordPress URLs.
 */
class Rewrite_URLs {

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_filter( 'login_url', array( $this, 'login_url' ), 10, 3 );
		add_filter( 'register_url', array( $this, 'register_url' ), 10, 1 );
		add_filter( 'lostpassword_url', array( $this, 'lostpassword_url' ), 10, 2 );
		add_filter( 'logout_url', array( $this, 'logout_url' ), 10, 2 );
	}

	/**
	 * Set custom Login URL
	 *
	 * @param  string $login_url    The URL for login.
	 * @param  string $redirect     The URL to redirect back to upon successful login.
	 * @param  bool   $force_reauth Whether to force reauthorization, even if a cookie is present.
	 * @return string               The new login URL
	 */
	public function login_url( $login_url, $redirect, $force_reauth = false ) {

		$slug = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_login_slug',
			'login'
		);

		if ( ! empty( $redirect ) ) {
			return home_url( '/' . $slug . '/?redirect_to=' . $redirect );
		}

		return home_url( '/' . $slug . '/' );
	}

	/**
	 * Set custom Register URL
	 *
	 * @param  string $register_url The URL for registration.
	 * @return string               The new register URL
	 */
	public function register_url( $register_url ) {

		$slug = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_register_slug',
			'register'
		);

	    return home_url( '/' . $slug . '/' );
	}

	/**
	 * Set custom Lost Password URL
	 *
	 * @param  string $lostpassword_url The URL for retrieving a lost password.
	 * @param  string $redirect         The path to redirect to.
	 * @return string                   The new lost password URL
	 */
	function lostpassword_url( $lostpassword_url, $redirect ) {

		$slug = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_lostpassword_slug',
			'forgot-password'
		);

		if ( ! empty( $redirect ) ) {
			return home_url( '/' . $slug . '/?redirect_to=' . $redirect );
		}

		return home_url( '/' . $slug . '/' );
	}

	/**
	 * Set custom Logout URL
	 *
	 * @param  string $logout_url The URL for logout.
	 * @param  string $redirect   The URL to redirect back to upon successful logout.
	 * @return string             The new logout URL
	 */
	public function logout_url( $logout_url, $redirect ) {

		$slug = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_logout_slug',
			'logout'
		);

		if ( ! empty( $redirect ) ) {
			return home_url( '/' . $slug . '/?redirect_to=' . $redirect );
		}

		return home_url( '/' . $slug . '/' );
	}
}
