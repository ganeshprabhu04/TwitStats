<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// put your code here
/*
 * Including OAuth authentication libraries by Abraham: Reference: https://github.com/abraham/twitteroauth 
 * 
 */
require('twitteroauth.php');

// path to twitter oauth library
class APICaller {

//Input credentials are set and User is authenticated.

    function __construct($consumer_key, $consumer_secret, $accesstoken, $accesstokensecret, $screen_name) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->accesstoken = $accesstoken;
        $this->accesstokensecret = $accesstokensecret;
        $this->screen_name = $screen_name;
    }

    function authenticate($consumer_key, $consumer_secret, $accesstoken, $accesstokensecret) {
        //Authentication using OAuth

        $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $accesstoken, $accesstokensecret);
        return $twitter;
    }

    function getUserTimeLineJson($screen_name, $twitter) {

        /*
          We are requesting twitter for user time line
          screen_name is twitter user name of the user whose tweets and re tweets  are being tracked
          It retrieves recent 100 tweets for given screen name
         */
        
        /*
         * Testing using sample as API has rate limit;
         */
        
        //$tweets = $twitter->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $screen_name);
        $tweets = file_get_contents("./profile.json"); //Testing using sample as API has rate limit;

        $decode_tweet = json_decode($tweets, true); //Converting User time line JSON to PHP Array so that it can be easily parsed.
        return $decode_tweet;
    }

    function getUserTimeLineID($decode_tweet) {
        /*
          Iterating the Twitter response and saving the tweet IDs having 100 and above re-tweets.
         */

        $tweet_id = array();
        $tweet_string = array();
        $saved_tweet_count = 0;

        for ($tweet_count = 0; $tweet_count < count($decode_tweet); $tweet_count++) {



            if (($decode_tweet[$tweet_count]['retweet_count']) >= 100) { //Check if the re tweet count for the tweet is equal or greater than 100
                $tweet_id[$saved_tweet_count] = $decode_tweet[$tweet_count]['id_str']; //Save the tweet ID with above 100 re tweets

                $saved_tweet_count++;
            }
        }
        return $tweet_id;
    }

    function getUserTimeLineStatus($decode_tweet) {
        /*
          Iterating the Twitter response and saving the tweets.
         */


        $tweet_string = array();
        $saved_tweet_count = 0;

        for ($tweet_count = 0; $tweet_count < count($decode_tweet); $tweet_count++) {



            if (($decode_tweet[$tweet_count]['retweet_count']) >= 100) { //Check if the re tweet count for the tweet is equal or greater than 100

                $tweet_string[$saved_tweet_count] = $decode_tweet[$tweet_count]['text']; //Save the tweets with above 100 re tweets
                $saved_tweet_count++;
            }
        }

        return $tweet_string;
    }

    function displayTweets($tweet_id, $twitter) {
        /*
          Iterating the tweet ID's to get the re-tweeted user statistics like User name and follower count
          Sorting the Users in descending Order of follower count
          Finally list top 10 re-tweet users based on follower count
         */

//Displaying only 10 tweets, modify below line for all the tweets


        /*
          By default twitter ID number appears in Exponential form hence converting the number to comma-separated format and then removing comma's using String Replace function.
          ex: 4.38334720728772609E+17 to 438,334,720,728,772,609
          Then 438,334,720,728,772,609 to 438334720728772609
         */

        $unformatted_tweet_id = $tweet_id;
        $formatted_twitter_id = number_format($unformatted_tweet_id);
        $formatted_twitter_id = str_replace(',', '', $formatted_twitter_id);

//Get User related Re-tweet data for every tweet
        
        /*
         * Testing using sample as API has rate limit;
         */
        
        //$retweets = $twitter->get("https://api.twitter.com/1.1/statuses/retweets/" . $formatted_twitter_id . ".json");

        $retweets = file_get_contents("./retweet.json"); 


        $retweets_followers = array();
        $decode_retweet = json_decode($retweets, true);
        //print_r($decode_retweet);

        for ($i = 0; $i < count($decode_retweet); $i++) {

            $a = $decode_retweet[$i]['user']['name'];
            $b = $decode_retweet[$i]['user']['followers_count'];

            $retweets_followers[$a] = $b;
        }

        arsort($retweets_followers);
        return $retweets_followers;
    }

}
