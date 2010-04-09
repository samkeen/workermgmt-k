<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * simple utility methods
 *
 * @author  Sam Keen
 * @version		v.0.1
 */
class u {
    /**
     * Shortcut wrapper for htmlspecialchars()
     *
     * @param string $string_to_output
     * @param boolean $echo_output
     * @return echo'ed output or string if $echo_output is false
     */
    public static function h($string_to_output, $echo_output=true) {
        $cleaned = htmlspecialchars($string_to_output, ENT_NOQUOTES, 'UTF-8');
        if($echo_output) {
            echo $cleaned;
        } else {
            return $cleaned;
        }
    }
    /**
     * For each array element in an array, ensure that it has all keys supplied
     * by $keys.  If key not present, add it with a value of null.
     *
     * $array is expected to be array(array(),array(),array(),...)
     *
     * @param $array integer indexed set arrays
     *  ex: array(array('name'=>cat,'age'->1),array('name'=>dog,'age'->2,'foo'=9))
     * @param $keys array of keys values to ensure are set on each array in $array
     *  ex: array('foo','bar');
     *
     * resultant array from above examples would be
     * <code>
     *  array(
     *    array(
     *      'name'=>cat,'age'->1,'foo'=>null,'bar'=>null
     *    ),
     *    array(
     *      'name'=>dog,'age'->2,'foo'=>9,'bar'=>null
     *    )
     *  )
     * </code>
     */
    public static function ensure_keys(&$array, $keys) {
        $keys = array_fill_keys(array_values($keys),null);
        foreach($array as $index => &$item) {
            if(is_array($item)) {
                $item = array_merge($keys, $item);
            } else {
                unset ($array[$index]);
            }
        }
    }
    /**
     * Simple safe array get value function
     * a(rray)_g(et)_e(lse)
     *
     * @param array $array The haystack
     * @param string $key_sought The key wea re looking for
     * @param mixed What to return if key not found (defaults to null)
     */
    public static function arrge($array, $key_sought, $alternative=null) {
        return is_array($array) && isset ($array[$key_sought])
            ? $array[$key_sought]
            : $alternative;
    }
}
