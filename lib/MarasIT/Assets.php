<?php
namespace DvkWP\MarasIT;

class Assets {

    public $enqueued_styles;
    public $enqueued_scripts;
    public $styles;
    public $scripts;
    public $version;

    public function __construct($enqueued_styles = [], $styles = [], $enqueued_scripts = [], $scripts = [], $version = "1.0.0") {

        $this->enqueued_styles = $enqueued_styles;
        $this->enqueued_scripts = $enqueued_scripts;
        $this->styles = $styles;
        $this->scripts = $scripts;
        $this->version = $version;

        $this->enqueue_styles();
        $this->enqueue_scripts();

    }

    public function wp_enqueue_style($enqueue_style = '', $enqueue_style_uri = '') {

        if (!empty($enqueue_style) && !empty($enqueue_style_uri)) {

            wp_enqueue_style(
                $enqueue_style
                , $enqueue_style_uri
                , $this->enqueued_styles
                , $this->version
            );
            array_push($this->enqueued_styles, $enqueue_style);

        }

    }

    public function wp_enqueue_script($enqueue_script = '', $enqueue_script_uri = '', $in_footer = true) {

        if (!empty($enqueue_script) && !empty($enqueue_script_uri)) {

            wp_enqueue_script(
                $enqueue_script
                , $enqueue_script_uri
                , $this->enqueued_scripts
                , $this->version
                , $in_footer === false ? false : true
            );
            array_push($this->enqueued_scripts, $enqueue_script);

        }

    }

    public function enqueue_styles() {

        foreach ($this->styles as $enqueue_style => $enqueue_style_uri) {
            $this->wp_enqueue_style(
                $enqueue_style,
                $enqueue_style_uri
            );
        }

    }

    public function enqueue_scripts($in_footer = true) {

        foreach ($this->scripts as $enqueue_script => $enqueue_script_uri) {
            $this->wp_enqueue_script(
                $enqueue_script,
                $enqueue_script_uri,
                $in_footer
            );
        }

    }

}

