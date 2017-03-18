<?php
print date('Y-m-d H:i:s ');

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// OAuthオブジェクト生成
$to = new TwitterOAuth(
	getenv('CONSUMER_KEY'),
	getenv('CONSUMER_SECRET'),
	getenv('ACCESS_TOKEN'),
	getenv('ACCESS_TOKEN_SECRET')
);

// フォロワーのIDを読み込み
$followers = $to->get('followers/ids');
// フォローしているアカウントのIDを読み込み
$friends = $to->get('friends/ids');

//$reply_post = 'フォローしてくれてありがとうお兄ちゃん！ スティーブ・ジョブズみたいな人間を目指してこれからも頑張ってね！';

$counter = 0;
foreach ( $followers->ids as $i => $id) {
	if (empty($friends->ids) or !in_array($id, $friends->ids)) {
		$req = $to->post('friendships/create', array('user_id' => $id));
		if ($req) {
			$counter = $counter +1;
			//$reply = '@'.$to->[
			//$req2 = $to->post('statuses/update',array('status'=>$reply));
		}
	}
	if ($counter ==30) break;
}

print "Auto followed $counter user(s). \n";
$result = json_decode($req);
// echo "<pre>";
var_dump($result);

?>
