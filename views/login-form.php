<form id="login-form" method="post">

	<label for="username" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Email Address:', MKDO_FEL_TEXT_DOMAIN );?>
	</label>
	<input type="email" id="username" name="username" placeholder="<?php esc_html_e( 'Email Address', MKDO_FEL_TEXT_DOMAIN );?>" value="<?php echo $username;?>"/>

	<label for="username" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Password:', MKDO_FEL_TEXT_DOMAIN );?>
	</label>
	<input type="password" id="password" name="password" placeholder="<?php esc_html_e( 'Password', MKDO_FEL_TEXT_DOMAIN );?>" value="<?php echo $password;?>"/>

	<input class="btn button" type="submit" value="<?php esc_html_e( 'Login', MKDO_FEL_TEXT_DOMAIN );?>"/>

	<?php wp_nonce_field( 'login', 'login_nonce' ); ?>
</form>
