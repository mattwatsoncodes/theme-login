<?php
/**
 * Class Settings
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * The main plugin settings page
 */
class Settings {

	/**
	 * Constructor.
	 *
	 * @since	0.1.0
	 */
	public function __construct() {}

	/**
	 * Do Work
	 *
	 * @since	0.1.0
	 */
	public function run() {
		add_action( 'admin_init', array( $this, 'init_settings_page' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'plugin_action_links_' . plugin_basename( MKDO_FRONT_END_LOGIN_ROOT ) , array( $this, 'add_setings_link' ) );
	}

	/**
	 * Initialise the Settings Page.
	 *
	 * @since	0.1.0
	 */
	public function init_settings_page() {

		// Register settings.
		register_setting( MKDO_FRONT_END_LOGIN_PREFIX . '_settings_group', MKDO_FRONT_END_LOGIN_PREFIX . '_example_setting' );

		// Add sections.
		add_settings_section( MKDO_FRONT_END_LOGIN_PREFIX . '_example_section',
			esc_html__( 'Example Section Heading', 'front-end-login' ),
			array( $this, MKDO_FRONT_END_LOGIN_PREFIX . '_example_section_cb' ),
			MKDO_FRONT_END_LOGIN_PREFIX . '_settings'
		);

		// Add fields to a section.
		add_settings_field( MKDO_FRONT_END_LOGIN_PREFIX . '_example_field',
			esc_html__( 'Example Field Label:', 'front-end-login' ),
			array( $this, MKDO_FRONT_END_LOGIN_PREFIX . '_example_field_cb' ),
			MKDO_FRONT_END_LOGIN_PREFIX . '_settings',
			MKDO_FRONT_END_LOGIN_PREFIX . '_example_section'
		);
	}

	/**
	 * Call back for the example section.
	 *
	 * @since	0.1.0
	 */
	public function mkdo_front_end_login_example_section_cb() {
		echo '<p>' . esc_html( 'Example description for this section.', 'front-end-login' ) . '</p>';
	}

	/**
	 * Call back for the example field.
	 *
	 * @since	0.1.0
	 */
	public function mkdo_front_end_login_example_field_cb() {
		$example_option = get_option( MKDO_FRONT_END_LOGIN_PREFIX . '_example_option', 'Default text...' );
		?>

		<div class="field field-example">
			<p class="field-description">
				<?php esc_html_e( 'This is an example field.', 'front-end-login' );?>
			</p>
			<ul class="field-input">
				<li>
					<label>
						<input type="text" name="<?php echo esc_attr( MKDO_FRONT_END_LOGIN_PREFIX . '_example_field' ); ?>" value="<?php echo esc_attr( $example_option ); ?>" />
					</label>
				</li>
			</ul>
		</div>

		<?php
	}

	/**
	 * Add the settings page.
	 *
	 * @since	0.1.0
	 */
	public function add_settings_page() {
		add_submenu_page( 'options-general.php',
			esc_html__( 'Front End Login', 'front-end-login' ),
			esc_html__( 'Front End Login', 'front-end-login' ),
			'manage_options',
			MKDO_FRONT_END_LOGIN_PREFIX,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @since	0.1.0
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Front End Login', 'front-end-login' );?></h2>

			<form action="settings.php" method="POST">
				<?php settings_fields( MKDO_FRONT_END_LOGIN_PREFIX . '_settings_group' ); ?>
				<?php do_settings_sections( MKDO_FRONT_END_LOGIN_PREFIX . '_settings' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}

	/**
	 * Add 'Settings' action on installed plugin list.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @since	0.1.0
	 */
	function add_setings_link( $links ) {
		array_unshift( $links, '<a href="options-general.php?page=' . esc_attr( MKDO_FRONT_END_LOGIN_PREFIX ) . '">' . esc_html__( 'Settings', 'front-end-login' ) . '</a>' );

		return $links;
	}
}
