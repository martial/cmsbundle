<?php
/**
 * Created by PhpStorm.
 * User: martialou
 * Date: 28/10/2013
 * Time: 21:08
 */

namespace scrclub\CMSBundle\Service;




use TwitterOAuth\TwitterOAuth;

class TwitterUtils {

    public function getTweets ($user) {

        $config = array(
            'consumer_key' => 'UoHC4g3idzXeo1r6EvTmvw',
            'consumer_secret' => 'lLN3YieJGsNdolUe0j8ALceF4pX0GUN0nMiobEC9Wg',
            'oauth_token' => '13363032-HtNfirK03JXqNmwsxEZEnr5OqA9YaNf3zFuMnsaln',
            'oauth_token_secret' => 'lc3wlSaGKg8ro44VctuY3YjoJpMvPLcatk34lnbpQe4qf',
            'output_format' => 'object'
        );

        /**
         * Instantiate TwitterOAuth class with set tokens
         */
        $tw = new TwitterOAuth($config);


        /**
         * Returns a collection of the most recent Tweets posted by the user
         * https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
         */
        $params = array(
            'screen_name' => $user,
            'count' => 1,
            'exclude_replies' => true
        );

        /**
         * Send a GET call with set parameters
         */
        return $tw->get('statuses/user_timeline', $params);

    }

    public function getSearch($key) {

        $config = array(
            'consumer_key' => 'UoHC4g3idzXeo1r6EvTmvw',
            'consumer_secret' => 'lLN3YieJGsNdolUe0j8ALceF4pX0GUN0nMiobEC9Wg',
            'oauth_token' => '13363032-HtNfirK03JXqNmwsxEZEnr5OqA9YaNf3zFuMnsaln',
            'oauth_token_secret' => 'lc3wlSaGKg8ro44VctuY3YjoJpMvPLcatk34lnbpQe4qf',
            'output_format' => 'object'
        );

        /**
         * Instantiate TwitterOAuth class with set tokens
         */
        $tw = new TwitterOAuth($config);


        /**
         * Returns a collection of the most recent Tweets posted by the user
         * https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
         */
        $params = array(
            'q' => urlencode($key),
            'result_type' => "recent",
            'count' => 100

        );

        /**
         * Send a GET call with set parameters
         */
        return $tw->get('search/tweets', $params);

    }

} 