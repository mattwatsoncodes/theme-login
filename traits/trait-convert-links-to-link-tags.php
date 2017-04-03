<?php
/**
 * Trait convert_links_to_link_tags
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * Convert all links into clickable <a> tags
 */
trait Helper_Convert_Links_To_Link_Tags {

	/**
	 * Convert links to URLs
	 *
	 * @param string $content    The content to be replaced.
	 * @param bool   $new_window Should the link open in a new window.
	 * @return $content          The content with the replacements made
	 */
	public static function convert_links_to_link_tags( $content, $new_window = true ) {

		$pattern     = '/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/';
		$replacement = '<a target="_blank" href="$1">$1</a>';

		if ( ! $new_window ) {
			$replacement 	= '<a href="$1">$1</a>';
		}

		return preg_replace( $pattern, $replacement, $content );
	}
}
