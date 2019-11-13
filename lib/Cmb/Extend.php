<?php

namespace DvkWP\Cmb;

use DvkWP\Utils\Debug as D;

if ( ! class_exists( '\DvkWP\Cmb\Extend', false ) ) {

    class Extend {

        protected $text_domain;
        protected $type;
        protected $name;
        protected $prefix;
        protected $suffix;
        protected $taxonomy;
        protected $taxonomies;
        protected $groups;

        public function __construct($post_type = 'post', $prefix = 'post', $text_domain = 'marasit') {

            $this->type = $post_type;
            $this->name = ucfirst($prefix);
            $this->prefix = $prefix.'_';
            $this->taxonomy = 'category';
            $this->taxonomies = ['category', 'tag'];
            $this->suffix = 'base';
            $this->suffixes = array_merge(['base'], $this->taxonomies);
            $this->text_domain = $text_domain;
            $this->groups = [];

        }

        public function register($capability_type = "page", $custom_supports = [], $menu_icon = 'dashicons-admin-post', $menu_position = 100, $taxonomies = [], $permalinks = []) {

            // Set UI labels for Custom Post Type
            $labels = array(
                'name' => _x($this->name, $this->name.' Type General Name', $this->text_domain),
                'singular_name' => _x($this->name, $this->name.' Type Singular Name', $this->text_domain),
                'menu_name' => __($this->name, $this->text_domain),
                'parent_item_colon' => __('Parent '.$this->name, $this->text_domain),
                'all_items' => __('All '.$this->name, $this->text_domain),
                'view_item' => __('View '.$this->name, $this->text_domain),
                'add_new_item' => __('Add New '.$this->name, $this->text_domain),
                'add_new' => __('Add New', $this->text_domain),
                'edit_item' => __('Edit '.$this->name, $this->text_domain),
                'update_item' => __('Update '.$this->name, $this->text_domain),
                'search_items' => __('Search '.$this->name, $this->text_domain),
                'not_found' => __('Not Found', $this->text_domain),
                'not_found_in_trash' => __('Not found in Trash', $this->text_domain),
            );

            if (empty($custom_supports)) {
                $custom_supports = array(
                    'title',
                    'editor',
                    'author',
                    'thumbnail',
                    'excerpt',
                    'trackbacks',
                    'custom-fields',
                    'comments',
                    'revisions',
                    'page-attributes',
                    'post-formats'
                );
            }

            $supports = $custom_supports;

            $rewrite = array(
                'slug' => get_option($this->prefix.$this->suffix, $this->type),
            );

            $args = array(
                'label' => __($this->name, $this->text_domain),
                'description' => __($this->name.' news and reviews', $this->text_domain),
                'labels' => $labels,
                'supports' => $supports,
                'taxonomies' => [],
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'show_in_rest' => true, // activate gutemberg editor
                'menu_position' => $menu_position,
                'can_export' => true,
                'rewrite' => $rewrite,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => $capability_type,
                'menu_icon' => $menu_icon,
            );

            register_post_type($this->type, $args);

            array_merge($this->taxonomies, $taxonomies);
            $this->taxonomies();

            if (empty($permalinks)) $permalinks = $taxonomies;
            array_merge($this->suffixes, $permalinks);

            add_action('load-options-permalink.php', array($this,'permalink'));

        }


        protected function getGroupId($groupName) {
            return urlencode($this->prefix . 'group_' . $groupName);
        }

        protected function getGroupTitle($groupName) {
            return esc_html__(ucfirst($groupName), $this->text_domain);
        }

        protected function getGroupParams($groupName = 'Maras IT', $context = 'side', $priority = 'low', $show_names = true) {
            return array(
                'id' => $this->getGroupId($groupName),
                'title' => $this->getGroupTitle($groupName),
                'object_types' => [$this->type],
                'context'      => $context, //  'normal', 'advanced', or 'side'
                'priority'     => $priority,  //  'high', 'core', 'default' or 'low'
                'show_names'   => $show_names, // Show field names on the left
            );
        }

        protected function getUserGroupParams($groupName = 'Maras IT', $context = 'side', $priority = 'low', $show_names = true) {
            return array(
                'id' => $this->getGroupId($groupName),
                'title' => $this->getGroupTitle($groupName),
                'object_types' => [$this->type],
                'context'      => $context, //  'normal', 'advanced', or 'side'
                'priority'     => $priority,  //  'high', 'core', 'default' or 'low'
                'show_names'   => $show_names, // Show field names on the left
                'new_user_section' => 'add-new-user',
            );
        }

        protected function addGroup($key = 'content', $params = [], $fields = []) {

            $this->groups[$key]['params'] = $params;
            $this->groups[$key]['fields'] = $fields;

        }

        public function setGroups() {
            foreach ($this->groups as $key => $group) {

                if (!empty($group['params']['id']) && !empty($group['params']['object_types'])) {

                    // https://github.com/CMB2/CMB2/wiki/Display-Options
                    $box = new_cmb2_box($group['params']);


                    foreach ($group['fields'] as $key => $field) {
                        // https://github.com/CMB2/CMB2/wiki/Field-Types

                        if(!empty($field["fields"]) && !empty($field["params"])) {

                            $group_id = $box->add_field($field["params"]);

                            foreach ($field['fields'] as $key => $params) {
                                // https://github.com/CMB2/CMB2/wiki/Field-Types
                                $box->add_group_field($group_id, $params);
                            }

                        } else {

                            $params = $field;
                            $box->add_field($params);

                        }

                    }

                }

            }
        }

        public function taxonomies() {

            foreach ($this->taxonomies as $key => $taxonomy) {

                $this->taxonomy = $taxonomy;

                $labels = array(
                    'name' => _x(ucfirst($this->type).' '.ucfirst($this->taxonomy), 'taxonomy general name'),
                    'singular_name' => _x(ucfirst($this->type), 'taxonomy singular name'),
                    'search_items' => __('Search '.ucfirst($this->taxonomy)),
                    'all_items' => __('All '.ucfirst($this->taxonomy)),
                    'parent_item' => __('Parent '.ucfirst($this->type)),
                    'parent_item_colon' => __('Parent '.ucfirst($this->type).':'),
                    'edit_item' => __('Edit '.ucfirst($this->type)),
                    'update_item' => __('Update '.ucfirst($this->type)),
                    'add_new_item' => __('Add New '.ucfirst($this->type)),
                    'new_item_name' => __('New '.ucfirst($this->type).' Name'),
                    'menu_name' => __(ucfirst($this->taxonomy)),
                );

                $taxonomy_slug = get_option($this->prefix.$this->taxonomy, $this->type.'-'.$this->taxonomy);

                register_taxonomy($this->type.'_'.$this->taxonomy, array($this->type), array(
                    'labels' => $labels,
                    'public' => true,
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'show_admin_column' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => $taxonomy_slug),
                ));

                \Routes::map($taxonomy_slug.'/:taxonomy', function($params){
                    $query = 'posts_per_page=3&post_type='.$params['taxonomy'];
                    \Routes::load('taxonomy.php', null, $query, 200);
                });

            }

        }

        public function permalink() {

            foreach ($this->suffixes as $key => $suffix) {
                $this->suffix = $suffix;

                if (isset($_POST[$this->prefix.$this->suffix])) {
                    update_option($this->prefix.$this->suffix, sanitize_title_with_dashes($_POST[$this->prefix.$this->suffix]));
                }
                // Add a settings field to the permalink page
                add_settings_field($this->prefix.$this->suffix, __($this->name.' '.ucfirst($this->suffix)), array($this, 'save_permalink'), 'permalink', 'optional', $this->suffix);
            }


        }

        function save_permalink($suffix = 'base') {
            $default_value = $this->type;
            if ($suffix != 'base') {
                $default_value .= "-".$suffix;
            }
            $value = get_option($this->prefix.$suffix, $default_value);
            echo '<input type="text" value="' . esc_attr($value) . '" name="'.$this->prefix.$suffix.'" id="'.$this->prefix.$suffix.'" class="regular-text" />';
        }

    }

}
