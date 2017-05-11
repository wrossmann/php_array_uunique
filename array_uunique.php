<?php 
if( ! function_exists('array_uunique') ) {
	/**
	 * Remove duplicate elements from an array using a user-defined Reductor
	 * @param array $array
	 * @param callable $reductor Reduces a single array element to a simple type for strict equivalence checking.
	 */
	function array_uunique(array $array, callable $reductor) {
		$seen = [];
		return array_filter(
			$array,
			function($a)use(&$seen, $reductor){
				$val = $reductor($a);
				if( ! in_array($val, $seen, true) ) {
					$seen[] = $val;
					return true;
				} else {
					return false;
				}
			}
		);
	}
}
