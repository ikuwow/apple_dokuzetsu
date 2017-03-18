<?php

print date('Y-m-d H:i:s ');

require_once("functions.php");

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// MySQLに接続
try {
    $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
    $dbh = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    var_dump($e->getMessage());
    exit;
}

// OAuthオブジェクト生成
$to = new TwitterOAuth(
	getenv('CONSUMER_KEY'),
	getenv('CONSUMER_SECRET'),
	getenv('ACCESS_TOKEN'),
	getenv('ACCESS_TOKEN_SECRET')
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
$req = $to->post('statuses/update',array('status'=>$tweets[$tw_idx]['tweet']));

// var_dump($req);

// $result = json_decode($req);

$sql = 'UPDATE tweets set num_of_times = :num where ID = :id';
$stmt = $dbh->prepare($sql);
$stat = $stmt->execute(array(':num'=>$tweets[$tw_idx]['num_of_times']+1,':id'=>$tweet_id));

if ($stat) {
  $tmp = $tweets[$tw_idx]['num_of_times'] +1;
  print "Tweet $tweet_id was incremented to $tmp.\n";
} else {
  print "Cannot incremented.";
};

