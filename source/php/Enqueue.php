<?php

namespace ModularityRss;

class Enqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {

        //Capability check
        if (!is_user_logged_in() || !current_user_can('edit_posts')) {
            return false;
        }

        wp_enqueue_script('modularity-rss-js', MODULARITYRSS_URL . '/dist/js/modularity-rss.min.js', false);
    }
}
