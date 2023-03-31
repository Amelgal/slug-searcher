<?php
/*
Plugin Name: Slug Searcher
Plugin URI: https://github.com/Amelgal/slug-searcher
Description: Plugin that allows searching for posts/pages/post_types by slug in the /wp-admin area.
Version: 0.0.1
Requires PHP: 7.4
Author: cmsadmin
Text Domain: slug-searcher
*/

if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('SlugSearcher')) :

    class SlugSearcher
    {

        var $version = '0.0.1';

        var $text_domain = 'slug-searcher';

        var $settings = [];

        public function __construct()
        {
        }

        public function initialize()
        {
            // Define constants.
            $this->define('SLUG_SEARCHER', true);
            $this->define('SLUG_SEARCHER_PATH', plugin_dir_path(__FILE__));
            $this->define('SLUG_SEARCHER_VERSION', $this->version);

            // Include utility functions.
            include_once(SLUG_SEARCHER_PATH . 'includes/utility-functions.php');

            // Include admin.
            if (is_admin()) {}

            // Add actions.

            // Add filters.
            add_filter('posts_search', [$this, 'search_by_slug'], 10, 2);
        }

        public function search_by_slug($search, $wp_query)
        {
            if ( ! $wp_query->is_admin || ! $wp_query->is_search() || !array_key_exists('search_terms', $wp_query->query_vars)) return $search;

            global $wpdb;

            $search    = '';
            $and = '';
            foreach ($wp_query->query_vars['search_terms'] as $term) {
                if ('slug:' !== mb_substr(trim($term), 0, 5)) {
                    $like   = '%' . $wpdb->esc_like($term) . '%';
                    $search .= $wpdb->prepare("{$and}(($wpdb->posts.post_title LIKE %s) OR ($wpdb->posts.post_excerpt LIKE %s) OR ($wpdb->posts.post_content LIKE %s))",
                        $like, $like, $like);
                } else {
                    $slug = mb_strtolower(
                        trim(mb_substr($term, 5))
                    );

                    if (empty($slug)) continue;

                    $like   = '%' . $wpdb->esc_like($slug) . '%';
                    $search .= $wpdb->prepare("{$and}(($wpdb->posts.post_name LIKE %s))", $like);
                }
                $and = 'AND';
            }

            if ( ! empty($search)) {
                $search = " AND ({$search}) ";
            }

            return $search;
        }

        private function define($name, $value = true)
        {
            if ( ! defined($name)) {
                define($name, $value);
            }
        }

        private function slug_searcher_include($filename = '')
        {
            $file_path = SLUG_SEARCHER_PATH . ltrim($filename, '/');

            if (file_exists($file_path)) {
                include_once($file_path);
            }
        }
    }

    function slug_creator()
    {
        global $slug_creator;

        if ( ! isset($slug_creator)) {
            $slug_creator = new SlugSearcher();
            $slug_creator->initialize();
        }

        return $slug_creator;
    }

    slug_creator();

endif;
