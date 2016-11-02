<?php

namespace mkdo\front_end_login;

/**
 * Class Plugin Options
 *
 * Options page for the plugin
 *
 * @package mkdo\front_end_login
 */
class Plugin_Options {

	private $options_prefix;
	private $options_menu_slug;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options_prefix      = 'mkdo_fel_';
		$this->options_menu_slug   = 'front_end_login';
		$this->plugin_settings_url = admin_url( 'options-general.php?page=' . $this->options_menu_slug );
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_init', array( $this, 'init_options_page' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'plugin_action_links_' . plugin_basename( MKDO_FEL_ROOT ) , array( $this, 'add_setings_link' ) );
	}

	/**
	 * Getters and Setters
	 */
	public function get_options_prefix() {
		return $this->options_prefix;
	}

	public function get_plugin_settings_url() {
		return $this->plugin_settings_url;
	}

	/**
	 * Initialise the Options Page
	 */
	public function init_options_page() {

		$prefix   = $this->options_prefix;
		$settings = $prefix . 'settings';

		// Register Settings
		register_setting( $settings . '_group', $prefix . 'form_location_login' );
		register_setting( $settings . '_group', $prefix . 'form_location_register' );
		register_setting( $settings . '_group', $prefix . 'form_location_reset' );
		register_setting( $settings . '_group', $prefix . 'form_location_home' );
		register_setting( $settings . '_group', $prefix . 'enqueue_front_end_assets' );
		register_setting( $settings . '_group', $prefix . 'enqueue_back_end_assets' );
		register_setting( $settings . '_group', $prefix . 'should_plugin_control_back_end_access' );
		register_setting( $settings . '_group', $prefix . 'control_login_access_users' );
		register_setting( $settings . '_group', $prefix . 'control_back_end_access_users' );
		register_setting( $settings . '_group', $prefix . 'should_plugin_control_front_end_access' );
		register_setting( $settings . '_group', $prefix . 'control_front_end_access_pages_anon_users' );
		register_setting( $settings . '_group', $prefix . 'control_front_end_access_users' );

		// Add section and fields for Form Pages
		$section = $prefix . 'section_form_locations';
		add_settings_section( $section, 'Form Locations', array( $this, 'render_section_form_locations' ), $settings );
		add_settings_field( $prefix . 'field_form_location_home', __( 'Home Page:', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_form_location_home' ), $settings, $section );
		add_settings_field( $prefix . 'field_form_location_login', __( 'Login Page:', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_form_location_login' ), $settings, $section );
		add_settings_field( $prefix . 'field_form_location_register', __( 'Register Page:', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_form_location_register' ), $settings, $section );
		add_settings_field( $prefix . 'field_form_location_reset', __( 'Reset Password Page:', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_form_location_reset' ), $settings, $section );

		// Add section and fields for Back End Access Control
		$section = $prefix . 'section_back_end_access_control';
		add_settings_section( $section, __( 'Control Access to WordPress Admin Screens', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_section_back_end_access_control' ), $settings );
		add_settings_field( $prefix . 'field_control_back_end_access', __( 'Restrict Admin Access', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_back_end_access' ), $settings, $section );
		add_settings_field( $prefix . 'field_control_login_access_users', __( 'Which user roles can access WordPress login screen?', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_login_access_users' ), $settings, $section );
		add_settings_field( $prefix . 'field_control_back_end_access_users', __( 'Which user roles can access WordPress admin screens?', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_back_end_access_users' ), $settings, $section );

		// Add section and fields for Back End Access Control
		$section = $prefix . 'section_front_end_access_control';
		add_settings_section( $section, __( 'Control Access to Certain Pages', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_section_front_end_access_control' ), $settings );
		add_settings_field( $prefix . 'field_control_front_end_access', __( 'Restrict Site Access', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_front_end_access' ), $settings, $section );
		add_settings_field( $prefix . 'field_control_login_access_pages_anon_users', __( 'Which pages can anonymous users (users who are not logged in) access?', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_login_access_pages_anon_users' ), $settings, $section );
		// add_settings_field( $prefix . 'field_control_back_end_access_users', __( 'Which user roles can access WordPress admin screens?', MKDO_FEL_TEXT_DOMAIN ), array( $this, 'render_field_control_back_end_access_users' ), $settings, $section );

		// Add section and fields for Asset Enqueing
		// $section = $prefix . 'section_enqueue_assets';
		// add_settings_section( $section, 'Enqueue Assets', array( $this, 'render_section_enqueue_assets' ), $settings );
		// add_settings_field( $prefix . 'field_enqueue_front_end_assets', 'Enqueue Front End Assets:', array( $this, 'render_field_enqueue_front_end_assets' ), $settings, $section );
		// add_settings_field( $prefix . 'field_enqueue_back_end_assets', 'Enqueue Back End Assets:', array( $this, 'render_field_enqueue_back_end_assets' ), $settings, $section );
	}

	/**
	 * Render the Form Locations section
	 */
	public function render_section_form_locations() {
		echo '<p>';
		esc_html_e( "Configure the location of each form within your site. The 'Home' page is the page you are taken to after a successful login.", MKDO_FEL_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Login Page Location Selector
	public function render_field_form_location_login() {
		$this->render_field_form_location( 'login', 'Login Page', 'form_location_login' );
	}

	// Render the Register Page Location Selector
	public function render_field_form_location_register() {
		$this->render_field_form_location( 'register', 'Register Page', 'form_location_admin' );
	}

	// Render the Password Reset Page Location Selector
	public function render_field_form_location_reset() {
		$this->render_field_form_location( 'reset', 'Reset Password Page', 'form_location_reset' );
	}

	// Render the Home Page Location Selector
	public function render_field_form_location_home() {
		$this->render_field_form_location( 'home', 'Home Page', 'form_location_home' );
	}

	// Render the Enqueue Front End Assets field
	public function render_field_form_location( $default_path, $title, $id ) {

		$prefix       = $this->options_prefix;
		$default_page = get_page_by_path( $default_path );
		if ( ! is_object( $default_page ) ) {
			$default_page = new \stdClass();
			$default_page->ID = 0;
		}
		$location     = get_option(
			$prefix . $id,
			$default_page->ID
		);
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'orderby'        => 'name',
				'order'          => 'ASC'
			)
		);

		?>
		<div class="field field-select field_form_location_login">
			<div class="field field-select field-front-end-assets">
				<label class="screen-reader-text" for="<?php echo $prefix . $id;?>"><?php esc_html_e( $title, MKDO_FEL_TEXT_DOMAIN );?></label>
				<select id="<?php echo $prefix . $id;?>" name="<?php echo $prefix . $id;?>">
					<option value="0" <?php selected( $location, 0, true );?>><?php esc_html_e( 'Please Select...', MKDO_FEL_TEXT_DOMAIN );?></option>
					<?php
					foreach ( $pages as $page ) {
						?>
						<option value="<?php echo $page->ID;?>" <?php selected( $location, $page->ID, true );?>><?php echo $page->post_title;?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Form Back End Access Control Section
	 */
	public function render_section_back_end_access_control() {
		echo '<p>';
		esc_html_e( 'This plugin has the ability to prevent users from logging into WordPress. You can disable this option and use another plugin to manage this if you prefer.', MKDO_FEL_TEXT_DOMAIN );
		echo '</p>';
	}


	// Render the Control Back End Access field
	public function render_field_control_back_end_access() {

		$prefix         = $this->options_prefix;
		$control_access = get_option(
			$prefix . 'should_plugin_control_back_end_access',
			'true'
		);

		if ( empty( $control_access ) ) {
			$control_access = 'false';
		}

		?>
		<div class="field field-checkbox field-control-back-end-access">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>should_plugin_control_back_end_access" value="true" <?php checked( 'true', $control_access ); ?> />
						<?php esc_html_e( 'Restrict users from using WordPress admin screens', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	// Render the Control Login Access field
	public function render_field_control_login_access_users() {

		global $wp_roles;

		$roles  = $wp_roles->roles;
		$prefix = $this->options_prefix;
		$users  = get_option(
			$prefix . 'control_login_access_users',
			array()
		);

		if ( ! is_array( $users ) ) {
			$users = array();
		}

		?>
		<div class="field field-checkbox field-removed-admin-roles">
			<ul class="field-input">
				<?php
				foreach ( $roles as $key => $role ) {
					?>
					<li>
						<label>
							<input type="checkbox" name="<?php echo $prefix;?>control_login_access_users[]" value="<?php echo $key; ?>" <?php if ( in_array( $key, $users ) ) { echo ' checked="checked"'; } ?> />
							<?php _e( $role['name'] );?>
						</label>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}

	// Render the Control Back End Access Users field
	public function render_field_control_back_end_access_users() {

		global $wp_roles;

		$roles  = $wp_roles->roles;
		$prefix = $this->options_prefix;
		$users  = get_option(
			$prefix . 'control_back_end_access_users',
			array( 'administrator' )
		);

		if ( ! is_array( $users ) ) {
			$users = array( 'administrator' );
		}

		if ( ! in_array( 'administrator', $users ) ) {
			$users[] = 'administrator';
		}

		?>
		<div class="field field-checkbox field-removed-admin-roles">
			<ul class="field-input">
				<?php
				foreach ( $roles as $key => $role ) {
					?>
					<li>
						<label>
							<input type="checkbox" name="<?php echo $prefix;?>control_back_end_access_users[]" value="<?php echo $key; ?>" <?php if ( in_array( $key, $users ) ) { echo ' checked="checked"'; } ?> <?php echo 'Administrator' === $role['name'] ? ' disabled="disabled"' : '';?> />
							<?php _e( $role['name'] );?>
						</label>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}

	/*
	* Render the Form Front End Access Control Section
	*/
	public function render_section_front_end_access_control() {
		echo '<p>';
		esc_html_e( 'This plugin has the ability to prevent users from accessing certain areas of the site unless logged in. You can disable this option and use another plugin to manage this if you prefer.', MKDO_FEL_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Control Front End Access field
	public function render_field_control_front_end_access() {

		$prefix         = $this->options_prefix;
		$control_access = get_option(
			$prefix . 'should_plugin_control_front_end_access',
			'true'
		);

		if ( empty( $control_access ) ) {
			$control_access = 'false';
		}

		?>
		<div class="field field-checkbox field-control-front-end-access">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>should_plugin_control_front_end_access" value="true" <?php checked( 'true', $control_access ); ?> />
						<?php esc_html_e( 'Restrict users from accessing certain areas of the site unless logged in', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	// Render the Control Front End Access Pages Anon Users field
	public function render_field_control_login_access_pages_anon_users() {

		$prefix        = $this->options_prefix;
		$defaults      = array();
		$page_login    = Helper::get_page_location( $prefix . 'form_location_login', 'login' );
		$page_register = Helper::get_page_location( $prefix . 'form_location_register', 'register' );
		$page_reset    = Helper::get_page_location( $prefix . 'form_location_reset', 'reset' );

		if ( ! in_array( $page_login->ID, $defaults ) ) {
			$defaults[] = $page_login->ID;
		}

		if ( ! in_array( $page_register->ID, $defaults ) ) {
			$defaults[] = $page_register->ID;
		}

		if ( ! in_array( $page_reset->ID, $defaults ) ) {
			$defaults[] = $page_reset->ID;
		}

		$locations = get_option(
			$prefix . 'control_front_end_access_pages_anon_users',
			$defaults
		);

		if ( ! is_array( $locations ) ) {
			$locations = array();
		}

		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'orderby'        => 'name',
				'order'          => 'ASC'
			)
		);

		?>
		<div class="field field-select">
			<div class="field field-select">
				<label class="screen-reader-text" for="<?php echo $prefix;?>control_front_end_access_pages_anon_users"><?php esc_html_e( 'Select pages that anonymous (not logged in) users can access:', MKDO_FEL_TEXT_DOMAIN );?></label>
				<select style="height: 200px;" multiple="multiple" id="<?php echo $prefix?>control_front_end_access_pages_anon_users" name="<?php echo $prefix;?>control_front_end_access_pages_anon_users[]">
					<?php
					foreach ( $pages as $page ) {
						?>
						<option value="<?php echo $page->ID;?>" <?php echo in_array( $page->ID, $locations ) ? 'selected="selected"' : '';?>><?php echo $page->post_title;?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Enqueue Assets section
	 */
	public function render_section_enqueue_assets() {
		echo '<p>';
		esc_html_e( 'Assets are loaded by default, however we recomend that you disable asset loading and include assets in your frontend workflow.', MKDO_FEL_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Enqueue Front End Assets field
	public function render_field_enqueue_front_end_assets() {

		$prefix          = $this->options_prefix;
		$enqueued_assets = get_option(
			$prefix . 'enqueue_front_end_assets',
			array(
				'plugin_css',
				'plugin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_front_end_assets[]" value="plugin_css" <?php echo in_array( 'plugin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin CSS', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_front_end_assets[]" value="plugin_js" <?php echo in_array( 'plugin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin JS', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	// Render the Enqueue Back End Assets field
	public function render_field_enqueue_back_end_assets() {

		$prefix          = $this->options_prefix;
		$enqueued_assets = get_option(
			$prefix . 'enqueue_back_end_assets',
			array(
				'plugin_admin_css',
				'plugin_admin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_back_end_assets[]" value="plugin_admin_css" <?php echo in_array( 'plugin_admin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin CSS', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $prefix;?>enqueue_back_end_assets[]" value="plugin_admin_js" <?php echo in_array( 'plugin_admin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin JS', MKDO_FEL_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Add the options page
	 */
	public function add_options_page() {
		add_submenu_page( 'options-general.php', esc_html__( 'Front End Login', MKDO_FEL_TEXT_DOMAIN ), esc_html__( 'Front End Login', MKDO_FEL_TEXT_DOMAIN ), 'manage_options', 'front_end_login', array( $this, 'render_options_page' ) );
	}

	/**
	 * Render the options page
	 */
	public function render_options_page() {
		$prefix   = $this->options_prefix;
		$settings = $prefix . 'settings';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Front End Login', MKDO_FEL_TEXT_DOMAIN );?></h2>
			<form action="options.php" method="POST">
	            <?php settings_fields( $settings . '_group' ); ?>
	            <?php do_settings_sections( $settings ); ?>
	            <?php submit_button(); ?>
	        </form>
		</div>
	<?php
	}

	/**
	 * Add 'Settings' action on installed plugin list
	 */
	public function add_setings_link( $links ) {
		array_unshift( $links, '<a href="' . $this->plugin_settings_url . '">' . esc_html__( 'Settings', MKDO_FEL_TEXT_DOMAIN ) . '</a>' );
		return $links;
	}

}
