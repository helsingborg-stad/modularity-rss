<?php

namespace ModularityRss;

class App extends \Modularity\Module
{
    public $slug = 'rssfeed';
    public $supports = array();

    public $feedArgs;

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
        $data = get_field('mod_rss', $this->ID);

        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array(), $this->post_type, $this->args));

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
        $limit = is_numeric(get_field('mod_social_items', $this->ID)) ? get_field('mod_social_items', $this->ID) : 10;
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
            return (int) $b['timestamp'] - (int) $a['timestamp'];
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
