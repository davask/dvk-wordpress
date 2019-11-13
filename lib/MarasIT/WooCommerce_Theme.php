<?php
namespace DvkWP\MarasIT;

class WooCommerce_Theme {

    private $MarasITWooCommerce_Theme;

    public function __construct() {

        \Timber\Integrations\WooCommerce\WooCommerce::init(array( 'subfolder' => 'woo' ));

        // Disable WooCommerce image functionality
        // Timber\Integrations\WooCommerce\WooCommerce::disable_woocommerce_images();

        $this->MarasITWooCommerce_Theme = new \MarasIT\WooCommerce_Theme();

        add_action( 'after_setup_theme', [ $this, 'hooks' ] );
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
    }

    /**
     * Customize WooCommerce
     *
     * @see plugins/woocommerce/includes/wc-template-hooks.php for a list of actions.
     *
     * Everything here is hooked to `after_setup_theme`, because child theme functionality runs before parent theme
     * functionality. By hooking it, we make sure it runs after all hooks in the parent theme were registered.
     */
    public function hooks() {
       // Add your hooks to customize WooCommerce here
        $this->MarasITWooCommerce_Theme->hooks();
    }

    /**
     * Setup.
     */
    public function setup() {
        add_theme_support( 'woocommerce' );

        $this->MarasITWooCommerce_Theme->setup();
    }

    /**
     * Render default Twig templates.
     *
     * This function can be called from `woocommerce.php` template file in the root of the theme. It
     * mimicks the logic used by WooCommerce to sort out which template to load and tries to load
     * the corresponding Twig file. It builds up an array with Twig templates to check for. Timber
     * will use the first Twig file that exists. In addition to the default WooCommerce template
     * files, there are some opininated "Goodies" that can make your life easier. E.g., you don’t
     * have to to use *woocommerce/single-product.twig*, but can use *woocommerce/single.twig*.
     *
     * If you have your own solution going on or need to do more checks, you don’t have to call this
     * function.
     *
     * @api
     * @todo Add functionality for product tags
     * @see WC_Template_Loader::get_template_loader_files()
     */
    public static function render_default_template( $context = [] ) {

        $context = array_merge( \Timber::context(), $context );

        $templates = [];

        if ( is_singular( 'product' ) ) {
            $post = $context['post'];

            // Timber goodies
            $templates[] = "single-{$post->post_name}.twig";

            // WooCommerce default
            $templates[] = 'single-product.twig';

            // Timber goodie
            $templates[] = 'single.twig';

        } elseif ( is_archive() ) {
            $context['title'] = woocommerce_page_title( false );

            if ( is_product_taxonomy() ) {
                $term = $context['term'];

                // WooCommerce defaults
                $templates[] = "taxonomy-{$term->taxonomy}-{$term->slug}.twig";
                $templates[] = "taxonomy-{$term->taxonomy}.twig";

                // Timber goodies
                $templates[] = "taxonomy-{$term->slug}.twig";
                $templates[] = 'taxonomy.twig';
            }

            // WooCommerce default
            $templates[] = 'archive-product.twig';

            // Timber goodie
            $templates[] = 'archive.twig';
        }

        // Prepend subfolder to templates
        $templates = array_map( function( $template ) {
            return \Timber\Integrations\WooCommerce\WooCommerce::$subfolder . $template;
        }, $templates );

        \Timber::render( $templates, $context );
    }

}
