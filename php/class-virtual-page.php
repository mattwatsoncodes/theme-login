<?php
/**
 * Class Virtual_Page
 *
 * @package mkdo\ground_control
 */

namespace mkdo\theme_login;

/**
 * Creates a virtual page if the page slug dosnt exist
 */
class Virtual_Page {


	/**
	 * Array of slugs to check
	 *
	 * @var 	array
	 * @access	private
	 * @since	0.1.0
	 */
	private $slugs = array();

	/**
	 * Array of post_data
	 *
	 * @var 	array
	 * @access	private
	 * @since	0.1.0
	 */
	private $post_data;

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_filter( 'init', array( $this, 'setup_virtual_slugs' ) );
		add_filter( 'init', array( $this, 'setup_virtual_post_data' ) );
		add_filter( 'the_posts', array( $this, 'setup_virtual_page' ) );
	}

	/**
	 * Setup the virtual slugs
	 */
	public function setup_virtual_slugs() {
		global $wp_query;

		$this->slugs = array();

		$this->slugs['login'] = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_login_slug',
			'login'
		);

		$this->slugs['register'] = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_register_slug',
			'register'
		);

		$this->slugs['forgot-password'] = apply_filters(
			MKDO_THEME_LOGIN_PREFIX . '_lostpassword_slug',
			'forgot-password'
		);
	}

	/**
	 * Setup the virtual post data
	 */
	public function setup_virtual_post_data() {
		global $wp_query;

		$this->post_data = array();

		$this->post_data['login'] = array(
			'post_title' => esc_html(
				apply_filters(
					MKDO_THEME_LOGIN_PREFIX . '_login_title',
					__( 'Login', 'theme-login' )
				)
			),
			'post_content' => apply_filters(
				MKDO_THEME_LOGIN_PREFIX . '_login_content',
				''
			) .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_notice_login]' .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_form_login]',
		);

		$this->post_data['register'] = array(
			'post_title' => esc_html(
				apply_filters(
					MKDO_THEME_LOGIN_PREFIX . '_register_title',
					__( 'Register', 'theme-login' )
				)
			),
			'post_content' => apply_filters(
				MKDO_THEME_LOGIN_PREFIX . '_register_content',
				''
			) .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_notice_register]' .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_form_register]',
		);

		$this->post_data['forgot-password'] = array(
			'post_title' => esc_html(
				apply_filters(
					MKDO_THEME_LOGIN_PREFIX . '_lostpassword_title',
					__( 'Forgot Password', 'theme-login' )
				)
			),
			'post_content' => apply_filters(
				MKDO_THEME_LOGIN_PREFIX . '_lostpassword_content',
				''
			) .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_notice_forgot_password]' .
			'[' .  MKDO_THEME_LOGIN_PREFIX . '_form_forgot_password]',
		);
	}

	/**
	 * Setup the Virtual Post data
	 *
	 * @param array $posts Posts.
	 */
	public function setup_virtual_page( $posts ) {

		global $wp_query, $post;

		// Get the page slug.
		$slug = Helper::page_slug_from_url();

		// If the post matches the slug.
		if ( in_array( $slug, $this->slugs, true ) ) {

			$key  = array_search( $slug , $this->slugs, true );

			// We need to virtually create the page.
			$post_object = new \stdClass();
			$post_object->ID           = 0;
			$post_object->post_name    = $slug;
			$post_object->post_title   = $this->post_data[ $key ]['post_title'];
			$post_object->post_type    = 'page';
			$post_object->post_content = $this->post_data[ $key ]['post_content'];
			$post_object->post_status  = 'publish';

			// Create the posts collection.
			$posts = array( $post_object );

			// Check if the post exists.
			$post_object_check = get_page_by_path( $post_object->post_name, OBJECT, $post_object->post_type );
			if ( is_object( $post_object_check ) ) {
				$posts = array( $post_object_check );
			}

			// If registration is not allowed, do not create the register
			// virtual page.
			if ( 'register' === $key && ! get_option( 'users_can_register' ) ) {
				return $posts;
			}

			// We have hit our page, it is no longer a 404.
			status_header( 200 );
			$wp_query->is_404         = false;
			$wp_query->is_page        = true;
			$wp_query->is_singular    = true;
			$wp_query->is_single      = false;
			unset( $wp_query->query['error'] );
			$wp_query->query_vars['error'] = '';
		}

		return $posts;
	}
}
