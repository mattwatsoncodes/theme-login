<?php
/**
 * Trait get_category_tags
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * Get tags associated with category
 */
trait Helper_Get_Category_tags {

	/**
	 * Get tags associated with category
	 *
	 * Based on: http://www.wprecipes.com/wordpress-trick-function-to-get-tags-related-to-category/
	 *
	 * @param  int    $term_id           The ID of the term you want to find tags for.
	 * @param  string $category_taxonomy The taxonomy of the term.
	 * @param  string $tag_taxonomy      The taxonomy of the tags.
	 * @return array                     Tags associated with the category
	 */
	public static function get_category_tags( $term_id = 0, $category_taxonomy = 'category', $tag_taxonomy = 'post_tag' ) { // @codingStandardsIgnoreLine

		global $wpdb;

		// @codingStandardsIgnoreStart
		//
		// Coding Standards wants us to use single quotes, but then our normal quotes
		// wont work properly without escaping.
		$tags = $wpdb->get_results
		("
			SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name, null as tag_link
			FROM
				" . esc_sql( $wpdb->prefix ) . "posts as p1
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "term_relationships as r1 ON p1.ID = r1.object_ID
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "terms as terms1 ON t1.term_id = terms1.term_id,

				" . esc_sql( $wpdb->prefix ) . "posts as p2
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "term_relationships as r2 ON p2.ID = r2.object_ID
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
				LEFT JOIN " . esc_sql( $wpdb->prefix ) . "terms as terms2 ON t2.term_id = terms2.term_id
			WHERE
				t1.taxonomy = '" . esc_sql( $category_taxonomy ) . "' AND p1.post_status = 'publish' AND terms1.term_id IN (" . esc_sql( $term_id ) . ") AND
				t2.taxonomy = '" . esc_sql( $tag_taxonomy ) . "' AND p2.post_status = 'publish'
				AND p1.ID = p2.ID
			ORDER by tag_name
		");
		// @codingStandardsIgnoreEnd
		$count = 0;
		foreach ( $tags as $tag ) {
			$tags[ $count ]->tag_link = get_tag_link( $tag->tag_id );
			$count++;
		}
		return $tags;
	}
}
