<?php
/**
 * Form Password Reset
 *
 * If you wish to override this file, you can do so by creating a version in your
 * theme, and using the `MKDO_THEME_LOGIN_PREFIX . '_view_template_folder` hook
 * to set the right location.
 *
 * @package mkdo\theme_login
 */

/**
 * Variables
 *
 * The following variables can be used in this view.
 * $password          = '';
 * $password_confirm  = true;
 *
 * If we need to define others we can do it here too.
 */

/**
 * Output
 *
 * Here is the HTML output, this can be styled however.
 * Do not alter this file, instead duplicate it into your theme.
 */
?>

<form id="form_forgot_password_reset" class="o-box | c-login-form" method="post" autocomplete="off">

	<?php
	// Add a filter to add controls before the password.
	do_action( MKDO_THEME_LOGIN_PREFIX . 'form_forgot_password_reset_before_password' );
	?>

	<div class="c-login-form__input c-login-form__input--password">
		<label for="password" class="u-hidden-visually">
			<?php esc_html_e( 'Password:', 'theme-login' );?>
		</label>
		<input
			type="password"
			id="password"
			name="password"
			placeholder="<?php esc_html_e( 'Password', 'theme-login' );?>"
			value="<?php echo $password;?>"
		/>
	</div>

	<?php
	// Add a filter to add controls before the password confrim.
	do_action( MKDO_THEME_LOGIN_PREFIX . 'form_forgot_password_reset_before_password_confirm' );
	?>

	<div class="c-login-form__input c-login-form__input--password_confirm">

		<label for="confirm_password" class="u-hidden-visually">
			<?php esc_html_e( 'Confirm Password:', 'theme-login' );?>
		</label>
		<input
			type="password"
			id="password_confirm"
			name="password_confirm"
			placeholder="<?php esc_html_e( 'Confirm Password', 'theme-login' );?>"
			value="<?php echo $password_confirm;?>"
		/>
	</div>

	<?php
	// Add a filter to add controls before the submit button.
	do_action( MKDO_THEME_LOGIN_PREFIX . 'form_forgot_password_reset_before_submit' );
	?>

	<div class="c-login-form__input c-login-form__input--submit">
		<input
			class="c-btn c-btn--primary c-btn--small"
			type="submit"
			value="<?php esc_html_e( 'Set Password', 'theme-login' );?>"
		/>
	</div>

	<?php
	// Add a filter to add controls before the navigation.
	do_action( MKDO_THEME_LOGIN_PREFIX . 'form_forgot_password_reset_before_navigation' );
	?>

	<nav role="navigation">
		<ul class="o-list-inline | c-login-form__navigation">
			<li class="o-list-inline__item">
				<a
					href="<?php echo esc_url( wp_login_url() ); ?>"
					title="<?php esc_html_e( 'Login', 'theme-login' );?>"
				>
					<?php esc_html_e( 'Login', 'theme-login' );?>
				</a>
			</li>
			<?php
			if ( get_option( 'users_can_register' ) ) {
				?>
				<li class="o-list-inline__item">
					<a
						href="<?php echo esc_url( wp_registration_url() ); ?>"
						title="<?php esc_html_e( 'Register', 'theme-login' );?>"
					>
						<?php esc_html_e( 'Register', 'theme-login' );?>
					</a>
				</li>
				<?php
			}
			?>
		</ul>
	</nav>

	<?php
	// Add a filter to add controls after the form.
	do_action( MKDO_THEME_LOGIN_PREFIX . 'form_forgot_password_reset_after_form' );
	?>

	<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />

	<?php
	// Render the NOnce for security.
	wp_nonce_field( 'form_forgot_password_reset', 'form_forgot_password_reset_nonce' );
	?>
</form>
