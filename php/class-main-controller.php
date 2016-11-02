<?php

namespace mkdo\front_end_login;

/**
 * Class Main_Controller
 *
 * The main loader for this plugin
 *
 * @package mkdo\front_end_login
 */
class Main_Controller {

	private $plugin_options;
	private $assets_controller;
	private $admin_notices;
	private $login_form;
	private $back_end_access_control;
	private $front_end_access_control;
	private $logout;

	/**
	 * Constructor
	 *
	 * @param Options            $options              Object defining the options page
	 * @param AssetsController   $assets_controller    Object to load the assets
	 */
	public function __construct(
		Plugin_Options $plugin_options,
		Assets_Controller $assets_controller,
		Admin_Notices $admin_notices,
		Login_Form $login_form,
		Back_End_Access_control $back_end_access_control,
		Front_End_Access_control $front_end_access_control,
		Logout $logout
	) {
		$this->plugin_options           = $plugin_options;
        $this->assets_controller        = $assets_controller;
		$this->admin_notices            = $admin_notices;
		$this->login_form               = $login_form;
		$this->back_end_access_control  = $back_end_access_control;
		$this->front_end_access_control = $front_end_access_control;
		$this->logout                   = $logout;
	}

	/**
	 * Do Work
	 */
	public function run() {
		load_plugin_textdomain( MKDO_FEL_TEXT_DOMAIN, false, MKDO_FEL_ROOT . '\languages' );
		$this->plugin_options->run();
		$this->assets_controller->run();
		$this->admin_notices->run();
		$this->login_form->run();
		$this->back_end_access_control->run();
		$this->front_end_access_control->run();
		$this->logout->run();
	}
}
