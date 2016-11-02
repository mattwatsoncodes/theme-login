<?php

/**
 * @link              https://github.com/mkdo/front-end-login
 * @package           mkdo\front_end_login
 *
 * Plugin Name:       Front End Login
 * Plugin URI:        https://github.com/mkdo/front-end-login
 * Description:       Front End Login, Password Reset and Register User
 * Version:           1.0.0
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        http://www.makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       front-end-login
 * Domain Path:       /languages
 */

// Constants
define( 'MKDO_FEL_ROOT', __FILE__ );
define( 'MKDO_FEL_VERSION', '1.0.0' );
define( 'MKDO_FEL_TEXT_DOMAIN', 'front-end-login' );

// Load Classes
require_once 'php/class-helper.php';
require_once 'php/class-plugin-options.php';
require_once 'php/class-assets-controller.php';
require_once 'php/class-admin-notices.php';
require_once 'php/class-login-form.php';
require_once 'php/class-back-end-access-control.php';
require_once 'php/class-front-end-access-control.php';
require_once 'php/class-main-controller.php';

// Use Namespaces
use mkdo\front_end_login\Helper;
use mkdo\front_end_login\Plugin_Options;
use mkdo\front_end_login\Assets_Controller;
use mkdo\front_end_login\Admin_Notices;
use mkdo\front_end_login\Login_form;
use mkdo\front_end_login\Back_End_Access_Control;
use mkdo\front_end_login\Front_End_Access_Control;
use mkdo\front_end_login\Main_Controller;

// Initialize Classes
$helper                   = new Helper();
$plugin_options           = new Plugin_Options();
$assets_controller        = new Assets_Controller( $plugin_options );
$admin_notices            = new Admin_Notices( $plugin_options );
$login_form               = new Login_Form( $plugin_options );
$back_end_access_control  = new Back_End_Access_Control( $plugin_options );
$front_end_access_control = new Front_End_Access_Control( $plugin_options );
$main_controller          = new Main_Controller(
	$plugin_options,
	$assets_controller,
	$admin_notices,
	$login_form,
	$back_end_access_control,
	$front_end_access_control
);

// Run the Plugin
$main_controller->run();
