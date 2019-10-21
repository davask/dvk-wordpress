<?php

namespace DvkWP\Cmb;

use DvkWP\Utils\Debug as D;

if ( ! class_exists( '\DvkWP\Cmb\Extend', false ) ) {

    class Extend {

        protected $text_domain;
        protected $type;
        protected $name;
        protected $prefix;
        protected $groups;

        public function __construct($post_type = 'post', $prefix = 'post', $text_domain = 'marasit') {

            $this->type = $post_type;
            $this->name = ucfirst($prefix);
            $this->prefix = $prefix.'_';
            $this->text_domain = $text_domain;
            $this->groups = [];

        }

        public function register() {

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

            $supports = array(
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

            $taxonomies = array();

            $rewrite = array(
                'slug' => get_option($this->prefix.'base', $this->type),
            );

            $args = array(
                'label' => __($this->name, $this->text_domain),
                'description' => __($this->name.' news and reviews', $this->text_domain),
                'labels' => $labels,
                'supports' => $supports,
                'taxonomies' => $taxonomies,
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'show_in_rest' => true, // activate gutemberg editor
                'menu_position' => 8,
                'can_export' => true,
                'rewrite' => $rewrite,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'post',
                'menu_icon' => 'dashicons-admin-post',
            );

            register_post_type($this->type, $args);
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
                    foreach ($group['fields'] as $key => $params) {
                        // https://github.com/CMB2/CMB2/wiki/Field-Types
                        $box->add_field($params);
                    }
                }
            }
        }


    }

}
