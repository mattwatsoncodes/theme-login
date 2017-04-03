<?php
/**
 * Trait create_guid
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * Create GUID
 */
trait Helper_Create_GUID {

	/**
	 * Create GUID
	 *
	 * I have stripped out opening and closing brackets in these guids.
	 *
	 * @return string GUID
	 */
	public static function create_guid() {
	    if ( function_exists( 'com_create_guid' ) ) {
	        $guid = com_create_guid();
			$guid = str_replace( '{', '', $guid );
			$guid = str_replace( '}', '', $guid );
			return $guid;
	    } else {
	        mt_srand( (double) microtime() * 10000 ); // optional for php 4.2.0 and up.
	        $charid = strtoupper( md5( uniqid( rand(), true ) ) );
	        $hyphen = chr( 45 ); // "-".
	        $uuid   = '' // chr( 123 ) // "{".
	            . substr( $charid, 0, 8 ) . $hyphen
	            . substr( $charid, 8, 4 ) . $hyphen
	            . substr( $charid, 12, 4 ) . $hyphen
	            . substr( $charid, 16, 4 ) . $hyphen
	            . substr( $charid, 20, 12 )
	            . ''; // chr( 125 ); // "}".
	        return $uuid;
	    }
	}
}
