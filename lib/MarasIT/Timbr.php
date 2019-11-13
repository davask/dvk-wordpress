<?php
namespace DvkWP\MarasIT;

use \Timber as Timber;
use DvkWP\MarasIT\StarterSite as StarterSite;
use DvkWP\MarasIT\WooCommerce_Theme as WooCommerce_Theme;

use DvkWP\Utils\Debug as D;

class Timbr {

    private $timber;

    public function __construct($lang = "en") {

        $this->timber = new Timber\Timber();

        if ( ! class_exists( 'Timber' ) ) {
            add_action(
                'admin_notices',
                function() {
                    echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
                }
            );
            add_filter(
                'template_include',
                function( $template ) {
                    return MIT_DVKWP.'/wp-content/themes/MarasIT/static/no-timber.html';
                }
            );
            return;
        }

        /**
         * Sets the directories (inside your theme) to find .twig files
         */
        Timber::$dirname = array( 'views', 'templates' );
        /**
         * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
         * No prob! Just set this value to true
         */
        Timber::$autoescape = false;

        new StarterSite();

        new WooCommerce_Theme();

        foreach (glob(MIT_SRC_MARASIT.'/Timber/*.php') as $file) {

            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $className = basename($file, '.php');
            $class = "\\MarasIT\\Timber\\".$className;

            if (class_exists($class)) {
                $this->post_types[$class] = new $class();
            }
        }

        return $this->timber;

    }

}

