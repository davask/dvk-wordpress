<?php
namespace DvkWP\MarasIT;

use DvkWP\MarasIT\Assets as Assets;
use DvkWP\MarasIT\Timbr as Timbr;
use DvkWP\MarasIT\Rgpd as Rgpd;

use DvkWP\Utils\Debug as D;

class MarasIT {

    public $theme;
    public $parent;
    public $version;
    public $assets;
    public $sidebars;
    public $rgpd;
    public $timber;
    public $text_domain;

    public function __construct($lang = "en", $text_domain = 'marasit') {

        $this->init();

        $composer_autoload = MIT_DVKWP . '/vendor/autoload.php';

        if ( file_exists( $composer_autoload ) ) {

            require_once $composer_autoload;

            $this->text_domain = $text_domain;

            $this->setVersion();
            $this->rgpd = Rgpd::get($lang);
            $this->timber = new Timbr($lang);
            $this->sidebars = [
                "sidebar-main" => "Main Sidebar",
            ];
            $this->setSidebars();

            add_action( 'widgets_init', [$this, 'setSidebars'] );

        }

    }

    public function init() {

        /* media version */
        define('MIT_VERSION', ( WP_DEBUG ? time() : ( defined('MIT_VERSION') ? MIT_VERSION : '1.0.0' ) ) );
        define('MIT_HOST_ENV', ( isset( $_SERVER['HTTP_HOST'] ) ? preg_replace('/([^\.]+\.)*?([^\.]+)(\.[^\.]{2,3})$/','$2',$_SERVER['HTTP_HOST']):NULL));

        /* PATH version */
        define('MIT_PARENT_DIR', get_template_directory());
        define('MIT_CHILD_DIR', get_stylesheet_directory());
        define('MIT_PARENT_URI', get_template_directory_uri());
        define('MIT_CHILD_URI', get_stylesheet_directory_uri());

        define('MIT_SRC_MARASIT', MIT_CHILD_DIR."/src/MarasIT");
        define('MIT_DVKWP', MIT_CHILD_DIR."/vendor/dvk/wordpress");
        define('MIT_DVKWP_URI', MIT_CHILD_URI."/vendor/dvk/wordpress");

    }

    public function setVersion() {

        $this->theme = wp_get_theme();
        // use the parent version for cachebusting
        $this->parent = $this->theme->parent();
        $this->version = defined('MIT_VERSION') ? MIT_VERSION : $this->parent->get('Version');

    }

    public function setAssets($enqueued_styles = [], $styles = [], $enqueued_scripts = [], $scripts = []) {
        $this->assets = new Assets($enqueued_styles, $styles, $enqueued_scripts, $scripts, $this->version);
    }

    public function setSidebars() {

        foreach ($this->sidebars as $id => $name) {
            register_sidebar( array(
                'name'          => __( $name, $this->text_domain ),
                'id'            => $id,
                'description'   => __( 'Widgets here', $this->text_domain ),
                'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>',
            ) );
        }
    }

}
