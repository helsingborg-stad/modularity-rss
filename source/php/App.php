<?php

namespace ModularityRss;

class App extends \Modularity\Module
{
    public $slug = 'rssfeed';
    public $supports = array();

    public $feedArgs;

    public $hiddenInlays = array();

    public $curl;

    public function init()
    {
        $this->nameSingular = __("RSS Feed", 'modularity');
        $this->namePlural = __("RSS Feeds", 'modularity');
        $this->description = __("Outputs a rss feed from desired links. The feed can combine multiple sources in a single feed.", 'modularity-rss');
        $this->ttl = 3600;

        $this->curl = new \Modularity\Helper\Curl(true, 60);
    }

    /**
     * Allocate view with data
     * @return array $data Data sent to the view
     */

    public function data() : array
    {
        include_once(ABSPATH . WPINC . '/feed.php');

        $feeds = get_field('mod_rss', $this->ID);

        $data['moduleId'] = $this->ID;
        $data['feed'] = array();
        $data['display'] = get_field('mod_rss_display', $this->ID);
        $data['hidden'] = get_post_meta($this->ID, 'mod_rss_hidden_inlays', true);

        if (is_array($feeds) && !empty($feeds)) {
            foreach ($feeds as $feed) {

                //Fetch the feed
                $rss = fetch_feed($feed['mod_rss_url']);

                //Error? Jump to next
                if (is_wp_error($rss)) {
                    $this->registerError("Modularity RSS Feed:" . $result->get_error_message());
                    continue;
                }

                //Get items
                $rss_items = $rss->get_items(0, $rss->get_item_quantity(20));

                //Append to result
                if (!empty($rss_items)) {
                    foreach ($rss_items as $item) {

                        //Inlay item
                        $current = array(
                            'encloushure' => array(),
                            'id' => base_convert(md5($item->get_id()), 10, 36),
                            'title' => $item->get_title(),
                            'excerpt' => strip_tags($item->get_description()),
                            'content' => strip_tags($item->get_content()),
                            'author' => $item->get_author(),
                            'link' => $item->get_permalink(),
                            'time' => strtotime($item->get_date('Y-m-d H:i:s')),
                            'time_markup' => $item->get_date('Y-m-d H:i'),
                            'time_readable' => $this->readableTimeStamp(strtotime($item->get_date('Y-m-d H:i:s')))
                        );

                        //Append label
                        $current['encloushure']['title'] = $feed['mod_rss_label'] ? $feed['mod_rss_label'] : $rss->get_title();

                        //Append class
                        if (in_array($current['id'], $data['hidden'])) {
                            $current['visibilityClass'] = "is-hidden";
                        } else {
                            $current['visibilityClass'] = "";
                        }

                        //Append full item, if not hidden and !logged in
                        if (is_user_logged_in() && current_user_can('edit_posts')) {
                            $data['feed'][] = $current;
                        } else {
                            if (!in_array($current['id'], $data['hidden'])) {
                                $data['feed'][] = $current;
                            }
                        }
                    }
                }
            }
        }

        //Sort
        $data['feed'] = $this->sortByTimestamp($data['feed']);

        //Truncate
        $data['feed'] = $this->truncateFeed($data['feed']);

        //Translation strings
        $data['translations'] = array(
            'readmore' => __("Read more", 'modularity-rss'),
            'noposts' => __("No posts avabile from the selected sources.", 'modularity-rss'),
            'ago' => __("ago", 'modularity-rss'),
        );

        //Mod classes
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array(), $this->post_type, $this->args));

        //Can edit?
        if (is_user_logged_in() && current_user_can('edit_posts')) {
            $data['showVisibilityButton'] = true;
        } else {
            $data['showVisibilityButton'] = false;
        }

        return $data;
    }

    /**
     * Get a class for item with by column count
     * @return string a class indicating how many columns that should be shown
     */

    public function getNumberOfColumnsClass()
    {
        $numberOfColumns = get_field('mod_rss_columns', $this->ID);

        //Retain to allowed values
        if (!in_array($numberOfColumns, array(1, 2, 3, 4))) {
            $numberOfColumns = 3;
        }

        //Calculate number of colums
        if (is_numeric($numberOfColumns) && $numberOfColumns != 1) {
            return "grid-xs-12 grid-sm-6 grid-md-6 grid-lg-" . (12 / $numberOfColumns);
        }

        return 'grid-xs-12 grid-sm-6 grid-md-6 grid-lg-4';
    }

    /**
     * Truncate feed according to max items
     * @param array $feed array items with the feed data
     * @return array $feed truncated feed
     */

    public function truncateFeed($feed)
    {
        $limit = is_numeric(get_field('mod_rss_limit', $this->ID)) ? get_field('mod_rss_limit', $this->ID) : 7;
        return array_slice($feed, 0, $limit);
    }

    /**
     * Sort data by timestamp
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */

    public function sortByTimestamp($feed)
    {
        usort($feed, function ($a, $b) {
            return (int) $b['time'] - (int) $a['time'];
        });

        return $feed;
    }

    /**
     * Remove duplicate items by media type, compares id of the post.
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */

    public function removeDuplicates($feed)
    {
        $sanitized= array();

        if (is_array($feed) && !empty($feed)) {
            foreach ($feed as $item) {
                if (!array_key_exists($item['id'], $sanitized)) {
                    $sanitized[$item['id']] = $item;
                }
            }

            return $sanitized;
        }

        return $feed;
    }

    /**
     * Remove hidden inlays
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */

    public function removeHidden($feed)
    {
        $sanitized= array();

        if (is_array($feed) && !empty($feed)) {
            foreach ($feed as $item) {
                if (in_array($item['id'], $this->hiddenInlays)) {
                    continue;
                }
                $sanitized[$item['id']] = $item;
            }
            return $sanitized;
        }

        return $feed;
    }

    /**
     * Tell what view to render as module.
     * @return string The view that should be rendered
     */

    public function template() : string
    {
        return "feed.blade.php";
    }

    /**
     * Format a unix timestamp to a human friendly format
     * @param string $unixtime The timestamp in unixtime format
     * @return string Humean readable time
     */
    public function readableTimeStamp($unixtime) : string
    {
        return human_time_diff($unixtime, current_time('timestamp'));
    }

    /**
     * Register error in log
     * @param string $errorMessage A written error.
     * @return void
     */
    public function registerError($errorMessage)
    {
        $trace = debug_backtrace();
        if (isset($trace[1])) {
            $errorInClass = $trace[1]['class'];
            $errorInFunction = $trace[1]['function'];
        } else {
            $errorInClass = "";
            $errorInFunction = "";
        }

        error_log($errorMessage . 'in' . $errorInClass . '->' . $errorInFunction);
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
