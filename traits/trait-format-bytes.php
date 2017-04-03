<?php
/**
 * Trait format_bytes
 *
 * @since	0.1.0
 *
 * @package mkdo\front_end_login
 */

namespace mkdo\front_end_login;

/**
 * Trait the Helper class
 */
trait Helper_Format_Bytes {

	/**
	 * Convert Bytes
	 *
	 * Source: http://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
	 *
	 * Convert Bytes into KB, MB, GB, TB...
	 *
	 * @param  string  $bytes     The bytes.
	 * @param  integer $precision Rounding.
	 * @return string             The converted bytes
	 */
	public static function format_bytes( $bytes, $precision = 0 ) {
		$base     = log( floatval( $bytes ), 1024 );
	    $suffixes = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
	    return round( pow( 1024, $base - floor( $base ) ), $precision ) . $suffixes[ floor( $base ) ];
	}
}
