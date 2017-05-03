<?php
/**
 * Theme Login
 *
 * @link              https://github.com/mwtsn/theme-login
 * @package           mkdo\theme-login
 *
 * Plugin Name:       Theme Login
 * Plugin URI:        https://github.com/mwtsn/theme-login
 * Description:       Login, Register and Reset Password, all from the front-end of your WordPress theme.
 * Version:           1.0.4
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        https://makedo.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       theme-login
 * Domain Path:       /languages
 */

// Abort if this file is called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Constants.
define( 'MKDO_THEME_LOGIN_ROOT', __FILE__ );
define( 'MKDO_THEME_LOGIN_NAME', 'Theme Login' );
define( 'MKDO_THEME_LOGIN_PREFIX', 'mkdo_theme_login' );

// Classes.
require_once 'php/class-helper.php';
require_once 'php/class-settings.php';
require_once 'php/class-access-screen-login.php';
require_once 'php/class-controller-assets.php';
require_once 'php/class-controller-main.php';
require_once 'php/class-form-forgot-password.php';
require_once 'php/class-form-login.php';
require_once 'php/class-form-register.php';
require_once 'php/class-logout.php';
require_once 'php/class-notices-admin.php';
require_once 'php/class-rewrite-urls.php';
require_once 'php/class-virtual-page.php';

// Namespaces
//
// Add references for each class here. If you add new classes be sure to include
// the namespace.
use mkdo\theme_login\Helper;
use mkdo\theme_login\Settings;
use mkdo\theme_login\Access_Screen_Login;
use mkdo\theme_login\Controller_Assets;
use mkdo\theme_login\Controller_Main;
use mkdo\theme_login\Form_Forgot_Password;
use mkdo\theme_login\Form_Login;
use mkdo\theme_login\Form_Register;
use mkdo\theme_login\Logout;
use mkdo\theme_login\Notices_Admin;
use mkdo\theme_login\Rewrite_URLs;
use mkdo\theme_login\Virtual_Page;

// Instances.
$settings                 = new Settings();
$access_screen_login  	  = new Access_Screen_Login();
$controller_assets  	  = new Controller_Assets();
$form_forgot_password     = new Form_Forgot_Password();
$form_login               = new Form_Login();
$form_register            = new Form_Register();
$logout                   = new Logout();
$notices_admin  	      = new Notices_Admin();
$rewrite_urls             = new Rewrite_URLs();
$virtual_page             = new Virtual_Page();
$controller_main          = new Controller_Main(
	$settings,
	$controller_assets,
	$access_screen_login,
	$form_forgot_password,
	$form_login,
	$form_register,
	$logout,
	$notices_admin,
	$rewrite_urls,
	$virtual_page
);

// Go.
$controller_main->run();
