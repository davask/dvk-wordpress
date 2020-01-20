<?php
namespace DvkWP\MarasIT;

use DvkWP\MarasIT\PostType;

use DvkWP\Utils\Debug as D;

class Cmb {

    private $post_types;

    public function __construct() {

        foreach (glob(MIT_SRC_MARASIT.'/PostType/*.php') as $file) {

            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $className = basename($file, '.php');
            $class = "\\MarasIT\\PostType\\".$className;

            if (class_exists($class)) {
                $this->post_types[$class] = new $class();
            }
        }

    }

}

