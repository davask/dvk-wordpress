<?php
namespace DvkWP\MarasIT;

use Timber as Timber;
use DvkWP\Utils\Functions as F;

class ParentTheme extends \DvkWP\Utils\Functions {

    private $context;

    public function __construct($context) {
        parent::__construct();
        $this->context = $context;
    }

    public function language_attributes() {
        echo Timber::compile_string( 'lang="{{ site.language }}"', $this->context );
    }

    public function body_class() {
        echo Timber::compile_string( 'class="{{ body_class }}"', $this->context );
    }

}
