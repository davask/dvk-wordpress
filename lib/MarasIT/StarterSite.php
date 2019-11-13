<?php

namespace DvkWP\MarasIT;

use Timber as Timber;
use Twig as Twig;
use DvkWP\MarasIT\Cmb as Cmb;

class StarterSite extends \Timber\Site {

    /** Add timber support. */
    public function __construct() {

        add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        parent::__construct();

    }

    /** This is where you can register custom post types. */
    public function register_post_types() {

        $post_types = new Cmb();

    }

    /** This is where you can register custom taxonomies. */
    public function register_taxonomies() {

    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context( $context ) {
        $context['menu']  = new Timber\Menu();
        $context['site']  = $this;
        $context['media'] = [
            'version' => defined('MIT_VERSION') ? MIT_VERSION : '1.0.0',
        ];

        return $context;
    }

    public function theme_supports() {

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support( 'menus' );

    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig( $twig ) {

        $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

        $twig->addFunction( new Timber\Twig_Function( 'dd', '\DvkWP\Utils\Debug::dump' ) );
        $twig->addFunction( new Timber\Twig_Function( 'bc', function(){
            // <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span class="mr-2"><a href="blog.html">Blog <i class="ion-ios-arrow-forward"></i></a></span> <span>{{post.title}} <i class="ion-ios-arrow-forward"></i></span></p>
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<p class="breadcrumbs">','</p>');
            }
        } ) );

        $twig->addFilter( new Timber\Twig_Filter( 'unserialize', 'unserialize' ) );

        return $twig;

    }

}
