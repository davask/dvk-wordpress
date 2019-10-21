<?php

namespace DvkWP\Utils;

use DvkWP\Utils\Debug as D;

if ( ! class_exists( '\DvkWP\Utils\Functions', false ) ) {

    class Functions {

        private $defined_functions;

        public function __construct() {

            $this->defined_functions = get_defined_functions()['user'];

        }

        function __call($func, $params){
            if(in_array($func, $this->defined_functions)){
                if(method_exists($this,$func)){
                    call_user_func_array([$this,$func],$params);
                } else {
                    call_user_func_array($func,$params);
                }
            } else {
                D::dump($func.' do not exists');
            }
        }

    }

}
