=== Theme Login ===
Contributors: mkdo, mwtsn
Donate link:
Tags: front-end, front end, login, register, reset password
Requires at least: 4.7
Tested up to: 4.7
Stable tag: 1.0.4
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

== Frequently Asked Questions ==

= Gah! These styles are horrible, how do I set my own? =

Simple, turn off the default styles and JavaScript and incorporate them into your
own Workflow / Stylesheet with the following hook: [Enqueues](https://github.com/mwtsn/theme-login/blob/master/README.md#hooks-enqueues)

= How can I disable access to the WordPress dashboard for certain logged in users? =

Use the [Restrict Dashboard by Role](https://en-gb.wordpress.org/plugins/restrict-dashboard-by-role/) plugin to do this.

= How can I make it so users have to login to view certain pages or content? =

Use the [Restrict Content by Role](https://en-gb.wordpress.org/plugins/restrict-content-by-role/) plugin to do this.

= I want to use custom templates for the 'login pages', how can I do this? =

You can do this via the instructions here: [Custom Templates](https://github.com/mwtsn/theme-login/blob/master/README.md#faqs-custom-templates)

= How can I customise the slugs to the login, forgot-password, logout and register pages? =

There are hooks available to do this: [Customise page paths](https://github.com/mwtsn/theme-login/blob/master/README.md#hooks-page-paths)

= Can I force the plugin to use an email address as the username? =

Yes you can! View this hook: [Email Login](https://github.com/mwtsn/theme-login/blob/master/README.md#hooks-email-login)

= What is the best way to report an issue? =

I would prefer issues come to the GitHub repository. You can [report an issue here](https://github.com/mwtsn/theme-login/issues).

== Installation ==

1. Download this repository and unzip it into the folder `theme-login`
2. Upload the `theme-login` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin via the 'Theme Login' options page under the WordPress 'Settings' Menu

== Changelog ==

= 1.0.0 =
* First stable release

= 1.0.1 =
* Fixed issue where 'redirect_to' query string was not wired up.

= 1.0.2 =
* Added an additional hook to the register form view.

= 1.0.3 =
* Fixed an issue when overriding a view.

= 1.0.3 =
* Do not add a redirect link unless required.
