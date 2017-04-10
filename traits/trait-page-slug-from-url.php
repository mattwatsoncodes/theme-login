<?php
/**
 * Trait page_slug_from_url
 *
 * @since	0.1.0
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

/**
 * Get the slug of a page from the URL
 */
trait Helper_Page_Slug_From_URL {

	/**
	 * Page Slug from URL
	 *
	 * Get the slug of a page from the url.
	 */
	public static function page_slug_from_url() {

		global $wp_query;

		$slug = '';

		// Try a whole bunch of ways to get the slug from WP Query.
		if (
			property_exists( $wp_query, 'query' ) &&
			isset( $wp_query->query['attachment'] ) &&
			! empty( $wp_query->query['attachment'] )
		) {
			$slug = $wp_query->query['attachment'];
		} elseif (
			property_exists( $wp_query, 'query' ) &&
			isset( $wp_query->query['pagename'] ) &&
			! empty( $wp_query->query['pagename'] )
		) {
			$slug = $wp_query->query['pagename'];
		} elseif (
			property_exists( $wp_query, 'query' ) &&
			isset( $wp_query->query['name'] ) &&
			! empty( $wp_query->query['name'] )
		) {
			$slug = $wp_query->query['name'];
		} elseif (
			property_exists( $wp_query, 'query_vars' ) &&
			isset( $wp_query->query_vars['pagename'] ) &&
			! empty( $wp_query->query_vars['pagename'] )
		) {
			$slug = $wp_query->query_vars['pagename'];
		} elseif (
			property_exists( $wp_query, 'query_vars' ) &&
			isset( $wp_query->query_vars['name'] ) &&
			! empty( $wp_query->query_vars['name'] )
		) {
			$slug = $wp_query->query_vars['name'];
		} else {
			// If all else fails, grab it from the URL.
			$url  = strtok( $_SERVER["REQUEST_URI"], '?' );
			$url  = trim( $url, '/' );
			$slug = substr( $url, strrpos( $url, '/' ) + 1 );
		}

		return $slug;
	}
}
