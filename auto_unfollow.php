<?php
print date("Y-m-d H:i:s")." Unfollowing start.\n";

// twitteroauth.phpを読み込む。
require_once("config.php");

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// OAuthオブジェクト生成
$to = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET,
	ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
);

$followers = $to->get('followers/ids', array('cursor' => -1));
$friends = $to->get('friends/ids', array('cursor' => -1));

foreach ( $friends->ids as $i => $id) {
	if (!in_array($id, $followers->ids)) {
		$req = $to->post('friendships/destroy', array('user_id' => $id));
		if ($req) {
			print "Unfollowed $id.\n";
		} else {
			print "Failed to unfollow $id.\n";
		}
	}
}
// Twitterから返されたJSONをデコードする
// $result = json_decode($req);
// JSONの配列（結果）を表示する
// echo "<pre>";
// var_dump($result);
print "Unfollowing end. \n";

?>
