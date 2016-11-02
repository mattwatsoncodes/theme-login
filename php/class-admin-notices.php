<?php
namespace mkdo\front_end_login;
/**
 * Class Admin_Notices
 *
 * Notifies the user if the admin needs attention
 *
 * @package mkdo\front_end_login
 */
class Admin_Notices {

	private $options_prefix;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix      = $plugin_options->get_options_prefix();
		$this->plugin_settings_url = $plugin_options->get_plugin_settings_url();
	}

	/**
	 * Do Work
	 */
	public function run() {
		// add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function admin_notices() {

		if ( false ) {
			$url = 'http://example.com';
			?>
			<div class="notice notice-warning is-dismissible">
			<p>
			<?php _e( sprintf( 'The %sFront End Login%s plugin requires that you %sinstall and activate the Example plugin%s.', '<strong>', '</strong>', '<a href="' . $plugin_url . '" target="_blank">', '</a>' ) , MKDO_FEL_TEXT_DOMAIN ); ?>
			</p>
			</div>
			<?php
		}
	}
}
