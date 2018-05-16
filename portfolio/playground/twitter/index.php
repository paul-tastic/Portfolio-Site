<?php

ini_set('display_errors', 1);

require 'twitter/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey = "DEXIuf5jCbCWdfotfl1rXl1eJ";
$consumerSecret = "LZqtlE78xs2y7kfyf7yWadxhKflnVXAslBrsh4hjmSKmqZr80C";

$accessToken = "4091976604-7Di8Bt9udocZtP54a1V1VpZzaxKslhtchrajbIk";
$accessTokenSecret = "1Kvijh0rS8O3GyL91JD96pr6auledeHXrlZBQN6J3vc0u";

$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$content = $connection->get("account/verify_credentials");

$statuses = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);

print_r($statuses);

?>