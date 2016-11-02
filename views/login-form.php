<?php
$username = null;
$password = null;

if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['login_nonce'] ) ) {
	$username = sanitize_email( $_POST['username'] );
	$password = $_POST['password'];
}
?>

<form id="login-form" method="post">

	<label for="username" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Email Address:', TEXTDOMAIN );?>
	</label>
	<input type="email" id="username" name="username" placeholder="<?php esc_html_e( 'Email Address', TEXTDOMAIN );?>" value="<?php echo $username;?>"/>

	<label for="username" class="sr-only screen-reader-text">
		<?php esc_html_e( 'Password:', TEXTDOMAIN );?>
	</label>
	<input type="password" id="password" name="password" placeholder="<?php esc_html_e( 'Password', TEXTDOMAIN );?>" value="<?php echo $password;?>"/>

	<input class="btn button" type="submit" value="<?php esc_html_e( 'Login', TEXTDOMAIN );?>"/>
	<?php wp_nonce_field( 'login', 'login_nonce' ); ?>
</form>
