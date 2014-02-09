<?php

require_once("twitteroauth/twitteroauth/twitteroauth.php");
require_once("config.php");

// OAuthオブジェクト生成
$to = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET,
	ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
);

// TwitterへPOSTする。パラメーターは配列に格納する
// in_reply_to_status_idを指定するのならば
// array("status"=>"@hogehoge reply","in_reply_to_status_id"=>"0000000000");
// とする。
$tweet = 'お兄ちゃん、まだ起きてるの？ 今日はKeynoteはなかったと思うけど。';
$req = $to->OAuthRequest(
	"https://api.twitter.com/1.1/statuses/update.json",
	"POST",
	array("status"=>"$tweet"));
// TwitterへPOSTするときのパラメーターなど詳しい情報はTwitterのAPI仕様書を参照してください

// Twitterから返されたJSONをデコードする
$result = json_decode($req);
// JSONの配列（結果）を表示する
// echo "<pre>";
// var_dump($result);

// $close_flag = mysql_close($MySQLlink);
// if ($close_flag) {
	// print('MySQLから切断しました。');
// }

?>
