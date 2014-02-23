<?php

print date('Y-m-d H:i:s ');

require_once("twitteroauth/twitteroauth/twitteroauth.php");
require_once("functions.php");
require_once("config.php");


// MySQLに接続
try {
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
    $dbh = new PDO($dsn,DB_USER,DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    var_dump($e->getMessage());
    exit;
}

// OAuthオブジェクト生成
$to = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET,
	ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
);

// ツイートを重み付きランダムに取得
$stmt = $dbh->query('SELECT * FROM tweets ORDER BY ID');

$tweets = $stmt->fetchAll();
$nums = array();
foreach ($tweets as $tweet) {
    $nums[] = $tweet['num_of_times'];
}
$max_weight = max($nums);

$weights = array();
foreach ($nums as $key => $num) {
  $weights[$key] = $max_weight-$num+1;
}

$tw_idx = weighted_random($weights);
$tweet_id = $tw_idx+1; //インデックスを1始まりに

// var_dump($tweets[$tw_idx]);

// TwitterへPOSTする。パラメーターは配列に格納する
$req = $to->OAuthRequest(
  "https://api.twitter.com/1.1/statuses/update.json",
  "POST",
  array("status"=>$tweets[$tw_idx]['tweet'])
);

// $result = json_decode($req);

// echo "<pre>";
// var_dump($result);

// ツイート回数を更新
// $numoftimes = (int) $numoftimes['num'];

// $increment = 'UPDATE tweets SET num_of_times = '
// ."$numoftimes+1".' WHERE ID = '."$tweetid".' ;';
// $count_twinum = mysql_query($increment);

$sql = 'UPDATE tweets set num_of_times = :num where ID = :id';
$stmt = $dbh->prepare($sql);
$stat = $stmt->execute(array(':num'=>$tweets[$tw_idx]['num_of_times']+1,':id'=>$tweet_id));

if ($stat) {
  $tmp = $tweets[$tw_idx]['num_of_times'] +1;
  print "Tweet $tweet_id was incremented to $tmp.\n";
} else {
  print "Cannot incremented.";
};

/*
$close_flag = mysql_close($MySQLlink);
if (!$close_flag) { 
  die('Cannot disconnect MySQL.');
}
 */
