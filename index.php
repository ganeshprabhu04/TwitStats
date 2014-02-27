<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->


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
            
            
            //Including API caller file which has methods for performing various actions
            require('APICaller.php');
            //Below are my credentials for connecting to twitter API
            $consumer_key = '<Your consumer key>';
            $consumer_secret = '<Your consumer secret>';
            $accesstoken = '<Your accesstoken>';
            $accesstokensecret = '<Your accesstokensecret>';
            $screen_name='<Twitter user name>';
            
            //Create API caller Object
            $twitterCaller = new APICaller($consumer_key, $consumer_secret, $accesstoken, $accesstokensecret, $screen_name);
            //Authenticate
            $twitter=$twitterCaller->authenticate($consumer_key, $consumer_secret, $accesstoken, $accesstokensecret);
            
            //User timeline JSON
            $decode_tweet=$twitterCaller->getUserTimeLineJson($screen_name,$twitter);
            
            //Get twitter ID's for JSON
            $tweet_id=$twitterCaller->getUserTimeLineID($decode_tweet);
            
            //Get Twitter tweets
            $tweet_string=$twitterCaller->getUserTimeLineStatus($decode_tweet);
            
            
            /*
             * For every tweet, dispaly tweet and get the users and their follower count
             */
            for ($tweet_count = 0; $tweet_count < 10; $tweet_count++) {
                
            
                echo "<div id=\"marketing\" class=\"\"><div class=\"\">
                    <section class=\"\"><p class=\"subtitle\">" . $tweet_string[$tweet_count] . "</p> <ul class=\"style1\">";
                     
                $retweets_followers=$twitterCaller->displayTweets($tweet_id[$tweet_count],$twitter);
                $k=0;
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
            
