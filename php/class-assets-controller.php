<?php
namespace mkdo\front_end_login;
/**
 * Class Assets_Controller
 *
 * Sets up the JS and CSS needed for this plugin
 *
 * @package mkdo\front_end_login
 */
class Assets_Controller {

	private $options_prefix;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix = $plugin_options->get_options_prefix();
	}

	/**
	 * Do Work
	 */
	public function run() {
		// add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}


	/**
	 * Enqueue Scripts
	 */
	public function wp_enqueue_scripts() {

		$prefix = $this->options_prefix;

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

		// CSS
		if ( in_array( 'plugin_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'css/plugin.css', MKDO_FEL_ROOT );
			wp_enqueue_style( MKDO_FEL_TEXT_DOMAIN, $plugin_css_url, array(), MKDO_FEL_VERSION, false );
		}

		// JS
		if ( in_array( 'plugin_js', $enqueued_assets ) ) {
			$plugin_js_url  = plugins_url( 'js/plugin.js', MKDO_FEL_ROOT );
			wp_enqueue_script( MKDO_FEL_TEXT_DOMAIN, $plugin_js_url, array( 'jquery' ), MKDO_FEL_VERSION, true );
		}
	}

	/**
	 * Enqueue Admin Scripts
	 */
	public function admin_enqueue_scripts() {

		$prefix = $this->options_prefix;

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

		// CSS
		if ( in_array( 'plugin_admin_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'css/plugin-admin.css', MKDO_FEL_ROOT );
			wp_enqueue_style( MKDO_FEL_TEXT_DOMAIN . '-admin', $plugin_css_url, array(), MKDO_FEL_VERSION, false );
		}

		// JS
		if ( in_array( 'plugin_admin_js', $enqueued_assets ) ) {
			$plugin_js_url  = plugins_url( 'js/plugin-admin.js', MKDO_FEL_ROOT );
			wp_enqueue_script( MKDO_FEL_TEXT_DOMAIN . '-admin', $plugin_js_url, array( 'jquery' ), MKDO_FEL_VERSION, true );
		}
	}
}
