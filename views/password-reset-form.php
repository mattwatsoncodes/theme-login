<form id="set-password-form" method="post">
	<label for="password" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Password:', MKDO_FEL_TEXT_DOMAIN );?>
	</label>
	<input type="password" id="password" name="password" placeholder="<?php esc_html_e( 'Password', MKDO_FEL_TEXT_DOMAIN );?>" value="<?php echo $password;?>"/>

	<label for="confirm_password" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Confirm Password:', MKDO_FEL_TEXT_DOMAIN );?>
	</label>

	<input type="password" id="confirm_password" name="confirm_password" placeholder="<?php esc_html_e( 'Confirm Password', MKDO_FEL_TEXT_DOMAIN );?>" value="<?php echo $confirm_password;?>"/>

	<input class="btn button" type="submit" value="<?php esc_html_e( 'Set Password', MKDO_FEL_TEXT_DOMAIN );?>"/>

	<?php wp_nonce_field( 'set_password', 'set_password_nonce' ); ?>

	<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />
</form>
