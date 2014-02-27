<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML>


<html>
    <head>
        <title>Twitter API Statistics</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <noscript>
        <link rel="stylesheet" href="css/5grid/core.css" />
        <link rel="stylesheet" href="css/5grid/core-desktop.css" />
        <link rel="stylesheet" href="css/5grid/core-1200px.css" />
        <link rel="stylesheet" href="css/5grid/core-noscript.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style-desktop.css" />
        </noscript>
        <script src="css/5grid/jquery.js"></script>
        <script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none"></script>
        <!--[if IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
    </head>
    <body class="homepage">
        <!-- Header -->
        <div id="header-wrapper">

            <header id="header">
                <div class="5grid-layout">
                    <div class="row">
                        <div class="5u" id="logo">
                            <h1><a href="#" class="mobileUI-site-name">Twitter API</a></h1>
                            <p>by Ganesh</p>
                        </div>
                    </div>
                </div>
            </header>

        </div>
        <!-- Page Wrapper -->
        <div id="wrapper" class="5grid-layout">

            <!-- Banner Content -->
            <div id="banner" class="row">
                <div class="12u">
                    <section><a href="#"><img src="images/pics01.jpg" alt=""></a></section>
                </div>
            </div>

            <!-- Twitter Status Content -->
            <div id="marketing" class="row">

                <div class="3u">
                    <section class="box">
                        <h2>narendramodi</h2>
                    </section>
                </div>
             </div>
                
                <?php
                // put your code here
                /*
                 * Including OAuth authentication libraries by Abraham: Reference: https://github.com/abraham/twitteroauth 
                 * 
                 */


                require('twitteroauth.php'); // path to twitter oauth library
                //Below are my credentials for connecting to twitter API

                $consumerkey = '<Your Consumer key from Twitter>';
                $consumersecret = '<Your Consumer secret from Twitter'>;
                $accesstoken = '<Your access token from Twitter>';
                $accesstokensecret = '<Your access token Secret from Twitter>';

                //Authentication using OAuth
                $twitter = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

                /*
                  We are requesting twitter for user time line
                  screen_name is twitter user name of the user whose tweets and re tweets  are being tracked
                  It retrieves recent 100 tweets for given screen name
                 */

                $tweets = $twitter->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=narendramodi');
                // $tweets = file_get_contents("./profile.json");
                //print_r($tweets);


                $decode_tweet = json_decode($tweets, true); //Converting User time line JSON to PHP Array so that it can be easily parsed.

                /*
                  Iterating the Twitter response and saving the tweet IDs having 100 and above re-tweets.
                 */

                $tweet_id = array();
                $tweet_string = array();
                $saved_tweet_count = 0;

                for ($tweet_count = 0; $tweet_count < count($decode_tweet); $tweet_count++) {
                    //echo $decode_tweet[$tweet_count]['retweet_count'];


                    if (($decode_tweet[$tweet_count]['retweet_count']) >= 100) { //Check if the re tweet count for the tweet is equal or greater than 100
                        //echo "count".$decode_tweet[$i]['retweet_count'];
                        $tweet_id[$saved_tweet_count] = $decode_tweet[$tweet_count]['id_str']; //Save the tweet with above 100 re tweets
                        $tweet_string[$saved_tweet_count] = $decode_tweet[$tweet_count]['text'];
                        $saved_tweet_count++;
                    }
                }
                //print_r($tweet_id);

                /*
                  Iterating the tweet ID's to get the re-tweeted user statistics like User name and follower count
                  Sorting the Users in descending Order of follower count
                  Finally list top 10 re-tweet users based on follower count
                 */

                //Displaying only 10 tweets, modify below line for all the tweets


                for ($tweet_count = 0; $tweet_count < 10; $tweet_count++) {
                    /*
                      By default twitter ID number appears in Exponential form hence converting the number to comma-separated format and then removing comma's using String Replace function.
                      ex: 4.38334720728772609E+17 to 438,334,720,728,772,609
                      Then 438,334,720,728,772,609 to 438334720728772609
                     */

                    $unformatted_tweet_id = $tweet_id[$tweet_count];
                    $formatted_twitter_id = number_format($unformatted_tweet_id);
                    $formatted_twitter_id = str_replace(',', '', $formatted_twitter_id);

                    //Get User related Re-tweet data for every tweet
                    //echo "https://api.twitter.com/1.1/statuses/retweets/".$formatted_twitter_id.".json";
                    $retweets = $twitter->get("https://api.twitter.com/1.1/statuses/retweets/" . $formatted_twitter_id . ".json");
                    //$retweets = file_get_contents("./retweet.json");
                    //print_r($tweets_1);

                    $retweets_followers = array();
                    $decode_retweet = json_decode($retweets, true);

                    //echo "<div id=\"marketing\" class=\"row\"><div class=\"3u\">
                    //<section class=\"box\"><p class=\"subtitle\">" . $tweet_string[$tweet_count] . "</p> <ul class=\"style1\">";

                    echo "<div id=\"marketing\" class=\"\"><div class=\"\">
                    <section class=\"\"><p class=\"subtitle\">" . $tweet_string[$tweet_count] . "</p> <ul class=\"style1\">";

                    for ($i = 0; $i < count($decode_retweet); $i++) {
                        //echo $decode_retweet[$i]['user']['name'];
                        //echo $decode_retweet[$i]['user']['followers_count'];
                        //echo "<br/>";
                        $a = $decode_retweet[$i]['user']['name'];
                        $b = $decode_retweet[$i]['user']['followers_count'];

                        $retweets_followers[$a] = $b;
                    }

                    arsort($retweets_followers);
                    $k = 0;
                    foreach ($retweets_followers as $key => $value) {
                        //Display only top 10 users
                        if ($k == 10) {
                            break;
                        }

                        echo "<li><a href=\"#\">" . $key . " with follower count: " . $value . "</a></li>";
                        $k++;
                    }
                    echo "</ul></section></div></div>";
                    
                }
                ?>
		<!-- Wrapper Ends Here -->


	
		<!-- Copyright -->
		<div class="5grid-layout" id="copyright">
			<div class="row">
				<div class="12u">
					<p>&copy; Ganesh </p>
				</div>
			</div>
		</div>
		
</body>
</html>
