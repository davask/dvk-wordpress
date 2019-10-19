<?php

namespace DvkWP\Utils;

if ( ! class_exists( '\DvkWP\Utils\Functions', false ) ) {

    class Functions {

        public function __construct() {

        }

        static function fNotexisst() {
            \DvkWP\Utils\Debug::dump('Ooops');
        }

        /**
         * Integrate dump() function from symfony.
         *
         * @since      0.0.1
         */
        static function f($parent_theme_function_name = null, $prefix = 'dvk_') {

            $function_name = "{$prefix}{$parent_theme_function_name}";

            if (!empty($parent_theme_function_name) && !empty($parent_theme_function_name)) {

                if(function_exists($function_name)) {

                    return $function_name;

                } elseif (function_exists($parent_theme_function_name)) {
                    return $parent_theme_function_name;
                }
            }
            return '\DvkWP\Utils\Functions::fNotexisst';
        }

    }

}
