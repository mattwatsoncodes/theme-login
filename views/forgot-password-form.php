<form id="reset-form" method="post">
	<label for="username" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Email Address:', MKDO_FEL_TEXT_DOMAIN );?>
	</label>
	<input type="email" id="username" name="username" placeholder="<?php esc_html_e( 'Email Address', MKDO_FEL_TEXT_DOMAIN );?>" value="<?php echo $username;?>"/>

	<input class="btn button" type="submit" value="<?php esc_html_e( 'Reset Password', MKDO_FEL_TEXT_DOMAIN );?>"/>

	<?php wp_nonce_field( 'reset', 'reset_nonce' ); ?>
</form>
