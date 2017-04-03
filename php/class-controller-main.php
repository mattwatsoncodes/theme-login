<?php
/**
 * Class Controller_Main
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * The main loader for this plugin
 */
class Controller_Main {

	/**
	 * Define the settings page.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $settings;

	/**
	 * Enqueue the public and admin assets.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $controller_assets;

	/**
	 * Control access to the login screen.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $access_screen_login;

	/**
	 * The login form.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $form_login;

	/**
	 * Logout controller
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $logout;

	/**
	 * Notices on the admin screens.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $notices_admin;

	/**
	 * Rewrite common WordPress URLs.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $rewrite_urls;

	/**
	 * Create virtual pages if login slugs don't exist.
	 *
	 * @var 	object
	 * @access	private
	 * @since	0.1.0
	 */
	private $virtual_page;

	/**
	 * Constructor.
	 *
	 * @param Settings		      $settings            Define the settings page.
	 * @param Controller_Assets   $controller_assets   Enqueue the public and admin assets.
	 * @param Access_Screen_Login $access_screen_login Control access to the login screen.
	 * @param Form_Login          $form_login          The login form.
	 * @param Logout              $logout              Logout controller.
	 * @param Notices_Admin       $notices_admin       Notices on the admin screens.
	 * @param Rewrite_URLs        $rewrite_urls        Rewrite common WordPress URLs.
	 * @param Virtual_Page        $virtual_page        Create virtual pages if login slugs don't exist.
	 *
	 * @since 0.1.0
	 */
	public function __construct(
		Settings $settings,
		Controller_Assets $controller_assets,
		Access_Screen_Login $access_screen_login,
		Form_Login $form_login,
		Logout $logout,
		Notices_Admin $notices_admin,
		Rewrite_URLs $rewrite_urls,
		Virtual_Page $virtual_page
	) {
		$this->settings            = $settings;
		$this->controller_assets   = $controller_assets;
		$this->access_screen_login = $access_screen_login;
		$this->form_login          = $form_login;
		$this->logout              = $logout;
		$this->notices_admin       = $notices_admin;
		$this->rewrite_urls        = $rewrite_urls;
		$this->virtual_page        = $virtual_page;
	}

	/**
	 * Go.
	 *
	 * @since		0.1.0
	 */
	public function run() {
		load_plugin_textdomain(
			'front-end-login',
			false,
			MKDO_FRONT_END_LOGIN_ROOT . '\languages'
		);

		$this->settings->run();
		$this->controller_assets->run();
		$this->access_screen_login->run();
		$this->form_login->run();
		$this->logout->run();
		$this->notices_admin->run();
		$this->rewrite_urls->run();
		$this->virtual_page->run();
	}
}
