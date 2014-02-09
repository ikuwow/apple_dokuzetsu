<?php

print date('Y-m-d H:i:s ');

require_once("twitteroauth/twitteroauth/twitteroauth.php");
require_once("functions.php");
require_once("config.php");


// OAuthオブジェクト生成
$to = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET,
	ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
);

// MySQLに接続
$MySQLlink = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
if (!$MySQLlink) {
	die('MySQLへの接続に失敗しました。');
}

// データベースの選択
$db_selected = mysql_select_db(DB_NAME,$MySQLlink);
if (!$db_selected) {
	die('データベースの選択に失敗しました。');
}

// ツイートを重み付きランダムに取得
$tweetnum = mysql_query('SELECT ID,num_of_times FROM tweets ORDER BY ID;');

$itr = 0;
while ($row = mysql_fetch_assoc($tweetnum,MYSQL_NUM) ) {
  $num_of_times[$itr] = $row[1];
  $itr = $itr + 1;
}

foreach ($num_of_times as $key => $num) {
  $weights[$key] = max($num_of_times)-$num+1;
}
$tmp = weighted_random($weights);
$tweetid = $tmp+1; //インデックスを1始まりに

$select_tweet = "SELECT tweet FROM tweets WHERE ID = $tweetid ;";
$tweet_ = mysql_fetch_assoc(mysql_query($select_tweet));
$tweet = $tweet_['tweet'];

// TwitterへPOSTする。パラメーターは配列に格納する
$req = $to->OAuthRequest(
  "https://api.twitter.com/1.1/statuses/update.json",
  "POST",
  array("status"=>"$tweet")
);

$result = json_decode($req);

// echo "<pre>";
// var_dump($result);

// ツイート回数を更新
$numoftimes = mysql_fetch_assoc(
  mysql_query(
  'SELECT num_of_times AS num FROM tweets WHERE ID = '."$tweetid".';'
  )
);
$numoftimes = (int) $numoftimes['num'];

$increment = 'UPDATE tweets SET num_of_times = '
	      ."$numoftimes+1".' WHERE ID = '."$tweetid".' ;';
$count_twinum = mysql_query($increment);
if ($count_twinum) {
  $tmp = $numoftimes +1;
  print "Tweet $tweetid was incremented to $tmp.\n";
} else {
  print "Cannot incremented.";
};

$close_flag = mysql_close($MySQLlink);
if (!$close_flag) { 
  die('Cannot disconnect MySQL.');
}

?>
