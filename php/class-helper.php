<?php
/**
 * Class Helper
 *
 * @since	0.1.0
 *
 * @package mkdo\theme_login
 */

namespace mkdo\theme_login;

/**
 * Traits
 *
 * Require all the traits you want to use in your Helper here. EG:
 *
 * require_once __DIR__ . '/../traits/trait-convert-hashtags-to-twitter-urls.php';
 * require_once __DIR__ . '/../traits/trait-convert-links-to-link-tags.php';
 * ...
 *
 * Then within the Helper Class include the traits with the 'Use' declaration.
 */
require_once __DIR__ . '/../traits/trait-page-slug-from-url.php';
require_once __DIR__ . '/../traits/trait-render-view.php';

/**
 * Helper class containing useful static methods.
 *
 * We are using traits, so that only need to 'use' the traits that are valid in
 * this build.
 */
class Helper {

	/**
	 * Include Traits
	 *
	 * Include your traits here so that the methods can be called by
	 * the Helper. EG:
	 *
	 * use Helper_Convert_Hashtags_To_Twitter_URLs;
	 *
	 * Having this line of code will enable you to envoke the method
	 * as if it were part of this Class. EG:
	 *
	 * `Helper::convert_hashtags_to_twitter_urls( $content );`
	 */
	use Helper_Page_Slug_From_URL;
	use Helper_Render_View;
}
