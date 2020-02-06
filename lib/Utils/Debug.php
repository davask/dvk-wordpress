<?php

namespace DvkWP\Utils;

if ( ! class_exists( '\DvkWP\Utils\Debug', false ) ) {

    class Debug {

        public function __construct() {

        }

    	/**
    	 * Integrate dump() function from symfony.
    	 *
    	 * @since      0.0.1
    	 */
    	 public static function dump($var, $die = false) {

            if(!defined('WP_DEBUG')) define('WP_DEBUG', false);
            if(!defined('DVK_DEBUG')) define('DVK_DEBUG', false);

    		if (WP_DEBUG OR DVK_DEBUG) {
                if ((integer)substr(phpversion(),0,1) >= 7) {
                   dump($var);
                } else {
                    echo '<pre>';
                    var_dump($var);
                    echo '</pre>';
                }
                if ($die) {
                    die();
                }
            }

    	}

    }

}
