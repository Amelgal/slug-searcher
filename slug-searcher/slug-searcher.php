<?php
/*
Plugin Name: Slug Searcher
Plugin URI: ...
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

            // Include admin.
            if (is_admin()) {
                //$this->slug_searcher_include('includes/admin/admin.php');
            }

            // Add actions.
            add_action('init', [$this, 'init'], 5);

            // Add filters.
        }

        public function init()
        {

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

        // Instantiate only once.
        if ( ! isset($slug_creator)) {
            $slug_creator = new SlugSearcher();
            $slug_creator->initialize();
        }

        return $slug_creator;
    }

    slug_creator();

endif;
