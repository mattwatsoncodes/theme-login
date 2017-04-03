=== Front End Login ===
Contributors: mkdo, mwtsn
Donate link:
Tags: front-end, front end, login, register, reset password
Requires at least: 4.7
Tested up to: 4.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Login, Register and Reset Password, all from the front-end of your WordPress theme.

== Description ==

Hide the dashboard from users, by enabling forms for Login, Register and Reset
Password, all from the front-end of your WordPress theme.

Features:

- Pages and custom templates don't have to be created, the plugin will generate
virtual pages that you can override in your theme.
- Change the path to the login, register, forgot password and logout pages via
built in hooks (see FAQ for more).
- Change content on virtual pages via built in hooks (see FAQ for more).

== Installation ==

1. Download this repository and unzip it into the folder `front-end-login`
2. Upload the `front-end-login` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin via the 'Front End Login' options page under the WordPress 'Settings' Menu

== Frequently Asked Questions ==

= How can I disable access to the WordPress dashboard for certain logged in users? =
Use the [Restrict Dashboard by Role](https://en-gb.wordpress.org/plugins/restrict-dashboard-by-role/) plugin to do this.

= How can I make it so users have to login to view certain pages or content? =
Use the [Restrict Content by Role](https://en-gb.wordpress.org/plugins/restrict-content-by-role/) plugin to do this.

= How can I customise the slugs to the login, forgot-password, logout and register pages? =

You can use the following filters:

__Login__

`mkdo_front_end_login_login_slug`

__Register__

`mkdo_front_end_login_register_slug`

__Forgot Password__

`mkdo_front_end_login_lostpassword_slug`

__Logout__

`mkdo_front_end_login_logout_slug`

__Usage:__

The following line of code, when added to `functions.php` will change the login
page slug to `admin/login`.

`add_filter( 'mkdo_front_end_login_login_slug', function() {
	return 'admin/login';
} );`

== Changelog ==

= 1.0.0 =
* First stable release
