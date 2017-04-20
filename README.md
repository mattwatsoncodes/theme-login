# Theme Login

Login, Register and Reset Password, all from the front-end of your WordPress theme.

![Theme Login](https://github.com/mwtsn/theme-login/blob/master/assets/wp-org/banner-1544x500.png?raw=true "Theme Login")

## [More Information](#more-information)

You can use this plugin along with Use the [Restrict Dashboard by Role](https://en-gb.wordpress.org/plugins/restrict-dashboard-by-role/) to limit user access to the WordPress dashboard, or if you want people to login to view certain pages only you can use [Restrict Content by Role](https://en-gb.wordpress.org/plugins/restrict-content-by-role/).

You can find out more about this plugin, including the changelog, in the [`readme.txt`](https://github.com/mwtsn/theme-login/blob/master/readme.txt) file, which the WordPress repository uses to display information about the plugin.

You can also view the plugin on the WordPress repository here: [https://wordpress.org/plugins/theme-login/](https://wordpress.org/plugins/theme-login/)

## [Roadmap](#roadmap)
A bunch of features will be coming to this plugin, including:

- Create accompanying plugin / options to enable UI setting of page slugs and redirect locations
- Create accompanying plugin / options to enable the customisation of registration and password reset emails

## [Hooks](#hooks)

There are loads of hooks that to allow you to hook in and expand the functionality
of the plugin. The best way to find them is to look at the code, it should be well
enough documented for you to figure out what they do (if not, please raise an issue).

A few of the more useful hooks are:

#### [Enqueues](#hooks-enqueues)

CSS and JS Enqueues exist within the plugin for reference and development, but
we highly recommend that the appropriate filters are used to deactivate these
enqueues, and these are concatenated and enqueued using your own theme workflow.

Don't have a workflow? We recommend [Kapow](https://github.com/mkdo/kapow-setup).

The enqueue filters all accept a boolean, and the ones the plugin uses are set to true by default. Use the following method to disable them:

`add_filter( 'mkdo_theme_login_[filter_name]', '__return_false');`

The filters available are:

- `mkdo_theme_login_do_public_enqueue` &mdash; hide all the public asset enqueues (set to `true`).
- `mkdo_theme_login_do_public_css_enqueue` &mdash; hide the public CSS enqueue (set to `true`).
- `mkdo_theme_login_do_public_js_enqueue` &mdash; hide the public JS enqueue (set to `true`).
- `mkdo_theme_login_do_admin_enqueue` &mdash; hide all the admin asset enqueues.
- `mkdo_theme_login_do_admin_css_enqueue` &mdash; hide the admin CSS enqueue.
- `mkdo_theme_login_do_admin_editor_css_enqueue` &mdash; hide the admin editor CSS enqueue.
- `mkdo_theme_login_do_admin_js_enqueue` &mdash; hide the admin JS enqueue.
- `mkdo_theme_login_do_customizer_enqueue` &mdash; hide the customizer CSS enqueue.

#### [Render Views](#hooks-render-views)
Views reside within the `/views` folder in the plugin, but you may wish to override
these views in your theme.

Use the filter `mkdo_theme_login_view_template_folder` to set where the view
sits within your theme. EG:

`add_filter( 'mkdo_theme_login_view_template_folder', function() {  
	return get_stylesheet_directory() . '/template-parts/ground-control/';  
} );`  

You can also return a boolean for the filter `mkdo_theme_login_view_template_folder_check_exists`
to perform an optional check if the template exists in your theme. However best
practice is duplicating the `/views` folder within your theme at a custom location.

### [Customise page paths](#hooks-page-paths)

You can customise the paths to each 'login page' via the following slugs:

- `mkdo_theme_login_login_slug`
- `mkdo_theme_login_register_slug`
- `mkdo_theme_login_lostpassword_slug`
- `mkdo_theme_login_logout_slug`

#### Usage

The following line of code, when added to `functions.php` will change the login
page slug to `admin/login`.

`add_filter( 'mkdo_theme_login_login_slug', function() {
	return 'admin/login';
} );`

### [Email Login](#hooks-email-login)

You can force the plugin to use an email address as the username with the following hook:

`add_filter( 'mkdo_theme_login_username_is_email', '__return_true' )`

## [FAQs](#faqs)

### [Custom Templates](#faqs-custom-templates)

You can set the 'login page' slugs via the hooks here: [Customise page paths](https://github.com/mwtsn/theme-login/blob/master/README.md#hooks-page-paths).

Once you have done this, create pages in your WordPress install to match those slugs.

In your template add in the following lines of code to render the forms and notices for each template:

#### Login

- Render the notifications: `<?php do_action( 'mkdo_theme_login_form_login_render_notice' );?>`
- Render the form: `<?php do_action( 'mkdo_theme_login_render_login_form' );?>`

#### Register

- Render the notifications: `<?php do_action( 'mkdo_theme_login_form_register_render_notice' );?>`
- Render the form: `<?php do_action( 'mkdo_theme_login_render_register_form' );?>`

#### Forgot Password

- Render the notifications: `<?php do_action( 'mkdo_theme_login_form_forgot_password_render_notice' );?>`
- Render the form: `<?php do_action( 'mkdo_theme_login_render_forgot_password_form' );?>`

#### Shortcodes

You can also render the forms and notifications with the following shortcodes:

##### Login

- Render the notifications: `[mkdo_theme_login_notice_login]`
- Render the form: `[mkdo_theme_login_form_login]`

##### Register

- Render the notifications: `[mkdo_theme_login_notice_register]`
- Render the form: `[mkdo_theme_login_form_register]`

##### Forgot Password

- Render the notifications: `[mkdo_theme_login_notice_forgot_password]`
- Render the form: `[mkdo_theme_login_form_forgot_password]`

## [Credit](#credit)

Built using the [Ground Control](https://github.com/mwtsn/ground-control) plugin framework. A framework based on root composition principles, built by [Matt Watson](https://github.com/mwtsn/) and [Dave Green](https://github.com/davetgreen/), with thanks to [Make Do](https://www.makedo.net/).
