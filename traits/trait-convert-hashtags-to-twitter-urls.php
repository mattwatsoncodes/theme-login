<?php
/**
 * Trait convert_hashtags_to_twitter_urls
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * Convert all hashtags in content into Twitter URLs
 */
trait Helper_Convert_Hashtags_To_Twitter_URLs {

	/**
	 * Convert hashtags to Twitter URLs
	 *
	 * @param string $content    The content to be replaced.
	 * @param bool   $new_window Should the link open in a new window.
	 * @return $content          The content with the replacements made
	 */
	public static function convert_hashtags_to_twitter_urls( $content, $new_window = true ) {

		$pattern     = '/#([A-Za-z0-9\/\.]*)/';
		$replacement = '<a target="_blank" href="http://twitter.com/search?q=$1">#$1</a>';

		if ( ! $new_window ) {
			$replacement = '<a href="http://twitter.com/search?q=$1">#$1</a>';
		}

		return preg_replace( $pattern, $replacement, $content );
	}
}
