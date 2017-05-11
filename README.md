# Userland Implementation of Missing `array_uunique()` Function

There are various workarounds to get this functionality, and this is one of them!

## Code

```
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
```

## Example Usage

```
<?php

require('vendor/autoload.php');

$arr = [
	[ 'target' => 'a' ],
	[ 'target' => 'b' ],
	[ 'target' => 'c' ],
	[ 'target' => 'd' ],
	[ 'target' => 'c' ],
	[ 'target' => 'e' ],
];

var_dump( array_uunique($arr, function($a){return $a['target'];}) );
```

### Output:

```
array(5) {
  [0]=>
  array(1) {
    ["target"]=> string(1) "a"
  }
  [1]=>
  array(1) {
    ["target"]=> string(1) "b"
  }
  [2]=>
  array(1) {
    ["target"]=> string(1) "c"
  }
  [3]=>
  array(1) {
    ["target"]=> string(1) "d"
  }
  [5]=>
  array(1) {
    ["target"]=> string(1) "e"
  }
}
```

## What is a Reductor?

The Reductor is a function to take a complex type [eg: an array or object] and reduce it to a simple type [eg: a string or an integer] so that a simple, strict comparison can be performed.

If there's a better suited name for this kind of function please let me know.

## Caveats

* Filtering does not happen in-place, so there may be up to 2x memory usage from this.
* An array of unique Reductor outputs is maintained during execution, so this will also factor into memory usage.
	* Consider using a hash function to reduce the size of your Reductor output.
* Assumed Complexity: `n*log(n)`
* Someone on ##PHP@freenode.net said that this "looks slow" but didn't offer much in the way of explanation, so watch out for *that*, I guess. `:I`
* This doesn't use a Comparator function.
	* I wanted to rely on PHP builtins as much as possible, and while using a Comparator would make this much more flexible, I would have to re-implement elements of sorting and iteration in userland which would be less than ideal.

## Why is this not part of PHP core?

http://grokbase.com/t/php/php-internals/13b7qvy12m/array-unique-optional-compare-callback-proposal#20131120g1r6r48hkgdx81nd27v6gjmdmr

**TL;DR:** "It looks like a typo, and would require as much work as similar functions that were already implemented."

Gimme a break.
